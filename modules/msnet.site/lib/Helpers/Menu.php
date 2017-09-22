<?php

namespace Msnet\Site\Helpers;


class Menu
{
    public static function menuTreeBuild($arItems, $iDepthLevel = 1)
    {
        $arTree = [];
        foreach ($arItems as $iKey => $arItem) {
            if ($arItem['DEPTH_LEVEL'] < $iDepthLevel) {
                break;
            }

            if ($arItem['DEPTH_LEVEL'] == $iDepthLevel) {
                if ($arItem['IS_PARENT']) {
                    $arItem['ITEMS'] = self::menuTreeBuild(array_slice($arItems, $iKey + 1), $arItem['DEPTH_LEVEL'] + 1);
                }
                $arTree[] = $arItem;
            }
        }
        return $arTree;
    }
}