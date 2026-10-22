<?php

namespace App\Http\Controllers; //default namespace for controllers

use Illuminate\Http\Request;
//import 
class ProductController extends Controller
{
    //global variable
    public $school = "Mindoro State University"; //class variable
    
    public function index()
    {
        //local variable
        $student = "Fate";
        $message = "Welcome to Laravel, $student!"; //concatenation of string and variable

        return response()->json([
            'student' => $student,  
            'message' => $message,
        ]);
    }

    public function read(){
        $message = "Welcome to Laravel, $this->school!";
         
        return response()->json([
            'school' => $this->school,  
            'message' => $message
        ]);

    }

    //function parameter
    public function greet($name){
        $message = "Welcome to Laravel, $name!";

        return response()->json([
            'name' => $name,  
            'message' => $message
        ]);
    
    }
}
