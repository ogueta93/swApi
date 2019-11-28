<?php
// src/Base/AbstractService.php
namespace App\Base;

use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractService
{
    protected $container;

    protected $configName = null;
    protected $config = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->setConfigLoader();
        $this->setCustomParams();
    }

    /**
     * Sets config loaders
     */
    protected function setConfigLoader()
    {
        $this->setConfigName();

        if (!is_null($this->configName)) {
            $configDirectories = array($this->container->get('kernel')->getProjectDir() . '/config');
            $fileLocator = new FileLocator($configDirectories);
            $file = $fileLocator->locate($this->getConfigName(), null, false);

            $this->config = Yaml::parseFile($file[0]);
        }
    }

    /**
     * Returns configName
     *
     * @param string $configName
     */
    protected function getConfigName()
    {
        return $this->configName . '.yaml';
    }

    /**
     * Sets config name
     */
    abstract protected function setConfigName();

    /**
     * Sets custom params in the __construct
     */
    abstract protected function setCustomParams();
}