<h2>{{trans('page.pages')}}</h2>
<a href="{{ LaravelLocalization::localizeURL('/admin/'.(isset($event_id) && (int)$event_id ? 'event/'.(int)$event_id.'/' : '').'page/create') }}">
            {{ trans('messages.create_new_f') }}
</a>
