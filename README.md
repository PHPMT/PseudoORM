# PseudoORM
App de persistência para fins didáticos


Para criar o banco no Postgresql use:

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