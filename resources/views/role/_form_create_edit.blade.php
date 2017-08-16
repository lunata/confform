<div class="row">
    <div class="col col-sm-6">
        @include('widgets.form._formitem_text', 
                ['name' => 'slug', 
                 'title'=> 'slug'])
                 
        @include('widgets.form._formitem_text', 
                ['name' => 'name', 
                 'title'=> trans('auth.role_name')])
                 
        @include('widgets.form._formitem_text', 
                ['name' => 'prior', 
                 'title'=> trans('auth.prior')])
                 
        @include('widgets.form._formitem_btn_submit', ['title' => $submit_title])
    </div>    
    <div class="col col-sm-6">
        <?php if ($action=='create') { $perm_value = []; } ?>        
        @foreach ($perm_values as $perm => $perm_t)   
            <?php $checked = (in_array($perm, $perm_value) ? 'checked' : NULL); ?>
            @include('widgets.form._formitem_checkbox', 
                    ['name' => 'permissions['.$perm.']', 
                     'value' => 'true',
                     'checked' => $checked,
                     'tail'=>$perm_t])
        @endforeach
{{--         @include('widgets.form._formitem_select', 
                ['name' => 'permissions[]', 
                 'values' =>$perm_values,
                 'value' => $perm_value,
                 'title' => trans('auth.permissions'),
                 'attributes'=>['multiple'=>'multiple']]) --}}
    </div>    
</div>
