<?php
    if (!isset($is_ignore_add_lang_fields)) {
        $is_ignore_add_lang_fields = false;
    }
    if (!isset($lang_dir)) {
        $lang_dir = 'user';
    }
?>
    @if (!$is_ignore_add_lang_fields || $locale == $add_lang)
<div class="row">   
    <div class="col col-sm-6">
    @endif
        @foreach ($translated_fields as $field)
            @include('widgets.form._formitem_text', 
                    ['name' => $field.'_'.$prim_lang, 
                     'title'=> $locale!=$prim_lang 
                            ? trans($lang_dir.'.'.$field).' ('.trans('messages.in_'.$prim_lang).')' 
                            : trans($lang_dir.'.'.$field)])
        @endforeach         
    @if (!$is_ignore_add_lang_fields || $locale == $add_lang)
    </div>
    <div class="col col-sm-6">
        @foreach ($translated_fields as $field)
            @include('widgets.form._formitem_text', 
                    ['name' => $field.'_'.$add_lang, 
                     'title'=> trans($lang_dir.'.'.$field).' ('.trans('messages.in_'.$add_lang).')'])
        @endforeach         
    </div>
</div>                 
    @endif
