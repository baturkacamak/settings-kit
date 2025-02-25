<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Field\CheckboxField;
use  WPSettingsKit\Field\Interface\IField;

/**
 * Builder for checkbox fields
 */
class CheckboxFieldBuilder extends AbstractFieldBuilder {
    private mixed $checkedValue = true;
    private mixed $uncheckedValue = false;

    /**
     * Set the value when checked
     */
    public function setCheckedValue(mixed $value): self {
        $this->checkedValue = $value;
        return $this;
    }

    /**
     * Set the value when unchecked
     */
    public function setUncheckedValue(mixed $value): self {
        $this->uncheckedValue = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): IField {
        $config = $this->getConfig();
        $config['checked_value'] = $this->checkedValue;
        $config['unchecked_value'] = $this->uncheckedValue;

        return new CheckboxField($config);
    }
}