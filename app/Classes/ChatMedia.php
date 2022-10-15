<?php
namespace App\Classes;
use Carbon\Carbon;
class ChatMedia
{
         public function __construct() {
         $this->id=0;
         $this->name='';
         $this->location='';
         $this->mimeType='';
         $this->size=0.0;
         $this->isDownloading=false;
         $this->isDownloaded=false;
        }      
  public $id;
  public $name;
  public $location;
  public $mimeType;
  public $size;
  public $isDownloading;
  public $isDownloaded; 
}

