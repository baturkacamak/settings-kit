<?php

namespace WPSettingsKit\Validation\SelectField;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that select field value is present in a remote data source.
 */
class RemoteOptionsValidator implements IValidationRule
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
     * Constructor for RemoteOptionsValidator.
     *
     * @param string $apiEndpoint The API endpoint for validation
     * @param string $responseField The field in API response containing valid values
     * @param array<string, mixed> $requestParams Additional parameters for the API request
     */
    public function __construct(string $apiEndpoint, string $responseField, array $requestParams = [])
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->responseField = $responseField;
        $this->requestParams = $requestParams;
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
        static $validOptions = null;

        if ($validOptions === null) {
            $validOptions = $this->fetchValidOptions();

            // If we couldn't fetch options, we can't validate properly
            if ($validOptions === null) {
                return true; // Fail open to avoid blocking users
            }
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                if (!in_array($item, $validOptions, true)) {
                    return false;
                }
            }
            return true;
        }

        return in_array($value, $validOptions, true);
    }

    /**
     * Fetches valid options from remote API.
     *
     * @return array<string>|null Array of valid values or null if fetch failed
     */
    private function fetchValidOptions(): ?array
    {
        $args = [
            'timeout' => 15,
            'body' => $this->requestParams
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

        return $data[$this->responseField];
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message indicating the selection is invalid
     */
    public function getMessage(): string
    {
        return __('The selected option is no longer valid.', 'settings-manager');
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
            'apiEndpoint' => $this->apiEndpoint,
            'responseField' => $this->responseField
        ];
    }
}