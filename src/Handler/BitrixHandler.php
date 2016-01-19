<?php
/**
 * @link https://github.com/bitrix-expert/monolog-adapter
 * @copyright Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Bex\Monolog\Formatter\BitrixFormatter;

/**
 * Monolog handler for the event log of Bitrix CMS.
 * 
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class BitrixHandler extends AbstractProcessingHandler
{
    private $event;
    private $module;
    private $siteId;

    /**
     * @param string $event Type of event in the event log of Bitrix.
     * @param string $module Code of the module in Bitrix.
     * @param int $level The minimum logging level at which this handler will be triggered.
     * @param bool $bubble Whether the messages that are handled can bubble up the stack or not.
     */
    public function __construct($event = null, $module = null, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->setEvent($event);
        $this->setModule($module);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        \CEventLog::Log(
            $record['formatted']['level'],
            $this->getEvent(),
            $this->getModule(),
            $record['formatted']['item_id'],
            $record['formatted']['message'],
            $this->getSite()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new BitrixFormatter();
    }

    /**
     * Sets event type for log of Bitrix.
     * 
     * @param string $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * Gets event type.
     * 
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets module for log of Bitrix.
     * 
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * Gets module.
     * 
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Sets site ID for log of Bitrix.
     * 
     * @param string $siteId
     */
    public function setSite($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * Gets site ID.
     * 
     * @return string
     */
    public function getSite()
    {
        return $this->siteId;
    }
}
