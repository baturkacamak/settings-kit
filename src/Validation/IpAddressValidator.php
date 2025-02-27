<?php

namespace WPSettingsKit\Validation;

use WPSettingsKit\Validation\Interface\IValidationRule;

/**
 * Validates that a string represents a valid IP address.
 */
class IpAddressValidator implements IValidationRule
{
    /**
     * @var bool Whether to allow only IPv4 addresses.
     */
    private readonly bool $ipv4Only;

    /**
     * @var bool Whether to allow only IPv6 addresses.
     */
    private readonly bool $ipv6Only;

    /**
     * Constructor for IpAddressValidator.
     *
     * @param bool $ipv4Only Whether to allow only IPv4 addresses.
     * @param bool $ipv6Only Whether to allow only IPv6 addresses.
     */
    public function __construct(bool $ipv4Only = false, bool $ipv6Only = false)
    {
        $this->ipv4Only = $ipv4Only;
        $this->ipv6Only = $ipv6Only;
    }

    /**
     * Validates if the given value is a valid IP address.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value is a valid IP address, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $flags = 0;

        if ($this->ipv4Only) {
            $flags = FILTER_FLAG_IPV4;
        } elseif ($this->ipv6Only) {
            $flags = FILTER_FLAG_IPV6;
        }

        $result = filter_var($value, FILTER_VALIDATE_IP, $flags) !== false;
        return apply_filters('wp_settings_ip_address_validator_result', $result, $value, $this->ipv4Only, $this->ipv6Only);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message.
     */
    public function getMessage(): string
    {
        if ($this->ipv4Only) {
            return __('Please enter a valid IPv4 address.', 'settings-manager');
        } elseif ($this->ipv6Only) {
            return __('Please enter a valid IPv6 address.', 'settings-manager');
        }

        return __('Please enter a valid IP address.', 'settings-manager');
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'ip_address';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the validator parameters.
     */
    public function getParameters(): array
    {
        return [
            'ipv4Only' => $this->ipv4Only,
            'ipv6Only' => $this->ipv6Only
        ];
    }
}