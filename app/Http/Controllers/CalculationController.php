<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;


class CalculationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function getView(Request $request, $month = 0) : View 
    {

        if (is_numeric($month) && $month >= 1 && $month <= 12) {
            $selectedMonth = ($month < 10) ? "0".$month : $month;
            $selected = \Carbon\Carbon::createFromFormat('d-m-Y h:i:s', '01-'.$selectedMonth.'-'.\Carbon\Carbon::now()->format('Y')." 00:00:00");
            $currentFilterStart = $selected->format('Y')."-".$selectedMonth."-01";
            $currentFilterEnd = $selected->addMonth(1)->format('Y-m')."-01";
            $previusFilterStart = $selected->subMonth(2)->format('Y-m')."-01";
            $comparativeMonth = $selected->format('m');

        } else {
            $selectedMonth = \Carbon\Carbon::now()->format('m');
            $currentFilterStart = \Carbon\Carbon::now()->format('Y-m')."-01";
            $currentFilterEnd = \Carbon\Carbon::now()->addMonth(1)->format('Y-m')."-01";
            $previusFilterStart = \Carbon\Carbon::now()->subMonth(1)->format('Y-m')."-01";
            $comparativeMonth = \Carbon\Carbon::now()->subMonth(1)->format('m');
        }

        $selectedMonthTransactions = Transaction::where('created_at', '>=', $currentFilterStart)
            ->where('created_at', '<', $currentFilterEnd)
            ->get();

        $comparativeMonthTransactions = Transaction::where('created_at', '>=', $previusFilterStart)
            ->where('created_at', '<', $currentFilterStart)
            ->get();

        $currentProfit = $this->getProfit($selectedMonthTransactions);
        $comparativeProfit = $this->getProfit($comparativeMonthTransactions);

        $currentFlow = $this->getFlow($selectedMonthTransactions);
        $comparativeFlow = $this->getFlow($comparativeMonthTransactions);

        $totalDataComparative = (count($currentFlow) > count($comparativeFlow)) ? count($currentFlow) : count($comparativeFlow);

        $currentIncomes = $this->countByType($selectedMonthTransactions,1);
        $currentBills = $this->countByType($selectedMonthTransactions,2);

        $comparativeIncomes = $this->countByType($comparativeMonthTransactions,1);
        $comparativeBills = $this->countByType($comparativeMonthTransactions,2);
        $months = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',
        ];
        $selectedMonthName = $months[(int) $selectedMonth -1];
        $comparativeMonthName = $months[(int) $comparativeMonth -1];

        $totalEarned = $this->getTotalEarned($selectedMonthTransactions);
        $totalBill = $this->getTotalBill($selectedMonthTransactions);
        
        $difference = $this->calculateDifference($totalEarned, ($totalBill * -1));
            
        return view('pages/calculation')->with(
            [
                "total"=>$totalDataComparative,
                "months"=>$months,
                "earned"=>$totalEarned,
                "bill"=>$totalBill,
                "difference"=>$difference,
                "current"=>[
                    "month"=>$selectedMonth,
                    "monthName"=>$selectedMonthName,
                    "list"=>$selectedMonthTransactions,
                    "flow"=>$currentFlow,
                    "profits"=>$currentProfit,
                    "incomes"=>$currentIncomes,
                    "bills"=>$currentBills
                ],
                "comparative"=>[
                    "month"=>$comparativeMonth,
                    "monthName"=>$comparativeMonthName,
                    "list"=>$comparativeMonthTransactions,
                    "flow"=>$comparativeFlow,
                    "profits"=>$comparativeProfit,
                    "incomes"=>$comparativeIncomes,
                    "bills"=>$comparativeBills
                ]
            ]
        );
    }

    public function changeMonth(Request $request) : RedirectResponse
    {
        $request->validateWithBag('generateValue',[
            'month' => ['required', 'numeric', 'min:0', 'max:13']
        ]);

        $month = $request->month + 1;
        return redirect(route('calculation',$month));
    }

    public function generateValues(Request $request) : RedirectResponse
    {

        $request->validateWithBag('generateValue',[
            'month' => ['required', 'numeric', 'min:0', 'max:13']
        ]);

        $month = $request->month;
        
        $fakeCreated = \Carbon\Carbon::now()->format('Y-').$month."-01 00:00:00";

        $randomDetail = [
            'Ingreso por salario',
            'Venta de productos',
            'Alquiler de propiedad',
            'Ingreso por intereses',
            'Gastos de alimentación',
            'Pago de facturas de servicios',
            'Compras de ropa',
            'Gastos de entretenimiento',
            'Inversión en acciones',
            'Gastos de transporte',
            'Ingreso por alquiler de vehículo',
            'Compra de equipos electrónicos',
            'Gastos de educación',
            'Ingreso por dividendos',
            'Gastos médicos',
            'Venta de activos',
            'Devolución de préstamo',
            'Impuestos pagados',
            'Ingreso por bonificaciones',
            'Gastos de viaje',
        ];

        for($i = 0; $i < 10; $i++){

            $type = mt_rand(1, 2);
            $amount = mt_rand(1000, 25000);
            $detail = $randomDetail[mt_rand(0, 19)];


            Transaction::create([
                'user_id' => Auth::user()->id,
                'type' => $type,
                'detail' => $detail,
                'amount' => $amount,
                'created_at'=>$fakeCreated,
                'updated_at'=>$fakeCreated,
            ]);
        }

        return redirect(RouteServiceProvider::TRANSACTIONS)
                    ->with(['status'=>true,'message'=>'Datos generados']);
    }

    private function calculateDifference($valueA,$valueB) : string
    {
        $diferencia = $valueA - $valueB;
        $diferencia_porcentual = ($diferencia / $valueB) * 100;
        return number_format($diferencia_porcentual, 2) . "%";
    }

    private function countByType(Collection $transactions, int $type) : int{
        $total = 0;
        foreach($transactions as $t){
            if($t->type == $type){
                $total++;
            }
        }
        return $total;
    }

    private function getTotalEarned(Collection $transactions) : int{
        $earned = 0;
        foreach($transactions as $t){
            if($t->type == 1){
                $earned += $t->amount;
            }
        }
        return $earned;
    }

    private function getTotalBill(Collection $transactions) : int{
        $bill = 0;
        foreach($transactions as $t){
            if($t->type == 2){
                $bill += $t->amount;
            }
        }
        return $bill * -1;
    }

    private function getProfit(Collection $transactions) : int{
        $profits = 0;
        foreach($transactions as $t){
            if($t->type == 1){
                $profits += $t->amount;
            }else{
                $profits -= $t->amount;
            }
        }
        return $profits;
    }
    
    private function getFlow(Collection $transactions) : array{
        $flow = array();
        $profits = 0;
        foreach($transactions as $t){
            if($t->type == 1){
                $profits += $t->amount;
            }else{
                $profits -= $t->amount;
            }
            array_push($flow, $profits);
        }
        return $flow;
    }
}
