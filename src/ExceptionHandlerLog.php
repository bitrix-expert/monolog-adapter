<?php

namespace Bex\Monolog;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Monolog\Handler\StreamHandler;
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
        if (!isset($options['channel']))
        {
            throw new ArgumentNullException('channel');
        }
        
        if (!isset($options['stream']))
        {
            throw new ArgumentNullException('stream');
        }
                
        $this->logger = new Logger($options['channel'], $this->getHandlers($options));
    }

    /**
     * Gets handlers for channel.
     * 
     * @param array $options Settings from .settings.php.
     *
     * @return array
     */
    protected function getHandlers($options)
    {
        return [
            new StreamHandler(Application::getDocumentRoot() . '/' . $options['stream'])
        ];
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