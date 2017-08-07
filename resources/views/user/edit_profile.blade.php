@extends('layouts.master')

@section('title')
{{ trans('navigation.profile') }}
@stop

@section('content')
        <h1>{{ trans('navigation.profile') }}</h1>
        
        {!! Form::model($user, ['method'=>'POST', 'route' => ['profile.update']]) !!}

        @include('widgets.form._formitem_text', 
                ['name' => 'email', 
                 'title'=> 'E-mail'])
                 
        @include('user._form_transl_fields',
                ['translated_fields'=>$user->getTranslatedFields(),
                 'prim_lang' => $user->getPrimLang(),
                 'add_lang' => $user->getAddLang()])
                 
        @include('widgets.form._formitem_btn_submit', ['title' => trans('messages.save')])
        
        {!! Form::close() !!}
@stop
