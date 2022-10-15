<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Repository\OperationManagerInterface;
class ScheduledEmail extends Command
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:license_expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification email to all users whose license is expiring soon.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(OperationManagerInterface $manager)
    {
          $manager->sendScheduledEmail();
         /* $result=User::select('name','email')->get();  
          foreach ($result as $user) {
          $emailDetail=new EmailDetail();
          $emailDetail->viewName='emails/forgot';
          $emailDetail->toAddress=$user->email;
          $emailDetail->subject='Password reset';
          $emailDetail->viewPayload=['password'=>'000000','name'=>$user->name];  
          dispatch(new SendEmailJob($emailDetail));
          }      */     
    }
}
