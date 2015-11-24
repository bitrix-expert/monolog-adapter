<?php
/**
 * @link https://github.com/bitrix-expert/niceaccess
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;
use Bex\Monolog\Processor\BitrixProcessor;
use Bitrix\Main\ArgumentNullException;

/**
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class BitrixHandler extends AbstractProcessingHandler
{
    private $event;
    private $module;

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

        $this->setEvent($event);
        $this->setModule($module);
        $this->pushProcessor(new WebProcessor());
        $this->pushProcessor(new BitrixProcessor());
    }

    protected function write(array $record)
    {
        \CEventLog::Add(array(
            'SEVERITY' => static::toBitrixLevel($record['level']),
            'AUDIT_TYPE_ID' => $this->getEvent(),
            'MODULE_ID' => $this->getModule(),
            'ITEM_ID' => isset($record['context']['ITEM_ID']) ? $record['context']['ITEM_ID'] : 'UNKNOWN',
            'REMOTE_ADDR' => $record['extra']['ip'],
            'USER_AGENT' => $record['extra']['user_agent'],
            'REQUEST_URI' => $record['extra']['url'],
            'SITE_ID' => $record['extra']['site_id'],
            'USER_ID' => $record['extra']['user_id'],
            'GUEST_ID' => $record['extra']['guest_id'],
            'DESCRIPTION' => $record['message'],
        ));
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return ($this->event) ? $this->event : 'UNKNOWN';
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return ($this->module) ? $this->module: 'UNKNOWN';
    }

    /**
     * Converts PSR-3 levels to Bitrix ones if necessary.
     *
     * @param int $level Level number.
     *
     * @return string
     */
    public static function toBitrixLevel($level)
    {
        $levels = array(
            Logger::DEBUG => 'DEBUG',
            Logger::INFO => 'INFO',
            Logger::NOTICE => 'WARNING',
            Logger::WARNING => 'WARNING',
            Logger::ERROR => 'ERROR',
            Logger::CRITICAL => 'ERROR',
            Logger::ALERT => 'ERROR',
            Logger::EMERGENCY => 'ERROR',
        );

        if (isset($levels[$level]))
        {
            return $levels[$level];
        }

        return 'UNKNOWN';
    }
}