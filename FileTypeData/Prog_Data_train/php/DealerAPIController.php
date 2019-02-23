<?php namespace App\Http\Controllers\Api;

use App\Dealer;
use App\Http\Controllers\Controller;
use App\Repositories\BrandRepository;
use App\Repositories\DealerRepository;
use App\Repositories\UsedVehicleRepository;
use App\Repositories\VehicleRepository;
use App\UsedVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DealerAPIController extends Controller {
    private $repository;

    /**
     * @param DealerRepository $repository
     */
    function __construct(DealerRepository $repository) {
        $this->repository = $repository;
        //$this->middleware('guest');
    }



	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $size = $request->get('size', getenv('DEFAULT_LIST_SIZE'));
        $sorts = array();
        $sorts = array_merge($sorts, [
            'dealer.name'            => 'asc',
        ]);
        $dealers = $this->repository->search(array_merge(['status' => 'ACTIVE'], $request->all()), $size, $sorts);
        return $dealers;
	}

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function show($id)
    {
        return Dealer::find($id);
    }
}
