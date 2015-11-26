<?php
/**
 * @link https://github.com/bitrix-expert/niceaccess
 * @copyright Copyright © 2015 Nik Samokhvalov
 * @license MIT
 */

namespace Bex\Monolog\Formatter;

use Monolog\Formatter\FormatterInterface;

/**
 * @author Nik Samokhvalov <nik@samokhvalov.info>
 */
class BitrixFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        if (!empty($record['context']))
        {
            foreach ($record['context'] as $name => $value)
            {
                $record['message'] .= "<br><b>" . $name . '</b>: ' . $value;
            }
        }

        return $record;
    }

    /**
     * {@inheritdoc}
     */
    public function formatBatch(array $records)
    {
        $formatted = array();

        foreach ($records as $record)
        {
            $formatted[] = $this->format($record);
        }

        return $formatted;
    }
}
