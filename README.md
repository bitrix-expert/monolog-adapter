# Monolog for Bitrix CMS

[![Build Status](https://travis-ci.org/bitrix-expert/monolog-adapter.svg)](https://travis-ci.org/bitrix-expert/monolog-adapter)
[![Latest Stable Version](https://poser.pugx.org/bitrix-expert/monolog-adapter/v/stable)](https://packagist.org/packages/bitrix-expert/monolog-adapter) 
[![Total Downloads](https://poser.pugx.org/bitrix-expert/monolog-adapter/downloads)](https://packagist.org/packages/bitrix-expert/monolog-adapter) 
[![License](https://poser.pugx.org/bitrix-expert/monolog-adapter/license)](https://packagist.org/packages/bitrix-expert/monolog-adapter)

Monolog handler and formatter for Bitrix CMS.

## Install

```bash
cd path/to/project/root
composer require bitrix-expert/monolog-adapter
```

## Example

```php
<?php

use Monolog\Logger;
use Bex\Monolog\Handler\BitrixHandler;

// Create a log channel
$logger = new Logger('name');

// Adding handler for write logs to Bitrix Event Log
$logger->pushHandler(new BitrixHandler('TYPE_FOR_EVENT_LOG', 'vendor.module');

$logger->error('message', [
    'item_id' => 21
]);
```

## Requirements

* PHP >= 5.3
* Bitrix CMS >= 15.0.2