<?php
namespace PseudoORM\Annotations;

use Addendum\Annotation;

class Join extends Annotation
{
    public $joinTable;
    public $joinColumn;
}
