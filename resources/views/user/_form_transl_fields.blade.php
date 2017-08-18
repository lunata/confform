<?php
    if (!isset($lang_dir)) {
        $lang_dir = 'user';
    }
?>
    @if ($locale == $add_lang)
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
    @if ($locale == $add_lang)
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
