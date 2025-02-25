<?php

namespace  WPSettingsKit\Event\Interface;


/**
 * Interface for field event handling
 */
interface IFieldEventDispatcher
{
    /**
     * Dispatch an event
     *
     * @param string $event
     * @param mixed $data
     */
    public function dispatch(string $event, mixed $data): void;

    /**
     * Add an event listener
     *
     * @param string $event
     * @param callable $callback
     */
    public function addListener(string $event, callable $callback): void;

    /**
     * Remove an event listener
     *
     * @param string $event
     * @param callable $callback
     */
    public function removeListener(string $event, callable $callback): void;
}