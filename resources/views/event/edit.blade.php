@extends('layouts.master')

@section('title')
{{ trans('navigation.event_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/bootstrap-datepicker3.min.css')!!}
@stop

@section('content')
        <h2>{{ trans('navigation.event_list') }}</h2>
        @include('event._'.$action)
        {!! Form::close() !!}
@stop

@section('footScriptExtra')
    {!!Html::script('js/bootstrap-datepicker.min.js')!!}
    {!!Html::script('js/bootstrap-datepicker.ru.min.js')!!}
    <script src="{{ asset('/js/ckeditor/ckeditor.js') }}" type="text/javascript" charset="utf-8" ></script>
    {!!Html::script('js/select2.min.js')!!}
@stop

@section('jqueryFunc')
    var editor = CKEDITOR.replace( 'editor1',{
                language: '{{$locale}}',
                customConfig: '/js/ckeditor-config.js'
                 
        });
    
    $('#started_at').datepicker({
        format: "yyyy-mm-dd",
        language: "{{$locale}}",
        orientation: "auto left"
    });
    
    $('#registr_start').datepicker({
        format: "yyyy-mm-dd",
        language: "{{$locale}}",
        orientation: "auto left"
    });
    
    $('#material_start').datepicker({
        format: "yyyy-mm-dd",
        language: "{{$locale}}",
        orientation: "auto left"
    });
    
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
