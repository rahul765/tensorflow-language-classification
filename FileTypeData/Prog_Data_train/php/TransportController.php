<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Transport;
use Auth;
use Flash;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $index=Transport::paginate(10);
        return view('transport.index',compact('index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('transport.create');
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
          'status'=>'required',
          'number_plate'=>'required'
      ]);
      $data= new Transport();
      $data->name=$request->name;
      $data->status=$request->status;
      $data->created_by=Auth::user()->id;
      $data->created_at=date("Y-m-d");
      $data->number_plate=$request->number_plate;
      $data->school_id=Auth::user()->school_id;
      $data->save();
      Flash::overlay('New Transport has been Created Successfully','Success');
      return redirect('transport');
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
      $edit=Transport::findOrFail($id);
      return view('transport.edit')->withEdit($edit);
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
            'status'=>'required',
            'number_plate'=>'required'
        ]);
        $data=Transport::findOrFail($id);
        $data->name=$request->name;
        $data->status=$request->status;
        $data->created_by=Auth::user()->id;
        $data->created_at=date("Y-m-d");
        $data->number_plate=$request->number_plate;
        $data->school_id=Auth::user()->school_id;
        $data->update();
        Flash::overlay('Transport has been Updated Successfully','Success');
        return redirect('transport');
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
