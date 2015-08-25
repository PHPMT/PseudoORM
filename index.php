<?php
ini_set("display_errors",1);

// TODO Include it in autoload
require_once 'libs/addendum/annotations.php';
require_once 'src/Annotations/Column.php';
require_once 'src/Annotations/Id.php';
require_once 'src/Annotations/Table.php';
require_once 'src/Annotations/Persistent.php';

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
use PseudoORM\Entity\Perfil;

$composer_autoload = 'vendor/autoload.php';
if (false === file_exists($composer_autoload)) {
        throw new RuntimeException('Por favor instalar as dependências do composer.');
}

include $composer_autoload;

/**
 * Exemplo de uso
 */

$dao = AppFactory::getRepository(new Usuario());

// USe para gerar o script de criação do banco
echo $dao->generate(new PostgreSQLDataBaseCreator());

// Realizar operações básicas
$usuario = $dao->create();
$usuario->nome = 'Zé da Silva';
$usuario->idade = 25;
$usuario->cpf = '000555111';
$usuario->senha  = '123456';

$dao->insert($usuario);


$usuarios = $dao->getList();

echo "<Br><Br>";
foreach ($usuarios as $usuario) {
    echo $usuario->nome.'<br>';
}
