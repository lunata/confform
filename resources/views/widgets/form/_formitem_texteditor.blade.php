<?php
if (!isset($name))
    $name ='editor1';
if (!isset($locale))
    $locale = 'en';
if (!isset($toolbar))
    $toolbar = 'Basic';
?>
<textarea name="{{$name}}" class="ckeditor"></textarea>
   <script type="text/javascript">
      CKEDITOR.replace( '{{$name}}',{
                language: '{{$locale}}',
                customConfig: '/js/ckeditor-config.js',
                toolbar: '{{$toolbar}}',
                filebrowserUploadUrl: '/files'
        });
      CKEDITOR.add            
   </script>
