<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Bex\Monolog;

use Bitrix\Main\Config\Configuration;
use Cascade\Cascade;

/**
 * Adapter Monolog to Bitrix CMS.
 *
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class MonologAdapter
{
    protected static $isConfigurationLoaded = false;

    /**
     * Load a configuration for the loggers from `.settings.php` or `.settings_extra.php`.
     *
     * @param bool $force Load even if the configuration has already been loaded.
     *
     * @return bool
     */
    public static function loadConfiguration($force = false)
    {
        if ($force === false && static::$isConfigurationLoaded === true)
        {
            return true;
        }

        if (class_exists('\Bitrix\Main\Config\Configuration'))
        {
            $config = Configuration::getInstance()->get('monolog');

            if (is_array($config) && !empty($config))
            {
                Cascade::fileConfig($config);

                return true;
            }
        }

        return false;
    }
}
