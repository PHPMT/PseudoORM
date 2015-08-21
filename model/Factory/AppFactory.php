<?php


class AppFactory
{
	private static $_instance;

	public function __construct(){}

	/**
	 * Set the factory instance for DI
	 * @param App_DaoFactory $f
	 */
	public static function setFactory(AppDaoFactory $f)
	{
		self::$_instance = $f;
	}

	/**
	 * Get a factory instance.
	 * @return App_DaoFactory
	 */
	public static function getFactory()
	{
		if(!self::$_instance)
			self::$_instance = new self;

		return self::$_instance;
	}
	
	public static function getRepository(EntidadeBase $objeto){
		try{
			$class = get_class($objeto);
			$respositoryPath = DAOS . $class . 'DAO.php';
			if(!file_exists($respositoryPath))
				return new GenericDAO($class);
			require_once $respositoryPath;
			$repository = $class . 'DAO';
			return new $repository;
		} catch (Exception $e){
			die($e->getTrace());
		}
	}
	
	
	/**
	 * TODO create an adequate SPL loader for DI
	 */
	

}
