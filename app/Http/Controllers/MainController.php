<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Transaction;

class MainController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }


    public function getView(Request $request) : View 
    {
        $startDate = \Carbon\Carbon::now()->format('Y-m')."-01";
        $endDate = \Carbon\Carbon::now()->addMonth(1)->format('Y-m')."-01";

        $allTransactions = Transaction::where('created_at', '>=', $startDate)
        ->where('created_at', '<', $endDate)
        ->get();

        $flow = array();
        $profits = 0;
        foreach($allTransactions as $t){
            if($t->type == 1){
                $profits += $t->amount;
            }else{
                $profits -= $t->amount;
            }
            array_push($flow, $profits);
        }

        return view('pages/dashboard')->with(
            [
                "list"=>$allTransactions,
                "flow"=>$flow,
                "profits"=>$profits
            ]
        );
    }

}
