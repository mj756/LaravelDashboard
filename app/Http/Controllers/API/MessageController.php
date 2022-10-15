<?php

namespace App\Http\Controllers\API;
use App\Classes\ChatMedia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\OperationManagerInterface;
use App\Classes\ChatMessage;
use Illuminate\Support\Facades\Log;
use Validator;

class MessageController extends Controller
{
    private OperationManagerInterface $provider;
     public function __construct(OperationManagerInterface $access) {
     $this->provider=$access;
   }
     public function sendPushNotification(Request $request){
      $rules=array(
            'senderId' => 'required|numeric',
            'receiverId' => 'required|numeric',
            'messageType'=>'required|String|max:10',
            'message'=>'required|String',
           // 'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        );
        $messages = array(
                'senderId.required' => 'Please provide sender id',
                'senderId.numeric' => 'Please provide numeric sender id',
                'receiverId.required' => 'Please provide receiver id',
                'receiverId.numeric' => 'Please provide numeric receiver id',
                'messageType.required' => 'Please provide valid message type',
                'messageType.String' => 'Please provide valid message type in string format',
                'messageType.max' => 'Please provide valid message type withing 10 character',
                'message.required' => 'Please provide message',
                'message.String' => 'Please provide message in string format',
                'image.image'=>'Please provide valid image file',
                'image.mimes'=>'Please provide valid mime file',
                'image.max'=>'Please provide valid image file with size less than 2mb',
            );
         $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        }
        else{
              $msg=new ChatMessage();
              $msg->senderId=(int)$request->senderId;
              $msg->receiverId=(int)$request->receiverId;
              $msg->messageType=$request->messageType;
              $msg->message=$request->message;

              if($msg->messageType!='text'){
                $msg->media=new ChatMedia();
                $msg->media->name=$request->file('image')->getClientOriginalName();
                $msg->media->size=(int) number_format($request->file('image')->getSize()/1024,2);
                $msg->media->mimeType=$request->file('image')->getMimeType();
                 $msg->media->location=($request->secure() ? 'https://':'http://').$request->getHttpHost();   //this is pregix url to get current host name
              }
              $result= $this->provider->saveMessage($msg, $msg->messageType!='text' ? $request->file('image'):null);
              return $result;

        } 
    }
    public function getMessages(Request $request){
        $rules=array(
            'senderId' => 'required|numeric',
            'receiverId' => 'required|numeric', 
        );
        $messages = array(
                'senderId.required' => 'Please provide sender id',
                'senderId.numeric' => 'Please provide numeric sender id',
                'receiverId.required' => 'Please provide receiver id',
                'receiverId.numeric' => 'Please provide numeric receiver id'        
            );
        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->stopOnFirstFailure()->fails()){
             return response()->validationError(106,$validator->errors()->first());
        }
        else{
              $msg=new ChatMessage();
              $msg->senderId=(int)$request->senderId;
              $msg->receiverId=(int)$request->receiverId;
               $msg->media=new ChatMedia();
              $msg->media->location=($request->secure() ? 'https://':'http://').$request->getHttpHost();
              return $this->provider->getMessageBetweenTwoPerson($msg);
        } 

    }
}
