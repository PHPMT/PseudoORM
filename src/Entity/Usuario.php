<?php
namespace PseudoORM\Entity;

/**
 * @Persistent
 * @Table('Usuario')
 */
class Usuario extends EntidadeBase
{
	
	/**
	 * @Column(name='nome')
	 */
	public $nome="";
	
	/**
	 * @Column(name='cpf')
	 */
	public $cpf="";
	 
	/**
	 * @Column(name='idade', type='integer')
	 */
	public $idade=0;
	 
	/**
	 * @Column(name='senha')
	 */
	public $senha="";
	 
	/**
	 * @Column(name='ativo', type='boolean')
	 */
	public $ativo=false;

}