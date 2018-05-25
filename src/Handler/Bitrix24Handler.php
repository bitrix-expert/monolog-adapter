<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Bex\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Monolog handler for the Bitrix24. Parent class.
 *
 * @author GSU <gsu1234@mail.ru>
 */
class Bitrix24Handler extends AbstractProcessingHandler
{

    protected $obB24App;

    /**
     * Bitrix24Handler constructor.
     * @param int $level
     * @param bool $bubble
     * @param string $application_scope Example ["pull", "pull_channel", "messageservice", "log", "user", "im"]
     * @param string $application_id Code of created local application in bitrix24 (****.bitrix24.ru/marketplace/local/list/)
     * @param string $application_secret
     * @param string $domain Your b24 domain, example: b24-aqm4rt.bitrix24.ru
     */
    public function __construct(
        $level = Logger::DEBUG,
        $bubble = true,
        $application_scope = '',
        $application_id = '',
        $application_secret = '',
        $domain = ''
    ) {
        parent::__construct($level, $bubble);

        $obB24App = new \Bitrix24\Bitrix24();

        $obB24App->setApplicationScope($application_scope);
        $obB24App->setApplicationId($application_id);
        $obB24App->setApplicationSecret($application_secret);

        $obB24App->setRedirectUri($this->getCurrentPage());

        $obB24App->setDomain($domain);
        $obB24App->getFirstAuthCode();
        $arRequestResult = $obB24App->getFirstAccessToken($obB24App->getCode());

        $obB24App->setMemberId($arRequestResult["member_id"]);
        $obB24App->setAccessToken($arRequestResult["access_token"]);
        $obB24App->setRefreshToken($arRequestResult["refresh_token"]);

        $this->set24App($obB24App);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
    }


    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
    }

    /**
     * @param $obB24App
     */
    public function set24App($obB24App)
    {
        $this->obB24App = $obB24App;
    }

    /**
     * @return mixed
     */
    public function get24App()
    {
        return $this->obB24App;
    }

    /**
     * @return string
     */
    protected function getCurrentPage(){
        global $APPLICATION;
        $CURRENT_PAGE = (\CMain::IsHTTPS()) ? "https://" : "http://";
        $CURRENT_PAGE .= $_SERVER["HTTP_HOST"];
        $CURRENT_PAGE .= $APPLICATION->GetCurPage();

        return $CURRENT_PAGE;
    }

}
