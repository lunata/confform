@extends('layouts.master')

@section('title')
{{ trans('navigation.users') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
@stop

@section('content')
        <h1>{{ trans('navigation.users') }}</h1>
        <h2>{{ trans('messages.editing')}} {{ trans('auth.of_user')}} "{{ $user->name}}"</h2>
        <p>
            <a href="{{ LaravelLocalization::localizeURL('/user') }}">
                {{ trans('messages.back_to_list') }}</a>
        </p>
        
        {!! Form::model($user, array('method'=>'PUT', 'route' => array('user.update', $user->id))) !!}
        @include('user._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
@stop

@section('jqueryFunc')
    $(".select-country").select2({
        placeholder: "{{trans('user.select_country')}}",
        allowClear: true
    });
    
    $(".select-region").select2({
        placeholder: "{{trans('user.select_region')}}",
        allowClear: true,
        width: 'resolve',
        ajax: {
          url: "/user/region_list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              country_id: $( "#country_id option:selected" ).val()
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },          
          cache: true
        }
    });
    
    $(".select-city").select2({
        placeholder: "{{trans('user.select_city')}}",
        allowClear: true,
        width: 'resolve',
        ajax: {
          url: "/user/city_list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              country_id: $( "#country_id option:selected" ).val(),
              region_id: $( "#region_id option:selected" ).val()
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },          
          cache: true
        }
    });
@stop
