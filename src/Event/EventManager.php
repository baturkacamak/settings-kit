<?php

namespace WPSettingsKit\Event;

use  WPSettingsKit\Event\Interface\IFieldEventDispatcher;

/**
 * Event manager implementation
 */
class EventManager implements IFieldEventDispatcher {
    /**
     * @var array<string, array<callable>>
     */
    private array $listeners = [];

    /**
     * @inheritDoc
     */
    public function dispatch(string $event, mixed $data): void {
        if (!isset($this->listeners[$event])) {
            return;
        }

        foreach ($this->listeners[$event] as $callback) {
            call_user_func($callback, $data);
        }
    }

    /**
     * @inheritDoc
     */
    public function addListener(string $event, callable $callback): void {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }
        $this->listeners[$event][] = $callback;
    }

    /**
     * @inheritDoc
     */
    public function removeListener(string $event, callable $callback): void {
        if (!isset($this->listeners[$event])) {
            return;
        }

        $this->listeners[$event] = array_filter(
            $this->listeners[$event],
            fn($listener) => $listener !== $callback
        );
    }
}