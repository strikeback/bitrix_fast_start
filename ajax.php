<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
global $USER;
$action = $_REQUEST['action'];
$data = $_REQUEST['data'];
switch ($action) {
    case "GET_DATA":
        echo json_encode($out);
        break;
}


if (isset($_REQUEST["auth_service_id"]) && $_REQUEST["auth_service_id"] <> '') {
    if (CModule::IncludeModule("socialservices")) {
        $arResult = array();
        $oAuthManager = new CSocServAuthManager();
        $arServices = $oAuthManager->GetActiveAuthServices(array(
            "BACKURL" => "/account/",
        ));
        if (!empty($arServices)) {
            $arResult["AUTH_SERVICES"] = $arServices;
            if (isset($_REQUEST["auth_service_id"]) && $_REQUEST["auth_service_id"] <> '' && isset($arResult["AUTH_SERVICES"][$_REQUEST["auth_service_id"]])) {
                $arResult["CURRENT_SERVICE"] = $_REQUEST["auth_service_id"];
                if (isset($_REQUEST["auth_service_error"]) && $_REQUEST["auth_service_error"] <> '') {
                    $arResult['ERROR_MESSAGE'] = $oAuthManager->GetError($arResult["CURRENT_SERVICE"], $_REQUEST["auth_service_error"]);
                } elseif (!$oAuthManager->Authorize($_REQUEST["auth_service_id"])) {
                    $ex = $APPLICATION->GetException();
                    if ($ex)
                        $arResult['ERROR_MESSAGE'] = $ex->GetString();
                }
            }
        }
    }
    LocalRedirect("/");
}