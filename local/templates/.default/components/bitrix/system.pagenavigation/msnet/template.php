<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$pageNomer = $arResult["NavPageNomer"];
$navPageCount = $arResult["NavPageCount"];
$navNum = $arResult["NavNum"];
$nextPage = $pageNomer + 1;
$size = $arResult["NavPageSize"];
$left = $arResult["NavRecordCount"] - $pageNomer * $size;
$moreCount = $left <= $size ? $left : $size;
if ($nextPage > $navPageCount) {
    return false;
}
?>
<a href=""
   data-navnum="<?= $navNum ?>" 
   data-nextpage="<?= $nextPage ?>"
   class="rw__loadmore js-ajax-more"
   >Показать отзывы (еще <?= $moreCount ?> из <?= $left ?>)</a>