<?php
/**
 * @link https://github.com/bitrix-expert/niceaccess
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Bex\Monolog\Formatter\BitrixFormatter;
use Bitrix\Main\ArgumentNullException;

/**
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class BitrixHandler extends AbstractProcessingHandler
{
    protected $event;
    protected $module;
    protected $siteId;

    /**
     * @param string $event Type of event in the event log.
     * @param string $module Code of the module in Bitrix.
     * @param int $level The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble Whether the messages that are handled can bubble up the stack or not
     *
     * @throws ArgumentNullException If audit is null.
     */
    public function __construct($event = null, $module = null, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->event = $event;
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        \CEventLog::Log(
            static::toBitrixLevel($record['level']),
            $this->event,
            $this->module,
            (isset($record['context']['ITEM_ID'])) ? $record['context']['ITEM_ID'] : null,
            $record['message'],
            $this->siteId
        );
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function setSite($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new BitrixFormatter();
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