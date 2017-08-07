<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Pivovarov aka AngryDeer http://studioweb.pro
 * Date: 25.01.16
 * Time: 4:51
 */?>
@extends('layouts.master')
@section('content')
<h1>{{trans('navigation.registration')}}</h1>
    {!! Form::open() !!}
<div class="row">   
    <div class="col col-sm-6">
    @include('widgets.form._formitem_text', ['name' => 'email', 'title' => 'Email', 'attributes'=>['placeholder' => 'Email' ]])
    @include('widgets.form._formitem_password', ['name' => 'password', 'title' => trans('auth.password'), 'placeholder' => trans('auth.password') ])
    @include('widgets.form._formitem_password', ['name' => 'password_confirm', 'title' => trans('auth.password_confirm'), 'placeholder' => trans('auth.password') ])
    </div>
    <div class="col col-sm-6">
    </div>
</div>                 
 
    @include('user._form_transl_fields')

    @include('widgets.form._formitem_btn_submit', ['title' => trans('auth.register')])
    {!! Form::close() !!}
@stop