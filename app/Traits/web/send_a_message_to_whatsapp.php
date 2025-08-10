<?php
namespace App\Traits\web;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
trait send_a_message_to_whatsapp
{
    function send_a_message_to_whatsapp_template($template, $phone)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.facebook.com/v15.0/212399081962685/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "messaging_product": "whatsapp",
            "to": '. $phone.',
            "type": "template",
            "template": {
                "name": "'.$template.'",
                "language": {
                    "code": "ar"
                }
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer EAAFHcPgL7LIBOx2xYGQMqCkUVWsEszZAbad6trp5xYWacPKTl3GVCKwlkrCwDHKttkMsW974fg5zSPYFLE8tAEnd1VsBg2PTNeaSXzLWctA1DljTNx9FBljTdvd62BeFBhiweszNdg3wdD2MWLYbSWGiNZB9NZBo6q5tKdVTu5GEs5VqJ4H7EBgqX4rW4FJ9dhqfbMn1Pvfxsn5tj0ZD',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

}

?>