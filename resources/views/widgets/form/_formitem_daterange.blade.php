<?php 
if(!isset($id)) 
    $id = null;
if(!isset($name1)) 
    $name1 = null;
if(!isset($name2)) 
    $name2 = null;
if(!isset($value1)) 
    $value1 = null;
if(!isset($value2)) 
    $value2 = null;
?>
<div class="form-group {!! $errors->has($name1) ? 'has-error' : null !!}">
    <div class="input-daterange input-group" id="{{$id}}">
        <input type="text" class="input-sm form-control" value="{{$value1}}" name="{{$name1}}" />
        <span class="input-group-addon">{{trans('messages.to')}}</span>
        <input type="text" class="input-sm form-control" value="{{$value2}}" name="{{$name2}}" />
    </div>        
    <p class="help-block">{!! $errors->first($name1) !!}</p>
</div>
