<?php

namespace App\Modules;

use App\Source\Events\BaseAppEvent;
use App\Source\Events\BaseLoggerEvent;

class ModuleManager implements IModulesManager
{
    /**
     * @var array
     */
    protected static $moduleContainer = [];

    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var mixed
     */
    protected $container;

    private static $instance = null;

    /**
     * @param $container
     * @param $app
     */
    protected function __construct($container, $app)
    {
        $this->app = $app;
        $this->container = $container;
    }

    public static function getInstance($container='', $app='')
    {
        if (!self::$instance) {
            self::$instance = new self($container, $app);
        }

        return self::$instance;
    }

    /**
     * @param $module
     */
    public function registerModule(IModule $module)
    {
        self::$moduleContainer[$module->getName()] = $module;
    }

    /**
     * @param $name
     */
    public function install($name)
    {}

    /**
     * @param $name
     */
    public function uninstall($name)
    {}

    public function coreInit()
    {
        $module = $this->getModule('core');

        if (!$module) {
            throw new \RuntimeException('Core module not found.');
        }

        if ($module->isInitModule()) {
            return;
        }

        $this->container['modules'] = $this->getModulesName();

        $module->initialization($this->app);

        $event = new BaseAppEvent($this->app);

        //$this->container->dispatcher->dispatch('module.core.beforeRegister.route', $event);
        $module->registerRoute();
        //$this->container->dispatcher->dispatch('module.core.afterRegister.route', $event);
        //$this->container->dispatcher->dispatch('module.core.beforeRegister.di', $event);
        $module->registerDi();
        //$this->container->dispatcher->dispatch('module.core.afterRegister.di', $event);
        //$this->container->dispatcher->dispatch('module.core.beforeRegister.middleware', $event);
        $module->registerMiddleware();
        //$this->container->dispatcher->dispatch('module.core.afterRegister.middleware', $event);
        $module->afterInitialization();
        $this->container->dispatcher->dispatch('module.core.afterInitialization', $event);

        return $this;
    }

    public function boot()
    {
        foreach ($this->getModules() as $module) {
            if ($module->isInitModule()) {
                continue;
            }
            $name = $module->getName();

            $event = new BaseAppEvent($this->app, $module);

            $this->container->dispatcher->dispatch('module.' . $name . '.beforeInitialization', $event);
            $module->initialization($this->app);
            //$this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.route', $event);
            $module->registerRoute();
            //$this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.route', $event);
            //$this->container->dispatcher->dispatch('module.'.$name.'.beforeRegister.di', $event);
            $module->registerDi();
            //$this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.di', $event);
            //$this->container->dispatcher->dispatch('module.'.$name.'.beforeRegister.middleware', $event);
            $module->registerMiddleware();
            //$this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.middleware', $event);
            $module->afterInitialization();
            $event = new BaseLoggerEvent($this->container->logger, $module);
            $this->container->dispatcher->dispatch('module.' . $name . '.afterInitialization', $event, $module);
        }

        $this->container->dispatcher->dispatch('module.allModuleLoaded');
    }

    public function getModules()
    {
        return self::$moduleContainer;
    }

    protected function getModulesName()
    {
        $arModules = [];
        foreach ($this->getModules() as $module) {
            $arModules[] = $module->getName();
        }
        return $arModules;
    }

    /**
     * @param $name
     */
    public function getModule($name)
    {
        return self::$moduleContainer[$name];
    }
}
