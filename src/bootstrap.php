<?php
/**
 * @link https://github.com/bitrix-expert/monolog-adapter
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

use Bitrix\Main\Config\Configuration;
use Bex\Monolog\Loggers;

if (class_exists('\Bitrix\Main\Config\Configuration'))
{
    $config = Configuration::getInstance()->get('monolog');
    
    if (is_array($config) && !empty($config))
    {
        Loggers::setConfigs($config);
    }
}