@if ($event_id && $event_title)
<h3>{{trans('event.event')}} "{{$event_title}}"</h3>
@endif

<div class="row">
    <div class="col col-sm-4">
    </div>
    <div class="col col-sm-2">
        @include('widgets.form._formitem_select',
                ['name' => 'parent_id',
                 'title' => trans('page.parent'),
                 'value' => $page->parent_id,
                 'values' => $page_list
        ])
    </div>
    <div class="col col-sm-6">
    </div>
</div>
        @include('widgets.form._form_transl_fields',
                ['translated_fields'=>$event->getTranslatedFields(),
                 'lang_dir' => 'event',
                 'prim_lang' => $prim_lang,
                 'add_lang' => $add_lang])
                 
        @include('event._form_edit_pages')
        @include('widgets.form._formitem_btn_submit', ['title' => $submit_title])
                 