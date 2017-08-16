<?php $list_count = 1;?>
@extends('layouts.master')

@section('title')
{{ trans('auth.user_list') }}
@stop

@section('content')
        <h2>{{ trans('auth.user_list') }}</h2>
              
        <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>E-mail</th>
                <th>{{ trans('auth.name') }}</th>
                <th>{{ trans('user.city') }}</th>
                <th>{{ trans('auth.permissions') }}</th>
                <th>{{ trans('auth.roles') }}</th>
                <th>{{ trans('auth.last_login') }}</th>
                @if (Confform\User::checkAccess('user.update'))                
                <th></th>
                @endif
                @if (Confform\User::checkAccess('user.delete'))                
                <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $list_count++ }}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->place}}</td>
                <td>{{$user->permissionString()}}</td>
                <td>{{$user->rolesNames()}}</td>
                <td>{{$user->last_login}}</td>
                @if (Confform\User::checkAccess('user.update'))
                <td>
                    <a  href="{{ LaravelLocalization::localizeURL('/user/'.$user->id.'/edit') }}" 
                        class="btn btn-warning btn-xs btn-detail" value="{{$user->id}}">{{ trans('messages.edit') }}</a> 
                </td>
                @endif
                @if (Confform\User::checkAccess('user.delete'))                
                <td>
                    @include('widgets.form._button_delete', ['is_button'=>true, $route = 'user.destroy', 'id' => $user->id])
                </td>
                @endif
            </tr> 
            @endforeach
        </tbody>
        </table>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}', '/corpus/user');
@stop


