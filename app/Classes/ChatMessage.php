<?php
namespace App\Classes;
use Carbon\Carbon;
use App\Classes\ChatMedia;
class ChatMessage
{
         public function __construct() {
         $this->id='0';
         $this->senderId=0;
         $this->receiverId=0;
         $this->status='Sending';
         $this->isDelivered=true;
         $this->isRead=true;
         $this->isDeleted=false;
         $this->messageType='';
         $this->message=null;
         $this->senderName='User';
         $this->imageUrl='';
         $this->insertedOn=0;
        }
        public $id;
        public $senderId;
        public $receiverId;
        public $messageType;
        public $message; 
        public $status;
        public $insertedOn;  
        public $isDelivered;  
        public $isRead;  
        public $isDeleted;  
        public ChatMedia $media;  
        public $senderName;  
        public $imageUrl;  
}

