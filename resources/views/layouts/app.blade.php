<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>東盟</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .side-bar
        {
            background: black;
            padding: 15px;
        }
        .side-bar a 
        {
            color: white !important;
        }
    </style>
    <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
    <!--<script data-require="jquery@*" data-semver="2.1.1" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
    <!--1<script data-require="chart.js@0.2.0" data-semver="0.2.0" src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/0.2.0/Chart.js"></script>-->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    
    <script type="text/javascript" src="{{asset('YearPicker-master/docs/yearpicker.js')}}"></script>
    <link rel="stylesheet" href="{{asset('YearPicker-master/docs/yearpicker.css')}}"/>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        東盟線上系統
                    </a>
                </div>

                <ul style="float: right; position: relative; top:15px; list-style: none;">
                    <li class="nav-item"><a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        <i style="color: white; font-size:20px" class="fa fa-sign-out" aria-hidden="true">登出</i>
                    </a></li>
    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </ul>

            </div>
        </nav>

        <div class="container">
            <div class="row">
                @auth
                <div class="col-sm-3">
                    <ul class="nav flex-column side-bar">
                        <li class="nav-item" id="member_toggle_sw"><a class="dropdown-item" href="#">管理用戶</a></li>
                        <ul class="nav-item" id="member_toggle">
                            <li class="nav-item" id="home"><a class="dropdown-item" href="{{ route('home') }}">所有用戶</a></li>
                            <li class="nav-item" id="user"><a class="dropdown-item" href="{{ route('cuserpage') }}">創建用戶</a></li>
                        </ul>
                            <li class="nav-item" id="hno3"><a class="dropdown-item" href="{{ route('hno3') }}">HNO3流量監管</a></li>
                        <li class="nav-item" id="hf"><a class="dropdown-item" href="{{ route('hf') }}">HF流量監管</a></li>
                        <li class="nav-item" id="brushrollerelectricity"><a class="dropdown-item" href="{{ route('brushrollerelectricity') }}">刷輥電流監管</a></li>
                        
                        <li class="nav-item" id="predict_toggle_sw"><a class="dropdown-item" href="#">濃度預測分析</a></li>
                        <ul class="nav-item" id="predict_toggle">
                            <li class="nav-item" id="no3_setting"><a class="dropdown-item" href="{{ route('no3_setting') }}">濃度公式參數設定</a></li>
                            <li class="nav-item" id="hno3_predict"><a class="dropdown-item" href="{{ route('hno3_predict') }}">HNO3濃度預測圖</a></li>
                            <li class="nav-item" id="hno3_predict_8h"><a class="dropdown-item" href="/hno3_predict_8h?C0=0&tanknum=tank11">HNO3區間濃度預估</a></li>
                            <li class="nav-item" id="hf_predict"><a class="dropdown-item" href="{{ route('hf_predict') }}">HF濃度預測圖</a></li>
                            <li class="nav-item" id="hf_predict_8h"><a class="dropdown-item" href="/hf_predict_8h?C0=0&tanknum=tank11">HF區間濃度預估</a></li>
                        </ul>
                        
                    </ul>
                </div>
                @endauth
                
                <div class="col-sm-9">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}"></script>-->
    <script src="https://use.fontawesome.com/2904db45a9.js"></script>
    
    <script>
        var url = window.location.href;

        if(url.split('/')[3] == 'home')
        {
            document.getElementById('home').style.backgroundColor  = 'rgb(119, 119, 119)';
        }

        if(url.split('/')[3] == 'user')
        {
            document.getElementById('user').style.backgroundColor  = 'rgb(119, 119, 119)';
        }

        if(url.split('/')[3] == 'hno3')
        {
            document.getElementById('hno3').style.backgroundColor  = 'rgb(119, 119, 119)';
        }

        if(url.split('/')[3] == 'hf')
        {
            document.getElementById('hf').style.backgroundColor  = 'rgb(119, 119, 119)';
        }

        if(url.split('/')[3] == 'brushrollerelectricity')
        {
            document.getElementById('brushrollerelectricity').style.backgroundColor  = 'rgb(119, 119, 119)';
        }

        if(url.split('/')[3] == 'no3_setting')
        {
            document.getElementById('no3_setting').style.backgroundColor  = 'rgb(119, 119, 119)';
        }

        if(url.split('/')[3] == 'hno3_predict')
        {
            document.getElementById('hno3_predict').style.backgroundColor  = 'rgb(119, 119, 119)';
        }


        var tmp = url.split('/')[3];
        var predicturl = tmp.split('?')
        if(predicturl[0] == 'hno3_predict_8h')
        {
            document.getElementById('hno3_predict_8h').style.backgroundColor  = 'rgb(119, 119, 119)';
        }

    </script>

    <script>
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'text' }):
            XLSX.writeFile(wb, fn || ('DATA.' + (type || 'xlsx')));
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $("#predict_toggle_sw").click(function(){
            $("#predict_toggle").toggle('normal');
        });
        $("#member_toggle_sw").click(function(){
            $("#member_toggle").toggle('normal');
        })
        
    </script>
</body>
</html>
