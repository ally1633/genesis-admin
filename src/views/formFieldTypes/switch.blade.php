<div class="form-group">
    <label class="control-label col-sm-2" for="{{$column}}">{{$label}}</label>
    <div class="col-sm-10">
        <input name="{{$column}}" id="{{$column}}" class="form-control" type="checkbox"/>
    </div>
</div>

<script>
    $("#{{$column}}").on('change', function() {
        if ($(this).is(':checked')) {
            $(this).attr('value', '1');
        } else {
            $(this).attr('value', '0');
        }
    });
</script>

