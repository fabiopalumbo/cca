<?php

use App\Module;
use Illuminate\Foundation\Bus\DispatchesJobs;

Route::get('/','ViewUserController@index');
Route::get('mercadolibre/types','MercadoLibreController@getVehiclesTypes');
Route::get('mercadolibre/brands','MercadoLibreController@getBrandsTypes');
Route::get('mercadolibre/models','MercadoLibreController@getModelsTypes');
Route::get('mercadolibre/versions','MercadoLibreController@getVersionsTypes');
Route::get('mercadolibre/regions','MercadoLibreController@getRegions');
Route::get('mercadolibre/cities','MercadoLibreController@getCities');
Route::get('mercadolibre/attributes','MercadoLibreController@getAttributes');
Route::get('mercadolibre/listingTypes','MercadoLibreController@getListingTypes');
//Route::get('autocosmos','AutoCosmosController@prueba');
Route::get('autocosmos/brands','AutoCosmosController@getBrands');
Route::get('autocosmos/models','AutoCosmosController@getModels');
//Route::get('deAutos');
Route::get('deAutos/brands','DeAutosController@getBrands');
Route::get('deAutos/models','DeAutosController@getModels');
Route::get('deAutos/versions','DeAutosController@getVersions');
Route::get('deAutos/provinces','DeAutosController@getProvinces');
Route::get('deAutos/cities','DeAutosController@getCities');
Route::get('deAutos/colors','DeAutosController@getColors');

Route::get('listado-cca-autos','ImportController@importCarCSV');
Route::get('listado-cca-camiones','ImportController@importTruckCSV');
Route::get('listado-cca-socios','ImportController@importPartnerCSV');
Route::get('listado-demotores-marcas','DeMotoresController@importCarCSV');
/*Route::get('mail-autocosmo',function(){
    return View::make('emails.respuestaAutocosmos')->with(array(
        "name" => "Pepe argento",
        "question" => "Lo tenes todavia?",
        "answer" => "Si, oferta que me lo quieren comprar"
    ));
});*/
//Route::get('mail-ac/{id}',function($id){
//    $dealer = \App\CarDealer::find(1);
//    $region = \App\Region::find($dealer->region_id)->name;
//    $city = \App\RegionCity::find($dealer->region_city_id)->name;
//    return \Response::view('emails.subscription-ac',array('dealer'=>$dealer,'region'=>$region,'city'=>$city));
//});

Route::group(['middleware' => 'guest'],function(){
    Route::get('ingresar','ViewUserController@login');
    Route::post('ingresar','UserController@login');
    Route::get('registro','ViewUserController@register');
    Route::post('registro','UserController@store');
    Route::get('olvido-contraseña','ViewUserController@forgotPassword');
    Route::post('olvido-contraseña','UserController@recoveryAccount');
    Route::get('contacto','ViewUserController@contact');
    Route::post('contacto','UserController@contact');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('logout','UserController@logout');
    Route::get('admin/logout','UserController@logout');
    Route::get('modificar-info','ViewUserController@modify');
});



Route::group(['middleware' => 'admin'],function(){
    /*Route::get('admin', function () {
        return view('apps.admin');
    });*/
});

// no verificados
Route::post('post/queue/{queue}','QueuePost@Post');

Route::group(array('prefix' => '?_escaped_fragment_='),function(){

});

Route::group(array('prefix' => 'api'),function(){
    Route::get( 'user/check','UserController@check');

    //fix-loopear: verificar
    Route::post('user/active','UserController@active');
    Route::get('user/recovery/reset-password/{token}','UserController@resetPassword');
    Route::get('user/verify/{token}','UserController@verifyToken');
    Route::post('user/updateRecovery','UserController@updateRecovery');
    Route::any('user/{id}/setImage','UserController@changeImage');
    Route::post('autocosmos/questions','AutoCosmosController@getQuestions');
    //hasta aca

    Route::group(['middleware' => 'auth'],function(){
        //Grupos
        Route::get('group','GroupController@index');
        Route::post('group','GroupController@store');
        Route::get('group/{id}','GroupController@show');
        Route::post('group/{id}','GroupController@update');
        Route::delete('group/{id}','GroupController@delete');
        Route::post('group/user/{user_id}/asociar','GroupController@asociate');
        Route::post('group/user/{user_id}/desasociar','GroupController@desasociate');

        //Permisos
        Route::get( 'user/permission','UserController@getUserPermission');
//	    Verificar si se llaman
        //Route::get( 'groups','GroupController@index');
        Route::get( 'modules','ModuleController@index');
        Route::get( 'permissions','PermissionController@index');
        Route::post('permissions','PermissionController@updatePermiso');

        //Usuarios
        Route::get( 'user','UserController@index');
        Route::get( 'user/{id}','UserController@getUser');
        Route::post('user','UserController@createUser');
        Route::post('user/image/upload','UserController@uploadImage');
        Route::post('user/{id}','UserController@updateUser');
        Route::delete('user/{id}','UserController@deleteUser');
        //todo: Realizar middleware para las rutas
        Route::get('user/{id}/admin','UserController@isAdmin');
        Route::post('user/update','UserController@update');
        Route::get('user/{id}/group','UserController@indexGroup');
        Route::post('user/updatePass','UserController@updatePassword');

        //Vehiculos
        Route::get('vehicle/fuels','VehicleController@getFuels');
        Route::get('vehicle/types','VehicleController@getTypes');
        Route::get('vehicle/brands','VehicleController@getBrands');
        Route::get('vehicle/models','VehicleController@getModels');
	    Route::get('vehicles/model/{model_id}/versions','VehicleController@getVersions');
        Route::get('vehicle/colors','VehicleController@getColors');
        Route::get('vehicle/currencies','VehicleController@getCurrencies');
	    Route::get('vehicle/features','VehicleController@getFeatures');

        Route::group(array('prefix' => 'dealer/{dealer_id}'),function(){

            Route::post('linking','CarDealerController@linking');
            Route::get('linking','CarDealerController@linkingShow');
            Route::delete('linking/{portal}','CarDealerController@linkingDelete');
            Route::post('image/upload','CarDealerController@uploadImage');
            Route::get('vehicles','VehicleController@index');
            Route::get('vehicles/{id}','VehicleController@show');
            Route::post('vehicles','VehicleController@store');
            Route::post('vehicles/{id}','VehicleController@update');
            Route::post('vehicles/{id}/update-portals','VehicleController@updatePortals');
            Route::delete('vehicles/{id}','VehicleController@delete');
            Route::get('vehicles/{id}/mercadolibre/verify','MercadoLibreController@verifyMlState');
            Route::get('vehicles/{id}/images','VehicleController@getVehiclePhotos');
            Route::post('vehicle/{id}/image/upload','VehicleController@uploadVehiclePhoto');
            Route::delete('vehicles/image/{id}','VehicleController@removeVehiclePhoto');
            Route::post('vehicles/image/{id}/destacada','VehicleController@changeDestacada');
            Route::post('vehicle/{id}/note','VehicleController@createNote');
            Route::post('vehicle/{vehicle_id}/note/{id}','VehicleController@updateNote');
            Route::post('vehicles/note/{id}/delete','VehicleController@deleteNote');

            //DeMotores
            Route::post('demotores/vehicle/{vehicle_id}/publish','DeMotoresController@publish');
            Route::post('demotores/vehicle/{vehicle_id}/update-vehicle','DeMotoresController@update');
            Route::delete('demotores/vehicle/{vehicle_id}/unpublish','DeMotoresController@unpublish');

            //deAutos
            Route::post('deautos/vehicle/{vehicle_id}/login','DeAutosController@login');
            Route::post("deautos/vehicle/{vehicle_id}/update-vehicle","DeAutosController@updateFeatures");
            Route::delete('deautos/vehicle/{vehicle_id}/unpublish','DeAutosController@deleteItem');

            //Red nuestrosAutos
            Route::get('nuestros-autos/vehicles/share','VehicleController@getSharedVehicles');
            Route::get('nuestros-autos/vehicle/{id}','VehicleController@getSharedVehicle');
            Route::post('vehicle/{id}/share','VehicleController@shareVehicle');
            Route::delete('vehicle/{id}/share','VehicleController@DeleteshareVehicle');

            //Mercadolibre
	        Route::get('mercadolibre/vehicles','MercadoLibreController@listItems');
            Route::get('mercadolibre/vehicles/{item_id}','MercadoLibreController@show');
	        Route::post('mercadolibre/vehicle/{vehicle_id}/publish','MercadoLibreController@publishItem');
            Route::delete('mercadolibre/item/{item_id}','MercadoLibreController@delete');
            Route::post('mercadolibre/vehicle/{id}/listing-type','MercadoLibreController@storeListingType');
            Route::delete('mercadolibre/vehicle/{vehicle_id}/listing-type','MercadoLibreController@closePublish');
            Route::post("mercadolibre/vehicle/{vehicle_id}/update-vehicle","MercadoLibreController@updateFeatures");


            //Facebook
            Route::post('facebook/vehicle/{vehicle_id}/share','FacebookController@shareFbItem');
            Route::delete('facebook/vehicle/{vehicle_id}/share','FacebookController@deleteFbItem');

            //autocosmos
            Route::post('autocosmos/vehicle/{id}/publish','AutoCosmosController@publishItem');
            Route::post("autocosmos/vehicle/{vehicle_id}/update-vehicle","AutoCosmosController@updateFeatures");
            Route::delete('autocosmos/vehicle/{id}/unpublish','AutoCosmosController@deleteItem');


            //Contacto
            Route::get('contact','ContactController@index');
            Route::get('contact/{id}','ContactController@show');
            Route::post('contact','ContactController@store');
            Route::post('contact/{id}','ContactController@store');
            Route::delete('contact/{id}','ContactController@delete');
            Route::post('contact/mercadolibre/question/vehicle/{vehicle_id}/ask','QuestionsController@ask');
            Route::post('contact/mercadolibre/question/{question_id}/answer/vehicle/{vehicle_id}','QuestionsController@answer');
            Route::get('contact/questions','QuestionsController@index');
            Route::get('contact/mercadolibre/questions/{question_id}/vehicle/{vehicle_id}','QuestionsController@show');
            Route::delete('contact/mercadolibre/question/{question_id}/vehicle/{vehicle_id}','QuestionsController@delete');
            Route::get('questions','QuestionsController@index');

            //Concesionaria
            Route::get( '/','CarDealerController@show');
            Route::post('/','CarDealerController@update');
            Route::delete('/','CarDealerController@delete');
            //Route::post('activate','CarDealerController@activate');
            Route::post('image/upload','CarDealerController@uploadDealerPhoto');
            Route::delete('image','CarDealerController@removeDealerPhoto');

            //Ventas
            Route::get('sale','SaleController@index');
            Route::get('sale/{id}','SaleController@show');
            Route::post('sale','SaleController@store');
            Route::post('sale/{id}/publish','SaleController@reserve');
            Route::post('sale/{id}/cancel','SaleController@cancel');
            Route::post('sale/{id}/sell','SaleController@sell');
            Route::post('sale/{id}/transfer','SaleController@transfer');


        });

        //Concesionaria
        Route::get( 'dealer','CarDealerController@index');
        Route::post('dealer','CarDealerController@store');

        //MercadoLibre Login
        Route::get('mercadolibre/login','MercadoLibreController@login');
        Route::get('mercadolibre/authorize','MercadolibreController@authorize');
        Route::get('mercadolibre/listing-types','MercadoLibreController@listingTypes');
        Route::get('mercadolibre/app-login','MercadoLibreController@mlLogin');

        //Facebook Login
        Route::get('facebook/login','FacebookController@login');

        //Facebook Pages
        Route::get('facebook/pages','FacebookController@indexPages');


        //Regiones
        Route::get('region','RegionController@getRegions');
        Route::get('region-city','RegionController@getRegionsCities');

        /*Route::group([
            'middleware' => 'permission:'.Module::USER.','.Permission::UPDATE
            ],function(){

            Route::post('','');
        });
        Route::group([
            'middleware' => 'permission:'.Module::PERMISSION.','.Permission::UPDATE
            ],function(){

        });*/
    });
});

$adminFunctions = function(){

};

Route::group(array('domain' => 'admin.nuestrosautos.com.ar'),$adminFunctions);
Route::group(array('domain' => 'staging.admin.nuestrosautos.com.ar'),$adminFunctions);

//Route::group(array('domain' => '{pre}.{subdominio}.wonoma.{post}'),function(){
//	Route::get('{args?}','ViewUserController@checkSubdomain')->
//		where('args', '(.*)');
//});

//rutas no definidas redirigen al home
if(App::environment('production')){
	Route::get("{args?}",function(){
		return Redirect::to("",301);
	})->where('args', '(.*)');
}
