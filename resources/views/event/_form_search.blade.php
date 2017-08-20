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
