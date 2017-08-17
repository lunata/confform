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
              
        {!! Form::open(['url' => '/user/',
                             'method' => 'get'])
        !!}
<div class='row'>      
    <div class='col col-sm-3'>
        @include('widgets.form._formitem_text',
                ['name' => 'search_email',
                'value' => $url_args['search_email'],
                'attributes'=>['placeholder'=>'E-mail']])
    </div>
    <div class='col col-sm-5'>
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
</div>
<div class='row'>      
    <div class='col col-sm-3'>
        @include('widgets.form._formitem_text',
                ['name' => 'search_name',
                'value' => $url_args['search_name'],
                'attributes'=>['placeholder'=>trans('auth.name')]])
    </div>
    <div class='col col-sm-2'>
        @include('widgets.form._formitem_select2', 
                ['name' => 'search_role', 
                 'values' => $role_values,
                 'value' => $url_args['search_role'],
                 'class'=>'select-role form-control'
            ])
    </div>
    <div class='col col-sm-3'>
        @include('widgets.form._formitem_select2', 
                ['name' => 'search_perm', 
                 'values' => $perm_values,
                 'value' => $url_args['search_perm'],
                 'class'=>'select-perm form-control'
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
                <td>{{$user->name}}</td>
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
