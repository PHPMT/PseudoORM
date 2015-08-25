<?php

namespace PseudoORM\Services;

use PseudoORM\Services\IDataBaseCreator;
use PseudoORM\Entity\EntidadeBase;

class PostgreSQLDataBaseCreator implements IDataBaseCreator{
	
	protected $tableName;
	
	/**
	 * @see \PseudoORM\Services\IDataBaseCreator::scriptCreation()
	 */
	public final function scriptCreation($entity, $generateDropStatement=false){
		
		$classe = new \ReflectionAnnotatedClass($entity);
    	if(!$classe->hasAnnotation('Table') && $classe->getAnnotation('Table') != ''){
    		$this->tableName = strtolower($classe->getAnnotation('Table')->value);
    	} else {
    		$this->tableName = strtolower($classe->getShortName());
    	}
       	
    	$tabela = $this->tableName;
    	
    	$propriedades = $classe->getProperties();

    	//TODO Refactor me
    	foreach($propriedades as $propriedade){
    		if($propriedade->hasAnnotation('Column')){
    			$getter = $propriedade->name;
    			$key = $propriedade->getAnnotation('Column')->name;
    			$params = (array) $propriedade->getAnnotation('Column');
    			foreach($params as $chave=>$valor){
    				$fields[$key][$chave] = $valor;
    			}
    		}
    		if ($propriedade->hasAnnotation('Join')){
    			$params = (array) $propriedade->getAnnotation('Join');
    			foreach($params as $chave=>$valor){
    				$fields[$key][$chave] = $valor;
    			}
    		}
    	}
    
    	$script = '';
    
    	if($generateDropStatement == true){
	    	$script .= "DROP TABLE IF EXISTS ".SCHEMA.$tabela."; \n";
    	}
    	
    	$script .= "CREATE TABLE ".SCHEMA.$tabela ." ( \n";
    	$uid;
    
    	// TODO extract to method
    	foreach($fields as $key=>$value){
    		$fk;
    		if (isset($value['joinTable'])){
    			$fk = "\tCONSTRAINT ".$tabela."_".$value['joinTable']."_fk FOREIGN KEY($key)\n";
    			$fk .= "\t\tREFERENCES ".SCHEMA.$value['joinTable']."($value[joinColumn]) MATCH SIMPLE\n";
    			$fk .= "\t\tON UPDATE NO ACTION ON DELETE NO ACTION,\n";
    			$script .= "\t". $key . " integer, \n";
    		} else if($key == 'uid'){
    			$script .= "\t". $key . " serial NOT NULL, \n";
    			$uid = $key;
    		} else {
    			// TODO Refactor to automatically detect the property's type
    			$script .= "\t". $key . " " . ($value['type'] == 'integer' ? 'integer' : ($value['type'] == 'timestamp' ? 'timestamp' : 'character varying')) . ", \n";
    		}
    	}
    	$script .= @$fk;
    	$script .= "\tCONSTRAINT ".$tabela."_pk PRIMARY KEY (".$uid.") \n";
    	$script .= " );";
    	
    	return $script;
	}
	
}