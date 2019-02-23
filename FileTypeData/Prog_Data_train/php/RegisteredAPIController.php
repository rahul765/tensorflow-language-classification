<?php namespace App\Http\Controllers\Api;

use App\AppKey;
use App\Dealer;
use App\Http\Controllers\Controller;
use App\Repositories\BrandRepository;
use App\Repositories\DealerRepository;
use App\Repositories\LeadRepository;
use App\Repositories\UsedVehicleRepository;
use App\Repositories\VehicleRepository;
use App\UsedVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RegisteredAPIController extends Controller {
    /**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $result = false;
        $api = $request->get('unique');
        $uuid = $request->get('uuid');
        if(!empty($api) && !empty($uuid)) {
            $appKey = AppKey::where('key', $api)->whereNull('uuid')->first();
            if(isset($appKey)) {
                $appKey->uuid = $uuid;
                $appKey->fname = $request->get('firstName');
                $appKey->lname = $request->get('lastName');
                $appKey->email = $request->get('email');
                $appKey->save();
                $result = true;
            }
        }
        $data = [];
        $data['result'] = $result;
        return $data;
	}

    /**
     * Show the application welcome screen to the user.
     *
     * @param Request $request
     * @return Response
     */
    public function check(Request $request)
    {
        $uuid = $request->get('uuid');
        $api = $request->get('unique');
        $appKey = AppKey::where('key', $api)->first();
        $data = [];
        if(!isset($appKey) || $appKey->uuid == $uuid) {
            $data['isExists'] = true;
        } else {
            $data['isExists'] = false;
        }
        return $data;
    }

	/**
     * Show the application welcome screen to the user.
     *
     * @param Request $request
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $uuid = $request->get('uuid');
        $api = $request->get('api');
        $appKey = AppKey::where('key', $api)->first();
        $data = [];
        if(isset($appKey) && $appKey->uuid == $uuid) {
            $data['authenticate'] = true;
        } else {
            $data['authenticate'] = false;
        }
        return $data;
    }
}
