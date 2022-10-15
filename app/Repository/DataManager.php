<?php
namespace App\Repository;
use Illuminate\Support\Facades\Log;
use App\Repository\DataManagerInterface;
use App\Repository\Models\User;
use App\Repository\Models\UserDevice;
use App\Repository\Models\ChatMessage as chatTable;
use App\Repository\Models\MediaMessage;
use App\Classes\Credential;
use App\Classes\UserDetail;
use App\Classes\ChatMessage;
use App\Classes\ChatMedia;
use App\Classes\EmailDetail;
use Illuminate\Support\Str;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Http;
use App\Mail\MyMail;
use Mail;
use Carbon\Carbon;
use Config;
use Auth;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Session;
class DataManager implements DataManagerInterface
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }
   public function login(Credential $data,$isWebRequest=true)
    {    
        try{

        $result=User::where([['email',$data->email], ['password',$data->password]])->first();         
         if($result==null)
         {
              return response()->toJson(103);
          }
          $result->profileImage=(string)config('app.appurl').'/Media/'.$result->profileImage;
          Auth::loginUsingId($result->id,true);
          if($isWebRequest==true)
          {
                    Session::put('id',$result->id);
          }else
          {
            $token=$result->createToken('Token')->accessToken;
            $userDevice=new UserDevice();
            $userDevice->fcmToken=$data->fcmToken;
            $userDevice->token=$token;
            $userDevice->userId=$result->id;
            $userDevice->save();
            $result['token']= $token;
          }
            return response()->toJson(0,$result);

        } 
        catch(Exception $e){
                return response()->error();
        }  
         
    }

    public function socialLogin(UserDetail $data,$isWebRequest=true)
    {        
        try{
        $result=User::where([['email',$data->email], ['isSocialUser',true]])->get()->first();         
         if($result==null){
                $user=new User();
                $user->name=$data->name;
                $user->email=$data->email;
                $user->isSocialUser=true;
                $user->password='1234567890';
                $user->gender='M';
                $user->dob=Carbon::now()->utc()->toDateTimeString();
                $user->profileImage="user-logo.png";
                $user->insertedOn=Carbon::now()->utc()->toDateTimeString();
                $user->save();   
          }
          if($result==null){
             $result=User::where([['email',$data->email], ['isSocialUser',true]])->first();   
          }
          $result->profileImage=(string)config('app.appurl').'/Media/'.$result->profileImage;
          Auth::loginUsingId($result->id,true);
          if($isWebRequest==true){
                    Session::put('id',$result->id);
          }
          else{
            $token=$result->createToken('Token')->accessToken;
            $userDevice=new UserDevice();
            $userDevice->fcmToken=$data->fcmToken;
            $userDevice->token=$token;
            $userDevice->userId=$result->id;
            $userDevice->insertedOn=Carbon::now()->utc()->toDateTimeString();
            $userDevice->save();
            $result['token']= $token;
          }
        return response()->toJson(0, $result);
        }catch(Exception $e){
 return response()->error();
        }
         
    }
    public function logout(Credential $user,$isWebRequest=true)
    {
        try{
 if($isWebRequest==true){
                 Auth::logout();
                 Session::forget($user->id);
        }
        else{
            $this->deleteUserToken($user->id,$user->fcmToken,false,false);
        }
          return response()->toJson(0,null);
        }catch(Exception $e){
return response()->error();
        }
       
    }
 
    public function checkUserExistById($userId){
        try{
$user=User::where('id',$userId)->select('id')->get()->first();
        if($user!=null){
            return true;
        }
        return false;
        }catch(Exception $e){
            return false;
        }
        
    }
    public function checkUserExistByEmail($email){
        try{
             $user=User::where('email',(string)$email)->select('id')->get()->first();
        if($user!=null){
            return true;
        }
        return false;
        }catch(Exception $e){
            return false;
        }
       
    }

    public function register(UserDetail $data,$isWebRequest=true)
   { 
        try{

        $result=$this->checkUserExistByEmail($data->email);
        if($result===true)
        {
             return response()->toJson(102);
        }
        $user=new User();
        $user->name=$data->name;
        $user->email=$data->email;
        $user->password=$data->password;
        $user->gender=$data->gender;
        $user->dob=$data->dob;
        $user->profileImage="user-logo.png";
        $user->insertedOn=Carbon::now()->utc()->toDateTimeString();
        $user->save();   
        $result=User::where('email', $data->email)->get()->first();
        $result->profileImage=(string)config('app.appurl').'/Media/user-logo.png';
        if($isWebRequest==true)
        {
            Auth::loginUsingId($result->id,true);
            Auth::user()->profileImage=$result->profileImage;
            Session::put('id',$result->id);
        }else
        {
            $token=$result->createToken('Token')->accessToken;
            $result['token']= $token;
        } 
        return response()->toJson(0,$result);

        }catch(Exception $e){
             return response()->error();
        }
       
    }

 
 
/* #region WEB */


/* #endregion WEB*/



/* #region API */

    public function refreshToken($id,$code){
    $response = Http::asForm()->post('http://passport-app.test/oauth/token', [
    'grant_type' => 'refresh_token',
    'refresh_token' => 'the-refresh-token',
    'client_id' => $id,
    'client_secret' => 'client-secret',
    'scope' => '',
    ]);
    return $response->json();
    }
   
    public function deleteUserToken($userId,$fcmToken,$isdeleteAll=false,$isApiRequest=true){
       try{
 if($isdeleteAll==true && $isApiRequest==true){
                UserDevice::where('userId',$userId)->delete();
        }else if($isApiRequest==true){
             UserDevice::where('fcmToken',$fcmToken)->where('userId',$userId)->delete();
        }
           return response()->toJson(0,null);
        }catch(Exception $e){
            return response()->error();
        }
       
    }

    public function changePassword(Credential $data){
     try{
 $result=User::where('email', $data->email)->select('email','password')
        ->get()->first();

        if($result==null){
            return response()->toJson(101);
        }
        else if($result->password!=$data->oldPassword)
        {
            return response()->toJson(104);
        }
        User::where('email', $data->email)->update(['password'=>$data->newPassword,'updatedOn'=>Carbon::now()->utc()->toDateTimeString()]);
        return response()->toJson(105);
        }catch(Exception $e){
            return response()->error();
        }
     
       
    }

    public function forgotPassword($email){
        try{
        $result=$this->checkUserExistByEmail($email);
        if($result===true){
             $detail= User::where('email', $email)->select('id','name','email','gender','dob','insertedOn','profileImage')
            ->get()->first();
          $newPassword=Str::random(10);
          User::where('email',$email)->update(['password'=>$newPassword,'updatedOn'=>Carbon::now()->utc()->toDateTimeString()]);    
          $emailDetail=new EmailDetail();
          $emailDetail->viewName='emails/forgot';
          $emailDetail->toAddress=$email;
          $emailDetail->subject='Password reset';
          $emailDetail->viewPayload=['password'=>$newPassword,'name'=>$detail->name];
          $this->sendMail($emailDetail);
          return response()->toJson(0);
        }
        else{
            return response()->toJson( 101,null);
        }
        }catch(Exception $e){
            return response()->error();
        }
       
    }
    public function getUserDetail(UserDetail $user,$allUser=false)
    {
try{
            $result=null;
            if($allUser==true){
                $result= User::select('id','name','email','gender','dob','insertedOn','profileImage')
                //->where('id','!=',$user->id)
                ->get()->toArray();
                if($result!=null){
                   
                    for($i=0;$i<count($result);$i++){
                        $result[$i]['token']='';
                        $result[$i]['profileImage']=(string)config('app.appurl').'/Media/'.$result[$i]['profileImage'];
                    }
                }
            }
            else if($user->email!=null &&  $user->email->isNotEmpty()){
                $result= User::where('email', $data->email)->select('id','name','email','gender','dob','insertedOn','profileImage')
                ->get()->first();
                if($result!=null){
                    $result->profileImage=(string)config('app.appurl').'/Media/'.$result->profileImage;
                     $result['token']='';
                }
            }else if($user->id!=0)
            {
            $result= User::where('id', $user->id)->select('id','name','email','gender','dob','insertedOn','profileImage')
            ->get()->first();
             if($result!=null){
                    $result->profileImage=(string)config('app.appurl').'/Media/'.$result->profileImage;
                    $result['token']='';
            }
            }
            return response()->toJson($result==null ? 101:0,$result);
        }catch(Exception $e){
             return response()->error();
        }
            
    }

    public function changeProfile(UserDetail $user,$file){
    try{

        $userExist=$this->checkUserExistById($user->id);
          if($userExist===false){
            return response()->toJson(101,null);
          }
        $query=[];
        if($file!=null){    
                $query['profileImage']=$file->getClientOriginalName();
        }
        if(Str::length($user->password)>0){
             $query['password']=$user->password;
        }
          $query['name']=$user->name;
          $query['email']=$user->email;
          $query['dob']=$user->dob;
          $query['gender']=$user->gender;
          $query['updatedOn']=Carbon::now()->toDateTimeString();
          $value=User::where('id',$user->id)->update($query);
          Log::debug($value);
          if($file!=null){
                $file-> move(public_path('Media'), $file->getClientOriginalName());     
          }
          $result= User::where('id', $user->id)->select('id','name','email','gender','dob','insertedOn','profileImage')
            ->get()->first();
             if($result!=null){
                    $result->profileImage=(string)config('app.appurl').'/Media/'.$result->profileImage;
                    $result['token']='';
            }
            Log::debug($result);
            return response()->toJson(0,$result);


        }catch(Exception $e){
             return response()->error();
        }
         
    }

    public function deleteUser($id){
        try{
            $result= User::where('id',$id)->delete();
           return response()->toJson($result==null ? 101:0,$result);
        }catch(Exception $e){
             return response()->error();
        }
           
    }
    private function sendMail(EmailDetail $detail)
    {      
        dispatch(new SendEmailJob($detail));      
    }
    public function getMessageBetweenTwoPerson(ChatMessage $message){
        try{

             $result=null;


                $result=chatTable::where(function($query) use ($message) {
                    $query->where('senderId', $message->senderId)
                    ->Where('receiverId',$message->receiverId);
                    })->orwhere(function($query) use ($message) {
                        $query->where('senderId', $message->receiverId)
                        ->Where('receiverId',$message->senderId);
                    })
                  ->get();

        if($result==null){
                return response()->toJson(103,null);
        }
            $messages=Array();
                for($i=0;$i<count($result);$i++){
                    $temp=new ChatMessage();
                    $temp->id=$result[$i]->id;
                    $temp->messageType=$result[$i]->messageType;
                    $temp->message=$result[$i]->message;
                    $temp->senderId=(int)$result[$i]->senderId;
                    $temp->receiverId=(int) $result[$i]->receiverId;       
                   // $temp->insertedOn= Carbon::createFromFormat('Y-m-d H:i:s',$result[$i]->insertedOn)->timestamp;
                    $temp->insertedOn=strtotime($result[$i]->insertedOn)*1000;
                   
                    if($temp->messageType!='text'){
                      $mediaDetail=MediaMessage::where('messageId', $temp->id)->get()->first();
                    if($mediaDetail!=null){
                        $temp->media=new ChatMedia();
                        $temp->media->id=(int)$temp->id;
                        $temp->media->mimeType=$mediaDetail->mimeType;
                        $temp->media->name=$mediaDetail->name;
                        $temp->media->size=(int)$mediaDetail->size;
                        $temp->media->location=(string)config('app.appurl').'/Media/'.$mediaDetail->name;
                    }                        
                    }
                  
                    $messages[$i]=$temp; 
                }
        return response()->toJson(0,$messages);
        }catch(Exception $e){
             return response()->error();
        }
       
    }

    public function saveMessage(ChatMessage $message,$file=null){
        try{
      
       $message->insertedOn=Carbon::now()->timestamp*1000;
        $msg=new chatTable();
        $msg->senderId=$message->senderId;
        $msg->receiverId=$message->receiverId;
        $msg->message=$message->message;
        $msg->messageType= $message->messageType;
        $msg->insertedOn=Carbon::now();
        if($message->messageType!='text'){
                $file->move(public_path('Media'), $file->getClientOriginalName()); 
        }else{
            $msg->media= 'no media';
        }
        $msg->save();
        $message->id=$msg->id;
        if($message->messageType!='text'){
            $mediaObj=new MediaMessage();
            $mediaObj->mimeType=$message->media->mimeType; 
            $mediaObj->size=$message->media->size; 
            $mediaObj->name=$message->media->name; 
            $mediaObj->messageId=$message->id;
            $mediaObj->save();

            $message->media->location=(string)config('app.appurl').'/Media/'.$message->media->name;
        }
        $userName=User::select('name','profileImage')->where('id',$message->senderId)->get()->first();
        $message->senderName=$userName->name;
        $message->imageUrl=(string)config('app.appurl').'/Media/'.$userName->profileImage;
        $this->sendPushMessage($message);

        return response()->toJson(0,$message);
        }catch(Exception $e){
             return response()->error();
        }
       
    }


    private function sendPushMessage($message){
        try{
$registrationIds = UserDevice::select('fcmToken')->where('userId',$message->receiverId)->get()->pluck('fcmToken')->toArray();
       if($registrationIds!=null || count($registrationIds)>0){
        $key=(string)"key=".config('app.pushnotification');
        $response = Http::withHeaders([
                    'Authorization' =>$key,
                    'content-Type' => 'application/json',
                    ])->post('https://fcm.googleapis.com/fcm/send', [
                     'registration_ids'=> $registrationIds,
                      'sound'=> "default",
                      'content_available'=> true,
                      'priority' => 'high',
                      'data'=> [
                            'title'=> 'SenderName',
                            'notificationType'=> $message->messageType,
                            'notificationPayload'=> json_encode($message)
                      ]
        ]);
       }else{
            Log::info('no fcmToken found of user=>'.$message->receiverId);
       }
       return response()->toJson(0,null);
        }catch(Exception $e){
             return response()->error();
        }

       
    }

/* #endregion */













   
}