<?php

namespace Msnet\Site;

use Bitrix\Main\Loader;

class Helper
{
    /**
     * Склонение слова
     *
     * @param int $n - число
     * @param string $f1 - строка для числа 1
     * @param string $f2 - строка для числа 2
     * @param string $f5 - строка для числа 5
     * @return string
     */
    public static function strMorph($n, $f1, $f2, $f5, $full = false)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20)
            return $full ? $n.' '.$f5 : $f5;
        if ($n1 > 1 && $n1 < 5)
            return $full ? $n.' '.$f2 : $f2;
        if ($n1 == 1)
            return $full ? $n.' '.$f1 : $f1;
        return $full ? $n.' '.$f5 : $f5;
    }

    /**
     * Определяем AJAX запрос
     *
     * @return bool
     */
    public static function isAjax()
    {
        return ($_REQUEST['ajax'] == 'y' || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }


    public static function stopApplication()
    {
        global $APPLICATION;
        $APPLICATION->FinalActions();
        exit;
    }

    /**
     * Возращает ссылку для редактирования элемента в инфоблоке
     *
     * @param int $iElementId
     * @param bool $bOnlyLink
     *
     * @return string
     */
    public static function getEditUrl($iElementId, $bOnlyLink = false)
    {
        global $USER;

        if (!$USER->isAdmin())
            return '';

        $rsItem = \CIBlockElement::GetByID($iElementId);

        if ($arItem = $rsItem->Fetch()) {
            $sLink = '/bitrix/admin/' . \CIBlock::GetAdminElementEditLink($arItem['IBLOCK_ID'], $arItem['ID']);

            if ($bOnlyLink) {
                return $sLink;
            } else {
                return '<a target="_blank" href="' . $sLink . '">edit</a>';
            }
        }

        return '';
    }

    public static function getYouTubeOEmbed($sVideoUrl)
    {
        $sVideoUrl = trim($sVideoUrl);
        $arResult = false;

        $obCache = new \CPHPCache();
        $iCacheTime = 604800;
        $sCachePath = '/youtubeoembed';

        if ($obCache->InitCache($iCacheTime, $sVideoUrl, $sCachePath)) {
            $ar = $obCache->GetVars();
            $arResult = $ar['RESULT'];
        } elseif ($obCache->StartDataCache($iCacheTime, $sVideoUrl, $sCachePath)) {
            if (filter_var($sVideoUrl, FILTER_VALIDATE_URL)) {
                $json = file_get_contents('http://www.youtube.com/oembed?format=json&url=' . $sVideoUrl);
                $arResult = array_change_key_case(json_decode($json, true), CASE_UPPER);
                //добавляем js api
                $arResult['HTML'] = preg_replace('/(src\=\")(.+?)(\")/', '$1$2&enablejsapi=1$3', $arResult['HTML']);
                $arResult['URL'] = $sVideoUrl;
            }
            $obCache->EndDataCache(array('RESULT' => $arResult));
        }

        return $arResult;
    }

    public static function getFullServerName()
    {
        $sServerName = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $sServerName .= 's';
        }

        $sServerName .= '://' . $_SERVER['SERVER_NAME'];

        if ($_SERVER['SERVER_PORT'] != '80' && !empty($_SERVER['SERVER_PORT'])) {
            $sServerName .= ':' . $_SERVER['SERVER_PORT'];
        }

        return $sServerName;
    }

    /**
     * Возращает путь до файла в котором объявлен класс
     * Полезно при работе с недокументированным API, например с bitrix 16
     *
     * @param string|object $obObject - строка с именем класса или экземпляр объекта класса
     * @return string
     */
    public static function getClassFile($obObject)
    {
        $obReflection = new \ReflectionClass($obObject);
        return $obReflection->getFileName();
    }

    /**
     * @param int|array $ID - id картинки или массив, результат метода \CFile::GetFileArray()
     * @param int $iWidth - новая ширина
     * @param int $iHeight - новая высота
     * @param bool $bExact - картинка по точным размерам, обрезая лишнее
     * @return bool|string
     */
    public static function getResizePath($ID, $iWidth, $iHeight, $bExact = false)
    {
        if (empty($ID))
            return false;

        if ($bExact) {
            $iResizeType = BX_RESIZE_IMAGE_EXACT;
        } else {
            $iResizeType = BX_RESIZE_IMAGE_PROPORTIONAL_ALT;
        }

        $arResize = \CFile::ResizeImageGet($ID, array('width' => $iWidth, 'height' => $iHeight), $iResizeType, false);

        return $arResize['src'] ?: false;
    }

    /**
     * @param string $sHtml
     * @return string
     */
    public static function minHTML($sHtml = '')
    {
        $sHtml = preg_replace('/(?:(?<=\>)|(?<=\/\>))\s+(?=\<\/?)/', '', $sHtml);

        if (strpos($sHtml, '<pre') === false) {
            $sHtml = preg_replace('/\s+/', ' ', $sHtml);
        }

        $sHtml = preg_replace('/[\t\r]\s+/', ' ', $sHtml);
        $sHtml = preg_replace('/<!(--)([^\[|\|])^(<!-->.*<!--.*-->)/', '', $sHtml);
        $sHtml = preg_replace('/\/\*.*?\*\//', '', $sHtml);

        return $sHtml;
    }


    public static function getEmptyImg($iWidth = 325, $iHeight = 325)
    {
        return "http://placehold.it/{$iWidth}x{$iHeight}";
    }

    public static function rgb2hex($rgb)
    {
        $rgb = explode(',', $rgb);

        $hex = '#';
        $hex .= str_pad(dechex($rgb[0]), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[1]), 2, '0', STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[2]), 2, '0', STR_PAD_LEFT);

        return $hex;
    }

    public static function getChangeUrl(array $addParams = [], array $removeParams = [])
    {
        global $APPLICATION;
        $stringAddParams = '';
        foreach ($addParams as $addParam => $addParamValue) {
            if (!empty($stringAddParams)) {
                $stringAddParams .= '&';
            }
            $stringAddParams .= $addParam.'='.$addParamValue;
        }

        $newUrl = $APPLICATION->GetCurPageParam($stringAddParams, $removeParams);
        return $newUrl;
    }
}