<?php namespace App\Http\Controllers\Dealer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\LeadRequest;
use App\Lead;
use App\Repositories\LeadRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LeadsController extends Controller {

    private $repository;
    private $userRepository;

    /**
     * @param LeadRepository $repository
     * @param UserRepository $userRepository
     */
    function __construct(LeadRepository $repository, UserRepository $userRepository) {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $size = $request->get('size', getenv('DEFAULT_LIST_SIZE'));
        $data = ['user_id' => [null, Auth::user()->id]] + $request->all();
        $leads = $this->repository->regexSearch($data, $size);
        return view('dealer.leads.index', compact('leads', 'size'))->with('request', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function mine(Request $request)
    {
        $delete_all_btn = $request->get('delete_all_btn');
        if(isset($delete_all_btn)) {
            $del_ids = $request->get('del_ids');
            if(isset($del_ids)) {
                $this->repository->destroy($del_ids);
            }
        }
        $size = $request->get('size', getenv('DEFAULT_LIST_SIZE'));
        $data = ['owner_id' => [null, Auth::user()->id]] + $request->all();
        $leads = $this->repository->regexSearch($data, $size);
        return view('dealer.leads.mine', compact('leads', 'size'))->with('request', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('dealer.leads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeadRequest $request
     * @return Response
     */
    public function store(LeadRequest $request, Guard $auth)
    {
        $ownedBy = ['owner_id' => $auth->user()->id];

        $user = null;
        $user_id = $request->get('user_id');
        if(!empty($user_id) && $user_id != -1) {
            $user = $this->userRepository->find($user_id);
        }
        $lead = $this->repository->create(array_merge($ownedBy, $request->all()), $user);
        flash()->success("Lead has been created successfully");
        return redirect(route('dealer-area.leads.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Lead $lead)
    {
        return view('dealer.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lead $lead
     * @internal param int $id
     * @return Response
     */
    public function edit(Lead $lead)
    {
        return view('dealer.leads.update', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Lead $lead
     * @param LeadRequest $request
     * @internal param int $id
     * @return Response
     */
    public function update(Lead $lead, LeadRequest $request, Guard $auth)
    {
        $data = $request->all();
        $data['owner_id']= $auth->user()->id;
        if(isset($data['user_id']) && $data['user_id'] == '-1') {
            $data['user_id'] = null;
        }
        $lead = $this->repository->update($lead, $data);
        flash()->success("Lead has been updated successfully");
        return redirect(route('dealer-area.leads.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        flash()->success("Lead has been removed successfully");
        return redirect(route('dealer-area.leads.index'));
    }

    /**
     * export a listing of the resource.
     *
     * @return Response
     */
    public function export(Request $request)
    {
        $size = $request->get('size', getenv('DEFAULT_LIST_SIZE'));
        $data = ['user_id' => [null, Auth::user()->id]] + $request->all();
        $leads = $this->repository->regexSearch($data, $size);
        Excel::create('Car-Mazic-Leads-List', function($excel) use ($leads) {
            $excel->sheet('Leads', function($sheet) use ($leads) {
                $sheet->loadView('dealer.leads.export')->with('leads', $leads);
            });
        })->export('xlsx');
    }
}