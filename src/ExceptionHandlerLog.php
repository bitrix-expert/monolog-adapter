<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Bex\Monolog;

use Bitrix\Main\ArgumentNullException;
use Monolog\Logger;
use Monolog\Registry;

/**
 * Logger of exceptions. Writes uncaught exceptions to the log.
 *
 * Register the application logger in the `.settings.php` and add his to `exception_handling`:
 * ```php
 * return array(
 *      'monolog' => array(
 *          'value' => array(
 *              'loggers' => array(
 *                  'app' => array(
 *                      // Logger configs
 *                  )
 *              )
 *          ),
 *          'readonly' => false,
 *      ),
 *      'exception_handling' => array(
 *          'value' => array(
 *              'log' => array(
 *                  'class_name' => '\Bex\Monolog\ExceptionHandlerLog',
 *                  'settings' => array(
 *                      'logger' => 'app',
 *                      'context' => function($exception) {
 *                          return [
 *                              'file' => $exception->getFile(),
 *                              'line' => $exception->getLine(),
 *                              'trace' => $exception->getTrace(),
 *                              'some_param' => $exception->getSomeParam(),
 *                          ];
 *                      },
 *                      'rules' => array(
 *					        '!instanceof' => '\Vendor\Exception\UnloggedInterface',
 *					    )
 *                  ),
 *              ),
 *          ),
 *          'readonly' => false,
 *      ),
 * );
 * ```
 *
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class ExceptionHandlerLog extends \Bitrix\Main\Diag\ExceptionHandlerLog
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var callable
     */
    protected $context;

    /**
     * @var string[]
     */
    protected $rules = array();

    /**
     * {@inheritdoc}
     */
    public function initialize(array $options)
    {
        if (!isset($options['logger']))
        {
            throw new ArgumentNullException('logger');
        }

        if (is_array($options['rules']) && !empty($options['rules']))
        {
            $this->rules = $options['rules'];
        }

        if (is_callable($options['context']))
        {
            $this->context = $options['context'];
        }

        $this->logger = Registry::getInstance($options['logger']);
    }

    /**
     * {@inheritdoc}
     */
    public function write(\Exception $exception, $logType)
    {
        foreach ($this->rules as $rule => $condition)
        {
            switch ($rule)
            {
                case '!instanceof':
                    if ($exception instanceof $condition)
                    {
                        return;
                    }
                    break;
                case 'instanceof':
                    if (!($exception instanceof $condition))
                    {
                        return;
                    }
                    break;
            }
        }

        $context = is_callable($this->context) ? call_user_func($this->context, $exception) : null;

        if ($context === null)
        {
            $context = array(
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
                'logType' => $logType
            );
        }

        $this->logger->emergency($exception->getMessage(), $context);
    }
}
