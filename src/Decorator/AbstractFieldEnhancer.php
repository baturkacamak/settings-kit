<?php

namespace WPSettingsKit\Enhancer;


use  WPSettingsKit\Enhancer\Interface\IFieldEnhancer;

/**
 * Abstract base class for field enhancers
 */
abstract class AbstractFieldEnhancer implements IFieldEnhancer {
    protected int $priority = 10;

    /**
     * @inheritDoc
     */
    public function getPriority(): int {
        return $this->priority;
    }

    /**
     * Set enhancer priority
     */
    public function setPriority(int $priority): self {
        $this->priority = $priority;
        return $this;
    }
}