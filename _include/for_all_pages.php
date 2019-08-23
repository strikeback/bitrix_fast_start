<?

use Bitrix\Main\Page\Asset;
?>
<? Asset::getInstance()->addJs('/_plugins/jquery-3.4.1.min.js'); ?>

<? $APPLICATION->SetAdditionalCSS('/_plugins/bootstrap/css/bootstrap.min.css'); ?>
<? Asset::getInstance()->addJs('/_plugins/bootstrap/js/bootstrap.min.js'); ?>

<? $APPLICATION->SetAdditionalCSS('/_plugins/slick/slick.css'); ?>
<? Asset::getInstance()->addJs('/_plugins/slick/slick.js'); ?>

<? $APPLICATION->SetAdditionalCSS('/_plugins/scrollbar/jquery.mCustomScrollbar.css'); ?> 
<? Asset::getInstance()->addJs('/_plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js'); ?>

<? Asset::getInstance()->addJs('/_plugins/mask/jquery.maskedinput.min.js'); ?>

<? $APPLICATION->SetAdditionalCSS('/_css/global.css'); ?> 
<? Asset::getInstance()->addJs('/_js/all.min.js'); ?>