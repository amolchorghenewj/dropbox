<?php

namespace App\Listeners;

use App\Events\DocumentUpload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;


class MoveToS3SendEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\DocumentUpload  $event
     * @return void
     */
    public function handle(DocumentUpload $event)
    {
        $user = User::find($event->userId)->toArray();

        $contents = Storage::disk('public')->get($event->localFilePath);
        $storagePath = Storage::disk('s3')->put($event->s3FilePath,$contents);
        Storage::delete($event->localFilePath);

        $Document = new Document();
        $Document->name = $event->documentName;
        $Document->size = $event->size;
        $Document->location_s3 = config("constants.S3PATH").$event->s3FilePath;
        $Document->created_by = $event->userId;
        $Document->created_at = date("Y-m-d H:i:s");
        $Document->save();

        
        $html = "File uploaded to s3 by user ". $user["name"];
        // @Mail::send(array(), array(), function ($message) use ($html) {
        //     $message->to(config("constants.SUCCESS_EMAIL"))
        //       ->subject("File uploaded to s3")
        //       ->from("admin@test.test")
        //       ->setBody($html, 'text/html');
        //   });
        
    }
}
