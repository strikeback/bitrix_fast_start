<?php

namespace Msnet\Site;

class Component extends \CBitrixComponent
{
    /**
     * Добавление элементов управления
     * @param $arItem
     * @return bool
     */
    public function addControls(&$arItem)
    {
        if (!$arItem['IBLOCK_ID'] || !$arItem['ID']) return false;

        $arButtons = \CIBlock::GetPanelButtons(
            $arItem['IBLOCK_ID'],
            $arItem['ID'],
            0,
            array('SECTION_BUTTONS' => false, 'SESSID' => false)
        );

        $arItem['EDIT_LINK'] = $arButtons['edit']['edit_element']['ACTION_URL'];
        $arItem['DELETE_LINK'] = $arButtons['edit']['edit_element']['ACTION_URL'];
        $arItem['CONTROL_ID'] = $this->getEditAreaId($arItem['ID']);
    }

    public function loaderModules($arModules = [])
    {
        foreach ($arModules as $sModule) {
            if (!\Bitrix\Main\Loader::includeModule($sModule)) {
                ShowError('Для работы необходим модуль ' . $sModule);
                return false;
            }
        }

        return true;
    }

    public function getDefaultSelectFields()
    {
        return [
            'ID',
            'NAME',
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE',
            'DATE_ACTIVE_FROM',
            'DATE_ACTIVE_TO',
            'LIST_PAGE_URL',
            'DETAIL_PAGE_URL'
        ];
    }
}