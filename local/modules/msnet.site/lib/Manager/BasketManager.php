<?php

namespace Msnet\Site\Manager;

use CSaleBasket,
    Bitrix\Sale\BasketComponentHelper as BasketComponentHelper;

\CModule::IncludeModule("sale");

class BasketManager
{
    public static function getCountBasket()
    {

        return CSaleBasket::GetList(false, array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID"))->SelectedRowsCount();
    }

    public static function getTotalPriceBasket() {
        return CurrencyFormat(BasketComponentHelper::getFUserBasketPrice(CSaleBasket::GetBasketUserID(), SITE_ID), "RUB");
    }

    public static function add($productId, $quantity)
    {
        \CModule::IncludeModule("catalog");
        $productId = intval($productId);
        $res = Add2BasketByProductID($productId, $quantity, array(), array());
        return true;
    }

    public static function clear() {
        CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
    }
}