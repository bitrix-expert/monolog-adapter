<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Bex\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Bex\Monolog\Formatter\BitrixIblockFormatter;
use Bitrix\Main\Loader;
/**
 * Monolog handler for the iblock of Bitrix CMS.
 * 
 * @author GSU <gsu1234@mail.ru>
 */
class BitrixIblockHandler extends AbstractProcessingHandler
{
    private $iblockID;
    private $notifyToAdmin;

    /**
     * BitrixIblockHandler constructor.
     * @param int $iblock_id
     * @param bool|int $level The minimum logging level at which this handler will be triggered.
     * @param int $rotating_count_elements Deleting by agents all elements from iblock, except the first
     * $rotating_count_elements elements. If $rotating_count_elements == 0, then the elements will not be deleted.
     * @param int $rotating_count_days Deleting elements from iblock, who are older than $countDays days.
     * @param bool $notify_to_admin
     * @param bool $bubble Whether the messages that are handled can bubble up the stack or not.
     */
    public function __construct($iblock_id, $level = Logger::DEBUG, $rotating_count_elements = 0, $rotating_count_days = 0, $notify_to_admin = false, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->setIblockID($iblock_id);
        $this->setNotifyToAdmin($notify_to_admin);

        if($rotating_count_elements > 0) {
            $result = \CAgent::GetList(Array("ID" => "DESC"), array("NAME" => "\Bex\Monolog\Agents\Iblock::AgentDeleteOldElementsByCount(" . $rotating_count_elements . ", " . $iblock_id . ");"));
            if(!$result->NavNext(true)){
                \CAgent::AddAgent(
                    "\Bex\Monolog\Agents\Iblock::AgentDeleteOldElementsByCount(" . $rotating_count_elements . ", " . $iblock_id . ");"
                );
            }
        }

        if($rotating_count_days > 0) {
            $result = \CAgent::GetList(Array("ID" => "DESC"), array("NAME" => "\Bex\Monolog\Agents\Iblock::AgentDeleteOldElementsByLastChangeTime(" . $rotating_count_days . ", " . $iblock_id . ");"));
            if(!$result->NavNext(true)){
                \CAgent::AddAgent(
                    "\Bex\Monolog\Agents\Iblock::AgentDeleteOldElementsByLastChangeTime(" . $rotating_count_days . ", " . $iblock_id . ");"
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        global $USER;
        Loader::includeModule("iblock");
        $el = new \CIBlockElement;
        $arLoadElementArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => $this->getIblockID(),
            "NAME"           => $record['formatted']['title'],
            "ACTIVE"         => "Y",
            "PREVIEW_TEXT"   => $record['formatted']['data']
        );
        $logRecordID = $el->Add($arLoadElementArray);

        if($this->getNotifyToAdmin()) {
            Loader::includeModule("main");
            \CAdminNotify::Add(array(
                    'MESSAGE' => $record['formatted']['title'],
                    'TAG' => $record["channel"] . "_" . $logRecordID,
                    'MODULE_ID' => '0',
                    'ENABLE_CLOSE' => 'Y'
                )
            );

        }

    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new BitrixIblockFormatter();
    }

    /**
     * @param $iblockID
     */
    public function setIblockID($iblockID){
        $this->iblockID = $iblockID;
    }

    /**
     * @return mixed
     */
    public function getIblockID(){
        return $this->iblockID;
    }

    /**
     * @param $notifyToAdmin
     */
    public function setNotifyToAdmin($notifyToAdmin){
        $this->notifyToAdmin = $notifyToAdmin;
    }

    /**
     * @return mixed
     */
    public function getNotifyToAdmin(){
        return $this->notifyToAdmin;
    }


}
