<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

global $USER;
global $APPLICATION;
#--- Получение параметров
$arResult["TYPE"] = $arParams["TYPE"] ? $arParams["TYPE"] : false;
$arResult["DATA"] = $arParams["DATA"] ? $arParams["DATA"] : false;
$arResult["AJAX"] = $arParams["AJAX"] ? $arParams["AJAX"] : false;
$arResult["RETURN_RESULT"] = $arParams["RETURN_RESULT"] ? $arParams["RETURN_RESULT"] : false;
#--- //Получение параметров

if (!$arResult["RETURN_RESULT"]) {
    $this->IncludeComponentTemplate();
} else {
    ob_start();
    $this->IncludeComponentTemplate();
    $out = ob_get_contents();
    ob_end_clean();
    $response["html"] = $out;
    return $response;
}

