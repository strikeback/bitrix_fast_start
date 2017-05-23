<?php

function setSEO($title, $description, $image, $url) {
    global $APPLICATION;
    $host = "//" . SITE_SERVER_NAME;
    $APPLICATION->SetPageProperty("og_title", strip_tags(htmlspecialchars_decode($title)));
    $APPLICATION->SetPageProperty("og_description", strip_tags(htmlspecialchars_decode($description)));
    $APPLICATION->SetPageProperty("og_image", $host . $image);
    $APPLICATION->SetPageProperty("og_url", $host . $url);
}
