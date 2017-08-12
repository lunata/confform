<?php 
if (!isset($is_multiple)) {
    $is_multiple=true;
}

if(!isset($value)) {
    $value = [];
}
if(!isset($values)) 
    $values = array(); 

if(!isset($title)) 
    $title = null;

if(!isset($tooltip)) 
    $tooltip = '';

if (!isset($grouped)) {
    $grouped=false;
}
if (!isset($class)) {
    $class = 'multiple-select form-control';
}
?>
<div class="form-group {{ $errors->has($name) || $errors->has($name) ? 'has-error' : '' }}
        <?=isset($group_class)  ? ' '.$group_class : '';?>
        "
        <?=isset($id)  ? ' id="'.$id.'"' : '';?>
        <?=isset($style)  ? ' style="'.$style.'"' : '';?>
     >
    @if($title)
    <label for="{{ $name }}{{$is_multiple ? "[]" : ''}}">{{ $title }}</label>
    @endif
    
    <select {{$is_multiple ? "multiple=\"multiple\"" : ''}} class="{{ $class }}" 
        name="{{ $name }}{{$is_multiple ? "[]" : ''}}" id="{{ $name }}"
        title="{{$tooltip}}">
    @if ($grouped)
        @foreach ($values as $group_name=>$group_values)
        <optgroup label="{{$group_name}}">
            @foreach ($group_values as $key=>$val)
                <option value="{{$key}}"<?=(in_array($key,$value)) ? ' selected' : '';?>>{{$val}}</option>
            @endforeach
        </optgroup>
        @endforeach
    @else
        @foreach ($values as $key=>$val)
            <option value="{{$key}}"<?=(in_array($key,$value)) ? ' selected' : '';?>>{{$val}}</option>
        @endforeach
    @endif
    </select>
    
    <p class="help-block">{!! $errors->first($name) !!}</p>
</div>
