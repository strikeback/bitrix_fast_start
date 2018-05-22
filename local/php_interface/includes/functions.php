<?

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
  return array(
      "title" => $title,
      "description" => $description,
      "url" => $host . $url,
      "image" => $host . $image
  );
}

function getSettings() {
  CModule::IncludeModule("iblock");
  $arOrder = array();
  $arFilter = array(
      "IBLOCK_ID" => 1,
  );
  $arSelect = array();
  $db_res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
  if ($res = $db_res->GetNextElement()) {
    $arItem = $res->GetFields();
    $arItem["PROPERTIES"] = $res->GetProperties();
    $GLOBALS["SETTINGS"] = array();
    foreach ($arItem["PROPERTIES"] as $prop) {
      if ($prop["PROPERTY_TYPE"] == "F") {
        $prop["~VALUE"] = CFile::GetPath($prop["~VALUE"]);
      }
      $GLOBALS["SETTINGS"][$prop["CODE"]] = $prop["~VALUE"];
    }
  }
}

function getSettingsProp($prop) {
  global $SETTINGS;
  return $SETTINGS[$prop];
}

/**
 * Убираем лишние символы из телефона
 * @param [type] $PHONE [Номер телефона вида +7 (999) 999-99-99]
 */
function clearPhone($PHONE) {
  $patterns_replace = array(
      '#\(#',
      '#\)#',
      '#\s#',
      '# #',
      '#\-#',
  );
  $replace_arr = array(
      "",
      "",
      "",
      "",
      "",
  );
  $PHONE_CLEAR = preg_replace($patterns_replace, $replace_arr, $PHONE);
  return $PHONE_CLEAR;
}

//Получение информации о пользователе по ID
function getUserFieldsByID($ID) {
  global $USER;
  $rsUser = CUser::GetById($ID);
  $arUser = $rsUser->Fetch();
  $arUser["ARRGROUPS"] = $USER->GetUserGroupArray();
  return $arUser;
}

function dateFormat($date) {
  $event_date = MakeTimeStamp($date);
  $month = FormatDate("F", $event_date);
  return date("d", $event_date) . " " . $month . " " . date("Y", $event_date);
}
