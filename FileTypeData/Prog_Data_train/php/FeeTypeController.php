<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\FeeType;
use Flash;
use Auth;
class FeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $index=FeeType::paginate(10);
      return view('fee-type.index',compact('index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('fee-type.create');
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

      ]);
      $data= new FeeType();
      $data->name=$request->name;
      $data->school_id=Auth::user()->school_id;
      $data->created_by=Auth::user()->id;
      $data->created_at=date("Y-m-d");
      $data->created_at=date("Y-m-d");

      $data->save();
     Flash::overlay('New Fee Type has been Added Successfully','Success');
     return redirect('fee-type');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $show = FeeType::findOrFail($id);

      return view('fee-type.show')->withShow($show);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $edit = FeeType::findOrFail($id);

      return view('fee-type.edit')->withEdit($edit);
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

      ]);
      $data=FeeType::find($id);
      $data->name=$request->name;
      $data->school_id=Auth::user()->school_id;
      $data->updated_by=Auth::user()->id;
      $data->updated_at=date("Y-m-d");

      $data->update();
     Flash::overlay('Fee Type has been Updated Successfully','Success');
     return redirect('fee-type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $session = FeeType::findOrFail($id);
      $session->delete();
      Flash::overlay('Fee Type has been Deleted Successfully','Success');
      return redirect('fee-type');
    }
}
