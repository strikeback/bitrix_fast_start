<?

CModule::AddAutoloadClasses(
        '', // не указываем имя модуля
        array(
    // ключ - имя класса, значение - путь относительно корня сайта к файлу с классом
//    'Mobile_Detect' => '/local/php_interface/classes/mobile_detect.php',
        )
);
include_once("includes/functions.php");
include_once("includes/handlers.php");
