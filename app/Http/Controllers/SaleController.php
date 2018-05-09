<?php

namespace App\Http\Controllers;
use App\Module;
use App\Permission;
use App\Sale;
use App\SaleState;
use App\Contact;
use App\TypeSaleState;
use App\Vehicle;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class SaleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:'.Module::VENTAS.','.Permission::INDEX, ['only' => ['index']]);
        $this->middleware('permission:'.Module::VENTAS.','.Permission::READ, ['only' => ['show']]);
        $this->middleware('permission:'.Module::VENTAS.','.Permission::CREATE, ['only' => ['store']]);
        $this->middleware('permission:'.Module::VENTAS.','.Permission::UPDATE, ['only' => ['reserve','cancel','transfer','sell']]);

        $this->middleware('access', ['only' => ['index','store','reserve','cancel','transfer','sell','show']]);


    }

    public function index($dealer_id){
        return $this->response(200,DB::table("sales")->
        join('vehicles','vehicles_id','=','sales.vehicle_id')->
        join('contact','contact.id','=','sales.contact_client_id')->
        join('types_sales_states','types_sales_states.id','=','sales.type_sale_state_id')->
        whereNull('sales.deleted_at')->
        where('sales.car_dealer_id','=',$dealer_id)->
        get(array(
            Vehicle::all(),
            'contact.first_name as contact.first_name',
            'contact.last_name as contact.last_name',
            'types_sales_states.name as state',
            'sales.details as details'
        )));

    }

    public function store($dealer_id){
        $data = Input::get("sale");
        $sale = Sale::create(array(
           "vehicle_id" => $data["vehicle"],
            "car_dealer_id" => $data["car_dealer_id"],
            "contact_client_id" => $data["contact_id"],
            "contact_owner_id" => $data["owner_id"],
            "type_sale_state_id" => TypeSaleState::PUBLICADO,
            "details" => $data['details']
        ));

        if($sale){
            SaleState::create(array(
                "sale_id" => $sale->id,
                "type_sale_state_id'" => $sale->state_id
            ));

            return $this->response(200,$sale);

        }else{
            return $this->response(400,'Error');
        }

    }

    public function reserve($dealer_id,$id){
        $data = Input::get("sale");
        $sale = Sale::find($id);
        $sale->update(array('type_sale_state_id',TypeSaleState::RESERVADO,'details' => $data['details'] ? $data['details'] : $sale->details));
        SaleState::create(array(
               "sale_id" => $id,
               "type_sale_state_id" => TypeSaleState::RESERVADO
        ));

        return $this->response(200,$sale);
    }

    public function cancel($dealer_id,$id){
        $data = Input::get("sale");
        $sale = Sale::find($id);
        $sale->update(array('type_sale_state_id'=>TypeSaleState::CANCELADO,'details' => $data['details'] ? $data['details'] : $sale->details));
        SaleState::create(array(
            "sale_id" => $id,
            "type_sale_state_id" => TypeSaleState::CANCELADO
        ));

        return $this->response(200,$sale);
    }

    public function sell($dealer_id,$id){
        $data = Input::get("sale");
        $sale = Sale::find($id);
        $sale->update(array('type_sale_state_id',TypeSaleState::VENDIDO,'details' => $data['details'] ? $data['details'] : $sale->details));
        SaleState::create(array(
            "sale_id" => $id,
            "type_sale_state_id" => TypeSaleState::VENDIDO
        ));

        return $this->response(200,$sale);
    }

    public function transfer($dealer_id,$id){
        $data = Input::get("sale");
        $sale = Sale::find($id);
        $sale->update(array('type_sale_state_id',TypeSaleState::TRANSFERIDO,'details' => $data['details'] ? $data['details'] : $sale->details));
        SaleState::create(array(
            "sale_id" => $id,
            "type_sale_state_id" => TypeSaleState::TRANSFERIDO
        ));

        return $this->response(200,$sale);
    }

    public function show($dealer_id,$id){
        return $this->response(200,DB::table("sales")->
        join('vehicles','vehicles_id','=','sales.vehicle_id')->
        join('contact','contact.id','=','sales.contact_client_id')->
        join('types_sales_states','types_sales_states.id','=','sales.type_sale_state_id')->
        join('sales_states','sales_states.sale_id','=','sales.id')->
        whereNull('sales.deleted_at')->
        where('sales_states.sale_id','=',$id)->
        get(array(
            Vehicle::all(),
            'contact.first_name as contact.first_name',
            'contact.last_name as contact.last_name',
            'types_sales_states.name as state'
        )));
    }

}