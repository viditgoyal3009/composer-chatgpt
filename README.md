# Chatgpt package

This Composer package allows you to chat with OpenAPI's Chatgpt API

## Installation

To install this package, you can use Composer. Run the following command in your project directory:

```
composer require innovination/chatgpt
```

## Usage

To use this package, follow these steps:

1. Import the package into your PHP file:

```php
use Innovination\Chatgpt;
```

2. Create an instance of the `Chatgpt` class:

```php
$chatgpt = new Chatgpt();
```
3. Add GPT_API_KEY key to your `.env` file

```php
GPT_API_KEY='your-api-key'
```

4. Convert text to audio:

```php
//Required
$elevenlabs->voice_id = 'voice-id-on-elevenlabs';
$elevenlabs->text = "Hello, world!";
//Optional
$elevenlabs->file_prefix = "prefix"; // default is audio
$elevenlabs->path = "folder-name"; //default folder is audio
$audioFile = $elevenlabs->generateAudio();
```

The `generateAudio` method will return the path to the generated audio file along with `status` as `sucess` or `error`
