<div class="row">
    <div class="col col-sm-6">
    @include('widgets.form._formitem_texteditor',
             ['name' => 'page1'])
    </div>
    <div class="col col-sm-6">
    @include('widgets.form._formitem_texteditor',
             ['name' => 'page2',
              'toolbar' => 'Full'])
    </div>
</div>    