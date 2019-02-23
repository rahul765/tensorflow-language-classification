<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\SessionMaster;
use Session;
use Flash;
class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessions=SessionMaster::paginate(10);
        return view('session.index',compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('session.create');
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
           'name' => 'required|unique:session_master|max:32',
           'start_date'=>'required',
           'end_date'=>'required'
       ]);
      $input = $request->all();
      SessionMaster::create($input);
      Flash::overlay('Session has been Created Successfully','Success');
      return redirect('session');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $session = SessionMaster::findOrFail($id);

      return view('session.view')->withSession($session);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $session = SessionMaster::findOrFail($id);

      return view('session.edit')->withSession($session);

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
      $SessionUpdate=$request->all();
      $session=SessionMaster::find($id);
      $session->update($SessionUpdate);

    //  Session::flash('flash_message', 'Session is Updated Successfully!');
      Flash::overlay('Session has been Updated Successfully','Success');
      return redirect('session');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $session = SessionMaster::findOrFail($id);
       $session->delete();
       Flash::overlay('Session has been Deleted Successfully','Success');
       return redirect('session');
    }
}
