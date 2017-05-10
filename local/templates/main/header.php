<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();
?>
<!DOCTYPE html>
<html>
    <head>
        <? $APPLICATION->ShowHead(); ?>
        <meta name="viewport" content="width=1100">
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.png"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><? $APPLICATION->ShowTitle(); ?></title>
        <? CUtil::InitJSCore(array('jquery', 'window', 'popup', 'ajax', 'date')); ?> 
        <?
        $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => "/_include/for_all_pages.php",
            "AREA_FILE_SUFFIX" => "inc",
            "AREA_FILE_RECURSIVE" => "N",
            "EDIT_TEMPLATE" => ""
                )
        );
        ?>
    </head>
    <body>
        <div id="panel">
            <? $APPLICATION->ShowPanel(); ?>
        </div>
        <div class="wrapper">
            <?
            $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/_include/header.php",
                "AREA_FILE_SUFFIX" => "inc",
                "AREA_FILE_RECURSIVE" => "N",
                "EDIT_TEMPLATE" => ""
                    )
            );
            ?>