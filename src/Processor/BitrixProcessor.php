<?php
/**
 * @link https://github.com/bitrix-expert/niceaccess
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog\Processor;

use Bitrix\Main\Context;

/**
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class BitrixProcessor
{
    protected $extra = array();

    /**
     * @param array $extra Extra fields.
     */
    public function __construct(array $extra = null)
    {
        if ($extra !== null)
        {
            $this->extra = $extra;
        }
    }

    /**
     * Adds extra field.
     *
     * @param string $name
     * @param mixed $value
     */
    public function addExtraField($name, $value)
    {
        $this->extra[$name] = $value;
    }

    /**
     * Extra fields of processor.
     *
     * @return array
     */
    public function fields()
    {
        return array(
            'site_id',
            'user_id',
            'guest_id',
            'user_agent',
        );
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        // skip processing if for some reason request data
        // is not present (CLI or wonky SAPIs)
        if (Context::getCurrent()->getServer()->getRequestUri() === null)
        {
            return $record;
        }

        $record['extra'] = $this->getExtraFields();

        return $record;
    }

    /**
     * @return array
     */
    protected function getExtraFields()
    {
        global $USER;

        $extra = array();

        foreach ($this->fields() as $field)
        {
            if (!isset($this->extra[$field]))
            {
                switch ($field)
                {
                    case 'site_id':
                        $value = Context::getCurrent()->getSite();
                        break;

                    case 'user_id':
                        $value = is_object($USER) && ($USER->GetID() > 0) ? $USER->GetID() : false;
                        break;

                    case 'guest_id':
                        $value = (isset($_SESSION) && array_key_exists('SESS_GUEST_ID', $_SESSION) && $_SESSION['SESS_GUEST_ID'] > 0 ? $_SESSION['SESS_GUEST_ID'] : false);
                        break;

                    case 'user_agent':
                        $value = Context::getCurrent()->getServer()->get('HTTP_USER_AGENT');
                        break;
                }
            }

            $extra[$field] = $value;
        }

        return $extra;
    }
}
