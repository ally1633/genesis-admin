<div class="form-group">
        <label class="control-label col-sm-2" for="{{$column}}">{{$label}}</label>
        <div class="col-sm-10">
        <select class="form-control" name="{{$column}}" id="{{$column}}">
                @foreach ($options as $option)
                        <option value="{{$option->value}}"> {{ucfirst($option->label)}}</option>
                @endforeach
        </select>
        </div>
</div>
