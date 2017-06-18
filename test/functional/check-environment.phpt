--TEST--
Confirmando se o ambiente está funcionando.
--FILE--
<?php
require 'vendor/autoload.php';

define("DB_USERNAME", getenv('db_username'));
define("DB_PASSWORD", getenv('db_password'));
define("DB_HOST", getenv('db_host'));
define("DB_PORT", 5432);
define("DB_NAME", 'pseudoorm');
define("SCHEMA", '');
define('ENCODING', "SET NAMES 'utf8';");

/**
 * @todo Remover constantes
 */
define("DB_DSN", "pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";");
define("SHOW_SQL_ERROR", PDO::ERRMODE_EXCEPTION);

use PseudoORM\Entity\Usuario;
use PseudoORM\Factory\AppFactory;
use PseudoORM\Services\PostgreSQLDatabaseCreator;

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
$dao->criaBancoDeDados(new PostgreSQLDatabaseCreator());

// Realizar operações básicas
$usuario = $dao->create();
$usuario->setNome('Zé da Silva');
$usuario->setIdade(25);
$usuario->setCpf('000555111');
$usuario->setSenha('123456');
$usuario->setAtivo(1);

$dao->insert($usuario);


$usuarios = $dao->getList();

print_r($usuarios);
?>
--EXPECTF--
Array
(
    [0] => PseudoORM\Entity\Usuario Object
        (
            [nome:PseudoORM\Entity\Usuario:private] => Zé da Silva
            [cpf:PseudoORM\Entity\Usuario:private] => 000555111
            [idade:PseudoORM\Entity\Usuario:private] => 25
            [senha:PseudoORM\Entity\Usuario:private] => 123456
            [ativo:PseudoORM\Entity\Usuario:private] => 1
            [uid:protected] => 1
        )

)
