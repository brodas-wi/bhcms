<?php

namespace App\Services;

class HookSystemCopy
{
    protected $actions = [];
    protected $filters = [];

    public function addAction($hook, $callback, $priority = 10)
    {
        $this->actions[$hook][$priority][] = $callback;
    }

    public function doAction($hook, ...$args)
    {
        if (isset($this->actions[$hook])) {
            ksort($this->actions[$hook]);
            foreach ($this->actions[$hook] as $priorities) {
                foreach ($priorities as $callback) {
                    call_user_func_array($callback, $args);
                }
            }
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
}
