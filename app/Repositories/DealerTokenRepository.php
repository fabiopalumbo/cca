<?php

namespace App\Repositories;


class DealerTokenRepository
{
    public function getToken($dealer_id,$portal)
    {
        if($tokens = \DB::table('car_dealers_tokens')->
            where('car_dealer_id','=',$dealer_id)->
            whereNull('deleted_at')->
            whereNotNull($portal)->
            first()){
            return $tokens->{$portal};
        }
        return \Response::make('no posee una cuenta vinculada',400);

    }
}