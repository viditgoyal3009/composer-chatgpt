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

2. Add GPT keys to your `.env` file

```php
GPT_API_KEY='your-api-key'
GPT_MODEL_NAME='your-preferred model (ex. gpt-3.5-turbo-0125)'
```
3. Create an instance of the `Chatgpt` class:

```php
$obj = new Chatgpt();
$audio_path = 'link to audio';
$transcription = $obj->audioToText($audio_path);
```

4. Convert audio to text: