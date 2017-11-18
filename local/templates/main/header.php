<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();
?>
<!DOCTYPE html>
<html>
    <head>
        <? $APPLICATION->ShowHead(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
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
<!--        <meta name="theme-color" content="#fff">
        <meta name="msapplication-TileColor" content="#fff">-->

        <meta property="og:image" content="<?= $APPLICATION->ShowProperty("og_image"); ?>"/>
        <meta property="og:image:url" content="<?= $APPLICATION->ShowProperty("og_image"); ?>"/>
        <meta property="og:title" content="<?= $APPLICATION->ShowProperty("og_title"); ?>"/>
        <meta property="og:description" content="<?= $APPLICATION->ShowProperty("og_description"); ?>"/>
        <meta property="og:url" content="<?= $APPLICATION->ShowProperty("og_url"); ?>"/>

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="<?= $APPLICATION->ShowProperty("og_url"); ?>" />
        <meta name="twitter:title" content="<?= $APPLICATION->ShowProperty("og_title"); ?>" />
        <meta name="twitter:description" content="<?= $APPLICATION->ShowProperty("og_description"); ?>" />
        <meta name="twitter:image:src" content="<?= $APPLICATION->ShowProperty("og_image"); ?>" />

        <link rel="image_src" href="<?= $APPLICATION->ShowProperty("og_image"); ?>" />
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