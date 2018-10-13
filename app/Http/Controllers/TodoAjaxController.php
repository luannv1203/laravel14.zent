<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Todo;

class TodoAjaxController extends Controller
{
    public function index(){
    	$todos = Todo::all();

    	// dd($todos);
    	return view('todo.index',['todos'=>$todos]);
    }

    public function store(Request $request){
    	$todo = Todo::store($request->all());
    	return $todo;
    }

    public function show($id){
    	return Todo::find($id);
    }

    public function update(Request $request,$id){
    	$todo= Todo::updateData($id,$request->all());

    	return $todo;
    }
    
    public function destroy($id){
    	Todo::find($id)->delete();

    	return response()->json([
    		'message'=>'Xoa thanh cong!!!'
    	]);
    }
}
