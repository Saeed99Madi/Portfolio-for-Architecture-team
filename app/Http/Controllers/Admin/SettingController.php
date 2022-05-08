<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use Validator;
use DB;

class SettingController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
        $userid = $user->id;
        $obj = Setting::first();
        return view('cp.setting.index', compact('obj'));
    }
	public function indexpost(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'title' => 'required|max:200'
        ]);
		if ($validator->passes()) {
            $user = auth()->user();
            $userid = $user->id;
            $count = Setting::count();

            $img_logo = '';
            if($file = $request->hasFile('logo')) {
                $file = $request->file('logo');
                $img_logo = time().'.'.$request->logo->getClientOriginalExtension();//$file->getClientOriginalName() ;
                $destinationPath = public_path().'/upload' ;
                $file->move($destinationPath,$img_logo);
            }

            $img_favicon = '';
            if($file = $request->hasFile('favicon')) {
                $file = $request->file('favicon');
                $img_favicon = time().'.'.$request->favicon->getClientOriginalExtension();//$file->getClientOriginalName() ;
                $destinationPath = public_path().'/upload' ;
                $file->move($destinationPath,$img_favicon);
            }

            if($count == 0){
			    Setting::create([
				    'facebook' =>  $request['facebook'],
				    'twitter' => $request['twitter'],
				    'instagram' => $request['instagram'],
                    'linkedin' => $request['linkedin'],
                    'footer1' => $request['footer1'],
                    'footer2' => $request['footer2'],
                    'footer3' => $request['footer3'],
                    'footer4' => $request['footer4'],
                    'title' => $request['title'],
			    ]);
            }
            else{
                Setting::first()->update([
				    'facebook' =>  $request['facebook'],
				    'twitter' => $request['twitter'],
				    'instagram' => $request['instagram'],
                    'linkedin' => $request['linkedin'],
                    'footer1' => $request['footer1'],
                    'footer2' => $request['footer2'],
                    'footer3' => $request['footer3'],
                    'footer4' => $request['footer4'],
                    'title' => $request['title'],
			    ]);
            }

            if($img_logo){
                Setting::first()->update([
                'logo' => $img_logo
                ]);
            }
            if($img_favicon){
                Setting::first()->update([
                'favicon' => $img_favicon
                ]);
            }
			return response()->json([
			'status'=> 1, 
			'msg' => 's: ' . __('msg.done'), 
			'toastr' => true,
			'reset' => true
			]);
        }
    	return response()->json([
        'error'=> $validator->errors()->messages(),
        'ErrorController' => true
        ]);
    }
    public function getimg($type)
    {
        $obj = Setting::first();
        if($obj){
            if($type == 1){
                return response()->file("upload/".$obj->logo);
            }
            else{
                return response()->file("upload/".$obj->favicon);
            }
        }
        else{
            return null;
        }
    }
}
