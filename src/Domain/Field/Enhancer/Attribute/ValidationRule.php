<?php

namespace WPSettingsKit\Domain\Field\Enhancer\Attribute;

use Attribute;

/**
 * Attribute for validation rules.
 *
 * Used to mark and configure validation rule classes for auto-discovery.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ValidationRule
{
    /**
     * Constructor for ValidationRule attribute.
     *
     * @param string|array<string> $type Field type(s) this validation rule applies to
     * @param string $method Suggested method name for IDE autocompletion
     * @param int $priority Priority of validation rule application (lower runs first)
     * @param string|null $autocomplete Optional IDE autocomplete hint
     */
    public function __construct(
        public readonly string|array $type,
        public readonly string $method,
        public readonly int $priority = 10,
        public readonly ?string $autocomplete = null
    ) {
    }
}