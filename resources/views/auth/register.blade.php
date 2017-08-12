@extends('layouts.master')

@section('title')
{{ trans('navigation.registration') }}
@stop

@section('headExtra')
    <script src="https://www.google.com/recaptcha/api.js"></script>
    {!!Html::style('css/select2.min.css')!!}
@stop

@section('content')
<h1>{{trans('navigation.registration')}}</h1>
    {!! Form::open(['files'=>true]) !!}
<div class="row">   
    <div class="col col-sm-6">
        @include('widgets.form._formitem_text', ['name' => 'email', 'title' => 'Email', 'attributes'=>['placeholder' => 'Email' ]])
        @include('widgets.form._formitem_password', ['name' => 'password', 'title' => trans('auth.password'), 'placeholder' => trans('auth.password') ])
        @include('widgets.form._formitem_password', ['name' => 'password_confirm', 'title' => trans('auth.password_confirm'), 'placeholder' => trans('auth.password') ])
    </div>
    <div class="col col-sm-6">
        @include('widgets.form._formitem_select2',
                ['name' => 'country_id',
                 'title' => trans('user.country'),
                 'values' => $country_values,
                 'is_multiple' => false,
                 'class'=>'form-control select-country'                            
        ])
        
        @include('widgets.form._formitem_select2',
                ['name' => 'region_id',
                 'title' => trans('user.region'),
                 'values' => NULL,
                 'is_multiple' => false,
                 'class'=>'form-control select-region'                            
        ])
        
        @include('widgets.form._formitem_select2',
                ['name' => 'city_id',
                 'title' => trans('user.city'),
                 'values' => NULL,
                 'is_multiple' => false,
                 'tooltip'=> trans('user.city_tooltip'),
                 'class'=>'form-control select-city'                            
        ])
    </div>
</div>                 
 
    @include('user._form_transl_fields')

<div class="row">   
    <div class="col col-sm-6">
    @include('widgets.form.captcha')
    </div>
    <div class="col col-sm-6">    
    @include('widgets.form._formitem_btn_submit', ['title' => trans('auth.register')])
    </div>
</div>                 
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
