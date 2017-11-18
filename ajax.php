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

