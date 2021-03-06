@extends('layouts.master')

@section('title')
{{ trans('page.pages') }}
@stop

@section('headExtra')
    <script src="{{ asset('/js/ckeditor/ckeditor.js') }}" type="text/javascript" charset="utf-8" ></script>
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/bootstrap-datepicker3.min.css')!!}
@stop

@section('content')
        <h2>{{ trans('page.pages') }}</h2>
        @include('page._'.$action)
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
