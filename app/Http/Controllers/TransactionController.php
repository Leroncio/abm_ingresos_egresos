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

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

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

        return redirect(RouteServiceProvider::TRANSACTIONS)
                    ->with(['status'=>true,'message'=>'Registro ingresado']);
    }

    public function delete(Request $request): RedirectResponse
    {

        $messages = [
            "required"=>"No se pudo seleccionar el registro a eliminar",
            "numeric"=>"El envio de registro se encuentra mal formateado",
            "min"=>"No es posible eliminar este registro"
        ];

        $request->validateWithBag('deleteTransaction',[
            'selected' => ['required', 'numeric', 'min:1'],
        ], $messages);

        $selected = $request->selected;

        $find = Transaction::find($selected);
        if($find){
            if(Transaction::destroy($selected)){
                return redirect(RouteServiceProvider::TRANSACTIONS)
                    ->with(['status'=>true,'message'=>'Registro eliminado']);
            }else{
                return redirect(RouteServiceProvider::TRANSACTIONS)
                    ->with(['status'=>false,'message'=>'No se pudo eliminar el estado']);
            }
        }else{
            return redirect(RouteServiceProvider::TRANSACTIONS)
            ->with(['status'=>false,'message'=>'El registro no existe']);
        }
    
    }

    public function update(Request $request) : RedirectResponse
    {
        $messages = [
            "required"=>"Este campo es obligatorio",
            "numeric"=>"Este campo debe ser un número",
            "min"=>"Ingresa un número mayor que 0"
        ];

        $request->validateWithBag('createTransaction',[
            'up-detail' => ['required', 'string', 'max:191'],
            'up-amount' => ['required', 'numeric', 'min:0'],
            //'type' => ['required', 'numeric', 'digits_between:0,3']
        ], $messages);

        $transaction_id = $request["to-update"];

        $transaction = Transaction::where('id',$transaction_id)->get()->first();

        $transaction->detail = $request["up-detail"];
        $transaction->amount = $request["up-amount"];

        if($transaction->update()){
            return redirect(RouteServiceProvider::TRANSACTIONS)
                ->with(['status'=>true,'message'=>'Registro actualizado']);
        }else{
            return redirect(RouteServiceProvider::TRANSACTIONS)
                ->with(['status'=>false,'message'=>'No se pudo actualizar el estado']);
        }        
    }
    
    public function test(Request $request) : RedirectResponse {

        return redirect(RouteServiceProvider::TRANSACTIONS);
        var_dump(Auth::user()->id);
        echo "soy una funcion de test";
        die();

        
    }

}
