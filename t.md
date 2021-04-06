```
1) Остановился на разработке каталога (Пагинация , Хлеб крошки)
2) Секции HeaderAssets FooterAssets + посмотреть как там отправляются Assents
3) Шорткоды передать
4) Для страницы #253 не задан шаблон
5) FAL + "_func" Отказаться от постфиксов "_func", сделать также алиас для uid_local_func as file
  * https://laravel.com/docs/8.x/eloquent-mutators 
  * protected function defineEntity(ClassDefinition $class) { $class->property($this->engine)->asObject(Engine::class); }



-----------------
<f:section name="HeaderAssets">
  <title>Super title from fluid</title>
  fewfefewfewfew
  fewfefewfewfewefew
</f:section>


<f:render partial="Page.Header" arguments="{_all}" />
	
	<f:vhsExtAirTable.content colPos="0" />

<f:render partial="Page.Footer" arguments="{_all}" />


<f:section name="FooterAssets">
  <title>Super title from fluid</title>
  fewfefewfewfew
  fewfefewfewfewefew
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



≡
Form

    ≡
    
    Container
    Content
    Data
    Render
    Variable 
	

<flux:*>


flux:grid

<flux:grid />

flux:outlet

<flux:outlet${1: enabled="1"} />

flux:variable

<flux:variable name="$1" />

<flux:content.*>
flux:content.get

<flux:content.get area="$1"${3: limit="$2"}${5: offset="$4"}${7: order="'${6:sorting}'"}${9: sortDirection="'${8:ASC}'"}${11: as="$10"}${13: loadRegister="$12"}${15: render="${14:1}"} />

flux:content.render

<flux:content.render area="$1"${3: limit="$2"}${5: offset="$4"}${7: order="'${6:sorting}'"}${9: sortDirection="'${8:ASC}'"}${11: as="$10"}${13: loadRegister="$12"}${15: render="${14:1}"} />





flux:form.container

<flux:form.container name="$1" label="$2" />

flux:form.content

<flux:form.content name="$1" label="$2" />

flux:form.data

<flux:form.data table="$1" field="$2" uid="$3" record="${4:{foo: 'bar'}}" as="$5" />




flux:form.render

<flux:form.render form="$1" />




flux:form.variable

<flux:form.variable name="$1" value="$2" />

<flux:grid.*>
flux:grid.column

<flux:grid.column name="$1" label="$2" colPos="$3"${5: colspan="$4"}${7: rowspan="$6"}${9: style="$8"} />

flux:grid.row

<flux:grid.row name="$1" label="$2" />

<flux:outlet.*>
flux:outlet.argument

<flux:outlet.argument name="$1" type="$2" />

flux:outlet.form

<flux:outlet.form name="$1" action="$2" controller="$3" extensionName="$4" pluginName="$5" pageUid="$6" />

flux:outlet.validate

<flux:outlet.validate type="$1"${3: options="$2"} />

<flux:pipe.*>
flux:pipe.controller

<flux:pipe.controller direction="$1" action="$2" controller="$3" extensionName="$4" />

flux:pipe.email

<flux:pipe.email direction="$1" body="$2" bodySection="$3" subject="$4" recipient="$5" sender="$6" />

flux:pipe.flashMessage

<flux:pipe.flashMessage direction="$1" message="$2" title="'$3'" severity="$4"${5: storeInSession="1"} />

flux:pipe.typeConverter

<flux:pipe.typeConverter direction="$1" targetType="$2" typeConverter="$3" />




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
	
	
