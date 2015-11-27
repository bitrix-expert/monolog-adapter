<?php
/**
 * @link https://github.com/bitrix-expert/niceaccess
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog\Formatter;

use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;

/**
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class BitrixFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $record['item_id'] = null;
        $record['level'] = static::toBitrixLevel($record['level']);

        if (!empty($record['context']))
        {
            foreach ($record['context'] as $field => $value)
            {
                if ($field === 'item_id')
                {
                    $record['item_id'] = $value;
                }
                else
                {
                    if (is_array($value))
                    {
                        $value = var_export($value, true);
                    }

                    $record['message'] .= '<br><br>' . $field . ': ' . $value;
                }
            }
        }

        return $record;
    }

    /**
     * {@inheritdoc}
     */
    public function formatBatch(array $records)
    {
        $formatted = array();

        foreach ($records as $record)
        {
            $formatted[] = $this->format($record);
        }

        return $formatted;
    }

    /**
     * Converts Monolog levels to Bitrix ones if necessary.
     *
     * @param int $level Level number.
     *
     * @return string|bool
     */
    public static function toBitrixLevel($level)
    {
        $levels = static::logLevels();

        if (isset($levels[$level]))
        {
            return $levels[$level];
        }

        return false;
    }

    /**
     * Translates Monolog log levels to Bitrix levels.
     *
     * @return array
     */
    public static function logLevels()
    {
        return array(
            Logger::DEBUG => 'DEBUG',
            Logger::INFO => 'INFO',
            Logger::NOTICE => 'WARNING',
            Logger::WARNING => 'WARNING',
            Logger::ERROR => 'ERROR',
            Logger::CRITICAL => 'ERROR',
            Logger::ALERT => 'ERROR',
            Logger::EMERGENCY => 'ERROR',
        );
    }
}
