<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Bex\Monolog\Handler\Bitrix24;

use Monolog\Logger;
use Bex\Monolog\Handler\Bitrix24Handler;
use Bex\Monolog\Formatter\Bitrix24\Bitrix24UserFormatter;

/**
 * Monolog User handler for the Bitrix24.
 *
 * @author GSU <gsu1234@mail.ru>
 */
class Bitrix24UserHandler extends Bitrix24Handler
{
    private $notifyType;
    private $userId;

    /**
     * Bitrix24UserHandler constructor.
     * @param int $level
     * @param bool $bubble
     * @param string $application_scope
     * @param string $application_id
     * @param string $application_secret
     * @param string $domain
     * @param string $notify_type
     * @param string $user_id
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
        $notify_type = 'SYSTEM',
        $user_id = '',
        $user_login = '',
        $user_password = ''
    ) {
        parent::__construct($level, $bubble, $application_scope, $application_id, $application_secret, $domain,
            $user_login, $user_password);
        $this->setNotifyType($notify_type);
        $this->setUserID($user_id);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $logger = new \Bitrix24\Im\Im($this->get24App());
        $logger->notify($this->getUserID(), $record['formatted']['data'], strtoupper($this->getNotifyType()));
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new Bitrix24UserFormatter();
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
     * @param $notify_type
     */
    public function setNotifyType($notify_type){
        $this->notifyType = $notify_type;
    }

    /**
     * @return mixed
     */
    public function getNotifyType(){
        return $this->notifyType;
    }

}
