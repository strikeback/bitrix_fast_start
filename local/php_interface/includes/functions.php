<?php

/**
 * svg - инклудит минифицированный svg
 * @param string $p путь к svg относительно корня сайта
 * @return string
 */
function svg($p) {
    $p = $_SERVER['DOCUMENT_ROOT'] . $p;
    return file_exists($p) ? preg_replace('/<[?|!].*?>/', '', str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', file_get_contents($p))) : '';
}

function removeHttp($url) {
    $url = preg_replace("#(http|https):\/\/#", "", $url);
    if ($url[strlen($url) - 1] == "/") {
        $url[strlen($url) - 1] = "";
    }
    return $url;
}

function formatPrice($val) {
    return number_format($val, 0, '.', ' ');
}

// возвращает склонение от числа: declination(10, 'день', 'дня', 'дней')
function declination($num, $one, $ed, $mn, $full = true) {
    if ((int) $num == 0)
        return '';
    if ($full)
        if (($num == "0") or ( ($num >= "5") and ( $num <= "20")) or preg_match("|[056789]$|", $num))
            return $mn;
    if (preg_match("|[1]$|", $num))
        return $one;
    if (preg_match("|[234]$|", $num))
        return $ed;
    return '';
}

function setSEO($title, $description, $image, $url) {
    global $APPLICATION;
    $host = "//" . SITE_SERVER_NAME;
    $APPLICATION->SetPageProperty("og_title", strip_tags(htmlspecialchars_decode($title)));
    $APPLICATION->SetPageProperty("og_description", strip_tags(htmlspecialchars_decode($description)));
    $APPLICATION->SetPageProperty("og_image", $host . $image);
    $APPLICATION->SetPageProperty("og_url", $host . $url);
}
