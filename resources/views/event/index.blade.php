<?php $list_count = 1;?>
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

        <p>
        @if (User::checkAccess('event.create'))
            <a href="{{ LaravelLocalization::localizeURL('/event/create') }}">
        @endif
            {{ trans('messages.create_new_g') }}
        @if (User::checkAccess('event.create'))
            </a>
        @endif
        </p>

        {!! Form::open(['url' => '/event/',
                             'method' => 'get'])
        !!}
<div class='row'>      
    <div class='col col-sm-4'>
        @include('widgets.form._formitem_text',
                ['name' => 'search_title',
                'value' => $url_args['search_title'],
                'attributes'=>['placeholder'=>trans('event.title')]])
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

        @if ($events)
        <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('event.title') }}</th>
                <th>{{ trans('user.city') }}</th>
                <th>{{ trans('event.dates') }}</th>
                @if (Confform\User::checkAccess('event.update'))                
                <th></th>
                @endif
                @if (Confform\User::checkAccess('event.delete'))                
                <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $list_count++ }}</td>
                <td>{{$event->title}}</td>
                <td>{{$event->place}}</td>
                <td>{{$event->dates}}</td>
            @if (Confform\User::checkAccess('event.update'))
                <td>
                    @include('widgets.form._button_edit', 
                            ['is_button'=>true, 
                             'route' => '/event/'.$event->id.'/edit'])
                </td>
                @endif
                @if (Confform\User::checkAccess('event.delete'))                
                <td>
                    @include('widgets.form._button_delete', ['is_button'=>true, $route = 'event.destroy', 'id' => $event->id])
                </td>
                @endif
            </tr> 
            @endforeach
        </tbody>
        </table>
        {!! $events->appends($url_args)->render() !!}
        @endif
@stop

@section('footScriptExtra')
    {!!Html::script('js/bootstrap-datepicker.min.js')!!}
    {!!Html::script('js/bootstrap-datepicker.ru.min.js')!!}
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}', '/event');
    
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