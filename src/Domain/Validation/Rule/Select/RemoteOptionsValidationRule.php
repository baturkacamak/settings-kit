<?php

namespace WPSettingsKit\Domain\Validation\Rules\Select;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Validates that select field value is present in a remote data source.
 */
#[ValidationRule(
    type: ['select', 'radio', 'checkbox'],
    method: 'addRemoteOptionsValidation',
    priority: 70
)]
class RemoteOptionsValidationRule implements IValidationRule
{
    /**
     * @var string The API endpoint URL to validate against
     */
    private string $apiEndpoint;

    /**
     * @var string The field name in response to check
     */
    private string $responseField;

    /**
     * @var array<string, mixed> Additional parameters to send with request
     */
    private array $requestParams;

    /**
     * @var int Cache TTL in seconds
     */
    private int $cacheTTL;

    /**
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for RemoteOptionsValidator.
     *
     * @param string $apiEndpoint The API endpoint for validation
     * @param string $responseField The field in API response containing valid values
     * @param array<string, mixed> $requestParams Additional parameters for the API request
     * @param int $cacheTTL Cache TTL in seconds (default: 3600)
     * @param string|null $customMessage Optional custom error message
     */
    public function __construct(
        string  $apiEndpoint,
        string  $responseField,
        array   $requestParams = [],
        int     $cacheTTL = 3600,
        ?string $customMessage = null
    )
    {
        $this->apiEndpoint   = $apiEndpoint;
        $this->responseField = $responseField;
        $this->requestParams = $requestParams;
        $this->cacheTTL      = $cacheTTL;
        $this->customMessage = $customMessage ??
            __('The selected option is no longer valid.', 'wp-settings-kit');
    }

    /**
     * Validates if selected value exists in remote data source.
     *
     * @param mixed $value The value to validate
     * @return bool True if value exists in remote data source, false otherwise
     */
    public function validate(mixed $value): bool
    {
        // Cache remote options to avoid multiple API calls
        $validOptions = $this->fetchValidOptions();

        // If we couldn't fetch options, we can't validate properly
        if ($validOptions === null) {
            return true; // Fail open to avoid blocking users
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                if (!in_array($item, $validOptions, true)) {
                    return false;
                }
            }
            return true;
        }

        $result = in_array($value, $validOptions, true);
        return apply_filters('wp_settings_remote_options_validator_result', $result, $value, $validOptions);
    }

    /**
     * Fetches valid options from remote API.
     *
     * @return array<string>|null Array of valid values or null if fetch failed
     */
    private function fetchValidOptions(): ?array
    {
        // Try to get from cache first
        $cacheKey      = 'remote_options_' . md5($this->apiEndpoint . serialize($this->requestParams));
        $cachedOptions = get_transient($cacheKey);

        if ($cachedOptions !== false) {
            return $cachedOptions;
        }

        // Perform API request
        $args = [
            'timeout' => 15,
            'body'    => $this->requestParams,
        ];

        $response = wp_remote_get($this->apiEndpoint, $args);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data[$this->responseField]) || !is_array($data[$this->responseField])) {
            return null;
        }

        // Cache the result
        set_transient($cacheKey, $data[$this->responseField], $this->cacheTTL);

        return $data[$this->responseField];
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the selection is invalid
     */
    public function getMessage(): string
    {
        return apply_filters('wp_settings_remote_options_validator_message', $this->customMessage);
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator
     */
    public function getName(): string
    {
        return 'remote_options';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing validation parameters
     */
    public function getParameters(): array
    {
        return [
            'apiEndpoint'   => $this->apiEndpoint,
            'responseField' => $this->responseField,
            'requestParams' => $this->requestParams,
            'cacheTTL'      => $this->cacheTTL,
            'customMessage' => $this->customMessage,
        ];
    }
}