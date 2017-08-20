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
