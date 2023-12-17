Text encoding:
```php
use chieff\helpers\SecurityHelper;

$encoded = SecurityHelper::encode('Simple string', 'aes-256-ctr', 'qwe');

echo $encoded; // 48Fme9BnBDR9DrBGRw==

$decoded = SecurityHelper::decode($encoded, 'aes-256-ctr', 'qwe');

echo $decoded; // Simple string
```

Image encoding:
```php
use chieff\helpers\SecurityHelper;

// Get encoded data of image
$encoded_image = SecurityHelper::encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/test.jpg'), 'aes-256-ctr', 'qwe');

// Save encoded data to file
file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/test2.jpg', $encoded_image);

// Get image element
$decoded_image = SecurityHelper::getImageElement($_SERVER['DOCUMENT_ROOT'] . '/test2.jpg', 'title', 'alt', 'lazy', true, 'aes-256-ctr', 'qwe');
echo $decoded_image; // <img src='...' title='title' alt='alt' loading='lazy'>
```