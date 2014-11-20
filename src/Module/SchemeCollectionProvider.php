<?php
/**
 * This file is part of the BEAR.Sunday package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Resource\Module;

use BEAR\Resource\AppAdapter;
use BEAR\Resource\Exception\InvalidAppNameException;
use BEAR\Resource\SchemeCollection;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;

class SchemeCollectionProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $appName;

    /**
     * @var InjectorInterface
     */
    protected $injector;

    /**
     * @param string $appName
     *
     * @return void
     *
     * @throws \BEAR\Resource\Exception\InvalidAppNameException
     * @Inject
     * @Named("appName=app_name")
     */
    public function setAppName($appName)
    {
        if (! is_string($appName)) {
            throw new InvalidAppNameException($appName);
        }
        $this->appName = $appName;
    }

    /**
     * @param InjectorInterface $injector
     *
     * @Inject
     */
    public function setInjector(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * Return instance
     *
     * @return SchemeCollection
     */
    public function get()
    {
        $schemeCollection = new SchemeCollection;
        $pageAdapter = new AppAdapter($this->injector, $this->appName, 'Resource\Page');
        $appAdapter = new AppAdapter($this->injector, $this->appName, 'Resource\App');
        $schemeCollection->scheme('page')->host('self')->toAdapter($pageAdapter);
        $schemeCollection->scheme('app')->host('self')->toAdapter($appAdapter);

        return $schemeCollection;
    }
}
