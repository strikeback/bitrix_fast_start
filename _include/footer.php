<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
    "AREA_FILE_SHOW" => "file",
    "PATH" => "/_include/metrics.php",
    "AREA_FILE_SUFFIX" => "inc",
    "AREA_FILE_RECURSIVE" => "N",
    "EDIT_TEMPLATE" => ""
        )
);
?>

<a href="javascript:void(0);" class="go_top js-go_top hidden_bt"></a>