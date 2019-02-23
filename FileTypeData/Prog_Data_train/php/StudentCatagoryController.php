<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\StudentCatagory;
use Auth;
use Flash;

class StudentCatagoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catagory=StudentCatagory::paginate(10);
        return view('student-catagory.index',compact('catagory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          return view('student-catagory.create');
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
          'name' => 'required|unique:student_catagory|max:60',

      ]);
      $data= new StudentCatagory();
      $data->name=$request->name;
      $data->school_id=Auth::user()->school_id;
      $data->created_by=Auth::user()->id;
      $data->created_at=date("Y-m-d");
      $data->save();
     Flash::overlay('New Student Catagory has been Added Successfully','Success');
     return redirect('student-catagory');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $show = StudentCatagory::findOrFail($id);

      return view('student-catagory.show')->withShow($show);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $edit = StudentCatagory::findOrFail($id);

      return view('student-catagory.edit')->withEdit($edit);
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
          'name' => 'required|unique:student_catagory|max:60',

      ]);
      $data=StudentCatagory::find($id);
      $data->name=$request->name;
      $data->school_id=Auth::user()->school_id;
      $data->updated_by=Auth::user()->id;
      $data->updated_at=date("Y-m-d");
      $data->update();
     Flash::overlay('Student Catagory has been Updated Successfully','Success');
     return redirect('student-catagory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $session = StudentCatagory::findOrFail($id);
      $session->delete();
      Flash::overlay('Student Catagory has been Deleted Successfully','Success');
      return redirect('student-catagory');
    }
}
