<?php

namespace App\Http\Controllers;

use App\Region;
use App\RegionCity;

class RegionController extends Controller
{

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getRegions(){
		return $this->response(200,Region::all());
  }

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getRegionsCities(){
		return $this->response(200,RegionCity::all());
  }

 }