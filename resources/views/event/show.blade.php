@extends('layouts.master')

@section('title')
{{ trans('navigation.event_list') }}
@stop

@section('headExtra')
@stop

@section('content')
        <h2>{{ trans('navigation.event_list') }}</h2>
        <p>
            <a href="{{ LaravelLocalization::localizeURL('/event') }}">
                {{ trans('messages.back_to_list') }}</a>
        @if (User::checkAccess('event.delete'))
            | @include('widgets.form._button_delete', 
                       ['route' => 'event.destroy', 
                        'id' => $event->id]) 
        @endif

            | <a href="/event/{{ $event->id }}/history{{$args_by_get}}">{{ trans('messages.history') }}</a>
        </p>
        <h3>
            {{ $event->title}}
            @if (User::checkAccess('event.update'))
                @include('widgets.form._button_edit', 
                         ['route' => '/event/'.$event->id.'/edit',
                          'without_text' => 1])
            @endif

        </h3>
        <h4>{{$event->dates}}</h4>
        <h4>{{$event->place}}</h4>
        
        <p><b>{{trans('messages.status')}}</b>: {{trans('event.status'.$event->status)}}</p>
        
        <p><b>{{trans('messages.prim_lang')}}</b>: {{trans('messages.lang_'.$event->prim_lang)}}</p>
        @if($event->add_lang)
        <p><b>{{trans('messages.add_lang')}}</b>: {{trans('messages.lang_'.$event->add_lang)}}</p>
        @endif
        
        <p><b>{{trans('event.registr_access')}}</b>: {{$event->registr_dates}}</p>
        <p><b>{{trans('event.material_accept')}}</b>: {{$event->material_dates}}</p>
        
@stop

@section('footScriptExtra')
@stop

@section('jqueryFunc')
@stop
