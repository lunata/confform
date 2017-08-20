        @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
        
        @include('widgets.form._form_transl_fields',
                ['translated_fields'=>$event->getTranslatedFields(),
                 'lang_dir' => 'event',
                 'prim_lang' => $event->prim_lang,
                 'add_lang' => $event->add_lang])
                 
<div class="row">
    <div class="col col-sm-4">
        @include('widgets.form._formitem_daterange',
                ['id' => 'started_at',
                 'label' => trans('event.dates').' '.trans('messages.from'),
                 'name1' => 'started_at',
                 'value1' => $event->started_at,
                 'name2' => 'finished_at',
                 'value2' => $event->finished_at
                ])
        @include('widgets.form._formitem_daterange',
                ['id' => 'registr_start',
                 'label' => trans('event.registr_access').' '.trans('messages.from'),
                 'name1' => 'registr_start',
                 'value1' => $event->registr_start,
                 'name2' => 'registr_finish',
                 'value2' => $event->registr_finish
                ])
        @include('widgets.form._formitem_daterange',
                ['id' => 'material_start',
                 'label' => trans('event.material_accept').' '.trans('messages.from'),
                 'name1' => 'material_start',
                 'value1' => $event->material_start,
                 'name2' => 'material_finish',
                 'value2' => $event->material_finish
                ])
    </div>
    <div class="col col-sm-2">
        @include('widgets.form._formitem_select',
                ['name' => 'prim_lang',
                 'title' => trans('messages.prim_lang'),
                 'value' => $event->prim_lang,
                 'values' => $lang_values
        ])
        @include('widgets.form._formitem_select',
                ['name' => 'add_lang',
                 'title' => trans('messages.add_lang'),
                 'value' => $event->add_lang,
                 'values' => $lang_values
        ])
        @include('widgets.form._formitem_select',
                ['name' => 'status',
                 'title' => trans('messages.status'),
                 'value' => $event->status,
                 'values' => $event->getStatusList()
        ])
    </div>
    <div class="col col-sm-6">
        @include('widgets.form._formitem_select2',
                ['name' => 'country_id',
                 'title' => trans('user.country'),
                 'value' => [$event->country_id],
                 'values' => $country_values,
                 'is_multiple' => false,
                 'class'=>'form-control select-country'                            
        ])
        
        @include('widgets.form._formitem_select2',
                ['name' => 'region_id',
                 'title' => trans('user.region'),
                 'value' => [$region_id],
                 'values' => $region_values,
                 'is_multiple' => false,
                 'class'=>'form-control select-region'                            
        ])
        
        @include('widgets.form._formitem_select2',
                ['name' => 'city_id',
                 'title' => trans('user.city'),
                 'value' => [$event->city_id],
                 'values' => $city_values,
                 'is_multiple' => false,
                 'class'=>'form-control select-city'                            
        ])
    </div>
</div>
        @include('event._form_edit_pages')
        @include('widgets.form._formitem_btn_submit', ['title' => $submit_title])
                 