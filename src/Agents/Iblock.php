<?
namespace Bex\Monolog\Agents;

class Iblock
{
    /**
     * Deleting all elements from iblock, except the first $countElements elements
     * @param $countElements
     * @param $iblockID
     * @return string
     */
    public static function AgentDeleteOldElementsByCount($countElements, $iblockID)
    {
        if (\CModule::IncludeModule("iblock")) {
            $arSelect = Array("ID");
            $arFilter = Array("IBLOCK_ID" => $iblockID);
            $rsResult = \CIBlockElement::GetList(Array("timestamp_x" => "DESC"), $arFilter, false, false, $arSelect);
            $intCounter = 1;
            while ($arItem = $rsResult->GetNext()) {
                if ($intCounter > $countElements) {
                    \CIBlockElement::Delete($arItem["ID"]);
                }
                $intCounter++;
            }
        }

        return "\Bex\Monolog\Agents\Iblock::AgentDeleteOldElementsByCount(" . $countElements . ", " . $iblockID . ");";
    }

    /**
     * Deleting elements from iblock, who are older than $countDays days
     * @param $countDays
     * @param $iblockID
     * @return string
     */
    public static function AgentDeleteOldElementsByLastChangeTime($countDays, $iblockID)
    {
        if (\CModule::IncludeModule("iblock")) {
            $arSelect = Array("ID");
            $arFilter = Array(
                "IBLOCK_ID" => $iblockID,
                ">DATE_ACTIVE_FROM" => (new \DateTime())->modify('-' . $countDays . ' days')->format('d.m.Y')
            );
            $rsResult = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while ($arItem = $rsResult->GetNext()) {
                \CIBlockElement::Delete($arItem["ID"]);
            }
        }

        return "\Bex\Monolog\Agents\Iblock::AgentDeleteOldElementsByLastChangeTime(" . $countDays . ", " . $iblockID . ");";
    }
}