<?php
namespace App\Repository;
use Illuminate\Support\Facades\Log;
use App\Repository\DataManagerInterface;
use App\Classes\Credential;
use App\Classes\UserDetail;
 use App\Classes\ChatMessage;
class OperationManager implements OperationManagerInterface
{
     private DataManagerInterface $repo;

     public function __construct(DataManagerInterface $obj) {
     $this->repo=$obj;
    }

    function login(Credential $data,$isWebRequest=true)
    {
           return $this->repo->login($data,$isWebRequest);   
    }
    public function socialLogin(UserDetail $data,$isWebRequest=true){
              return $this->repo->socialLogin($data,$isWebRequest);   
     }
    public function logout(Credential $user,$isWebRequest=true)
    {
           return $this->repo->logout($user,$isWebRequest);   
    }
    function register(UserDetail $data,$isWebRequest=true)
    {
           return $this->repo->register($data,$isWebRequest);   
    }
     function changePassword(Credential $data)
    {
           return $this->repo->changePassword($data);   
    }
      public function forgotPassword($email){
       return $this->repo->forgotPassword($email);   
      }

       public function getUserDetail(UserDetail $user,$allUser=false){
              return $this->repo->getUserDetail($user,$allUser);   
       }
        public function changeProfile(UserDetail $user,$file){
               return $this->repo->changeProfile($user,$file); 
        }
       public function deleteUser($userId){
               return $this->repo->deleteUser($userId);   
       }
       public function saveMessage(ChatMessage $message,$file=null){
               return $this->repo->saveMessage($message,$file);   
       }
       public function getMessageBetweenTwoPerson(ChatMessage $message){
               return $this->repo->getMessageBetweenTwoPerson($message);   
       }
       public function sendScheduledEmail(){
              return $this->repo->sendScheduledEmail();   
       }
}