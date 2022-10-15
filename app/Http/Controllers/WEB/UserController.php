<?php

namespace App\Http\Controllers\WEB;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\OperationManagerInterface;
use App\Classes\Credential;
use App\Classes\UserDetail;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{

    private OperationManagerInterface $provider;
    public function __construct(OperationManagerInterface $access) {
    $this->provider=$access;
   }
   public function index(){
    if (Auth::check()) {
            return  redirect('/dashboard');       
    }
        return view('session/login-session');
    }
    public function login(Request $request){
        $rules=array(
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:10',
        );
        $messages = array(
                'email.required' => 'Please provide valid email address',
                'email.email' => 'Please provide valid email address',
                'password.required' => 'Please provide valid password',
                'password.min' => 'Minimum password length must be 10 character',  
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return  redirect()->back()->withErrors($validator)->withInput();
        }else{
        $credential=new Credential();
        $credential->email=$request->email;
        $credential->password=$request->password;
        $data=  $this->provider->login($credential,true); 
        $detail = json_decode($data->getContent(), true);
        if($detail['status']!==0){
           return redirect()->back()->withErrors([
                'errors' => $detail['message'],
            ])->withInput();
        }
        }
        return  redirect('/dashboard');       
    }
    public function logout(Request $request){
        $credential=new Credential();
        $credential->id=$request->session()->getId();
        Log::debug($request->session()->getId());
        $data=$this->provider->logout($credential, true);
        $detail = json_decode($data->getContent(), true);
        if($detail['status']===0){           
            return  redirect('/');        
        }       
    }
     public function register(Request $request){
        return view('session/register');
    }
     public function validateRegister(Request $request){
      
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
             return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            $user=new UserDetail();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=$request->password;
            $user->gender=$request->gender;
            $user->dob=$request->dob;
        
            $data= $this->provider->register($user,true);
            $detail = json_decode($data->getContent(), true);
            if($detail['status']!==0){
            return redirect()->back()->withErrors([
                'errors' => $detail['message'],
            ])->withInput();
        }
        return  redirect('/dashboard');             
        }   
    }


    public function getUserProfile(){
        $user=new UserDetail();
        $user->id=Auth::user()->id;
        $data=$this->provider->getUserDetail($user, false);
        $detail = json_decode($data->getContent(), true);
        return view('laravel-examples/user-profile')->with(['user'=>$detail['data']]);
    }
    public function getProfile(){
         $user=new UserDetail();
        $user->id=Auth::user()->id;
        $data=$this->provider->getUserDetail($user, false);
        $detail = json_decode($data->getContent(), true);
       return view('profile')->with(['user'=>$detail['data']]);
    }

    public function userManagement(){
        $user=new UserDetail();
        $user->id=0;
        $data=$this->provider->getUserDetail($user, true);
        $detail = json_decode($data->getContent(), true);
        return view('laravel-examples/user-management')->with(['users'=>$detail['data']]);
    }
     public function getTables(){
       return view('tables');
    }
      public function getBilling(){
       return view('billing');
    }
      public function getVirtualReality(){
       return view('virtual-reality');
    }
    public function getPasswordResetView(){
        return view('session/reset-password/sendEmail');
    }
    public function forgotPassword(){
        return view('session/reset-password/sendEmail')->with(['success'=>'']);
    }
    public function resetPassword(Request $request){
        $rules=array(
            'email' => 'required|email:rfc,dns',
        );
        $messages = array(
                'email.required' => 'Please provide valid email address',
                'email.email' => 'Please provide valid email address', 
        );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return  redirect()->back()->withErrors($validator)->withInput();
        }else{
        $credential=new Credential();
        $credential->email=$request->email;
        $data=  $this->provider->forgotPassword((string)$request->email); 
        $detail = json_decode($data->getContent(), true);
        if($detail['status']!==0){
           return redirect()->back()->withErrors([
                'errors' => $detail['message'],
            ])->withInput();
        }
          return  redirect()->back()->with(['success'=>'Password is sent to your registered email, please check your email.']);    
    }
}
    
}
