<?php

namespace Msnet\Site;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Msnet\Site\Helpers\HL;

class Location
{

    static $_all;

    public static function all()
    {
        if (is_null(self::$_all)) {
            self::$_all = [];

            $obCache = Application::getCache();
            if ($obCache->initCache(3600 * 12, 'cities_list', '/msnet/cities/')) {
                $arResult = $obCache->getVars();
            } else {

                $arResult = self::getCities();

                if ($obCache->startDataCache())
                    $obCache->endDataCache($arResult);
            }

            self::$_all = $arResult;
        }

        return self::$_all;
    }

    public static function getCities()
    {
        $result = [];
        $CityClass = HL::getEntityClassByHLName('City');
        $entityDataClass = $CityClass->getDataClass();
        $rsData = $entityDataClass::getList(array(
            'order' => array("UF_SORT" => "ASC"),
            'select' => array('ID', 'UF_NAME', 'UF_XML_ID', 'UF_DEFAULT', 'UF_COORDINATES', 'UF_ORDER_DESC')
        ));

        while ($el = $rsData->fetch()) {
            $result[$el['ID']] = [
                'ID' => $el['ID'],
                'NAME' => $el['UF_NAME'],
                'XML_ID' => $el['UF_XML_ID'],
                'DEFAULT' => $el['UF_DEFAULT'],
                'COORDINATES' => $el['UF_COORDINATES'],
                'ORDER_DESCRIPTION' => $el['UF_ORDER_DESC']
            ];
        }

        return $result;
    }

    public static function getCurrent()
    {

        $arAll = self::all();

        $iCookieLocationID = intval($_COOKIE['CURRENT_LOCATION_ID']);
        if ($iCookieLocationID > 0 && array_key_exists($iCookieLocationID, $arAll)) {
            return $arAll[$iCookieLocationID];
        }

        $arFindLocation = self::getLocation($_SERVER['REMOTE_ADDR']);

        if ($arFindLocation['city'] <= 0) {
            return self::getDefault();
        }

        if (strlen($arFindLocation['city']) > 0) {
            foreach ($arAll as $arLocation) {
                if ($arLocation['NAME'] == $arFindLocation['city']) {
                    return $arLocation;
                }
            }
        }

        return self::getDefault();
    }

    public static function getDefault()
    {

        $arAll = self::all();
        foreach ($arAll as $arLocation) {
            if ($arLocation['DEFAULT'] == true) {
                return $arLocation;
            }
        }

        return false;
    }

    public static function getLocation($ip)
    {
        $data['charset'] = 'utf-8';
        $data['ip'] = $ip;
        $geo = new GeoIP($data);
        $cityData = $geo->get_geobase_data();

        return $cityData['name'];
    }

    public static function setCurrent($ID)
    {
        $ID = intval($ID);

        $arAll = self::all();

        if ($ID > 0 && in_array($ID, array_column($arAll, 'ID'))) {
            setcookie('CURRENT_LOCATION_ID', $ID, time() + 86400 * 30, '/');
            return true;
        }

        return false;
    }
}