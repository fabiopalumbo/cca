<?php

namespace App\Http\Controllers;

use App\CarDealer;
use App\CarDealerUser;
use App\UserPasswordReset;
use App\UserGroup;
use App\UserSession;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Input;
use Response;
use Lang;
use Auth;
use Hash;
use DB;
use Redirect;
use App\Repositories\QueuePusher;
use \Flow\Config;
use \Flow\Basic;
use Validator;
use View;
use Illuminate\Database\Query\JoinClause;
use App\Group;
use App\Module;
use App\Permission;
use Session;
use App\Repositories\CDNService;
use Intervention\Image\ImageManagerStatic as Image;
use Guzzle\Http\EntityBody;
class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:'.Module::USER.','.Permission::INDEX,['only' => ['index','getUserPermission']]);
		$this->middleware('permission:'.Module::USER.','.Permission::CREATE,['only' => ['createUser']]);
		$this->middleware('permission:'.Module::USER.','.Permission::UPDATE,['only' => ['updateUser','active']]);
		$this->middleware('permission:'.Module::USER.','.Permission::DELETE,['only' => ['deleteUser']]);
		$this->middleware('permission:'.Module::USER.','.Permission::READ,['only' => ['getUser']]);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(){
		return $this->response(200, User::all());
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getUserPermission(){
		$user = Auth::user();
		return $this->response(200,DB::table('users_groups')->
				where('users_groups.user_id','=',$user->id)->
				join('permissions','permissions.group_id','=','users_groups.group_id')->
				join('modules','modules.id','=','permissions.module_id')->
				get(array(
					'modules.id as id',
					'modules.name',
					'permissions.list as list'
				)));
	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getUser($id){
		return $this->response(200, User::with(array('groups','dealers'))->find($id));
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function createUser(){
		$obj = Input::all();

		if($exist = DB::table('users')->
				where('email','=',$obj['email'])->
				first()){
			if($exist->deleted_at){
				return $this->response(400, 'Exist');
			}
			return $this->response(400, 'Error');
		}

		$user = User::create(array(
			'first_name' => $obj["first_name"],
			'last_name' => $obj["last_name"],
			'email' => $obj["email"],
			'password' => Hash::make($obj["password"]),
			'phone' => $obj["phone"],
		));

		if(isset($obj['dealers'])){
			$user->dealers()->sync($obj["dealers"]);
		}

		if(isset($obj["groups"])){
			$user->groups()->sync($obj["groups"]);
		}
		return $this->response(200, 'Success');
	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function updateUser($id){
		$obj = Input::all();
		$user = User::find($id);
		$user->first_name = $obj["first_name"];
		$user->last_name = $obj["last_name"];
		$user->phone = $obj["phone"];
        if(isset($obj["password"])) $user->password = Hash::make($obj["password"]);
		$user->save();

		if(isset($obj['dealers'])){
			$user->dealers()->sync($obj["dealers"]);
		}

		if(isset($obj["groups"])){
			$user->groups()->sync($obj["groups"]);
		}
		return $this->response(200, 'Success');
	}

	/**
	 * @param $id
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function deleteUser($id)
	{
		User::destroy($id);
		$user = Request::all();
		if(Auth::attempt(array(
			"email" => $user['email'],
			"password" => $user['password']
		))){
			$user = Auth::user();
			UserGroup::where('user_id','=',$user->id)->delete();
			$userActive = User::where('email','=',$user['email'])->first();
			$userActive->activated = 0;
			CarDealer::where("user_id",'=',$user->id)->delete();
			User::find((int)$user->id)->delete();
			return Redirect::back()
					->with(['message' => trans('messages.usercontroller.delete.userdeleted')]);
		}else{
			return Redirect::back()
					->withErrors([trans('messages.usercontroller.delete.usernotdeleted')]);
		}

	}



    public function store()
    {
        $obj = Input::all();

        if($exist = DB::table('users')->
            where('email','=',$obj['email'])->
            first()){

            if($exist->deleted_at){
                return Redirect::back()
                    ->withErrors([trans('messages.usercontroller.register.userpreviouslydeleted')])->withInput($obj);
            }
            return Redirect::back()
                ->withErrors([trans('messages.usercontroller.register.emailnotavailable')])->withInput($obj);
        }
        $user = new User;
        $user -> first_name = $obj["first_name"];
        $user -> last_name = $obj["last_name"];
        $user -> email = $obj["email"];
        $user -> password = Hash::make($obj["password"]);
        $user -> phone = $obj["phone"];
        $user -> save();

        UserGroup::create(array(
            'group_id' => UserGroup::DEALER_ADMIN,
            'user_id' => $user->id
        ));
        if(isset($obj['car_dealer'])){
            CarDealerUser::create(array(
                "car_dealer_id" => $obj['car_dealer'],
                "user_id" => $user->id
            ));
        }
        return Redirect::to("ingresar")
            ->with(['message' => trans('messages.usercontroller.register.success')]);
    }

    public function update(Request $request){
        $user = User::find(Auth::user()->id);
        (!$request->input('name') ? : $user->name = $request->input('name'));
        (!$request->input('surname') ? : $user->surname = $request->input('surname'));
        (!$request->input('phone') ? : $user->phone = $request->input('phone'));
        if($request->hasFile('image')){
            $request->file('image')->move(public_path().'/src/images/',Auth::user()->id.'.jpg');
            $user->image_url = 'src/images/'.Auth::user()->id.'.jpg';
        }
        if($user->save()){
            return Redirect::to('/')
                ->with(['message' => trans('messages.usercontroller.update.userupdated')]);
        }
        else{
            return Redirect::back()
                ->withErrors([trans('messages.usercontroller.update.usernotupdated')]);
        }

    }

    public function contact() {
        $data = Input::all();
        $rules = array(
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|min:5'
        );
        $validator = Validator::make($data, $rules);        return View::make('emails.contact')
            ->with($data);
        //todo:es una prueba de la vista ,descomentar cuando esten listas las colas de trabajo
        /*
        if ($validator->passes()) {            Mail::send('emails.contact', $data, function ($message) use ($data) {
                $message->from($data['email'] , $data['name']);
                $message->from('feedback@gmail.com', 'feedback contact form');
                $message->to('feedback@gmail.com', 'John')->cc('feedback@gmail.com')->subject('feedback form submit');            });
            //return Redirect::to('/')
            //   ->with([trans('messages.usercontroller.contact.envioexitoso')]);        } else {
            return Redirect::back()
                ->withErrors([trans('messages.usercontroller.contact.envioerror')]);
        } */
    }

    public function login(Request $request){
        $obj = $request->all();
        $checked = array_key_exists('check',$obj) ? $obj['check'] : false;
        if(!array_key_exists('email',$obj) || !array_key_exists('password',$obj)){
            return Redirect::back()
                ->withErrors([trans('messages.usercontroller.login.error')])->withInput($obj);
        }
            $username = Input::get("email");
            $password = Input::get("password");
            $validatorEmail = Validator::make(Input::all(), [
                "email" => "Email"
            ]);
            if ($validatorEmail->passes()){
                $userLoged = Auth::attempt(array("email" => $username,"password" => $password),$checked);

            }else{
                $userLoged = Auth::attempt(array("username" => $username,"password" => $password),$checked);
            }
        if($userLoged){
            $user_admin = false;
            $user_groups = array();
            $user_dealers = array();
            $user = Auth::user();
            $groups = $user->groups()->whereNull("users_groups.deleted_at")->get()->toArray();
            foreach($groups as $group){
                $user_groups[] = (int)$group["id"];
                if((boolean)$group['admin']){
                    $user_admin = true;
                }
            }
            if(!$user_admin){
                $dealers = $user->dealers()->get()->toArray();
                $user_dealers = array_map(function($item){
                    return (int)$item["pivot"]["car_dealer_id"];
                },$dealers);
            }
            //$user_dealers[] = 0;
            session(['user' => new UserSession($user->id,$user_admin,$user_dealers,$user_groups)]);

            return Redirect::to('admin/index.html')
                ->with(['message' => trans('messages.usercontroller.login.success')]);
        }else{
            return Redirect::back()->withErrors([trans('messages.usercontroller.login.wrong')])->withInput($obj);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return Redirect::to('ingresar')
            ->with(trans('messages.usercontroller.logout.bye'));
    }

    public function check(Request $request)
    {
        if($user = Auth::user()){
	          $user_session = $request->session()->get('user');
            $user_permissions = DB::table('permissions')->
            join('modules',function(JoinClause $join){
                $join->on('modules.id','=','permissions.module_id');
            })->whereIn('permissions.group_id',$user_session->groups)->
                groupBy('permissions.module_id')->
                groupBy('permissions.group_id')
                ->get(array(
                    'modules.name as module',
                    'permissions.read',
                    'permissions.create',
                    'permissions.update',
                    'permissions.delete',
                    'permissions.list',
                ));

            $user["admin"] = $user_session->admin;
            $a = array();

            foreach($user_permissions as $permission){
                if(isset($a[$permission->module])){
                    if($permission->read)   $a[$permission->module]->read = 1;
                    if($permission->create) $a[$permission->module]->create = 1;
                    if($permission->update) $a[$permission->module]->update = 1;
                    if($permission->delete) $a[$permission->module]->delete = 1;
                    if($permission->list)   $a[$permission->module]->list = 1;
                }else{
                    $a[$permission->module] = $permission;
                }
            }
            $user["permission"] = $a;
            $user["dealers"] = $user_session->dealers;

            return Response::json($user);
        }
        return Response::make(\URL::action('ViewUserController@login'),401);

    }

    public function updatePassword()
    {
        $user = Auth::user();
        $data = \Request::all();
        if(Auth::attempt(array(
            "email" => $user['email'],
            "password" => $data['oldPassword']))){

                User::where('email','=',$user['email'])->
                    update(array(
                        'password' => Hash::make($data['newPassword'])
                    ));
            return Redirect::back()
                ->with(['message' => trans('messages.usercontroller.update.passwordupdate')]);
        }
        return Redirect::back()
            ->withErrors([trans('messages.usercontroller.update.passwordnotupdated')]);
    }

    public function active(){

        $data = \Request::all();
        User::onlyTrashed()->where('email','=',$data["email"])->restore();
        if(Auth::attempt(array(
                "email" => $data['email'],
                "password" => $data['password']

            ))){
                $user = User::where('email','=',$data["email"])->first();
                $user->activated = 1 ;
                $user->save();
                return Redirect::back()
                    ->with(['message'=>trans('messages.usercontroller.active.success')]);
            }else{
                User::where('email','=',$data["email"])->delete();
                return Redirect::back()
                    ->withErrors([trans('messages.usercontroller.active.error')])->withInput($data);
            }

        }

    public function recoveryAccount(){

        $email = Input::get("email");
        if($user = User::where("Email",'=',$email)->first()){
            $data = array(
                 'token'=> str_random(30),
            );
            $passreset = new UserPasswordReset;
            $passreset->user_id = $user->id;
            $passreset->token = $data['token'];
            $passreset->save();
            QueuePusher::pushMessageToQueue('MailJob',
             ['view'    => 'emails.recovery',
              'data'    => $data,
              'email'   => $email,
              'subject' => trans('messages.usercontroller.recovery.emailsubject'),]);
            return Redirect::to('/')->with(['message' => trans('messages.usercontroller.recovery.emailsent')]);
        }else{
            return Redirect::back()
                ->withErrors([trans('messages.usercontroller.recovery.emailerror')])->withInput($email);
        }
    }

    public function resetPassword($token){
        if ($user = User::where('remember_token','=',$token)->first()){
            return view('password-recovery')->with(array('token'=>$token));
        }else{
            return Redirect::to('/');
        }
    }

    public function verifyToken($token){
        $user = User::where('remember_token','=',$token)->first();
        if(empty($user)){
            return $this->response(400,'Error');
        }else return $this->response(200,$user->email);

    }
    public function updateRecovery(){
        $data = \Request::all();
        $userid = UserPasswordReset::where('token',$data['token'])->first();

        if($user = User::where('id','=',$userid->user_id)->first()){
            $user->password = Hash::make($data['password']);
            $user->save();
            UserPasswordReset::destroy($userid->id);
            return Redirect::to('login')
                ->with(['message' => trans('messages.usercontroller.recovery.success')]);
        }else{
            return Redirect::back()
                ->withErrors([trans('messages.usercontroller.recovery.error')]);
        }
    }

    public function changeImage($id){
        $request = new \Flow\Request();
        $destination = public_path().'/src/images/'.$request->getFileName();
        $config = new Config(array(
            'tempDir' => storage_path().'/app/images'
        ));
        if (Basic::save($destination, $config, $request)) {
            $user = User::find($id);
            $dest = "src/images/".$request->getFileName();
            $user->image_url = $dest;
            $user->save();
            return Redirect::back()
                ->with(['message' => trans('messages.usercontroller.image.success')]);
        } else {
            return Redirect::back()
                ->withErrors([trans('messages.usercontroller.image.error')]);
        }
    }

    public function uploadImage(){
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
                User::find(Auth::user()->id)->update(array("original_image_url" => $url));
            }else{
                User::find(Auth::user()->id)->update(array("cropped_image_url" => $url));
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

    public function isAdmin($id){
       $permission = \DB::table('users_groups')->
            join('users','users.id','=','users_groups.user_id')->
            join('groups','groups.id','=','users_groups.group_id')->
            where('users_groups.user_id','=',$id)->first(
           array(
               'groups.id',
               'groups.name',
               'users_groups.group_id'
                )
           );

         if($permission->name == "admin"){
             return $this->response(200,1);
         }else{
             return $this->response(400,0);
         }
    }

    public function getGroup($id){
       $userGroup = DB::table('users_groups')->
                    where('user_id','=',$id)->
                    get(array(
                      'users_groups.group_id'
                    ));

        $ids = array_map(function($item){
            return (int)$item->id;
        },$userGroup);
        $ids[] = 0;

        return  $this->response(200,$ids);
    }


    public function indexGroup($id){
        $group = DB::table('groups')->
        leftJoin('users_groups',function($join)use($id){
            $join->on('users_groups.group_id','=','groups.id');
            $join->on('users_groups.user_id','=',DB::raw($id));
        })->
        whereNull('groups.deleted_at')->
        orderBy('groups.name')->
        where('users_groups.user_id','=',$id)->
        get(array(
            'groups.id',
            'groups.name',
            DB::raw('IF(users_groups.group_id IS NULL,0,1) as selected')

        ));


        $results = array_map(function($item){
            $item->selected = (int)$item->selected;
            return $item;
        },$group);

        return $this->response(
            200,
            array(
                'total' => count($results),
                'data'  => $results
            )
        );
    }
}