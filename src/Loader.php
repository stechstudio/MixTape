<?php
namespace STS\Mixtape;

use Composer\Autoload\ClassLoader;
use Closure;

/**
 * Class Loader
 * @package STS\Mixtape
 */
class Loader
{
    /**
     * @var ClassLoader
     */
    protected $loader;

    /**
     * @var Closure
     */
    protected $unsetPsr4Closure;

    /**
     * @var Closure
     */
    protected $unsetClassMapClosure;

    /**
     * Mixtape constructor.
     *
     * @param ClassLoader $loader
     */
    public function __construct(ClassLoader $loader)
    {
        $this->loader = $loader;

        $this->bindClosures();
    }

    /**
     * @param $prefix
     */
    public function removePsr4($prefix)
    {
        call_user_func($this->unsetPsr4Closure, $this->loader, $prefix);
        $this->unsetClassMap($prefix);
    }

    /**
     * @param $prefix
     * @param $paths
     */
    public function replacePsr4($prefix, $paths)
    {
        $this->unsetClassMap($prefix);
        $this->loader->setPsr4($prefix, $paths);
    }

    /**
     * @param $prefix
     */
    public function unsetClassMap($prefix)
    {
        call_user_func($this->unsetClassMapClosure, $this->loader, $prefix);
    }

    /**
     *
     */
    protected function bindClosures()
    {
        $this->unsetClassMapClosure = Closure::bind(function(ClassLoader $loader, $prefix) {
            $loader->classMap = array_filter($loader->classMap, function($key) use($prefix) {
                return strpos($key, $prefix) !== 0;
            }, ARRAY_FILTER_USE_KEY);
        }, null, $this->loader);

        $this->unsetPsr4Closure = Closure::bind(function(ClassLoader $loader, $prefix) {
            if(array_key_exists($prefix, $loader->prefixDirsPsr4)) {
                unset($loader->prefixDirsPsr4[$prefix]);
                unset($loader->prefixLengthsPsr4[$prefix[0]][$prefix]);
            }
        }, null, $this->loader);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->loader, $name], $arguments);
    }
}
