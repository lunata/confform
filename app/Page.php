<?php

namespace Confform;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use \Dimsav\Translatable\Translatable;
    
    public $translatedAttributes = ['title', 'page'];
}
