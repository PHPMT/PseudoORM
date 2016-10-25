<?php
ini_set("display_errors",1);

define("DB_USERNAME", "postgres");
define("DB_PASSWORD", "postgres");
define("DB_HOST", 'localhost');
define("DB_PORT", 5432);
define("DB_NAME", 'meu_db');
define("SCHEMA", '');
define('ENCODING', "SET NAMES 'utf8';");
define("DB_DSN", "pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";");
define("SHOW_SQL_ERROR", PDO::ERRMODE_EXCEPTION);
// PATHS
define('MODELS', '../app/models/');
define('DAOS', MODELS . 'DAO/impl/');
define('EXCEPTIONS', MODELS . 'exception/');

use PseudoORM\Entity\Usuario;
use PseudoORM\Factory\AppFactory;
use PseudoORM\Services\PostgreSQLDataBaseCreator;



$composer_autoload = 'vendor/autoload.php';
if (false === file_exists($composer_autoload)) {
        throw new RuntimeException('Por favor instalar as dependências do composer.');
}

include $composer_autoload;

require_once 'src/Annotations/Column.php';
require_once 'src/Annotations/Id.php';
require_once 'src/Annotations/Table.php';
require_once 'src/Annotations/Join.php';
require_once 'src/Annotations/Persistent.php';


/**
 * Exemplo de uso
 */

$dao = AppFactory::getRepository(new Usuario());

// USe para gerar o script de criação do banco
echo '<pre>'.$dao->generate(new PostgreSQLDataBaseCreator()).'</pre>';



// Realizar operações básicas
$usuario = $dao->create();
$usuario->setNome('Zé da Silva');
$usuario->setIdade(25);
$usuario->setCpf('000555111');
$usuario->setSenha('123456');

$dao->insert($usuario);


$usuarios = $dao->getList();

echo "<Br><Br>";
foreach ($usuarios as $usuario) {
    echo $usuario->getNome().'<br>';
}
