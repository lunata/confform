        <h3>{{ trans('messages.creating')}} {{ trans('page.of_page')}}</h3>
        <p>
            <a href="{{ LaravelLocalization::localizeURL('/admin/page') }}">
                {{ trans('messages.back_to_list') }}</a>
        </p>
        
        {!! Form::model($page, array('method'=>'POST', 'route' => array('page.store'))) !!}
        @include('page._form_create_edit', 
                 ['submit_title' => trans('messages.save'),
                  'action' => $action])
