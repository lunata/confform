        @include('widgets.form._formitem_text', 
                ['name' => 'email', 
                 'title'=> 'E-mail'])
                 
        @include('user._form_transl_fields',
                ['translated_fields'=>$user->getTranslatedFields(),
                 'prim_lang' => $user->getPrimLang(),
                 'add_lang' => $user->getAddLang()])
                 
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

@include('widgets.form._formitem_btn_submit', ['title' => $submit_title])
