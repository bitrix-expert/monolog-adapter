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
    private $event;
    private $module;
    private $siteId;

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
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        \CEventLog::Log(
            $record['level'],
            $this->getEvent(),
            $this->getModule(),
            $record['item_id'],
            $record['message'],
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

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setSite($siteId)
    {
        $this->siteId = $siteId;
    }

    public function getSite()
    {
        return $this->siteId;
    }
}