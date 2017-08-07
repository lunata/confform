<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Pivovarov aka AngryDeer http://studioweb.pro
 * Date: 25.01.16
 * Time: 4:45
  * Updated: 24.08.2016 by Nataly Krizhanovsky
*/?>
<?php
if(!isset($value)) {
    $values = [];
}   
if(! isset($title)) $title = null;
?>
<div class="{!! $errors->has($name) ? 'has-error' : null !!}">
    @if ($title) 
    <p><b>{{ $title }}</b></p>
    @endif
    @foreach($values as $v=>$t)
        {!! Form::checkbox($name, $v, in_array($v,$value)) !!}
        {!! Form::label($name, $t) !!}<br>
    @endforeach 
    <p class="help-block">{!! $errors->first($name) !!}</p>
</div>