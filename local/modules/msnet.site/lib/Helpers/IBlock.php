<?php

namespace Msnet\Site\Helpers;


use Bitrix\Main\Application;
use Bitrix\Main\Loader;

class IBlock
{
    private static $sCacheDir = '/msnet/site/iblock_list/';
    private static $arIBlocks = null;
    private static $arCache = [];


    /**
     * Возвращает id инфоблока по его коду
     * todo возможна ситуация когда будет два инфоблока с одинаковым кодом, надо добавить проверку через тип инфоблока
     *
     * @param string $sCode - CODE инфоблока
     * @return int
     * @throws \Exception
     */
    public static function getIBlockId($sCode)
    {
        $obCache = Application::getInstance()->getCache();

        if (is_null(self::$arIBlocks)) {
            $arIBlocks = array();

            if ($obCache->initCache(604800, 'iblock_list', self::$sCacheDir)) {
                $arIBlocks = $obCache->getVars();
            } elseif (Loader::includeModule('iblock')) {
                $rsIBlocks = \CIBlock::GetList([], ['ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'N']);

                while ($arIBlock = $rsIBlocks->Fetch())
                    $arIBlocks[$arIBlock['CODE']] = $arIBlock['ID'];

                if ($obCache->startDataCache())
                    $obCache->endDataCache($arIBlocks);
            }

            self::$arIBlocks = $arIBlocks;
        }

        if (!array_key_exists($sCode, self::$arIBlocks)) {
            throw new \Exception("IBlock $sCode not found");
        }

        return self::$arIBlocks[$sCode];
    }

    /**
     * Возвращает id значения списка по его XML_ID
     *
     * @param string $sCode - XML_ID значения списка
     * @param string $sPropertyCode - CODE свойства
     * @param string|int $iIBlockID - ID или CODE инфоблока
     * @return int
     */
    public static function getEnumIdByCode($sCode, $sPropertyCode, $iIBlockID)
    {
        if (!is_numeric($iIBlockID))
            $iIBlockID = self::getIBlockId($iIBlockID);

        $sCacheKey = $iIBlockID . '_' . $sPropertyCode . '_' . $sCode;

        if (is_null(self::$arCache[$sCacheKey])) {
            Loader::includeModule('iblock');

            $arValue = \CIBlockPropertyEnum::GetList([], [
                'IBLOCK_ID' => $iIBlockID,
                'XML_ID' => $sCode,
                'CODE' => $sPropertyCode,
            ])->Fetch();

            self::$arCache[$sCacheKey] = intval($arValue['ID']);
        }

        return self::$arCache[$sCacheKey];
    }

    /**
     * @param int $iIBlockID
     * @param array $arVariables
     * @return array
     */
    public static function getSectionByVariables($iIBlockID, $arVariables = [])
    {
        $arCurSection = [];

        $arFilter = array(
            'IBLOCK_ID' => $iIBlockID,
            'ACTIVE' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
        );

        if (intval($arVariables['SECTION_ID']) > 0) {
            $arFilter['ID'] = intval($arVariables['SECTION_ID']);
        } elseif (!empty($arVariables['SECTION_CODE'])) {
            $arFilter['=CODE'] = trim($arVariables['SECTION_CODE']);
        } else {
            $arFilter['ID'] = -1;
        }

        $obCache = Application::getCache();
        $sCacheId = 'catalog_section_' . md5(serialize($arFilter));
        $sCacheDir = '/iblock/catalog';

        if ($obCache->initCache(604800, $sCacheId, $sCacheDir)) {
            $arCurSection = $obCache->getVars();
        } elseif ($obCache->startDataCache()) {
            if (Loader::includeModule('iblock')) {
                $arSelect = ['ID', 'IBLOCK_ID', 'NAME', 'DEPTH_LEVEL', 'SECTION_PAGE_URL', 'PICTURE'];
                $rsSections = \CIBlockSection::GetList([], $arFilter, false, $arSelect);

                Application::getInstance()->getTaggedCache()->startTagCache($sCacheDir);
                $arCurSection = $rsSections->GetNext(true, false);
                Application::getInstance()->getTaggedCache()->endTagCache();

                if ($arCurSection)
                    \Bitrix\Iblock\Component\Tools::getFieldImageData($arCurSection, ['PICTURE'], \Bitrix\Iblock\Component\Tools::IPROPERTY_ENTITY_SECTION);

                if (!$arCurSection)
                    $arCurSection = [];
            }

            $obCache->endDataCache($arCurSection);
        }

        return $arCurSection;
    }

    /**
     * Метод проверяет правильность пути раздела в комплексном компоненте
     * При использовании SECTION_CODE_PATH
     *
     * @param $arVariables - массив внутренних названий переменных, которые могут использоваться в комплексном компоненте
     * @param $iIBlockID - ID инфоблока
     * @param bool $bElementMode - проверять наличие элемента, true если проверка проходит на детальной странице элемента
     * @return bool
     */
    public static function checkSectionCodePath($arVariables, $iIBlockID, $bElementMode = false)
    {
        if (!Loader::includeModule('iblock'))
            return false;

        if (!$arVariables || !is_array($arVariables))
            return false;

        if (empty($arVariables['SECTION_CODE_PATH']))
            return true;

        $iIBlockID = intval($iIBlockID);
        if ($iIBlockID <= 0)
            return false;

        $arSectionsCodes = explode('/', $arVariables['SECTION_CODE_PATH']);
        $iDepthLevel = count($arSectionsCodes);

        $arSectionFilter = [
            'IBLOCK_ID' => $iIBlockID,
            'ACTIVE' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'DEPTH_LEVEL' => $iDepthLevel
        ];

        if (!empty($arVariables['SECTION_CODE'])) {
            $arSectionFilter['CODE'] = $arVariables['SECTION_CODE'];
        } elseif (!empty($arVariables['SECTION_ID'])) {
            $arSectionFilter['ID'] = $arVariables['SECTION_ID'];
        } else {
            $arSectionFilter['ID'] = -1;
        }

        $arSection = \CIBlockSection::GetList(
            ['ID' => 'ASC'],
            $arSectionFilter,
            false,
            ['ID', 'IBLOCK_ID', 'CODE', 'LEFT_MARGIN', 'RIGHT_MARGIN'],
            ['nTopCount' => 1]
        )->Fetch();

        if (!$arSection)
            return false;

        if ($iDepthLevel > 1) {
            $rsTree = \CIBlockSection::GetList(
                ['ID' => 'ASC'],
                [
                    'IBLOCK_ID' => $iIBlockID,
                    'ACTIVE' => 'Y',
                    'GLOBAL_ACTIVE' => 'Y',
                    '<=LEFT_BORDER' => $arSection['LEFT_MARGIN'],
                    '>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN']
                ],
                false,
                ['ID', 'IBLOCK_ID', 'CODE']
            );

            $arTree = [];
            while ($arTreeItem = $rsTree->Fetch()) {
                $arTree[] = $arTreeItem['CODE'];
            }

            if ($iDepthLevel != count($arTree))
                return false;

            if (count(array_diff($arSectionsCodes, $arTree)) > 0)
                return false;
        }

        if ($bElementMode) {
            $arElementFilter = [
                'IBLOCK_ID' => $iIBlockID,
                'ACTIVE' => 'Y',
                'SECTION_ID' => $arSection['ID'],
            ];

            if (!empty($arVariables['ELEMENT_CODE'])) {
                $arElementFilter['CODE'] = $arVariables['ELEMENT_CODE'];
            } elseif (!empty($arVariables['ELEMENT_ID'])) {
                $arElementFilter['ID'] = $arVariables['ELEMENT_ID'];
            } else {
                $arElementFilter['ID'] = -1;
            }

            $arItem = \CIBlockElement::GetList(
                ['ID' => 'ASC'],
                $arElementFilter,
                false,
                ['nTopCount' => 1]
            )->Fetch();

            if (!$arItem)
                return false;
        }

        return true;
    }
}