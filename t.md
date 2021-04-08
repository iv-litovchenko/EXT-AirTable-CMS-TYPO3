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

-----------------
<f:section name="HeaderAssets">
  <title>Super title from fluid</title>
  fewfefewfewfew
  fewfefewfewfewefew
  
   <v:page.header.meta name="keywords" content="{page.keywords}" />
 <v:page.header.meta name="description" content="{page.description}" />
 <v:page.header.meta name="og:title" content="{page.title}" />
 <v:page.header.meta name="og:type" content="article" />
 <v:page.header.meta name="og:url" content="{v:page.absoluteUrl()}" />
 <v:page.header.meta name="og:description" content="{page.description}" />
 <v:page.header.meta name="apple-mobile-web-app-capable" content="yes" />

	< v:asset.style path="{f:uri.resource(path: 'CSS/style.css')}" group="fluidcontentyoutube" name="style" />
 
 < f:cObject typoscriptObjectPath="lib.default_menu" />
	< f:cObject typoscriptObjectPath="lib.default_content" />
	
	<p>{value}</p>
	<p>< f:link.action action="standard" arguments="{value: 'A value'}">Pass value to controller< /f:link.action></p>
	
</f:section>


<f:render partial="Page.Header" arguments="{_all}" />
	
	<f:vhsExtAirTable.content colPos="0" />

<f:render partial="Page.Footer" arguments="{_all}" />


<f:section name="FooterAssets">
  <title>Super title from fluid</title>
  fewfefewfewfew
  fewfefewfewfewefew
</f:section>


<f:section name="HeaderAssets">
    <!-- zusätzliche Inhalte im <head> -->
</f:section>
 
<f:section name="FooterAssets">
    <!-- zusätzliche Inhalte vor </body> -->
</f:section>

------------------


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

AbstractFluxController
Prev

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




<v:asset.style path="{f:uri.resource(path: 'CSS/style.css')}" group="fluidcontentyoutube" name="style" />

    <v:page.header name="defaultHeader">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </v:page.header>
    <v:asset.style path="EXT:rico_provider/Resources/Public/Fonts/font-awesome-4.7.0/css/font-awesome.css" name="font-awesome" />
    <v:asset.style external="1" path="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" name="bootstrap-css" />
    <v:asset.style name="custom-css" path="EXT:rico_provider_oxid/Resources/Public/Css/custom.css" />
    <v:asset.script standalone="1" external="1" path="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" name="jquery" />
    <v:asset.script standalone="0" dependencies="jquery" name="searchbox-js">
        jQuery(document).ready(function($) {
            $('.clearme').click(function() {
                $('.ke_search_sword').val('').change();
            });
        });
    </v:asset.script>
    <v:asset.script dependencies="jquery" name="stickyheader-js">
        $(function() {
        //caches a jQuery object containing the header element
            var header = $("header.header");
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();
                if (scroll >= 100) {
                    header.addClass("sticky");
                } else {
                    header.removeClass("sticky");
                }
            });
        });
    </v:asset.script>
    <v:asset.script name="cookie-notice" dependencies="jquery">
        if(document.cookie.indexOf('hideCookieNotice=1') != -1){
            jQuery('#cookieNotice').hide();
        }
        else{
            jQuery('#cookieNotice .close').show();
        }
    </v:asset.script>
	
	
