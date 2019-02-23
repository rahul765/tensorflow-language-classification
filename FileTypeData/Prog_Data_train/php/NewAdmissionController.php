<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Classmaster;
use App\ClassSection;
use DB;
use App\StudentCatagory;
use App\State;
use App\District;
use App\RouteModel;
use Illuminate\Support\Facades\Input;

class NewAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('newadmission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class=Classmaster::all();
        $catagory=StudentCatagory::all();
        $state=State::all();
        $route=RouteModel::all();
        return view('newadmission.create',compact('class','catagory','state','route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function district(){
      $cat_id = Input::get('cat_id');
      $subcategories = District::where('state_id', '=', $cat_id)
                    ->orderBy('id', 'asc')
                    ->get();

       return \Response::json($subcategories);
    }
    public function section(){
      $cat_id = Input::get('cat_id');
      $subcategories =DB::table('section')
                        ->join('class_section', 'section.id', '=', 'class_section.section_id')
                        ->where('class_id','=',$cat_id)
                        ->get();

       return \Response::json($subcategories);
    }
    public function routestop(){
      $cat_id = Input::get('cat_id');
      $subcategories =DB::table('route')
                        ->join('route_stop', 'route_stop.route_id', '=', 'route.id')
                        ->where('route.id','=',$cat_id)
                        ->get();

       return \Response::json($subcategories);
    }
}
