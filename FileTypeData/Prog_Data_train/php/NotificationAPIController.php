<?php namespace App\Http\Controllers\Api;

use App\AppKey;
use App\Dealer;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeadRequest;
use App\Repositories\BrandRepository;
use App\Repositories\DealerRepository;
use App\Repositories\LeadRepository;
use App\Repositories\UsedVehicleRepository;
use App\Repositories\VehicleRepository;
use App\UsedVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class NotificationAPIController extends Controller {
    private $repository;

    /**
     * @param DealerRepository $repository
     */
    function __construct(LeadRepository $repository) {
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
            'updated_at'            => 'desc',
        ]);
        $notifications = $this->repository->search(array_merge(['status' => 'ACTIVE'], $request->all()), $size, $sorts, true);
        return $notifications;
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

    public function store(LeadRequest $request)
    {
        $data = array_merge([
            'status' => 'ACTIVE'
        ], $request->except('api'));
        $api = $request->get('api');
        $api = AppKey::where('key', $api)->firstOrFail();
        $user = $api->dealer->user;
        $lead = $user->leads_owned()->create($data);
        return $lead;
    }
}
