# PseudoORM
App de persistência Genérica para fins didáticos usando a interface do PDO.

## Getting started

 * Clone o repositorio  para sua maquina ` git clone https://github.com/HoraExtraSinop/PseudoORM `
 * Altere as configurações do banco no arquivo `index.php` que é apenas um arquivo de exemplo.

Para criar novas classes, basta criar uma entidade na pasta `model/Entity/` extendendo da classe `EntidadeBase` e criar a respectiva tabela no banco de dados, e está pronto para usar.

Para modificar o comportamento padrão, basta criar um Arquivo de persistencia extendendo a classe `GenericDAO` dentro da pasta `DAO/impl` podendo sobrescrever métodos existentes e/ou adicionar novos. 


## Para criar o banco no Postgresql use:

```
-- Table: usuario

-- DROP TABLE usuario;

CREATE TABLE usuario
(
  uid serial NOT NULL,
  nome character varying,
  idade integer,
  CONSTRAINT usuario_pkey PRIMARY KEY (uid)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE usuario
  OWNER TO postgres;

```
