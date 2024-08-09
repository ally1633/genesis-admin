<?php
$currentTable = $data['currentTable'];
$currentTableIndex = array_search($currentTable,array_column($tables,'value'));
?>

<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script
        src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet"
      href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">

<h2>{{$tables[$currentTableIndex]->label}}</h2>
<div class="col-md-offset-5">
    <button type="button" class="create-modal btn btn-success p-24" id="create">Add new record</button>
</div>

<table class="table" id="test">
    <thead>
    <tr>
        @foreach ($data['columnData'] as $column)
            <th class="text-center">{{$column->label}}</th>
        @endforeach
        <th/>
        <th/>
    </tr>
    </thead>
    <tbody>
    @foreach ($data['displayData'] as $record)
        <tr class="item{{$record->id}}">
            @foreach ($data['columnData'] as $column)
                <td class="text-center">{{$record[$column->dbName]}}</td>
            @endforeach
            <td><button class="edit-modal btn btn-info"
                        data-info="{{$record}}">
                    <span class="glyphicon glyphicon-edit"></span> Edit
                </button>
            </td>
            <td>
                <button class="delete-modal btn btn-danger"
                        data-info="{{$record}}">
                    <span class="glyphicon glyphicon-trash"></span> Delete
                </button></td>
        </tr>
    @endforeach
    </tbody>
</table>

@include('modalParts.modalBase',['data'=>$data['columnData'],'excludedColumnsFromModal'=>['id','uuid', 'created_at', 'updated_at']])

<script>
    let defaultToken = false;
</script>

<?php
if(Auth::user()){
$token = JWTAuth::fromUser(Auth::user());
?>
<script>
    defaultToken = '<?=$token?>';
</script>
<?php
}
?>

<script>
    var defaultColumnData = <?= json_encode($data['columnData']);?>;
    var currentTable = <?= $currentTable?>
</script>
