<?php
namespace PseudoORM\Factory;

use PseudoORM\Entity\EntidadeBase;
use PseudoORM\DAO\GenericDAO;
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
        $classShortName = (new \ReflectionClass($objeto))->getShortName();

        $repository = '\\PseudoORM\\DAO\\' . $classShortName . 'DAO';

        if(class_exists($repository))
            return new $repository((new \ReflectionClass($objeto))->getName());

        return new GenericDAO((new \ReflectionClass($objeto))->getName());
    }
}