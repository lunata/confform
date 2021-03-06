        <h2>{{ trans('messages.editing')}} {{ trans('event.of_event')}} "{{ $event->title}}"</h2>
        <p>
            <a href="{{ LaravelLocalization::localizeURL('/admin/event/'.$event->id) }}?{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a> 
            | <a href="{{ LaravelLocalization::localizeURL('/admin/event') }}">
                {{ trans('messages.back_to_list') }}</a>
        </p>
        
        {!! Form::model($event, array('method'=>'PUT', 'route' => array('event.update', $event->id))) !!}
        @include('event._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => $action])
