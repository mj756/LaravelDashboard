<?php
namespace App\Repository;
use App\Classes\Credential;
use App\Classes\UserDetail;
use App\Classes\ChatMessage;
interface DataManagerInterface
{
    public function login(Credential $data,$isWebRequest=true);
    public function socialLogin(UserDetail $data,$isWebRequest=true);
    public function logout(Credential $user,$isWebRequest=true);
    public function register(UserDetail $data,$isWebRequest=true);
    public function changePassword(Credential $data);
    public function forgotPassword($email);
    public function getUserDetail(UserDetail $user,$allUser=false);
    public function changeProfile(UserDetail $user,$file);
    public function deleteUser($userId);
    public function saveMessage(ChatMessage $message,$file=null);
    public function getMessageBetweenTwoPerson(ChatMessage $message);
    public function sendScheduledEmail();
}