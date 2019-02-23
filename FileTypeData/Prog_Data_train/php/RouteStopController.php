<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\RouteStop;
use App\RouteModel;
use Auth;
use Flash;
class RouteStopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index=RouteStop::paginate(10);
        return view('route-stop.index',compact('index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route=RouteModel::all();
        return view('route-stop.create',compact('route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validation=$this->validate($request, [
          'name' => 'required|max:60',
          'route_id'=>'required',

      ]);
      $data= new RouteStop();
      $data->name=$request->name;
      $data->route_id=$request->route_id;
      $data->created_by=Auth::user()->id;
      $data->created_at=date("Y-m-d");
      $data->school_id=Auth::user()->school_id;
      $data->save();
      Flash::overlay('New Route Stop has been Created Successfully','Success');
      return redirect('route-stop');
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
      $edit=RouteStop::findOrFail($id);
      $route=RouteModel::all();
      return view('route-stop.edit',compact('edit','route'));
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
      $validation=$this->validate($request, [
          'name' => 'required|max:60',
          'route_id'=>'required',

      ]);
      $data= RouteStop::findOrFail($id);
      $data->name=$request->name;
      $data->route_id=$request->route_id;
      $data->updated_by=Auth::user()->id;
      $data->updated_at=date("Y-m-d");
      $data->school_id=Auth::user()->school_id;
      $data->update();
      Flash::overlay('New Route Stop has been Updated Successfully','Success');
      return redirect('route-stop');
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
}
