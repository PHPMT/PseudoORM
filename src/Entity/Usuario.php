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
	private $nome="";
	
	/**
	 * @Column(name='cpf')
	 */
	private $cpf="";
	 
	/**
	 * @Column(name='idade', type='integer')
	 */
	private $idade=0;
	 
	/**
	 * @Column(name='senha')
	 */
	private $senha="";
	 
	/**
	 * @Column(name='ativo', type='boolean')
	 */
	private $ativo=false;

	
	
	public function getNome()
	{
		return $this->nome;
	}
	
	public function setNome($nome)
	{
		$this->nome = $nome;
	}
	
	public function getCpf()
	{
		return $this->nome;
	}
	
	public function setCpf($cpf)
	{
		$this->cpf = $cpf;
	}
	
	public function getIdade()
	{
		return $this->idade;
	}
	
	public function setIdade($idade)
	{
		$this->idade = $idade;
	}
	
	public function getSenha()
	{
		return $this->senha;
	}
	
	public function setSenha($senha)
	{
		$this->senha = $senha;
	}
	
	public function isAtivo()
	{
		return $this->ativo;
	}
	
	public function setAtivo($ativo)
	{
		$this->ativo = $ativo;
	}
}