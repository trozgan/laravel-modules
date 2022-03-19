<?php

namespace Nwidart\Modules\Process;

use Nwidart\Modules\Contracts\ModuleInterface;

class Updater extends Runner
{
    /**
     * Update the dependencies for the specified module by given the module name.
     * @param string $module
     * @throws \Nwidart\Modules\Exceptions\ModuleNotFoundException
     */
    public function update($module)
    {
        $module = $this->module->findOrFail($module);

        chdir(base_path());

        $this->installRequires($module);
        $this->installDevRequires($module);
        $this->copyScriptsToMainComposerJson($module);
    }

    /**
     * Check if composer should output anything.
     *
     * @return string
     */
    private function isComposerSilenced()
    {
        return config('modules.composer.composer-output') === false ? ' --quiet' : '';
    }

    /**
     * @param ModuleInterface $module
     */
    private function installRequires(ModuleInterface $module)
    {
        $packages = $module->getComposerAttr('require', []);

        $concatenatedPackages = '';
        foreach ($packages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (!empty($concatenatedPackages)) {
            $this->run("composer require {$concatenatedPackages}{$this->isComposerSilenced()}");
        }
    }

    /**
     * @param ModuleInterface $module
     */
    private function installDevRequires(ModuleInterface $module)
    {
        $devPackages = $module->getComposerAttr('require-dev', []);

        $concatenatedPackages = '';
        foreach ($devPackages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (!empty($concatenatedPackages)) {
            $this->run("composer require --dev {$concatenatedPackages}{$this->isComposerSilenced()}");
        }
    }

    /**
     * @param ModuleInterface $module
     */
    private function copyScriptsToMainComposerJson(ModuleInterface $module)
    {
        $scripts = $module->getComposerAttr('scripts', []);

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        foreach ($scripts as $key => $script) {
            if (array_key_exists($key, $composer['scripts'])) {
                $composer['scripts'][$key] = array_unique(array_merge($composer['scripts'][$key], $script));
                continue;
            }
            $composer['scripts'] = array_merge($composer['scripts'], [$key => $script]);
        }

        file_put_contents(base_path('composer.json'), json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
