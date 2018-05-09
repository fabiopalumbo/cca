<?php

namespace App\Http\Controllers;

use App\CarDealer;
use App\CarDealerToken;
use App\Contact;
use App\Permission;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Module;
use App\Repositories\CDNService;
use Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Guzzle\Http\EntityBody;
class CarDealerController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = session('user');

        $this->middleware('permission:'.Module::CONCESIONARIA.','.Permission::INDEX,  ['only' => ['index']]);
        $this->middleware('permission:'.Module::CONCESIONARIA.','.Permission::READ,   ['only' => ['show']]);
        $this->middleware('permission:'.Module::CONCESIONARIA.','.Permission::CREATE, ['only' => ['store','activate']]);
        $this->middleware('permission:'.Module::CONCESIONARIA.','.Permission::UPDATE, ['only' => ['update']]);
        $this->middleware('permission:'.Module::CONCESIONARIA.','.Permission::DELETE, ['only' => ['delete']]);

        $this->middleware('access', ['only' => ['show','update','delete']]);
    }

		/**
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function index(){
				return $this->response(200,
            $this->user->admin ?
            CarDealer::all() :
            CarDealer::whereIn('id',$this->user->dealers)->get()
        );
    }

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id){
        return $this->response(200,CarDealer::find($id));
    }

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(){
      $dealer = Input::get("dealer");
      $validator = Validator::make(
          array(
              'partner'   =>  $dealer["partner"],
              'company'     =>  $dealer["company"],
              'cuit'   =>  $dealer["cuit"],
              'last_name' => $dealer["last_name"],
              'dni_type' => $dealer["dni_type"],
              'dni' => $dealer['dni'],
              'location' => $dealer['location'],
              'cellphone' => $dealer["cellphone"],
              'fax' => $dealer["fax"],
              'contact' => $dealer["contact"]
          ),
          array(
              'partner'   => 'required',
              'company'     =>  'required',
              'cuit'   =>  'required',
              'last_name' => 'required',
              'dni_type' => 'required',
              'dni' => 'required',
              'location' => 'required',
              'cellphone' => 'required',
              'fax' => 'required',
              'contact' => 'required'
          )
      );

      if (! $validator->passes()) {
          return $this->response(400,'Campos requeridos');
      }
	    return $this->response(200,CarDealer::create(array(
            'name' => $dealer['name'],
            'email' => $dealer['email'],
            'phone' => $dealer['phone'],
            'address' => $dealer['address'],
            'work_hours' => $dealer['work_hours'],
            'region_id' => $dealer['region_id'],
            'region_city_id' => $dealer['region_city_id'],
            'validated_by' =>  $dealer['validated'] ? Auth::user()->id : null,
            'validated_at' => $dealer['validated'] ? Carbon::now() : null,
            'partner'   =>  $dealer["partner"],
            'company'     =>  $dealer["company"],
            'cuit'   =>  $dealer["cuit"],
            'last_name' => $dealer["last_name"],
            'dni_type' => $dealer["dni_type"],
            'dni' => $dealer['dni'],
            'location' => $dealer['location'],
            'cellphone' => $dealer["cellphone"],
            'fax' => $dealer["fax"],
            'contact' => $dealer["contact"]
             )));
    }

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($id){
        if($car_dealer = CarDealer::find($id)){
	        $dealer = Input::get("dealer");
	        if($dealer['validated']){
		        if(is_null($car_dealer->validated_by)){
			        $car_dealer->validated_by = Auth::user()->id;
                    $car_dealer->validated_at = Carbon::now();
		        }
	        }else{
		        $car_dealer->validated_by = null;
		        $car_dealer->validated_at = null;
		    }
	        $car_dealer->name = $dealer['name'];
	        $car_dealer->email = $dealer['email'];
	        $car_dealer->phone = $dealer['phone'];
	        $car_dealer->address = $dealer['address'];
	        $car_dealer->work_hours = $dealer['work_hours'];
	        $car_dealer->region_id = $dealer['region_id'];
	        $car_dealer->region_city_id = $dealer['region_city_id'];
			$car_dealer->partner = $dealer['partner'];
			$car_dealer->company = $dealer['company'];
			$car_dealer->cuit = $dealer['cuit'];
			$car_dealer->last_name = $dealer['last_name'];
			$car_dealer->dni_type = $dealer['dni_type'];
			$car_dealer->dni = $dealer['dni'];
			$car_dealer->location = $dealer['location'];
			$car_dealer->cellphone = $dealer['cellphone'];
			$car_dealer->fax = $dealer['fax'];
			$car_dealer->contact = $dealer['contact'];
	        $car_dealer->save();

            return $this->response(200,'OK');
        }else{
            return $this->response(400,'Error');
        }

    }

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($id){
        if(CarDealer::find($id)->delete()){
            return $this->response(200,'OK');
        }else{
            return $this->response(400,'Error');
        }
    }

//    public function activate($id){
//        if(CarDealer::find($id)->update(array(
//            'activated_at' => Carbon::now(),
//            'activated_by'=> Auth::user()->id
//        ))){
//            return $this->response(200,'OK');
//        }else{
//            return $this->response(400,'Error');
//
//        }
//    }

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function showSales($id){
        return $this->response(200,DB::table("sales")->
        join("sale_states",'sales.id','=','sale_states.sale_id')->
        join("vehicles",'vehicles.id','=',"sales.vehicle_id")->
        join('vehicle_types','vehicle_types.id','=','vehicles.vehicle_type_id')->
        join('fuels','fuels.id','=','vehicles.fuel_id')->
        join('sale_states','sale_states.id','=','sales.state_id')->
        join('contacts','contacts.id','=','sale.contact_client_id')->
        whereNull('sales.deleted_at')->
        whereNull('vehicles.deleted_at')->
        where('sales.id','=',$id)->
            get(array(
            "vehicles.ingress as ingress",
            "vehicles.domain as domain",
            "vehicles.brand as brand",
            "vehicles.model as model",
            "vehicles.version as version",
            "vehicles.year as year",
            "fuels.name as fuel",
            "vehicles.kilometers as kilometers",
            "vehicles.color as color",
            "vehicles.buy as buy",
            "vehicles.sell as sell",
            "vehicles.owner as owner",
            "vehicle_type.name as type",
            "sales.details as details",
            Contact::all(),
            "sale_states.name as state_name"
            ))
        );
    }

    public function uploadDealerPhoto($dealer_id){
        $files = Input::all();
        $inputs = array("original" => $files[0],"cropped" => $files[1]);
        foreach($inputs as $key => $input){
            $image = Image::make(base64_decode(explode(',',$input["file"])[1]));
            $url = CDNService::uploadBase64Image(
                $input['filename'],
                EntityBody::factory($image->encode('jpeg',100)
                )
            );

            if($key == "original"){
                CarDealer::find($dealer_id)->update(array("original_image_url" => $url));
            }else{
                CarDealer::find($dealer_id)->update(array("cropped_image_url" => $url));
                $cropped_url = $url;
            }
        }
        return $this->response(
            200,
            array(
                'url' => $cropped_url,
            )
        );

    }

    public function removeDealerPhoto($id){
        $dealer = CarDealer::find($id);
        $dealer->image_url = null;
        $dealer->save();
        return $this->response(200,'OK');
    }

    public function linking($dealer_id)
    {
        $data = \Request::get('data');
        if($dealer = CarDealerToken::where('car_dealer_id','=',$dealer_id)->first()){
            if(!$dealer->{$data['portal']}){
                $dealer->{$data['portal']} = $data['data'];
                $dealer->save();
            }else if($dealer->{$data['portal']} != $data['data']){
                return \Response::make('otra cuenta ya esta vinculada',400);
            }
        }else{
            return \Response::make('acceso denegado',401);
        }
        return $this->linkingShow($dealer_id);
    }

    public function linkingShow($dealer_id)
    {
        $dealer = CarDealerToken::where('car_dealer_id','=',$dealer_id)->first();
        if(!$dealer) $dealer = CarDealerToken::create(['car_dealer_id' => $dealer_id]);
        $dealer->mercadolibre = array('username' => !!$dealer->mercadolibre, 'portal' => 'mercadolibre');
        $dealer->autocosmos = array('username' => !!$dealer->autocosmos, 'portal' => 'autocosmos');
        $dealer->deautos = array('username' => !!$dealer->deautos, 'portal' => 'deautos');
        $dealer->demotores = array('username' => !!$dealer->demotores, 'portal' => 'demotores');

        return \Response::make($dealer,200);
    }

    public function linkingDelete($dealer_id,$portal)
    {
        CarDealerToken::where('car_dealer_id','=',$dealer_id)->update([$portal => '']);
        return $this->linkingShow($dealer_id);
    }
}
