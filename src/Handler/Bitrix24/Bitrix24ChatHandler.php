<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Bex\Monolog\Handler\Bitrix24;

use Monolog\Logger;
use Bex\Monolog\Handler\Bitrix24Handler;
use Bex\Monolog\Formatter\Bitrix24\Bitrix24ChatFormatter;

/**
 * Monolog Chat handler for the Bitrix24.
 *
 * @author GSU <gsu1234@mail.ru>
 */
class Bitrix24ChatHandler extends Bitrix24Handler
{
    private $userId = '';
    private $chatID = 1;
    private $system;

    /**
     * Bitrix24ChatHandler constructor.
     * @param int $level
     * @param bool $bubble
     * @param string $application_scope
     * @param string $application_id
     * @param string $application_secret
     * @param string $domain
     * @param string $user_id
     * @param string $chat_id
     * @param string $system
     * @param string $user_login
     * @param string $user_password
     */
    public function __construct(
        $level = Logger::DEBUG,
        $bubble = true,
        $application_scope = '',
        $application_id = '',
        $application_secret = '',
        $domain = '',
        $user_id = '',
        $chat_id = '',
        $system = "Y",
        $user_login = '',
        $user_password = ''
    ) {
        parent::__construct($level, $bubble, $application_scope, $application_id, $application_secret, $domain,
            $user_login, $user_password);
        $this->setUserID($user_id);
        $this->setChatID($chat_id);
        $this->setSystem($system);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $logger = new \Bitrix24\Im\Im($this->get24App());
        $logger->messageAdd($this->getChatID(), $record['formatted']['data'], $this->getSystem(), $this->getUserID());
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new Bitrix24ChatFormatter();
    }

    /**
     * @param $user_id
     */
    public function setUserID($user_id){
        $this->userId = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserID(){
        return $this->userId;
    }

    /**
     * @param $chat_id
     */
    public function setChatID($chat_id){
        $this->chatID = $chat_id;
    }

    /**
     * @return int
     */
    public function getChatID(){
        return $this->chatID;
    }

    /**
     * @param $system
     */
    public function setSystem($system){
        $this->system = $system;
    }

    /**
     * @return mixed
     */
    public function getSystem(){
        return $this->system;
    }


}
