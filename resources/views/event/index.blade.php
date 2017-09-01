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
            <a href="{{ LaravelLocalization::localizeURL('/admin/event/create') }}">
        @endif
            {{ trans('messages.create_new_g') }}
        @if (User::checkAccess('event.create'))
            </a>
        @endif
        </p>

        @include('event._form_search')
        
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
                <td><a href="{{ LaravelLocalization::localizeURL('/admin/event/'.$event->id.'?'.$args_by_get)}}">{{$event->title}}</a></td>
                <td>{{$event->place}}</td>
                <td>{{$event->dates}}</td>
            @if (Confform\User::checkAccess('event.update'))
                <td>
                    @include('widgets.form._button_edit', 
                            ['is_button'=>true, 
                             'route' => '/admin/event/'.$event->id.'/edit'])
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