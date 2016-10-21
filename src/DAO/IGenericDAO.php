<?php
namespace PseudoORM\DAO;

use PseudoORM\Entity\EntidadeBase;
use PseudoORM\Services\IDataBaseCreator;


interface IGenericDAO
{

    /**
     * Obtém um objeto por meio do seu UID
     *
     * @param int $uid UID do objeto que se deseja recuperar
     *
     * @return EntidadeBase o objeto que possui o UID informado;
     */
    public function getById($uid);
    /**
     * Obtém uma lista de objetos
     *
     * @param string $sortColumn (opcional) A coluna que se deseja ordenar.
     * @param string $sortOrder  (opcional) ASC|DESC Direção de ordenação.
     * @param number $limit      (opcional) O número máximo de objetos retornados.
     * @param number $offset     (opcional) O registro de início.
     *
     * @return array Uma lista de objetos.
     */
    public function getList($sortColumn = null, $sortOrder = 'ASC', $limit = 1000000, $offset = 0);
    /**
     * Remove um objeto
     * @param int $uid o id do objeto a ser removido
     */
    public function delete($uid);
    /**
     * Persiste um objeto no banco de dados
     * @param EntidadeBase $entidade o objeto a ser persistido
     */
    public function insert(EntidadeBase $entidade);

    /**
     * Atualiza um objeto no banco de dados
     * @param EntidadeBase $entidade o objeto a ser atualizado
     */
    public function update(EntidadeBase $entidade);
    
    /**
     * Gera script de criação do banco de dados e permite a criação automatica.
     * @param bolean $create True to create database automatically | False To print script in screen
     */
    public function generate(IDataBaseCreator $creator, $create=false);
}
