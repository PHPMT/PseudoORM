<?php
namespace PseudoORM\Factory;

use PseudoORM\Entity\EntidadeBase;
use \Exception;

class AppFactory
{
    private static $instance;

    /**
     * Set the factory instance for DI
     * @param App_DaoFactory $factory
     */
    public static function setFactory(AppDaoFactory $factory)
    {
        self::$instance = $factory;
    }

    /**
     * Get a factory instance.
     * @return App_DaoFactory
     */
    public static function getFactory()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function getRepository(EntidadeBase $objeto)
    {
        $class = get_class($objeto);
        $classShortName = (new \ReflectionClass($objeto))->getShortName();

        $classDAO = '\\PseudoORM\\DAO\\' . $classShortName . 'DAO';

        $entityName = class_exists($classDAO)
            ? $classShortName
            : 'Generic'
        ;

        $repository = '\\PseudoORM\\DAO\\' . $entityName . 'DAO';

        return new $repository($class);
    }
}
