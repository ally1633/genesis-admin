@extends('layouts.app')
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet"
      href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<?php
use App\Helpers\Internal;
$tables = Internal::getAllTables();
$currentTableIndex = array_search($data['currentTable'],array_column($tables,'value'));

?>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h2 class="card-header">Dashboard</h2>
                    <div class="col-md-2 list-group list-group-flush">
                        @foreach ($tables as $table)
                            <a href="/home/{{$table->value}}" class="list-group-item list-group-item-action bg-light">>{{$table->label}}</a>
                            <br/>
                        @endforeach
                    </div>
                    <div class="col-md-10" class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($data['currentTable'])
                            @if($tables[$currentTableIndex]->hasCustom)
                                @yield('modelCustomViews'.$data['currentTable'])
                            @else
                                @include('table',['tables'=>$tables])
                            @endif
                        @else
                            <div class="content">
                                <h2>Welcome to the Admin Dashboard!</h2>
                                <p>Navigate to any of the tables on the side menu to manipulate data</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection