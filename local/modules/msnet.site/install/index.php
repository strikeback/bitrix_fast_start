<?
class msnet_site extends CModule
{
    const MODULE_ID = 'msnet.site';

    public $MODULE_ID = 'msnet.site',
        $MODULE_VERSION,
        $MODULE_VERSION_DATE,
        $MODULE_NAME = 'Cайт',
        $PARTNER_NAME = 'msnet',
        $PARTNER_URI = 'http://ms-net.ru';

    public function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . 'version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }

    function InstallFiles($arParams = array())
    {
        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }

    public function DoInstall()
    {
        RegisterModule($this->MODULE_ID);

        $this->InstallFiles();
    }

    public function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);

        $this->UnInstallFiles();
    }
}