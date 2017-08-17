<?php $list_count = 1;?>
@extends('layouts.master')

@section('title')
{{ trans('navigation.conf_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/bootstrap-datepicker.min.css')!!}
@stop

@section('content')
        
        <h2>{{ trans('navigation.conf_list') }}</h2>

        <p>
        @if (User::checkAccess('conf.create'))
            <a href="{{ LaravelLocalization::localizeURL('/conference/create') }}">
        @endif
            {{ trans('messages.create_new_g') }}
        @if (User::checkAccess('conf.create'))
            </a>
        @endif
        </p>

        {!! Form::open(['url' => '/conf/',
                             'method' => 'get'])
        !!}
<div class='row'>      
    <div class='col col-sm-4'>
        @include('widgets.form._formitem_text',
                ['name' => 'search_title',
                'value' => $url_args['search_title'],
                'attributes'=>['placeholder'=>trans('conf.title')]])
    </div>
    <div class='col col-sm-1' style='text-align: right'>
        {{trans('messages.from')}}
    </div>
    <div class='col col-sm-7'>
        @include('widgets.form._formitem_daterange',
                ['id' => 'start-finish',
                 'name1' => 'search_started_after',
                 'value1' => $url_args['search_started_after'],
                 'name2' => 'search_finished_before',
                 'value2' => $url_args['search_finished_before'],
                ])
    </div>
</div>
<div class='row'>      
    <div class='col col-sm-4'>
        @include('widgets.form._formitem_select2', 
                ['name' => 'search_country', 
                 'values' => $country_values,
                 'value' => $url_args['search_country'],
                 'class'=>'select-country form-control'
            ])
    </div>
    <div class='col col-sm-4'>
        @include('widgets.form._formitem_select2', 
                ['name' => 'search_city', 
                 'values' => $city_values,
                 'value' => $url_args['search_city'],
                 'class'=>'select-city form-control'
            ])
    </div>
    <div class='col col-sm-1'>
        @include('widgets.form._formitem_btn_submit', ['title' => trans('messages.view')])
    </div>
    <div class='col col-sm-1' style='text-align: right'>
        {{trans('messages.show_by')}}
    </div>
    <div class='col col-sm-1'>
        @include('widgets.form._formitem_text',
                ['name' => 'limit_num',
                'value' => $url_args['limit_num'],
                'attributes'=>['placeholder' => trans('messages.limit_num') ]]) 
    </div>
    <div class='col col-sm-1'>
        {{ trans('messages.records') }}
    </div>
</div>                               
        {!! Form::close() !!}
        
        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>

        @if ($confs)
        <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('conf.title') }}</th>
                <th>{{ trans('user.city') }}</th>
                @if (Confform\User::checkAccess('conf.update'))                
                <th></th>
                @endif
                @if (Confform\User::checkAccess('conf.delete'))                
                <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($confs as $conf)
            <tr>
                <td>{{ $list_count++ }}</td>
                <td>{{$conf->title}}</td>
                @if (Confform\User::checkAccess('conf.update'))
                <td>
                    @include('widgets.form._button_edit', 
                            ['is_button'=>true, 
                             'route' => '/conf/'.$conf->id.'/edit'])
                </td>
                @endif
                @if (Confform\User::checkAccess('conf.delete'))                
                <td>
                    @include('widgets.form._button_delete', ['is_button'=>true, $route = 'conf.destroy', 'id' => $conf->id])
                </td>
                @endif
            </tr> 
            @endforeach
        </tbody>
        </table>
        {!! $confs->appends($url_args)->render() !!}
        @endif
@stop

@section('footScriptExtra')
    {!!Html::script('js/bootstrap-datepicker.min.js')!!}
    {!!Html::script('js/bootstrap-datepicker.ru.min.js')!!}
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}', '/conf');
    
    $('#start-finish').datepicker({
        format: "dd-mm-yyyy",
        language: "{{$locale}}",
        orientation: "auto left"
    });
    
    $(".select-country").select2({
        placeholder: "{{trans('user.select_country')}}",
        allowClear: true
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
              country_id: $( "#search_country option:selected" ).val(),
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