<?php namespace App\Http\Controllers\Frontend;

use App\Dealer;
use App\Http\Controllers\Controller;
use App\Repositories\BrandRepository;
use App\Repositories\UsedVehicleRepository;
use App\Repositories\VehicleRepository;
use App\UsedVehicle;
use App\Vehicle;
use Illuminate\Http\Request;

class DealerController extends Controller {
    function __construct() {
    }


    /**
     * Show the application welcome screen to the user.
     *
     * @param Request $request
     * @return Response
     */
	public function index(Request $request)
	{
        $size = $request->get('size', getenv('DEFAULT_LIST_SIZE'));
        $dealers = Dealer::where('status', 'ACTIVE')->get();
        return view('frontend.dealers.index', compact('dealers', 'request'));
	}

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function show(Request $request, $slug)
    {
        $dealer = Dealer::where('slug', $slug)->firstOrFail();
        return view('frontend.dealers.show', compact('dealer'));
    }
}
