<?php
namespace PseudoORM\DAO;

use PseudoORM\Services\IDatabaseCreator;
use PseudoORM\Entity\EntidadeBase;
use PseudoORM\Exception;
use \PDO;

use Addendum\ReflectionAnnotatedClass;

class GenericDAO implements IGenericDAO
{

    protected $type;
    protected $tableName;

    public function __construct($type)
    {
        $classe = new ReflectionAnnotatedClass($type);
        $this->type = $classe->getName();
        $this->setTableName();
    }

    private function setTableName()
    {
        $classe = new ReflectionAnnotatedClass($this->type);
        if ($classe->hasAnnotation('Table') && $classe->getAnnotation('Table') != '') {
            $this->tableName = strtolower($classe->getAnnotation('Table')->value);
            return;
        }
        $this->tableName = strtolower($classe->getShortName());
    }
    
    public function create()
    {
        return new $this->type();
    }

    /**
     * {@inheritDoc}
     */
    public function getById($uid)
    {
        $connection = new PDO(
            DB_DSN,
            DB_USERNAME,
            DB_PASSWORD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
        );
        $sql  = " SELECT * FROM "  . SCHEMA . strtolower($this->type) ." where uid = :uid";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->type);
        $stmt->execute();
        $object = $stmt->fetch();
        $connection = null;
        return $object;
    }

    /**
     * {@inheritDoc}
     */
    public function getList($sortColumn = null, $sortOrder = 'ASC', $limit = 1000000, $offset = 0)
    {
        $connection = new PDO(
            DB_DSN,
            DB_USERNAME,
            DB_PASSWORD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
        );
        $sql  = " SELECT * FROM " . SCHEMA . $this->tableName .
            ($sortColumn != null ? " ORDER BY $sortColumn $sortOrder " : '') .
            " LIMIT :limit OFFSET :offset; ";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->type);
        $stmt->execute();
        $list = $stmt->fetchAll();
        $connection = null;
        return $list;
    }

    /**
     * {@inheritDoc}
     */
    public function insert(EntidadeBase $object)
    {
        $connection = new PDO(
            DB_DSN,
            DB_USERNAME,
            DB_PASSWORD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
        );
        //TODO extract to a method. Too much responsability
        $obj = new \ReflectionObject($object);
        $parametros = array();
        $attributos = array();
        foreach ($obj->getProperties() as $property) {
            $property->setAccessible(true);
            if ($property->name != 'uid' && ($property->getValue($object) != '')) {
                $attributos[$property->name] = $property->getValue($object);
                $parametros[] = ":".$property->name;
            }
        }
        
        $queryParams = "(".implode(", ", array_keys($attributos)).") VALUES(". implode(', ', $parametros) ." )";
        try {
            $sql  = " INSERT INTO "  . SCHEMA . $this->tableName . " $queryParams RETURNING uid;";
            $stmt = $connection->prepare($sql);
            $this->bindArrayValue($stmt, $attributos);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $uid = $result['uid'];
            $connection = null;
        } catch (Exception $e) {
            $message = "Existem relacionamentos vinculados à esse objeto. Impossível excluir.";
            throw new RelacionamentoException($message);
            $connection = null;
        }
        return $uid;
    }


    /**
     * {@inheritDoc}
     */
    public function update(EntidadeBase $object)
    {
        $connection = new PDO(
            DB_DSN,
            DB_USERNAME,
            DB_PASSWORD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
        );
        //TODO extract to a method. Too much responsability
        $obj = new \ReflectionObject($object);
        $parametros = array();
        $attributos = array();
        foreach ($obj->getProperties() as $property) {
            $property->setAccessible(true);
            if ($property->name != 'uid' && ($property->getValue($object) != '')) {
                $attributos[] = $property->name;
                $parametros[] = ":".$property->name;
            }
        }
        
        $queryParams = implode(', ', $parametros);

        try {
            $sql = "UPDATE " . SCHEMA . $this->tableName . " SET " . $queryParams . ' WHERE uid = :uid;';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(":uid", $object->uid, PDO::PARAM_INT);
            $this->bindArrayValue($stmt, $attributos);
            $stmt->execute();
            $connection = null;
        } catch (Exception $e) {
            $message = "Existem relacionamentos vinculados à esse objeto. Impossível excluir.";
            throw new RelacionamentoException($message);
            $connection = null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete($uid)
    {
        if (is_null($uid)) {
            throw new Exception("Erro ao remover registro.");
        }
        try {
            $connection = new PDO(
                DB_DSN,
                DB_USERNAME,
                DB_PASSWORD,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
            );
            $sql  = " DELETE FROM "  . SCHEMA . $this->tableName . " where uid = :uid";
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
            $stmt->execute();
            $connection = null;
        } catch (Exception $e) {
            $message = "Existem relacionamentos vinculados à esse objeto. Impossível excluir.";
            throw new RelacionamentoException($message);
            $connection = null;
        }
    }


    /**
     *
     * @param string $req : the query on which link the values
     * @param array $array : associative array containing the values ​​to bind
     * @param array $typeArray : associative array with the desired value for its corresponding key in $array
     */
    private function bindArrayValue(\PDOStatement $query, $array, $typeArray = false)
    {
        foreach ($array as $key => $value) {
            if ($typeArray) {
                $query->bindValue(":$key", $value, $typeArray[$key]);
            } else {
                $valor = $value;
                $param = false;
                if (is_int($valor)) {
                    $param = PDO::PARAM_INT;
                } elseif (is_bool($valor)) {
                    $param = PDO::PARAM_BOOL;
                } elseif (is_null($valor)) {
                    $param = PDO::PARAM_NULL;
                } elseif (is_string($valor)) {
                    $param = PDO::PARAM_STR;
                }
                    
                if ($param) {
                    $query->bindValue(":$key", $valor, $param);
                }
            }
        }
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function geraScriptDeCriacaoDoBancoDeDados(IDatabaseCreator $creator)
    {
        $script = $creator->scriptCreation($this->type, true);
        
        return $script;
    }

    public function criaBancoDeDados(IDatabaseCreator $creator)
    {
        $script = $this->geraScriptDeCriacaoDoBancoDeDados($creator);
        try {
            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
            $dbh->exec($script);
        } catch (PDOException $e) {
            echo ("DB ERROR: ". $e->getMessage());
        }
    }
}
