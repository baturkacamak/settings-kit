<?php

namespace WPSettingsKit\Attribute;

use Attribute;

/**
 * Attribute for field decorators.
 *
 * Used to mark and configure field decorator classes for auto-discovery.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class FieldDecorator
{
    /**
     * Constructor for FieldDecorator attribute.
     *
     * @param string $type Field type this decorator applies to (text, select, checkbox, etc.)
     * @param string $method Suggested method name for IDE autocompletion
     * @param int $priority Priority of decorator application (lower runs first)
     * @param string|null $autocomplete Optional IDE autocomplete hint
     */
    public function __construct(
        public readonly string $type,
        public readonly string $method,
        public readonly int $priority = 10,
        public readonly ?string $autocomplete = null
    ) {
    }
}