<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Pivovarov aka AngryDeer http://studioweb.pro
 * Date: 25.01.16
 * Time: 4:46
 */
$attributes = ['class' => 'btn btn-primary btn-default'];

if (isset($is_disabled) && $is_disabled) {
    $attributes = $attributes + ['disabled'];
}
?>
<div class="form-group">
{!! Form::submit($title, $attributes) !!}
</div>