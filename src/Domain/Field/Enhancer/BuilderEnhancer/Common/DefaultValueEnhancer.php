<?php

namespace WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\Common;

use WPSettingsKit\Domain\Field\Enhancer\Attribute\FieldEnhancer;
use WPSettingsKit\Domain\Field\Enhancer\BuilderEnhancer\AbstractFieldBuilderEnhancer;

/**
 * Enhancer for setting default values for fields.
 */
#[FieldEnhancer(
    type: 'all',
    method: 'setDefaultValue',
    priority: 1
)]
class DefaultValueEnhancer extends AbstractFieldBuilderEnhancer {
    /**
     * @var mixed Default value
     */
    private mixed $defaultValue;

    /**
     * Constructor.
     *
     * @param mixed $defaultValue Default value
     * @param int|null $priority Optional priority override
     */
    public function __construct(mixed $defaultValue, ?int $priority = null) {
        parent::__construct($priority, 'all');
        $this->defaultValue = $defaultValue;
    }

    /**
     * Apply custom logic for default value.
     *
     * @param array<string, mixed> $config Current configuration
     * @return array<string, mixed> Modified configuration
     */
    protected function applyCustomLogic(array $config): array {
        // Only set the value if not already set
        if (!isset($config['value'])) {
            $config['value'] = $this->defaultValue;
        }

        return $config;
    }
}