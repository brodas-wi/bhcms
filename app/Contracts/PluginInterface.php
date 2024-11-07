<?php

namespace App\Contracts;
use App\Services\HookSystem;

interface PluginInterface
{
    public function register(HookSystem $hookSystem): void;
    public function boot(): void;
    public function getHooks(): array;
    public function activate(): void;
    public function deactivate(): void;
    public function uninstall(): void;
    public function getVersion(): string;
    public function getDependencies(): array;
    public function getSettings(): array;
    public function updateSettings(array $settings): void;
}
