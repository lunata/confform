@extends('layouts.master')

@section('title')
{{ trans('navigation.event_list') }}
@stop

@section('content')
        <h1>{{ trans('navigation.event_list') }}</h1>
        <p>
            <a href="{{ LaravelLocalization::localizeURL('/admin/event/'.$event->id) }}">
                {{ trans('messages.back_to_show') }}</a>
            | <a href="{{ LaravelLocalization::localizeURL('/admin/event') }}">
                {{ trans('messages.back_to_list') }}</a>
        </p>
        <h2>{{ $event->title}}</h2>
        <h3>{{ trans('messages.history') }}</h3>

        @foreach($event->revisionHistory as $history )
            <?php $user = \Confform\User::find($history->userResponsible()->id);?>
            <li>
                <i>{{ $history->updated_at }}</i>
                {{ $user->name }} 
                @if($history->fieldName() == 'created_at')
                    {{trans('messages.created')}} 
                @else
                    {{trans('messages.changed')}} 
                    <b>{{ $history->fieldName() }}</b> 
                    @if ($history->oldValue()) 
                        {{trans('messages.from')}} 
                        "<i>{{ $history->oldValue() }}</i>" 
                    @endif
                    {{trans('messages.to_on')}} 
                    "<b>{{ $history->newValue() }}</b>"
                @endif
            </li>
        @endforeach
        
@stop
