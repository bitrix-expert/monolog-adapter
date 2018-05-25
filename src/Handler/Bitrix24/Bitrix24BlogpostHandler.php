<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Bex\Monolog\Handler\Bitrix24;

use Monolog\Logger;
use Bex\Monolog\Handler\Bitrix24Handler;
use Bex\Monolog\Formatter\Bitrix24\Bitrix24BlogpostFormatter;

/**
 * Monolog Blogpost handler for the Bitrix24.
 * 
 * @author GSU <gsu1234@mail.ru>
 */
class Bitrix24BlogpostHandler extends Bitrix24Handler
{
    private $perm;

    /**
     * Bitrix24BlogpostHandler constructor.
     * @param int $level
     * @param bool $bubble
     * @param string $application_scope
     * @param string $application_id
     * @param string $application_secret
     * @param string $domain
     * @param array $perm
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
        $perm = array("U" => array("U1", "UA")),
        $user_login = '',
        $user_password = ''
    ) {
        parent::__construct($level, $bubble, $application_scope, $application_id, $application_secret, $domain,
            $user_login, $user_password);
        $this->setPerm($perm);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $logger = new \Bitrix24\Log\BlogPost($this->get24App());
        $logger->Add($record['formatted']['data'], $record['formatted']['title'], $this->getPerm());
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new Bitrix24BlogpostFormatter();
    }

    /**
     * @param $perm
     */
    public function setPerm($perm){
        $this->perm = $perm;
    }

    /**
     * @return mixed
     */
    public function getPerm(){
        return $this->perm;
    }


}
