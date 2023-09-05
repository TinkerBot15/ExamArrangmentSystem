<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilioClient;

    public function __construct()
    {
        $accountSid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');

        // Create Twilio client
        $this->twilioClient = new Client($accountSid, $authToken);
    }

    public function sendSMS($to, $message)
    {
        $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');

        // Send SMS using Twilio client
        $message = $this->twilioClient->messages->create(
            $to,
            [
                'from' => $twilioPhoneNumber,
                'body' => $message,
            ]
        );

        return $message->sid;
    }

    public function scheduleSMS($phoneNumber, $message, $notificationTime)
    {
        // Implement the logic to schedule the SMS using the Twilio library here
        // Example code using the Twilio PHP library
        $accountSid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');
        $twilio = new Client($accountSid, $authToken);
        $message = $twilio->messages->create(
            $phoneNumber,
            [
                'from' => $twilioPhoneNumber,
                'body' => $message,
          //      'statusCallback' => $notificationTime, // Schedule the SMS at the specified time
            ]
        );
    }

    // Add more methods as needed for Twilio integration

}
