<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VariableScopeController extends Controller
{
    public $message = "Hello, Laravel!";
    
    public function index($name)
    {
        $age=25; //local variable
        return view('variable_scope', compact('name', 'age'));
    }
}