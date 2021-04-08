<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!} select2-box-wen ">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}} box-body">
        @include('admin::form.error')
        {!! $__content__ !!}
        @include('admin::form.help-block')
    </div>
</div>
<script>
    $(function () {
        $(document).on('click', '.grid-expand-grid-row', function (e) {
            if (e.currentTarget.attributes['aria-expanded'].value == 'true') {
                $(this).closest("td").siblings('td').find('.fa-angle-double-up').trigger('click')
                $(this).find('a i').addClass('fa-angle-double-up');
                $(this).find('a i').removeClass('fa-angle-double-down');
            } else {
                $(this).find('a i').addClass('fa-angle-double-down');
                $(this).find('a i').removeClass('fa-angle-double-up');
            }
        });
    })
</script>