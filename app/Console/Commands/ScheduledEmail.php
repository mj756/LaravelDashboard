<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Jobs\SendEmailJob;
use App\Classes\EmailDetail;
use App\Repository\Models\User;
class ScheduledEmail extends Command
{
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
    public function handle()
    {
          $result=User::select('name','email')->get();  
          foreach ($result as $user) {
          $emailDetail=new EmailDetail();
          $emailDetail->viewName='emails/forgot';
          $emailDetail->toAddress=$user->email;
          $emailDetail->subject='Password reset';
          $emailDetail->viewPayload=['password'=>'000000','name'=>$user->name];  
          dispatch(new SendEmailJob($emailDetail));
          }           
    }
}
