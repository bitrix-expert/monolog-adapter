<?php
/**
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Bex\Monolog\Formatter;

use Bitrix\Main\Type\DateTime;

/**
 * Record formatter for iblock of Bitrix.
 * 
 * Context of record will also be written to iblock of Bitrix.
 * 
 * @author GSU <gsu1234@mail.ru>
 */
class BitrixIblockFormatter extends BitrixFormatter
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $record['level'] = parent::toBitrixLevel($record['level']);

        $objDateTime = new DateTime();
        $record['title'] = $record["channel"] . ": ";
        $record['title'] .= "[" . $record['level'] . "] ";
        $record['title'] .= $record['message'];
        $record['title'] .= " - " . $objDateTime->toString();

        if (!empty($record['context']))
        {
            $arData = [];
            foreach ($record['context'] as $field => $value) {
                if (is_array($value)) {
                    $value = var_export($value, true);
                }
                $arData[] = $field . "\n\n" . $value;
            }
            $record['data'] = implode("\n\n\n\n", $arData);
        }

        return $record;
    }

}
