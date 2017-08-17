<?php 
if(!isset($value)) 
    $value = null;
if(!isset($title)) 
    $title = null;
if(!isset($tail)) 
    $tail = null;

if (!isset($attributes)) {
    $attributes = [];
}

if(isset($attributes['size'])) {
    $attributes['class'] = 'form-control-sized';
    
} elseif (!isset($attributes['class'])) {
    $attributes['class'] = 'form-control';
}
/*
if(!isset($attributes['placeholder'])) {
    $attributes['placeholder'] = $title;
}*/    
?>
<div class="form-group {!! $errors->has($name) ? 'has-error' : null !!}">
    @if($title)
	<label for="{{$name}}">{{ $title }}&nbsp;</label>
    @endif
    {!! Form::date($name, $value, $attributes) !!}
    {{ $tail }}                                    
    <p class="help-block">{!! $errors->first($name) !!}</p>
</div>