<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;

class TodoController extends Controller
{
    public function index(){
    	//Lấy tất cả dữ liệu từ bảng data

    	$todos = Todo::all();

    	// dd($todos);
    	return view('todo.index',['todos'=>$todos]);
    }

    public function create(){
    	return view('todo.create');
    }

    public function store(Request $request){
    	
    	Todo::store($request->all());

    	return redirect('todos');
    }

    public function show($id){
    	$todo = Todo::find($id);

    	return view('todo.show',['todo'=>$todo]);
    }

    public function destroy($id){
    	Todo::find($id)->delete();

    	return redirect('todos');
    }

    public function edit($id){
    	$todo = Todo::find($id);
    	return view('todo.edit',['todo'=>$todo]);

    }

    public function update($id, Request $request){
    	Todo::updateData($id,$request->all());

    	return redirect('todos');
    }


}
?>
