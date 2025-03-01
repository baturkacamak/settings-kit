<?php

namespace WPSettingsKit\Domain\Validation\Rules\Text;

use DateTime;
use WPSettingsKit\Domain\Field\Enhancer\Attribute\ValidationRule;
use WPSettingsKit\Domain\Validation\Base\Interface\IValidationRule;

/**
 * Validates that a string represents a valid date in the specified format.
 */
#[ValidationRule(
    type: ['text', 'date'],
    method: 'addDateValidation',
    priority: 30
)]
class DateValidationRule implements IValidationRule
{
    /**
     * @var string The date format (PHP date format).
     */
    private readonly string $format;

    /**
     * @var ?DateTime The minimum allowed date, or null if no minimum.
     */
    private readonly ?DateTime $minDate;

    /**
     * @var ?DateTime The maximum allowed date, or null if no maximum.
     */
    private readonly ?DateTime $maxDate;

    /**
     * @var string Custom error message
     */
    private readonly string $customMessage;

    /**
     * Constructor for DateValidator.
     *
     * @param string $format The date format (PHP date format, e.g., 'Y-m-d').
     * @param string|DateTime|null $minDate The minimum allowed date, or null if no minimum.
     * @param string|DateTime|null $maxDate The maximum allowed date, or null if no maximum.
     * @param string|null $customMessage Optional custom error message.
     */
    public function __construct(
        string $format = 'Y-m-d',
        string|DateTime|null $minDate = null,
        string|DateTime|null $maxDate = null,
        ?string $customMessage = null
    ) {
        $this->format = $format;

        // Convert string dates to DateTime objects
        $this->minDate = $this->parseDate($minDate, $format);
        $this->maxDate = $this->parseDate($maxDate, $format);

        // Generate custom message if not provided
        if ($customMessage === null) {
            if ($this->minDate !== null && $this->maxDate !== null) {
                $this->customMessage = sprintf(
                    __('Please enter a valid date between %s and %s in the format %s.', 'wp-settings-kit'),
                    $this->minDate->format($this->format),
                    $this->maxDate->format($this->format),
                    $this->format
                );
            } elseif ($this->minDate !== null) {
                $this->customMessage = sprintf(
                    __('Please enter a valid date on or after %s in the format %s.', 'wp-settings-kit'),
                    $this->minDate->format($this->format),
                    $this->format
                );
            } elseif ($this->maxDate !== null) {
                $this->customMessage = sprintf(
                    __('Please enter a valid date on or before %s in the format %s.', 'wp-settings-kit'),
                    $this->maxDate->format($this->format),
                    $this->format
                );
            } else {
                $this->customMessage = sprintf(
                    __('Please enter a valid date in the format %s.', 'wp-settings-kit'),
                    $this->format
                );
            }
        } else {
            $this->customMessage = $customMessage;
        }
    }

    /**
     * Validates if the given value is a valid date in the specified format and range.
     *
     * @param mixed $value The value to validate (expected to be a string).
     * @return bool True if the value is a valid date within the range, false otherwise.
     */
    public function validate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Check if the value is a valid date in the specified format
        $date = DateTime::createFromFormat($this->format, $value);
        if ($date === false || $date->format($this->format) !== $value) {
            return false;
        }

        // Check min date if specified
        if ($this->minDate !== null && $date < $this->minDate) {
            return false;
        }

        // Check max date if specified
        if ($this->maxDate !== null && $date > $this->maxDate) {
            return false;
        }

        return apply_filters('wp_settings_date_validator_result', true, $value, $this->format, $this->minDate, $this->maxDate);
    }

    /**
     * Gets the error message for when validation fails.
     *
     * @return string The error message.
     */
    public function getMessage(): string
    {
        return apply_filters(
            'wp_settings_date_validator_message',
            $this->customMessage,
            $this->format,
            $this->minDate,
            $this->maxDate
        );
    }

    /**
     * Gets the name of this validation rule.
     *
     * @return string The identifier for this validator.
     */
    public function getName(): string
    {
        return 'date';
    }

    /**
     * Gets the parameters used by this validator.
     *
     * @return array<string, mixed> An array containing the validator parameters.
     */
    public function getParameters(): array
    {
        return [
            'format' => $this->format,
            'minDate' => $this->minDate ? $this->minDate->format($this->format) : null,
            'maxDate' => $this->maxDate ? $this->maxDate->format($this->format) : null,
            'customMessage' => $this->customMessage
        ];
    }

    /**
     * Parses a date from various formats into a DateTime object.
     *
     * @param string|DateTime|null $date The date to parse.
     * @param string $format The expected date format.
     * @return DateTime|null The parsed DateTime object or null.
     */
    private function parseDate(string|DateTime|null $date, string $format): ?DateTime
    {
        if ($date === null) {
            return null;
        }

        if ($date instanceof DateTime) {
            return $date;
        }

        // Try to create from the specified format
        $parsedDate = DateTime::createFromFormat($format, $date);

        // If that fails, try standard format
        if ($parsedDate === false) {
            try {
                $parsedDate = new DateTime($date);
            } catch (\Exception $e) {
                return null;
            }
        }

        return $parsedDate;
    }
}