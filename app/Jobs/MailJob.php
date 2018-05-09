<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SendGrid;

class MailJob extends Job implements SelfHandling
{
    protected $view,$data,$email,$subject;

    /**
     * @param $data
     */
    public function __construct($data){
        $this->view = $data['view'];
        $this->data = $data['data'];
        $this->email = $data['email'];
        $this->subject = $data['subject'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        if(Config::get('mail.sendgrid')){
            $sendgrid = new SendGrid(Config::get('mail.username'),Config::get('mail.password'));
            $mail = new SendGrid\Email();

            $random = Str::random();
            $mail->addUniqueArg('token',$random);

            $sendgrid->send($mail);
        }else {
            Mail::send($this->view, $this->data ,function ($message){
                $message->to($this->email)->
                    subject($this->subject);
            });
        }
    }
}
