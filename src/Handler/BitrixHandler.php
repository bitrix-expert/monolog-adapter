<?php
/**
 * @link https://github.com/bitrix-expert/niceaccess
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Bitrix\Main\ArgumentNullException;

/**
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class BitrixHandler extends AbstractProcessingHandler
{
    protected $audit;
    protected $module;
    
    /**
     * @param string $audit Type of event in the event log.
     * @param string $module Code of the module in Bitrix.
     * @param int $level The minimum logging level at which this handler will be triggered
     * @param Boolean $bubble Whether the messages that are handled can bubble up the stack or not
     *
     * @throws ArgumentNullException If audit is null.
     */
    public function __construct($audit, $module = null, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
        
        if (empty($audit))
        {
            throw new ArgumentNullException('audit');
        }
        
        $this->audit = $audit;
        $this->module = $module;
    }

    protected function write(array $record)
    {
        /*$arFields = array(
            "SEVERITY" => array_key_exists($arFields["SEVERITY"], $arSeverity)? $arFields["SEVERITY"]: "UNKNOWN",
            "AUDIT_TYPE_ID" => strlen($arFields["AUDIT_TYPE_ID"]) <= 0? "UNKNOWN": $arFields["AUDIT_TYPE_ID"],
            "MODULE_ID" => strlen($arFields["MODULE_ID"]) <= 0? "UNKNOWN": $arFields["MODULE_ID"],
            "ITEM_ID" => strlen($arFields["ITEM_ID"]) <= 0? "UNKNOWN": $arFields["ITEM_ID"],
            "REMOTE_ADDR" => $_SERVER["REMOTE_ADDR"],
            "USER_AGENT" => $_SERVER["HTTP_USER_AGENT"],
            "REQUEST_URI" => $url,
            "SITE_ID" => strlen($arFields["SITE_ID"]) <= 0 ? $SITE_ID : $arFields["SITE_ID"],
            "USER_ID" => is_object($USER) && ($USER->GetID() > 0)? $USER->GetID(): false,
            "GUEST_ID" => (isset($_SESSION) && array_key_exists("SESS_GUEST_ID", $_SESSION) && $_SESSION["SESS_GUEST_ID"] > 0? $_SESSION["SESS_GUEST_ID"]: false),
            "DESCRIPTION" => $arFields["DESCRIPTION"],
        );*/
        
//        \CEventLog::Add();
    }
}