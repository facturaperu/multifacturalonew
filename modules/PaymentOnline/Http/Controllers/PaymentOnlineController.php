<?php

namespace Modules\PaymentOnline\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PaymentOnlineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        dd($request);
        return view('paymentonline::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('paymentonline::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $culqi = new \Culqi\Culqi(array('api_key' => env('CULQI_PRIVATE_KEY')));
        // dd($request->all());
        try {
                // Creamos Cargo a una tarjeta
                $charge = $culqi->Charges->create(
                    array(
                      "amount" => $request->input('amount'),
                      "capture" => true,
                      "currency_code" => "PEN",
                      "description" => $request->input('description'),
                      "email" => $request->input('email'),
                      "installments" => 0,
                      "source_id" => $request->input('token')
                    )
                );
   
                 
                // $this->matricular($request->input('programacion_id'),$charge);
  
                echo json_encode($charge);
                //return \Response::json(['success' => 'true','message'=>'Su compra fue exitosa']);
  
        } catch (\Exception $e) {
  
              echo json_encode($e->getMessage());
              //return \Response::json(['success' => 'false', 'message' => "Ocurrio un error, verifique sus datos por favor"]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('paymentonline::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('paymentonline::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
