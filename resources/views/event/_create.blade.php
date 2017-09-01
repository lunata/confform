        <h3>{{ trans('messages.creating')}} {{ trans('event.of_event')}}</h3>
        <p>
            <a href="{{ LaravelLocalization::localizeURL('/admin/event') }}">
                {{ trans('messages.back_to_list') }}</a>
        </p>
        
        {!! Form::model($event, array('method'=>'POST', 'route' => array('event.store'))) !!}
        @include('event._form_create_edit', 
                 ['submit_title' => trans('messages.save'),
                  'region_id' => null,
                  'action' => $action])
