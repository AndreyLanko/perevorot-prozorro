<?php namespace App\Http\Controllers;

class HomeController extends Controller
{
	public function index()
	{
		return view('home');
	}

	public function index2()
	{
		return view('home_v2');
	}
}
