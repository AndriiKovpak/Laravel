<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Util;

class Emailer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emailer:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans the database for an emails that need to be sent and sends them accordingly.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // TODO: Move # of emails processed to config
            $queue = \App\Models\EmailMessage::queued()->take(10)->get();
            // Set to processing
            foreach ($queue as $message) {
                $message['Status'] = 5;
                $message->save();
            }

            foreach ($queue as $message) {
                try {
                    $data = (array)json_decode($message['Data']);
                    Mail::send($message->EmailTemplate['ViewName'], $data, function ($headers) use ($message, $data) {
                        $headers->to($message['ToEmailAddress'])
                            ->subject($message['Subject']);
                        if(isset($message['FromEmailAddress']) && strlen(trim($message['FromEmailAddress']))>0)
                            $headers->from($message['FromEmailAddress']);
                        if(isset($data) && is_array($data) && isset($data['attachments']) && is_array($data['attachments'])){
                            foreach($data['attachments'] as $attachment){
                                $headers->attach($attachment);
                            }
                        }
                    });

                    // check for failures
                    if (Mail::failures()) {
                        $message['Status'] = 6;
                        Util::log("Unable to send email #{$message['EmailMessageQueueID']} to {$message['ToEmailAddress']}.", false);
                    } else
                        $message['IsSent'] = 1;

                    $message->save();
                } catch (\Exception $e) {
                    Util::log("Error while attempting to send email #{$message['EmailMessageQueueID']}.\nError: {$e->getMessage()}");
                }
            }
        } catch (\Exception $e) {
            Util::log("Error while attempting to send emails.\nError: {$e->getMessage()}");
        }

    }
}
