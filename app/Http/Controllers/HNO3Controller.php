<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\HNO3;
use App\HNO3Daily;
use App\HNO3Spec;
use App\HNO3Year;
use App\HNO3ParaSetting;
use App\HNO3C0;
use App\Tank;
use DB;

class HNO3Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $HNO3 = HNO3::where('date_time', '<=' , date('y-m-d', strtotime('+2 days')))
        ->where('date_time', '>=' , date('y-m-d', strtotime('+0 days')))->get();

        $HNO3Daily = HNO3Daily::where('Day', '<', date("Y-m-t", strtotime(date("Y-m-d"))) )
                          ->where('Day', '>=', date("Y-m-01", strtotime(date("Y-m-d"))) )->get();

        
        $HNO3Year = HNO3Daily::where('Day', '<', date("Y-m-t", strtotime(date("Y-m-d"))) )
                            ->where('Day', '>=', date("Y").'-01-01' )->get();

        $spec     = HNO3Spec::orderBy('created_at', 'desc')->first();

        $resultData = array();
        $resultTimeLabe = array();

        foreach($HNO3 as $item)
        {
            array_push($resultData, $item->HNO3);
            array_push($resultTimeLabe, $item->date_time);
        }

        //當月累積
        $resultHNO3Month = 0;
        foreach($HNO3Daily as $day)
        {
            $resultHNO3Month += $day->HNO3;
        }

        //當年累積
        $resultHNO3Year = 0;
        foreach($HNO3Year as $day)
        {
            $resultHNO3Year += $day->HNO3;
        }

        $data = [
            'HNO3'     => json_encode($resultData),
            'TLable'   => json_encode($resultTimeLabe),
            'HNO3Month'=> $resultHNO3Month,
            'HNO3Year' => $resultHNO3Year,
            'Spec'     => $spec
        ];

        return view('hno3')->with('DATA', $data);
    }

    public function showReportDay(Request $req)
    {
        $start = $req->start;
        $end = $req->end;

        if($start == null || $end == null)
        {
            $start = date('Y-m-d h:i:s');
            $end = date('Y-m-d h:i:s');
        }

        $HNO3 = HNO3::where('date_time', '<=' , $end)
                  ->where('date_time', '>=' , $start)->get();

        $data = [
            'HNO3' => $HNO3
        ];
        return view('hno3report')->with('DATA', $data);
    }

    public function showChart(Request $req)
    {
        $spec     = HNO3Spec::orderBy('created_at', 'desc')->first();
        $resultData = array();
        $resultTimeLabe = array();
        
        $chartType = $req->chart_type;

        if($chartType == 'day')
        {
            $HNO3 = HNO3::where('date_time', '<=' , date('y-m-d', strtotime('+2 days')))
            ->where('date_time', '>=' , date('y-m-d', strtotime('+0 days')))->get();

            foreach($HNO3 as $item)
            {
                array_push($resultData, $item->HNO3);
                array_push($resultTimeLabe, $item->date_time);
            }
        }
        elseif($chartType == 'month')
        {
            $HNO3 = HNO3Daily::where('Day', '<', date("Y-m-t", strtotime(date("Y-m-d"))) )
            ->where('Day', '>=', date("Y-m-01", strtotime(date("Y-m-d"))) )->get();

            foreach($HNO3 as $item)
            {
                array_push($resultData, $item->HNO3);
                array_push($resultTimeLabe, $item->Day);
            }
        }
        else
        {
            $HNO3_01 = HNO3Year::where('Month', '=', '1' )->get()->sum('HNO3');
            $HNO3_02 = HNO3Year::where('Month', '=', '2' )->get()->sum('HNO3');
            $HNO3_03 = HNO3Year::where('Month', '=', '3' )->get()->sum('HNO3');
            $HNO3_04 = HNO3Year::where('Month', '=', '4' )->get()->sum('HNO3');
            $HNO3_05 = HNO3Year::where('Month', '=', '5' )->get()->sum('HNO3');
            $HNO3_06 = HNO3Year::where('Month', '=', '6' )->get()->sum('HNO3');
            $HNO3_07 = HNO3Year::where('Month', '=', '7' )->get()->sum('HNO3');
            $HNO3_08 = HNO3Year::where('Month', '=', '8' )->get()->sum('HNO3');
            $HNO3_09 = HNO3Year::where('Month', '=', '9' )->get()->sum('HNO3');
            $HNO3_10 = HNO3Year::where('Month', '=', '10' )->get()->sum('HNO3');
            $HNO3_11 = HNO3Year::where('Month', '=', '11' )->get()->sum('HNO3');
            $HNO3_12 = HNO3Year::where('Month', '=', '12' )->get()->sum('HNO3');

            $resultData     = [$HNO3_01, $HNO3_02, $HNO3_03, $HNO3_04, $HNO3_05, $HNO3_06,
                               $HNO3_07, $HNO3_08, $HNO3_09, $HNO3_10, $HNO3_11, $HNO3_12];
            $resultTimeLabe = ['1','2','3','4','5','6','7','8','9','10','11','12'];
        }


        $data = [
            'HNO3'     => json_encode($resultData),
            'TLable'   => json_encode($resultTimeLabe),
            'Spec'     => $spec
        ];

        return view('hno3chart')->with('DATA', $data);
    }

    public function setSpecPage()
    {
        return view('hno3spec');
    }

    public function submitSpec(Request $req)
    {
        $HNO3Spec = new HNO3Spec;
        $HNO3Spec->top = $req->input('top');
        $HNO3Spec->bottom = $req->input('bottom');
        $HNO3Spec->save();
        
        return redirect()->back();
    }

    // 預測
    public function settingPage()
    {
        return view('hno3setting');
    }

    public function passSettingPara(Request $req)
    {
        $HNO3Setting = new HNO3ParaSetting;
        // $HNO3Setting->HNO3_auto_para = $req->input('HNO3_auto_para');
        // $HNO3Setting->H2O_auto_para = $req->input('H2O_auto_para');
        // $HNO3Setting->HF_auto_para = $req->input('HF_auto_para');
        $HNO3Setting->line_speed = $req->input('line_speed');
        $HNO3Setting->board_width = $req->input('board_width');
        $HNO3Setting->add_time = $req->input('add_time');
        $HNO3Setting->K1 = $req->input('K1');
        $HNO3Setting->K2 = $req->input('K2');
        $HNO3Setting->K3 = $req->input('K3');
        $HNO3Setting->N_plus = $req->input('N_plus');
        $HNO3Setting->W_plus = $req->input('W_plus');
        $HNO3Setting->F_plus = $req->input('F_plus');
        $HNO3Setting->V0 = $req->input('V0');

        $HNO3Setting->save();
        return redirect()->back();
    }

    public function calculate()
    {
        $HNO3Setting = HNO3ParaSetting::orderBy('created_at', 'desc')->first();
        $HNO3C0 = HNO3C0::orderBy('created_at', 'desc')->first();
        $Tank = Tank::orderBy('create_date', 'desc')->first();

        //設定第一次的C0
        $tank11C0 = 0;
        $tank12C0 = 0;
        $tank22C0 = 0;
        
        //若不是第一次則取出上一次的C0
        if($HNO3C0 != NULL)
        {
            $tank11C0 = $HNO3C0->tank11C0;
            $tank12C0 = $HNO3C0->tank12C0;
            $tank22C0 = $HNO3C0->tank22C0;
        }

        //dd($tank11_HNO3_auto_para);

        //勳哥的自動辨識
        $tank11_HNO3_auto_para = round($Tank->tank11_hno3);
        $tank11_H2O_auto_para = round($Tank->tank11_h2o);
        $tank11_HF_auto_para = round($Tank->tank11_hf);

        $tank12_HNO3_auto_para = round($Tank->tank12_hno3);
        $tank12_H2O_auto_para = round($Tank->tank12_h2o);
        $tank12_HF_auto_para = round($Tank->tank12_hf);

        $tank22_HNO3_auto_para = round($Tank->tank22_hno3);
        $tank22_H2O_auto_para = round($Tank->tank22_h2o);
        $tank22_HF_auto_para = round($Tank->tank22_hf);

        //不會自動變化
        $line_speed = $HNO3Setting->line_speed;
        $board_width = $HNO3Setting->board_width;
        $add_time = $HNO3Setting->add_time;
        $K1 = $HNO3Setting->K1;
        $K2 = $HNO3Setting->K2;
        $K3 = $HNO3Setting->K3;
        $N_plus = $HNO3Setting->N_plus;
        $W_plus = $HNO3Setting->W_plus;
        $F_plus = $HNO3Setting->F_plus;
        $V0 = $HNO3Setting->V0;

        /**
         * 運算開始...
         * 求N = 硝酸自動添加參數×線速(M/m)×板寬(mm)×添加時間(5min)×k1
         * 求W = H2O自動添加參數×線速(M/m)×板寬(mm)×添加時間(5min)×k2
         * 求F = 氫氟酸自動添加參數×線速(M/m)×板寬(mm)×添加時間(5min)×k3
         * C1 = 結果參照說明公式
         */
        
        $tank11_N = $tank11_HNO3_auto_para*$line_speed*$board_width*$add_time*$K1;
        $tank11_W = $tank11_H2O_auto_para*$line_speed*$board_width*$add_time*$K2;
        $tank11_F = $tank11_HF_auto_para*$line_speed*$board_width*$add_time*$K3;

        $tank12_N = $tank12_HNO3_auto_para*$line_speed*$board_width*$add_time*$K1;
        $tank12_W = $tank12_H2O_auto_para*$line_speed*$board_width*$add_time*$K2;
        $tank12_F = $tank12_HF_auto_para*$line_speed*$board_width*$add_time*$K3;

        $tank22_N = $tank22_HNO3_auto_para*$line_speed*$board_width*$add_time*$K1;
        $tank22_W = $tank22_H2O_auto_para*$line_speed*$board_width*$add_time*$K2;
        $tank22_F = $tank22_HF_auto_para*$line_speed*$board_width*$add_time*$K3;

        $tank11_C1 = (830*($tank11_N + $N_plus) + $tank11C0*$V0)/( $V0 + ($tank11_N + $N_plus) + ($tank11_F + $F_plus) + ($tank11_W + $W_plus) );
        $tank12_C1 = (830*($tank12_N + $N_plus) + $tank12C0*$V0)/( $V0 + ($tank12_N + $N_plus) + ($tank12_F + $F_plus) + ($tank12_W + $W_plus) );
        $tank22_C1 = (830*($tank22_N + $N_plus) + $tank22C0*$V0)/( $V0 + ($tank22_N + $N_plus) + ($tank22_F + $F_plus) + ($tank22_W + $W_plus) );

        $tank11_C1 = round($tank11_C1, 2);
        $tank12_C1 = round($tank12_C1, 2);
        $tank22_C1 = round($tank22_C1, 2);
        
        if($Tank->tank11_hno3 == null)
        {
            HNO3C0::create([
                'tank11C0' => 0,
                'tank12C0' => $tank12_C1,
                'tank22C0' => $tank22_C1,
            ]);
        }
        if($Tank->tank12_hno3 == null)
        {
            HNO3C0::create([
                'tank11C0' => $tank11_C1,
                'tank12C0' => 0,
                'tank22C0' => $tank22_C1,
            ]);
        }
        if($Tank->tank22_hno3 == null)
        {
            HNO3C0::create([
                'tank11C0' => $tank11_C1,
                'tank12C0' => $tank12_C1,
                'tank22C0' => 0,
            ]);
        }
        
    }

    public function predictPage()
    {
        $HNO3C0 = HNO3C0::all();

        $tank11_resultData = array();
        $tank12_resultData = array();
        $tank22_resultData = array();
        $resultTimeLabe = array();

        foreach($HNO3C0 as $item)
        {
            array_push($tank11_resultData, $item->tank11C0);
            array_push($tank12_resultData, $item->tank12C0);
            array_push($tank22_resultData, $item->tank22C0);
            array_push($resultTimeLabe, date_format($item->created_at, 'Y-m-d H:i:s'));
        }

        $data = [
            'tank11C0'     => json_encode($tank11_resultData),
            'tank12C0'     => json_encode($tank12_resultData),
            'tank22C0'     => json_encode($tank22_resultData),
            'TLable'       => json_encode($resultTimeLabe),
        ];

        //dd($data);

        return view('hno3predict')->with('DATA', $data);
    }
}
