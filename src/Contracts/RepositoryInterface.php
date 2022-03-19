<?php

namespace Nwidart\Modules\Contracts;

use Nwidart\Modules\Module;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Exceptions\ModuleNotFoundException;

interface RepositoryInterface
{
    /**
     * Get all modules
     */
    public function all(): array;

    /**
     * Get cached modules
     */
    public function getCached(): array;

    /**
     * Scan & get all available modules.
     */
    public function scan(): array;

    /**
     * Get modules as modules collection instance.
     */
    public function toCollection(): Collection;

    /**
     * Get scanned paths.
     */
    public function getScanPaths(): array;

    /**
     * Get list of enabled modules.
     *
     * @return array
     */
    public function allEnabled(): array;

    /**
     * Get list of disabled modules.
     *
     * @return mixed
     */
    public function allDisabled();

    /**
     * Get count from all modules.
     */
    public function count(): int;

    /**
     * Get all ordered modules.
     */
    public function getOrdered(string $direction = 'asc'): array;

    /**
     * Get modules by the given status.
     *
     * @param int $status
     *
     * @return array
     */
    public function getByStatus($status): array;

    /**
     * Find a specific module.
     *
     * @param $name
     * @return Module|null
     */
    public function find(string $name);

    /**
     * Find all modules that are required by a module. If the module cannot be found, throw an exception.
     *
     * @param $name
     * @return array
     * @throws ModuleNotFoundException
     */
    public function findRequirements($name): array;

    /**
     * Find a specific module.
     * @param $name
     * @return mixed
     * @throws ModuleNotFoundException
     */
    public function findOrFail(string $name);

    public function getModulePath($moduleName);

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles();

    /**
     * Get a specific config data from a configuration file.
     * @param string $key
     *
     * @param string|null $default
     * @return mixed
     */
    public function config(string $key, $default = null);

    /**
     * Get a module path.
     *
     * @return string
     */
    public function getPath() : string;

    /**
     * Find a specific module by its alias.
     * @param string $alias
     * @return Module|void
     */
    public function findByAlias(string $alias);

    /**
     * Boot the modules.
     */
    public function boot(): void;

    /**
     * Register the modules.
     */
    public function register(): void;

    /**
     * Get asset path for a specific module.
     *
     * @param string $module
     * @return string
     */
    public function assetPath(string $module): string;

    /**
     * Delete a specific module.
     * @param string $module
     * @return bool
     * @throws \Nwidart\Modules\Exceptions\ModuleNotFoundException
     */
    public function delete(string $module): bool;

    /**
     * Determine whether the given module is activated.
     * @param string $name
     * @return bool
     * @throws ModuleNotFoundException
     */
    public function isEnabled(string $name) : bool;

    /**
     * Determine whether the given module is not activated.
     * @param string $name
     * @return bool
     * @throws ModuleNotFoundException
     */
    public function isDisabled(string $name) : bool;

    /**
     * Determine whether the given module exist.
     *
     * @param $name
     * @return bool
     */
    public function exists(string $name) : bool;

    /**
     * Delete a specific module.
     * @param string $name
     * @return bool
     * @throws \Nwidart\Modules\Exceptions\ModuleNotFoundException
     */
}
