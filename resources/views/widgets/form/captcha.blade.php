<?php $name = 'g-recaptcha-response';?>

<div class="form-group {!! $errors->has($name) ? 'has-error' : null !!}">
    {!! app('captcha')->display()!!}
    <p class="help-block">{!! $errors->first($name) !!}</p>
</div>