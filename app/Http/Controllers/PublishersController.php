<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Http\Requests;
use Auth;

class PublishersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'publisher_name'=> 'required|min:5'
            ],
            [
                'publisher_name.required'=>'Publisher name is required. Publisher not saved.',
                'publisher_name.min'=>'Publisher name must be minimum of 5 characters'
            ]
        );
        if($request->ajax())
        {
            return response()->json($this->_saveNewPublisher($request));
        }
    }

    private function _saveNewPublisher(Request $request)
    {
        $publisher = new Publisher();
        $publisher->name = $request->publisher_name;
        $publisher->description = $request->description;
        $publisher->user_id_creator = Auth::id();
        $isSuccess = $publisher->save();
        if($isSuccess && $publisher->id)
        {
            $copy = new Publisher();
            $copy->name = $publisher->name;
            $copy->description = $publisher->description;
            $copy->user_id_creator = $publisher->user_id_creator;
            $copy->record_id = $publisher->id;
            $copy->save();
            return ['error'=>FALSE, 'message'=>'New publisher successfully created', 'data'=>array('publisher'=>['name'=>$publisher->name, 'id'=>$publisher->id] ) ];

        }
        return ['error'=>TRUE, 'message'=>'Unknown error encountered', 'data'=>[]];
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
}
