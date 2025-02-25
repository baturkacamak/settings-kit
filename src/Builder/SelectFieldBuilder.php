<?php
/**
 * Builder for select fields
 */
class SelectFieldBuilder extends AbstractFieldBuilder {
    private array $options = [];

    /**
     * Set all options at once
     */
    public function setOptions(array $options): self {
        $this->options = $options;
        return $this;
    }

    /**
     * Add a single option
     */
    public function addOption(string $key, mixed $value): self {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): IField {
        $config = $this->getConfig();
        $config['options'] = $this->options;

        return new SelectField($config);
    }
}