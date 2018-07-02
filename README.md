### Table of contents

**[Installation](#installation)**  
**[Initialization](#initalization)**  
**[How to get one](#how-to-get-one)**  
**[How to get all](#how-to-get-all)**


### Installation
```php
require_once('Shoutcast.php');
```

## Initialization
```php
$shoutcast = new Shoutcast('http://shoutcast.radio.com'); // Example: http://127.0.0.1:1234
```

## How to get one
```php
$shoutcast->get('broadcaster'); // params: broadcaster, program, music, url, quality, online_time, listeners and uniques
```

## How to get all
```php
$shoutcast->all(); // json
```
