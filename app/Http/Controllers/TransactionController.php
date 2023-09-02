<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{


    public function getView(Request $request) : View 
    {
        $allTransactions = Transaction::all();

        $options = array();
        foreach(TransactionType::all() as $t){
            $options[$t->id] = $t->type;
        }
        
        return view('pages/transactions')->with(
            [
                "options"=>$options,
                "list"=>$allTransactions
            ]
        );
    }

    public function create(Request $request): RedirectResponse
    {
        $messages = [
            "required"=>"Este campo es obligatorio",
            "numeric"=>"Este campo debe ser un número",
            "min"=>"Ingresa un número mayor que 0"
        ];

        $request->validateWithBag('createTransaction',[
            'detail' => ['required', 'string', 'max:191'],
            'amount' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'numeric', 'digits_between:0,3']
        ], $messages);

        
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'type' => $request->type,
            'detail' => $request->detail,
            'amount' => $request->amount
        ]);

        return redirect(RouteServiceProvider::TRANSACTIONS);
    }
    
    public function test(Request $request) : RedirectResponse {

        return redirect(RouteServiceProvider::TRANSACTIONS);
        var_dump(Auth::user()->id);
        echo "soy una funcion de test";
        die();

        
    }

}
