<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\SubjectType;
use Auth;
use Flash;

class SubjectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index=SubjectType::paginate(10);
        return view('subject-type.index',compact('index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('subject-type.create');
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
      $data= new SubjectType();
      $data->name=$request->name;
      $data->school_id=Auth::user()->school_id;

      $data->save();
     Flash::overlay('New Subject Type has been Added Successfully','Success');
     return redirect('subject-type');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show=SubjectType::findOrFail($id);
        return view('subject-type.show')->withShow($show);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $edit=SubjectType::findOrFail($id);
      return view('subject-type.edit')->withEdit($edit);
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
      $data=SubjectType::find($id);
      $data->name=$request->name;
      $data->school_id=Auth::user()->school_id;

      $data->update();
     Flash::overlay('Subject Type has been Updated Successfully','Success');
     return redirect('subject-type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $session = SubjectType::findOrFail($id);
      $session->delete();
      Flash::overlay('Class has been Deleted Successfully','Success');
      return redirect('class');
    }
}
