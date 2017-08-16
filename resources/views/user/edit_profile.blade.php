@extends('layouts.master')

@section('title')
{{ trans('navigation.profile') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
@stop

@section('content')
        <h1>{{ trans('navigation.profile') }}</h1>
        
        {!! Form::model($user, ['method'=>'POST', 'route' => ['profile.update']]) !!}

<div class='row'>
    <div class="col col-sm-4">
        <p><b>E-mail</b><br>
            {{$user->email}}</p>
    </div>
    <div class="col col-sm-4">
        <p><b>{{trans('auth.roles')}}</b><br>
            {{join('<br>',$role_value)}}</p>
    </div>
    <div class="col col-sm-4">
        <p><b>{{trans('auth.permissions')}}</b><br>
            {!!join('<br>',$perm_value)!!}</p>
    </div>
</div>
                 
        @include('user._form_transl_fields',
                ['translated_fields'=>$user->getTranslatedFields(),
                 'prim_lang' => $user->getPrimLang(),
                 'add_lang' => $user->getAddLang()])
<div class='row'>
    <div class="col col-sm-4">
        @include('widgets.form._formitem_select2',
                ['name' => 'country_id',
                 'title' => trans('user.country'),
                 'value' => [$user->country_id],
                 'values' => $country_values,
                 'is_multiple' => false,
                 'class'=>'form-control select-country'                            
        ])
    </div>
    <div class="col col-sm-4">
        @include('widgets.form._formitem_select2',
                ['name' => 'region_id',
                 'title' => trans('user.region'),
                 'value' => [$region_id],
                 'values' => $region_values,
                 'is_multiple' => false,
                 'class'=>'form-control select-region'                            
        ])
    </div>
    <div class="col col-sm-4">      
        @include('widgets.form._formitem_select2',
                ['name' => 'city_id',
                 'title' => trans('user.city'),
                 'value' => [$user->city_id],
                 'values' => $city_values,
                 'is_multiple' => false,
                 'tooltip'=> trans('user.city_tooltip'),
                 'class'=>'form-control select-city'                            
        ])
    </div>
</div>
                 
        @include('widgets.form._formitem_btn_submit', ['title' => trans('messages.save')])
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
