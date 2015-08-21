<?php

ini_set("display_errors", 1);

// Pode-se utilizar um Autoload ou SPL ou o que preferir.
require_once 'model/Entity/EntidadeBase.php';
require_once 'model/Entity/Usuario.php';
require_once 'model/DAO/IGenericDAO.php';
require_once 'model/DAO/impl/GenericDAO.php';
require_once 'model/Factory/AppFactory.php';
require_once 'model/exception/RelacionamentoException.php';


// Crie um arqui para definir e carregar essas configurações
// DB
define( "DB_USERNAME", "postgres" );
define( "DB_PASSWORD", "postgres" );
define( "DB_HOST", 'localhost');
define( "DB_PORT", 5432);
define( "DB_NAME", 'meu_db');
define( "SCHEMA", '' );
define( 'ENCODING', "SET NAMES 'utf8';");
define( "DB_DSN", "pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";");
define( "SHOW_SQL_ERROR", PDO::ERRMODE_EXCEPTION);
// PATHS
define('MODELS', '../app/models/' );
define('DAOS', MODELS . 'DAO/impl/' );
define('EXCEPTIONS', MODELS . 'exception/' );



/**
 * Exemplo de uso
 */

$dao = AppFactory::getRepository(new Usuario());

$usuario = $dao->create();
$usuario->nome = 'Zé da Silva';
$usuario->idade = 25;

$dao->insert($usuario);


$usuarios = $dao->getList();

foreach ($usuarios as $usuario){
	echo $usuario->nome.'<br>';
}


