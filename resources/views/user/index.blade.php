<?php $list_count = 1;?>
@extends('layouts.master')

@section('title')
{{ trans('auth.user_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
@stop

@section('content')
        <h2>{{ trans('auth.user_list') }}</h2>
              
        @include('user._form_search')
        
        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        @if ($users)
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
                <td>{{$user->full_name}}</td>
                <td>{{$user->place}}</td>
                <td>{{$user->permissionString()}}</td>
                <td>{{$user->rolesNames()}}</td>
                <td>{{$user->last_login}}</td>
                @if (Confform\User::checkAccess('user.update'))
                <td>
                    @include('widgets.form._button_edit', 
                            ['is_button'=>true, 
                             'route' => '/user/'.$user->id.'/edit'])
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
        {!! $users->appends($url_args)->render() !!}
        @endif
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}', '/user');

    $(".select-country").select2({
        placeholder: "{{trans('user.select_country')}}",
        allowClear: true
    });
    
    $(".select-role").select2({
        placeholder: "{{trans('user.select_role')}}",
        allowClear: true
    });
    
    $(".select-perm").select2({
        placeholder: "{{trans('user.select_perm')}}",
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
