<?php

namespace Innovination\Chatgpt;
use Illuminate\Support\Facades\Storage;

class Chatgpt
{
    public function generateResponse($obj)
    {
        $api_key = env('GPT_API_KEY');
        if(isset($obj->prompt))
            $messages = [
                [
                    "role" => "system",
                    "content" => $obj->prompt
                ]
                ];
        else if(isset($obj->messages))
            $messages = $obj->messages;
        else
            $messages = [];

        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "model" => env('GPT_MODEL_NAME'),
                "max_tokens" => 2500,
                "temperature" => 0.5,
                "top_p" => 1,
                "frequency_penalty" => 0,
                "presence_penalty" => 0,
                "messages" => $messages,
            ]),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $api_key,
            ),
        ));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);

        return $response;

    }

    public function audioToText($audio_path)
    {
        $api_key = env('GPT_API_KEY');

        $model = 'whisper-1';
        $temp_file = '/tmp/'.Str::random(10).'.mp3';

        // Step 1: Download the file from the URL
        $file_content = file_get_contents($audio_path);
        if ($file_content === FALSE) {
            die('Failed to download file from URL');
        }

        // Save the file to a temporary location
        file_put_contents($temp_file, $file_content);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/audio/transcriptions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: multipart/form-data'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $post_fields = [
            'file' => new \CURLFile($temp_file),
            'model' => $model
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

        $response = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // dd($response);
        curl_close($ch);
        $response = json_decode($response);

        return $response;
    }
}