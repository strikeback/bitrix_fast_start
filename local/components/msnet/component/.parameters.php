<?

CModule::IncludeModule("iblock");


$c = new CComponentUtil();
$resArrTemplates = $c->GetTemplatesList($componentName);
$arrTemplates = array();
foreach ($resArrTemplates as $item) {
    $arrTemplates[$item["NAME"]] = $item["NAME"];
}

$arComponentParameters = array(
     //"GROUPS" => array(
      //  "SETTINGS" => array(
      //      "NAME" => GetMessage("SETTINGS_PHR")
       // ),
        //"BUTTON" => array(
         //   "NAME" => "Параметры для кнопки (если используется шаблон кнопки)"
        //),
    //),
   //    "PARAMETERS" => array(
//        "BUTTON_NAME" => array(
//            "PARENT" => "BUTTON",
//            "NAME" => "Текст кнопки",
//            "TYPE" => "STRING",
//        ),
//        "BUTTON_CLASSES" => array(
//            "PARENT" => "BUTTON",
//            "NAME" => "Дополнительные классы для кнопки",
//            "TYPE" => "STRING",
//        ),
//        "BUTTON_ATTRIBUTES" => array(
//            "PARENT" => "BUTTON",
//            "NAME" => "Дополнительные аттрибуты (data-attr)",
//            "TYPE" => "STRING",
//            "MULTIPLE" => "Y",
//        ),
//        "FORM_TEMPLATE" => array(
//            "PARENT" => "BUTTON",
//            "NAME" => "Шаблон модального окна",
//            "TYPE" => "LIST",
//            "VALUES" => $arrTemplates,
//        ),
//        "FORM_TITLE" => array(
//            "PARENT" => "BUTTON",
//            "NAME" => "Заголовок формы",
//            "TYPE" => "STRING",
//        ),
//    )
);
?>