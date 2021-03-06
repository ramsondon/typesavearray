<?php
/**
 * TypedArrayFactory.php
 * copyright by Matthias Schmid <ramsondon@gmail.com>
 * Date: 08/08/14
 */

namespace Ramsondon\TypedArray;
use Ramsondon\TypedArray\Exceptions\TypedArrayFactoryInputException;
use Ramsondon\TypedArray\Generate\ClassCacheFactory;

/**
 * Class TypedArrayFactory
 * @package Ramsondon\TypedArray
 */
class TypedArrayFactory
{
    /**
     * @var string
     */
    private $namespace = '\Ramsondon\TypedArray\Cache';

    /**
     * @var null|string
     */
    private $validationMessage;

    /**
     * @var
     */
    private $cacher;

    /**
     * @param string $classname
     */
    public function create($classname)
    {
        if ( ! $this->validate($classname)) {
            throw new TypedArrayFactoryInputException($this->validationMessage);
        }

        return $this->getCacher()->get($classname);
    }

    /**
     * @return Generate\ClassCacher
     */
    private function getCacher()
    {
        if (null === $this->cacher) {
            $factory = new ClassCacheFactory();
            $this->cacher = $factory->create($this->namespace);
        }

        return $this->cacher;
    }

    /**
     * @param string $classname
     * @return bool
     */
    private function validate($classname)
    {
        if ( ! is_string($classname)) {
            $this->validationMessage = 'String as parameter expected!';
            return false;
        }

        if ( ! class_exists($classname)) {
            $this->validationMessage = sprintf('The class %s does not exists', $classname);
            return false;
        }

        return true;
    }
}
