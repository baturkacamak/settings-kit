<?php

namespace WPSettingsKit\Decorator;


use  WPSettingsKit\Decorator\Interface\IFieldDecorator;

/**
 * Abstract base class for field decorators
 */
abstract class AbstractFieldDecorator implements IFieldDecorator {
    protected int $priority = 10;

    /**
     * @inheritDoc
     */
    public function getPriority(): int {
        return $this->priority;
    }

    /**
     * Set decorator priority
     */
    public function setPriority(int $priority): self {
        $this->priority = $priority;
        return $this;
    }
}