<?php
/**
 * @link https://github.com/bitrix-expert/monolog-adapter
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog;

use Cascade\Cascade;

class Loggers
{
    public static function __callStatic($name, $arguments)
    {
        return static::get($name);
    }
    
    public static function add($name, array $handlers = array(), array $processors = array())
    {
        return Cascade::createLogger($name, $handlers, $processors);
    }
    
    public static function get($name)
    {
        return Cascade::getLogger($name);
    }
    
    public static function setConfigs($resource)
    {
        Cascade::fileConfig($resource);
    }
}