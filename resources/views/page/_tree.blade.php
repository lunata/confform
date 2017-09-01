<h2>{{trans('page.pages')}}</h2>
<a href="{{ LaravelLocalization::localizeURL('/admin/page/create') }}{{isset($event_id) && (int)$event_id ? '?event_id'.(int)$event_id : ''}}">
            {{ trans('messages.create_new_g') }}
</a>
