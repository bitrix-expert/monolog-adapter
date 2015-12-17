<?php
/**
 * @link https://github.com/bitrix-expert/monolog-adapter
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog;

use Bitrix\Main\ArgumentNullException;
use Monolog\Logger;

class ExceptionHandlerLog extends \Bitrix\Main\Diag\ExceptionHandlerLog
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * {@inheritdoc}
     */
    public function initialize(array $options)
    {
        if (!isset($options['logger']))
        {
            throw new ArgumentNullException('logger');
        }
        
        $this->logger = Loggers::get($options['logger']);
    }

    /**
     * {@inheritdoc}
     */
    public function write(\Exception $exception, $logType)
    {
        $this->logger->emergency($exception->getMessage(), [
            'exception' => $exception->getTrace(),
            'logType' => $logType
        ]);
    }
}