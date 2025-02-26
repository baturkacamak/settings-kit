<?php

namespace WPSettingsKit\Builder;

use WPSettingsKit\Field\Base\Interface\IField;
use WPSettingsKit\Field\Basic\TextField;

/**
 * Builder for text fields
 */
class TextFieldBuilder extends AbstractFieldBuilder {
    private string $placeholder = '';
    private int $maxLength = 0;

    /**
     * Set placeholder text
     */
    public function setPlaceholder(string $text): self {
        $this->placeholder = $text;
        return $this;
    }

    /**
     * Set maximum length
     */
    public function setMaxLength(int $length): self {
        $this->maxLength = $length;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): IField {
        $config = $this->getConfig();
        $config['placeholder'] = $this->placeholder;
        $config['max_length'] = $this->maxLength;

        return new TextField($config);
    }
}