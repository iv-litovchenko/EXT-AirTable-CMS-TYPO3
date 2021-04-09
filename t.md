```
+ https://gist.github.com/cedricziel/7384595
+ https://www.aemka.de/news/fluid-content-inhaltselemente-ohne-extfluidcontent.html
+ https://fluidtypo3.org/library/code-examples.html?tx_fluidshare_display%5Baction%5D=display&tx_fluidshare_display%5Bcontroller%5D=Gist&tx_fluidshare_display%5Bgist%5D=1&cHash=cefb368f64b6984abcfd0934b6ff3edd

1) Остановился на разработке каталога (Пагинация , Хлеб крошки)
2) Шорткоды передать
3) Хелпер выборки контента с оберткой кнопок "Добавить сверху содержимое".
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

		
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['paths']['test_provider_extension'] = [
    'templateRootPaths' => ['EXT:test_provider_extension/Resources/Private/OverrideTemplates'],
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['atoms']['test'][] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('test_provider_extension', 'Resources/Private/Partials');


    
	
