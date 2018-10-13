<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
	//Lưu dữ liệu vào database
    public static function store($data){
    	$todo = new Todo;

    	$todo->todo=$data['todo'];

    	$todo->save();

    	return $todo;
    }

    public static function updateData($id,$data){
    	$todo = Todo::find($id);

    	$todo->todo=$data['todo'];

    	 $todo->save();
         return $todo;
    }


}
