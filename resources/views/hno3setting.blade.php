@extends('layouts.app')

@section('content')
<h2>濃度運算公式基礎參數設定</h2>

<div class="panel panel-default">
    <div class="panel-body">
        <form method="GET" action="{{route('no3_setting_pass')}}" style="padding: 10px">
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="line_speed">線速(M/m)</label>
                <input id="line_speed" class="form-control" name="line_speed" type="number" step="0.01" value="{{$DATA->line_speed}}">
            </div>
            <div class="form-group row">
                <label for="board_width">板寬(mm)</label>
                <input id="board_width" class="form-control" name="board_width" type="number" step="0.01" value="{{$DATA->board_width}}">
            </div>
            <div class="form-group row">
                <label for="add_time">添加時間(5min)</label>
                <input id="add_time" class="form-control" name="add_time" type="number" value="5" step="0.01" value="{{$DATA->add_time}}">
            </div>
            <div class="form-group row">
                <label for="K1">K1</label>
                <input id="K1" class="form-control" name="K1" type="number" step="0.01" value="{{$DATA->K1}}">
            </div>
            <div class="form-group row">
                <label for="K2">K2</label>
                <input id="K2" class="form-control" name="K2" type="number" step="0.01" value="{{$DATA->K2}}">
            </div>
            <div class="form-group row">
                <label for="K3">K3</label>
                <input id="K3" class="form-control" name="K3" type="number" step="0.01" value="{{$DATA->K3}}">
            </div>
            <div class="form-group row">
                <label for="N_plus">硝酸一次性添加體積(L)</label>
                <input id="N_plus" class="form-control" name="N_plus" type="number" step="0.01" value="{{$DATA->N_plus}}">
            </div>
            <div class="form-group row">
                <label for="W_plus">H2O一次性添加體積(L)</label>
                <input id="W_plus" class="form-control" name="W_plus" type="number" step="0.01" value="{{$DATA->W_plus}}">
            </div>
            <div class="form-group row">
                <label for="F_plus">氫氟酸一次性添加體積(L)</label>
                <input id="F_plus" class="form-control" name="F_plus" type="number" step="0.01" value="{{$DATA->F_plus}}">
            </div>
            <div class="form-group row">
                <label for="V0">估算前混酸總體積(L)</label>
                <input id="V0" class="form-control" name="V0" type="number" step="0.01" value="{{$DATA->V0}}">
            </div>

            <div class="form-group row">
                <button type="submit" class="btn btn-primary btn-lg btn-block">設定</button>
            </div>
        </form>
    </div>
</div>

@endsection
