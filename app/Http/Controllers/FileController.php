<?php

namespace Confform\Http\Controllers;

use Confform\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
use DB;

class FileController extends Controller
{
	public function getCaptchaForm(){
        return view('files.captchaform');
	}
     
	public function postCaptchaForm(Request $request){
	     $this->validate($request, [
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required|numeric|digits:10',
            'details'=>'required',
            'g-recaptcha-response'=>'required|captcha',
		]);
	 }
}
?>