```
https://stackoverflow.com/questions/58094445/typo3-8-7-backend-fields-for-a-custom-content-element-are-not-rendered
https://fluidtypo3.org/documentation/templating-manual/advanced-provider-extensions/custom-flux-providers/use-cases-for-providers.html
+ https://gist.github.com/cedricziel/7384595
+ https://www.aemka.de/news/fluid-content-inhaltselemente-ohne-extfluidcontent.html
+ https://fluidtypo3.org/library/code-examples.html?tx_fluidshare_display%5Baction%5D=display&tx_fluidshare_display%5Bcontroller%5D=Gist&tx_fluidshare_display%5Bgist%5D=1&cHash=cefb368f64b6984abcfd0934b6ff3edd





1) Остановился на разработке каталога (Пагинация , Хлеб крошки)
2) Секции HeaderAssets FooterAssets + посмотреть как там отправляются Assents
3) Шорткоды передать
4) Для страницы #253 не задан шаблон
5) Хелпер выборки контента с оберткой кнопок "Добавить сверху содержимое".
<f:vhsExtAirTable.content gridContainerId="{gridId}" gridColumn="1" />
<v:content.render contentUids="{0: _item.uid}"/>
<v:content.render contentUids="{0: _item.uid}"/>

   <!-- Варианты как получить контент-->
   <v:content.render column="1" /> <!-- PAGE -->
   <flux:content.render area="mycontentB" /> <!-- CONTENT -->
   
6) FAL + "_func" Отказаться от постфиксов "_func", сделать также алиас для uid_local_func as file


Зарегистрируйте  flux как плагин контента.

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FluidTYPO3.Flux',
    'Content',
    [
        'Content' => 'render, error',
    ]
);

Добавьте TS для нового CTYPE

tt_content.flux_2columns = USER
tt_content.flux_2columns {
    userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
    vendorName = FluidTYPO3
    extensionName = Flux
    pluginName = Content
}

if (TYPO3_MODE == 'FE') {
			// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSources'][] = \FluidTYPO3\Flux\Backend\TableConfigurationPostProcessor::class . '->processData';
		}
 

site_package/
site_package/Configuration
site_package/Configuration/TCA
site_package/Configuration/TCA/Overrides
site_package/Configuration/TCA/Overrides/sys_template.php
site_package/Configuration/TypoScript
site_package/Configuration/TypoScript/constants.typoscript
site_package/Configuration/TypoScript/setup.typoscript
site_package/ext_emconf.php
site_package/ext_icon.png
site_package/Resources
site_package/Resources/Private
site_package/Resources/Private/Layouts
site_package/Resources/Private/Layouts/Page
site_package/Resources/Private/Layouts/Page/Default.html
site_package/Resources/Private/Partials
site_package/Resources/Private/Partials/Page
site_package/Resources/Private/Partials/Page/Jumbotron.html
site_package/Resources/Private/Templates
site_package/Resources/Private/Templates/Page
site_package/Resources/Private/Templates/Page/Default.html
site_package/Resources/Public
site_package/Resources/Public/Css
site_package/Resources/Public/Css/website.css
site_package/Resources/Public/Images/
site_package/Resources/Public/Images/logo.png
site_package/Resources/Public/JavaScript
site_package/Resources/Public/JavaScript/website.js

		
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['paths']['test_provider_extension'] = [
    'templateRootPaths' => ['EXT:test_provider_extension/Resources/Private/OverrideTemplates'],
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['atoms']['test'][] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('test_provider_extension', 'Resources/Private/Partials');


    
	
