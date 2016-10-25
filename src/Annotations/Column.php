<?php
namespace PseudoORM\Annotations;

use Addendum\Annotation;

/** @Target("property") */
class Column extends Annotation
{
    public $name;
    public $type;
}
