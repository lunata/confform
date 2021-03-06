        @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
        
        @include('widgets.form._form_transl_fields',
                ['translated_fields'=>$user->getTranslatedFields(),
                 'prim_lang' => $user->getPrimLang(),
                 'add_lang' => $user->getAddLang()])
                 
<div class="row">
    <div class="col col-sm-6">
        @include('widgets.form._formitem_text', 
                ['name' => 'email', 
                 'title'=> 'E-mail'])
                 
        @include('widgets.form._formitem_select2',
                ['name' => 'country_id',
                 'title' => trans('user.country'),
                 'value' => [$user->country_id],
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
                 'value' => [$user->city_id],
                 'values' => $city_values,
                 'is_multiple' => false,
                 'class'=>'form-control select-city'                            
        ])
        
        @include('widgets.form._formitem_btn_submit', ['title' => $submit_title])
    </div>
    <div class="col col-sm-6">
        <?php if ($action=='create') { $role_value = NULL; } ?>        
         @include('widgets.form._formitem_checkbox_group', 
                ['name' => 'roles[]', 
                 'values' =>$role_values,
                 'value' => $role_value,
                 'title' => trans('auth.roles')]) 
                 
        <?php if ($action=='create') { $perm_value = NULL; } ?>        
       @include('widgets.form._formitem_checkbox_group', 
                ['name' => 'permissions[]', 
                 'values' =>$perm_values,
                 'value' => $perm_value,
                 'title'=>trans('auth.permissions')]) 
    </div>
</div>