<?php
/**
 * @link https://github.com/bitrix-expert/monolog-adapter
 * @copyright Nik Samokhvalov
 * @license MIT
 */

use Bitrix\Main\Config\Configuration;
use Cascade\Cascade;

if (class_exists('\Bitrix\Main\Config\Configuration'))
{
    $config = Configuration::getInstance()->get('monolog');
    
    if (is_array($config) && !empty($config))
    {
        Cascade::fileConfig($config);
    }
}