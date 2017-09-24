<?php

namespace Msnet\Site\Manager;
use CIBlockElement;

class ReviewsManager {

    const IBLOCK_ID = IBLOCK_REVIEWS;
    const QUANTITY_STARS = 5;

    public static function getByProductId($productId) {

        $arResult = false;

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "PROPERTY_RATING", "PROPERTY_ADVANTAGES", "PROPERTY_SHORTCOMINGS");
        $arFilter = Array("IBLOCK_ID"=>self::IBLOCK_ID, "ACTIVE"=>"Y", 'PROPERTY_PRODUCT_ID' => $productId);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($arFields = $res->GetNext()){
            $arResult[] = [
                "NAME" => $arFields['NAME'],
                "TEXT" => $arFields['PREVIEW_TEXT'],
                "ADVANTAGES" => $arFields['PROPERTY_ADVANTAGES_VALUE'],
                "SHORTCOMINGS" => $arFields['PROPERTY_SHORTCOMINGS_VALUE'],
                "RATING" => $arFields['PROPERTY_RATING_VALUE'],
            ];
        }

        return $arResult;
    }

    public static function getRating($productId) {

        $arReviews = self::getByProductId($productId);

        if(!$arReviews) {
            return $arResult = ["COUNT" => 0, "AVERAGE" => 0, "STARS_HTML" => self::getStarsHtml(0)];
        }

        $count = count($arReviews);
        $average = round(array_sum(array_column($arReviews, "RATING")) / $count);

        $arResult = [
            "COUNT" => $count,
            "AVERAGE" => $average,
            "STARS_HTML" => self::getStarsHtml($average)
        ];

        return $arResult;
    }

    public static function getStarsHtml($count) {

        return str_repeat('<i class="fill"></i>', $count).str_repeat('<i class="empty"></i>' , self::QUANTITY_STARS - $count);

    }

    public static function add($arLoadArray) {

        $el = new CIBlockElement;

        $arLoadProductArray['IBLOCK_ID'] = self::IBLOCK_ID;

        if($PRODUCT_ID = $el->Add($arLoadArray)) {
            return $PRODUCT_ID;
        } else {
            return false;
        }
    }
}

