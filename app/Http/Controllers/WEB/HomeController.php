<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\OperationManagerInterface;
use App\Classes\UserDetail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use View;
use Auth;
use DataTables;
use Validator;
use App\Classes\Credential;
class HomeController extends Controller
{
     private OperationManagerInterface $provider;
     public function __construct(OperationManagerInterface $access) {
     $this->provider=$access;
   }
    public function index(Request $request){
         return view('dashboard');
    }
     public function getUser(Request $request){
          $user=new UserDetail();
          $user->id=$request->id;
          $user->id=0;
          $data= $this->provider->getUserDetail($user,true); 
          $detail = json_decode($data->getContent(), true);
          if ($request->ajax()) {
           
            return Datatables::of(json_decode( json_encode($detail['data']),true))
                    ->addIndexColumn()
                    ->addColumn('action', function($row){ 
                           $id=$row['id'];                   
                           $btn = '<button class="edit btn btn-info btn-sm" type="button" id="'.$id.'"  }}">View</button>&nbsp';
                           $btn = $btn.'<button class="edit btn btn-primary btn-sm" type="button" id="'.$id.'"}}">Edit</button>&nbsp';
                           $btn = $btn.'<button class="btn btn-danger btn-sm" type="button" id="'.$id.'"  onclick="deleteRecord(this.id,this)" }}">Delete</button>                                                                
                           ';         
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
         return response()->toJson(106);
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
}