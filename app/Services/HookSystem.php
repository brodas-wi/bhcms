<?php

namespace App\Services;

class HookSystem
{
    protected $actions = [];
    protected $filters = [];
    protected static $instance = null;

    // Make the constructor private for singleton pattern
    private function __construct()
    {
    }

    // Get the singleton instance
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addAction($hook, $callback, $priority = 10)
    {
        $this->actions[$hook][$priority][] = $callback;
    }

    public function doAction($hook, ...$args)
    {
        \Log::info("Executing hook: {$hook}");

        if (isset($this->actions[$hook])) {
            ksort($this->actions[$hook]);
            foreach ($this->actions[$hook] as $priority => $callbacks) {
                foreach ($callbacks as $callback) {
                    \Log::info("Executing callback for hook '{$hook}' with priority {$priority}");
                    try {
                        call_user_func_array($callback, $args);
                        \Log::info("Callback for hook '{$hook}' executed successfully");
                    } catch (\Exception $e) {
                        \Log::error("Error executing callback for hook '{$hook}': " . $e->getMessage());
                    }
                }
            }
        } else {
            \Log::info("No callbacks registered for hook: {$hook}");
        }
    }

    public function addFilter($hook, $callback, $priority = 10)
    {
        $this->filters[$hook][$priority][] = $callback;
    }

    public function applyFilters($hook, $value, ...$args)
    {
        if (isset($this->filters[$hook])) {
            ksort($this->filters[$hook]);
            foreach ($this->filters[$hook] as $priorities) {
                foreach ($priorities as $callback) {
                    $value = call_user_func_array($callback, [$value, ...$args]);
                }
            }
        }
        return $value;
    }

    // New method to remove an action
    public function removeAction($hook, $callback, $priority = 10)
    {
        if (isset($this->actions[$hook][$priority])) {
            $this->actions[$hook][$priority] = array_filter(
                $this->actions[$hook][$priority],
                function ($registered_callback) use ($callback) {
                    return $registered_callback !== $callback;
                }
            );
        }
    }

    // New method to remove a filter
    public function removeFilter($hook, $callback, $priority = 10)
    {
        if (isset($this->filters[$hook][$priority])) {
            $this->filters[$hook][$priority] = array_filter(
                $this->filters[$hook][$priority],
                function ($registered_callback) use ($callback) {
                    return $registered_callback !== $callback;
                }
            );
        }
    }

    // New method to check if an action exists
    public function hasAction($hook, $callback = null)
    {
        if (null === $callback) {
            return !empty($this->actions[$hook]);
        }

        foreach ($this->actions[$hook] as $priority => $callbacks) {
            if (in_array($callback, $callbacks, true)) {
                return true;
            }
        }

        return false;
    }

    // New method to check if a filter exists
    public function hasFilter($hook, $callback = null)
    {
        if (null === $callback) {
            return !empty($this->filters[$hook]);
        }

        foreach ($this->filters[$hook] as $priority => $callbacks) {
            if (in_array($callback, $callbacks, true)) {
                return true;
            }
        }

        return false;
    }
}
