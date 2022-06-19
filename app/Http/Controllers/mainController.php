<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use App\Models\login;
use Session;

class mainController extends Controller{

    public function index(){

        if(Auth::check()){
            return redirect('/dashboard');
        }

        return view('index');
    }

    public function doLogin(Request $request){
        if($request->ismethod('post')){
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);
            $credentials = $request->only('email', 'password');

            if(Auth::attempt($credentials)){
                return redirect('/dashboard');
            }
            else{
                return redirect('/')->with('failed', 'Email/password is incorrect');
            }

        }
        else{
            return redirect('/')->with('failed', 'Dont Play With URL');
        }
    }

    public function dashboard(){
        if(!Auth::check()){
            return redirect('/');
        }
        return view('dashboard');
    }

    public function register(){
        return view('register');
    }

    public function doRegisteration(Request $request){
        if($request->isMethod('POST')){
            $request->validate([
                'name'=> 'required',
                'email'=> 'required',
                'password'=> 'required'
            ]);
            $userData = new login;
            $userData->name = $request->name;
            $userData->email = $request->email;
            $userData->password = Hash::make($request->password);
            $resp = $userData->save();
            
            if($resp){
                return redirect('/')->with('success', 'Account Created Successfully');
            }
            else{
                return redirect('/')->with('failed', 'Something Went Wrong');
            }
        }
        return redirect('/')->with('failed', 'Dont Play With URL');
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect('/');
    }

}
