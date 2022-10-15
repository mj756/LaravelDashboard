<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\OperationManagerInterface;
use App\Classes\UserDetail;
use App\Classes\Credential;
use Illuminate\Support\Facades\Log;

use Validator;
use Auth;
class UserController extends Controller
{
     private OperationManagerInterface $provider;
     public function __construct(OperationManagerInterface $access) {
     $this->provider=$access;
   }

    public function login(Request $request)
    {
        $startTime=microtime(as_float:true);
        $rules=array(
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:10',
            'fcmToken'=>'required|string',
        );
        $messages = array(
                'email.required' => 'Please provide valid email address',
                'email.email' => 'Please provide valid email address',
                'password.required' => 'Please provide valid password',
                'password.min' => 'Minimum password length must be 10 character',  
                'fcmToken.required' => 'Please provide FCM token',  
                'fcmToken.string' => 'Please provide valid FCM token', 
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        }else
        {
        $credential=new Credential();
        $credential->email=$request->email;
        $credential->password=$request->password;
        $credential->fcmToken=$request->fcmToken;
        $seconds=number_format(num:(microtime(as_float:true)-$startTime),decimals:2);
        return $this->provider->login($credential,false); 
        /*$detail = json_decode($data->getContent(), true);
        if($detail['status']!==0){
           return redirect()->back()->withErrors([
                'errors' => $detail['message'],
            ])->withInput();
        }
        Auth::loginUsingId($detail['data']['id'],true);*/
        
    }

    }
    public function logout(Request $request){
       
        $rules=array(
            'id' => 'required|numeric',
            'fcmToken' => 'required',
        );
        $messages = array(
                'id.required' => 'Please provide user id',
                'id.numeric'=>'Please provide valid numeric user id',
                'fcmToken.required' => 'Please provide token',
            );
         $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        }else
        {
            $user=new Credential();
            $user->id=$request->id;
            $user->fcmToken=$request->fcmToken;
            return $this->provider->logout($user,false);
        }
    }

    public function register(Request $request){
       $rules=array(
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:10',
            'name'=>'required',
            'gender'=>'required',
            'dob'=>'required|date'
        );
        $messages = array(
                'email.required' => 'Please provide valid email address',
                'email.email' => 'Please provide valid email address',
                'password.required' => 'Please provide valid password',
                'password.min' => 'Minimum password length must be 10 character',
                'name.required' => 'Please provide valid user name',
                'gender.required' => 'Please provider gender',
                'dob.required' => 'Please provide valid date of birth',
                'dob.date' => 'Please provide valid date of birth',
            );
         $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        }
        else{
        $user=new UserDetail();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=$request->password;
        $user->gender=$request->gender;
        $user->profileImage=($request->secure() ? 'https://':'http://').$request->getHttpHost();
        $user->dob=date('y-m-d',strtotime($request->dob));
        return $this->provider->register($user,false); 
        }
        
    }

    public function changePassword(Request $request){
          $rules=array(
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:10',
            'oldPassword'=>'required|min:10',
        );
        $messages = array(
                'email.required' => 'Please provide valid email address',
                'email.email' => 'Please provide valid email address',
                'password.required' => 'Please provide valid password',
                'password.min' => 'Minimum password length must be 10 character',  
                'oldPassword.required' => 'Password mismatched',
                'oldPassword.min' => 'Password mismatched',  
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        }
        else{
        $credential=new Credential();
        $credential->email=$request->email;
        $credential->newPassword=$request->newPassword;
        $credential->oldPpassword=$request->oldPassword;
        return $this->provider->changePassword($credential); 
        }
        }
       
      public function forgotPassword(Request $request){
       $rules=array(
            'email' => 'required|email:rfc,dns',
        );
        $messages = array(
                'email.required' => 'Please provide valid email address',
                'email.email' => 'Please provide valid email address',
            );
         $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             Log::debug($request->email);
             return response()->validationError(106,$validator->errors()->first());
        } else{
                return $this->provider->forgotPassword($request->email); }
    }

    public function deleteUser(Request $request){
     
        $rules=array(
            'id' => 'required|numeric',
        );
        $messages = array(
                'id.required' => 'Please provide valid user id',
                'id.numeric' => 'Please provide valid user id',
            );
        $validator=Validator::make($request->query(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        } else{
             return $this->provider->deleteUser($request->id); 
    }
         return response()->toJson(106);
    }
   
    public function index(Request $request){
        
        $rules=array(
            'id' => 'required|numeric',
        );
        $messages = array(
                'id.required' => 'Please provide valid user id',
                'id.numeric' => 'Please provide valid user id',
            );
         $validator=Validator::make($request->query(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        } else{
            $user=new UserDetail();
            $user->id=$request->id;
             return $this->provider->getUserDetail($user); 
    }
         return response()->toJson(106);
    }

     public function changeProfile(Request $request)
     {
 
         $rules=array(
            'id' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',           
            'email' => 'required|email:rfc,dns',
            'password' => 'nullable|min:10',
            'name'=>'required|string',
            'gender'=>'required|string',
            'dob'=>'required|date'         
        );
        $messages = array(
                'id.required' => 'Please provide valid user id',
                'id.numeric' => 'Please provide valid user id',
               // 'image.required'=>'Please provide valid profile image file',
                'image.image'=>'Please provide valid image file',
                'image.mimes'=>'Please provide valid image file',
                'image.max'=>'Please provide valid image file with size less than 1mb',

                'email.required' => 'Please provide valid email address',
                'email.email' => 'Please provide valid email address',
                'password.min' => 'Minimum password length must be 10 character',
                'name.required' => 'Please provide valid user name',
                'gender.required' => 'Please provider gender',
                'dob.required' => 'Please provide valid date of birth',
                'dob.date' => 'Please provide valid date of birth',
            );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        } else{        
        $user=new UserDetail();
        $user->id=$request->id;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=$request->password==null ? '':$request->password;
        $user->dob=$request->dob;
        $user->gender=$request->gender;
        $file=$request->file('image')==null ? null:$request->file('image');
        return $this->provider->changeProfile($user,$file);
        
     }
    }
    public function getAllUser(Request $request){
        $rules=array(
            'id' => 'required|numeric',
        );
        $messages = array(
                'id.required' => 'Please provide valid user id',
                'id.numeric' => 'Please provide valid user id',
            );
         $validator=Validator::make($request->query(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        }else{
            $id=$request->query('id');
            $excludeUser=new UserDetail();
            $excludeUser->id=(int)$id;
            $excludeUser->profileImage=($request->secure() ? 'https://':'http://').$request->getHttpHost();
            return $this->provider->getUserDetail($excludeUser,true); 
        }
    }

    public function socialLogin(Request $request){
        $rules=array(
            'email' => 'required|email:rfc,dns',
            'name' => 'required|string',
            'fcmToken'=>'required|string',
        );
        $messages = array(
                'email.required' => 'Please provide valid email address',
                'email.email' => 'Please provide valid email address', 
                'fcmToken.required' => 'Please provide FCM token',  
                'fcmToken.string' => 'Please provide valid FCM token', 
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        }else
        {
        $user=new UserDetail();
        $user->email=$request->email;
        $user->name=$request->name;
        $user->fcmToken=$request->fcmToken;
        return $this->provider->socialLogin($user,false); 
        }
    }
     
}
