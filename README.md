## Installation

```
composer require chieff/helpers
```

## Helpers

```php
use chieff\helpers\Helper;

Helper::print_r($array); // '<pre>printed array</pre>'
Helper::var_dump($mixed); // '<pre>dumped data</pre>'

echo Helper::makePhoneLink(''); // ''
echo Helper::makePhoneLink('', false); // If empty return false
echo Helper::makePhoneLink('7 (911) 911-91-91'); // +79119119191

Helper::sendFileLog('data'); // Save to $_SERVER['DOCUMENT_ROOT'] . '/log.log';
Helper::sendFileLog('data', $_SERVER['DOCUMENT_ROOT'] . '/log.log'); // Save to $_SERVER['DOCUMENT_ROOT'] . '/log.log';
Helper::sendFileLog('data', '', true); // Shows time in log
Helper::sendFileLog('data', '', true, true); // Appends file
```

## Validation

```php
use chieff\helpers\ValidatorHelper;

// make strip_tags, htmlspecialchars, trim and return false if value is empty else return sanitized string

$string = '<br>';
$string = ValidatorHelper::validate($string);
echo $string; // false

$string = '<br>qwe';
$string = ValidatorHelper::validate($string);
echo $string; // qwe

$string = 'qwerty';
$string = ValidatorHelper::validate($string, 3); // check length
echo $string; // false

$string = 'qwerty';
$string = ValidatorHelper::validate($string, 0, true); // is number
echo $string; // false

// make strip_tags, htmlspecialchars, trim if value is not empty and return sanitized string

$string = '<br>';
$string = ValidatorHelper::validateEmpty($string, 0, true); // is number
echo $string; // ''

$string = '<br>qwe';
$string = ValidatorHelper::validateEmpty($string, 0, true); // is number
echo $string; // 'qwe'

$string = 'qwerty';
$string = ValidatorHelper::validateEmpty($string, 3); // check length
echo $string; // ''

$string = 'qwerty';
$string = ValidatorHelper::validateEmpty($string, 0, true); // is number
echo $string; // ''
```

## Encoding

### Text encoding:
```php
use chieff\helpers\SecurityHelper;

$encoded = SecurityHelper::encode('Simple string', 'aes-256-ctr', 'passphrase');

echo $encoded; // 48Fme9BnBDR9DrBGRw==

$decoded = SecurityHelper::decode($encoded, 'aes-256-ctr', 'passphrase');

echo $decoded; // Simple string
```

### Image encoding:
```php
use chieff\helpers\SecurityHelper;

$img = SecurityHelper::getImageContent($_SERVER['DOCUMENT_ROOT'] . '/image.jpg', false, true);

if ($img) {

    // Get encoded data of image
    $encoded_image = SecurityHelper::encode(
        $img, 
        'aes-256-ctr', 
        'passphrase'
    );
    
    // Save encoded data to file
    if ($encoded_image)
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/image2.jpg', $encoded_image);
    
}

// Get image element
$decoded_image = SecurityHelper::getImageElement(
    $_SERVER['DOCUMENT_ROOT'] . '/image2.jpg', 
    'title', 
    'alt', 
    'lazy', 
    true, 
    'aes-256-ctr', 
    'passphrase'
);

if ($decoded_image)
    echo $decoded_image; // <img src='...' title='title' alt='alt' loading='lazy'>
```