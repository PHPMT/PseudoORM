<?php


class Persistent extends Annotation {}  
  
/** @Target("class") */
class Table extends Annotation {}  

/** @Target("property") */
class Column extends Annotation {
    public $name;
    public $type;
}

/** @Target("property") */
class Id extends Annotation {}

class Join extends Annotation {
    public $joinTable;
    public $joinColumn;
}
