<?php

namespace App\Http\Controllers;

use App\CarDealer;
use App\Module;
use App\Permission;
use App\Vehicle;
use App\VehicleMercadolibre;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Facades\Input;
use App\Contact;
use DB;
use MyProject\Proxies\__CG__\stdClass;
use Validator;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Meli;
use Config;


class ContactController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:'.Module::CONTACTOS.','.Permission::CREATE, ['only' => ['store']]);
        $this->middleware('permission:'.Module::CONTACTOS.','.Permission::DELETE, ['only' => ['delete']]);
        $this->middleware('permission:'.Module::CONTACTOS.','.Permission::UPDATE, ['only' => ['update']]);
        $this->middleware('permission:'.Module::CONTACTOS.','.Permission::INDEX, ['only' => ['index']]);
        $this->middleware('permission:'.Module::CONTACTOS.','.Permission::READ, ['only' => ['show']]);


        $this->middleware('access', ['only' => ['index','delete','update','show']]);

    }
    public function store($dealer_id,$id = 0)
    {
        $validator = Validator::make(
            array(
                'contact_type'   =>  Input::get('contact_type'),
                'email'     =>  Input::get('email'),
                'phone'   =>  Input::get('phone')
            ),
            array(
                'contact_type' => 'required',
                'email' => 'required|email',
                'phone' => 'required'
            )
        );

        if (! $validator->passes()) {
            return $this->response(400,$validator->errors());
        }

        $datos = array(
            'contact_type' => Input::get('contact_type',''),
            'first_name' => Input::get('first_name',''),
            'last_name' => Input::get('last_name',''),
            'business_name' => Input::get('business_name',''),
            'dni' => Input::get('dni',''),
            'phone' => Input::get('phone',''),
            'mobile' => Input::get('mobile',''),
            'address' => Input::get('address',''),
            'province' => Input::get('province',''),
            'zip_code' => Input::get('zip_code',''),
            'birthday' => Input::get('birthday'),
            'genre' => Input::get('genre',''),
            'iva_condition' => Input::get('iva_condition',''),
            'iibb_condition' => Input::get('iibb_condition',''),
            'email' => Input::get('email',''),
            'car_dealer_id' => $dealer_id
        );
        if($id){
            Contact::where('id','=',$id)->
                update($datos);
            return $this->response(200,'OK');
        }else{
            if(Contact::where("email",'=',Input::get('email',''))->first()){
                return $this->response(400,"Email en uso");
            }
            Contact::create($datos);
            return $this->response(200,'OK');
        }
    }
    public function index($dealer_id,Request $request)
    {
        $page = Input::get("page",1);
        $paginate = 25;
        $mlUser = $request->session()->get('mlUser');
        $client = new GuzzleHttp\Client();
        if(!is_null($mlUser)){
            $response = $client->request('GET', "https://api.mercadolibre.com/my/received_questions/search?access_token=".$mlUser->access_token);
            $questions = json_decode($response->getBody(),true);
            foreach($questions["questions"] as $question){
                if($vehicle = VehicleMercadolibre::where("item_id",'=',$question["item_id"])->first()){
                    if($dealer = Vehicle::find($vehicle->vehicle_id)){
                        if(!Contact::where("email",'=',$question["from"]["email"])->first()){
                            Contact::create(array(
                                "contact_type" => "Persona",
                                "first_name" =>  $question["from"]["first_name"],
                                "last_name" => $question["from"]["last_name"],
                                "phone" =>  $question["from"]["phone"]["number"],
                                "email" => $question["from"]["email"],
                                "car_dealer_id" => $dealer->car_dealer_id
                            ));
                        }
                    }
                }
            }
        }
        $slug = str_slug(Input::get("tag",''),"-");
        $query = DB::table('contacts')->whereNull('deleted_at')->where('car_dealer_id','=',$dealer_id);
        $dealers = DB::table("car_dealers")->
        select(array("id",DB::raw('"Empresa" as contact_type'), "company as name","phone","email",DB::raw('"concesionaria" as `type`')))->
        whereNull("deleted_at")->
        where("id",'<>',$dealer_id);
        if(strlen($slug) > 1){
            $words = explode('-',$slug);
            foreach($words as $word){
                $query->where(function($q) use($word){
                    $q->where('first_name','LIKE', $word.'%')->
                    orWhere('last_name','LIKE', $word.'%')->
                    orWhere('email','LIKE', $word.'%');
                });
                $dealers->where(function($q) use($word){
                    $q->where('name','LIKE', $word.'%')->
                        orWhere('last_name','LIKE', $word.'%')->
                        orWhere('email','LIKE', $word.'%');
                });
            }

        }elseif(strlen($slug) == 1){
            $query->where(function($q) use ($slug){
                $q->where('last_name','LIKE', $slug.'%')->
                orWhere('business_name','LIKE', $slug.'%');
            });
            $dealers->where(function($q) use ($slug){
                $q->where('name','LIKE', $slug.'%')->
                    orWhere('last_name','LIKE', $slug.'%');
            });
        }
        $query->orderBy('name');

        $result = $query->select(array(
            'id',
            'contact_type',
            DB::raw('IF(contact_type = "empresa" ,business_name,CONCAT(`last_name`," ",`first_name`)) as name'),
            'phone',
            'email',
            DB::raw('"contacto" as `type`')
        ))->unionAll($dealers)->get();

       $paginator = new LengthAwarePaginator(
            $result,
            count($result),
            $paginate,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
        return $this->response(200,$paginator);
    }
    public function delete($dealer_id,$id)
    {
        return $this->response(200,Contact::find($id)->delete());
    }

    public function show($dealer_id,$id){
        return $this->response(200,Contact::find($id));
    }
}