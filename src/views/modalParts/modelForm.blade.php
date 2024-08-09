<form class="form-horizontal" role="form">
        @foreach ($data as $column)
                @if(!in_array($column->dbName,$excludedColumnsFromModal))
                        @include('formFieldTypes.'.$column->displayBlade,['options'=>$column->options,'column'=>$column->dbName,'label'=>$column->label])
                @endif
        @endforeach
</form>

