<?php

namespace Innovination\Chatgpt;

class Chatgpt
{
    protected $api_key;

    public $prompt, $messages, $model_name, $max_tokens, $temperature, $top_p, $frequency_penalty, $presence_penalty;

    public function __construct()
    {
        $this->api_key = env('GPT_API_KEY');
    }
    public function generateResponse()
    {
        $api_key = $this->api_key;
        $prompt = $this->prompt;
        $model_name = $this->model_name??env('GPT_MODEL_NAME');

        $max_tokens = $this->max_tokens??2500;
        $temperature = $this->temperature??0.5;
        $top_p = $this->top_p??1;
        $frequency_penalty = $this->frequency_penalty??0;
        $presence_penalty = $this->presence_penalty??0;


        if(isset($prompt))
            $messages = [
                [
                    "role" => "system",
                    "content" => $prompt
                ]
                ];
        else if(isset($this->messages))
            $messages = $this->messages;
        else
            $messages = [];

        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "model" => $model_name,
                "max_tokens" => $max_tokens,
                "temperature" => $temperature,
                "top_p" => $top_p,
                "frequency_penalty" => $frequency_penalty,
                "presence_penalty" => $presence_penalty,
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
        $api_key = $this->api_key;

        $model = 'whisper-1';
        $temp_file = '/tmp/'.\Str::random(10).'.mp3';

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

        // Delete the temporary file
        unlink($temp_file);

        return $response;
    }
}