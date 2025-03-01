<?php

namespace   WPSettingsKit\Infrastructure\Context\Interface;

use WPSettingsKit\Domain\Field\Base\Interface\IField;

interface IContextManager {
    /**
     * Get the current context
     *
     * @return string Current context
     */
    public function getContext(): string;

    /**
     * Set the current context
     *
     * @param string $context Context to set
     */
    public function setContext(string $context): void;

    /**
     * Get the contextual value for a field
     *
     * @param IField $field Field to get value for
     * @return mixed Contextual value
     */
    public function getContextualValue(IField $field): mixed;
}
