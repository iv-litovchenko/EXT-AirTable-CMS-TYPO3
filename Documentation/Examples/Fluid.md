# Fluid-шаблонизатор (примеры)
```html
******************************************
* Отправка данных в секцию "head" (assets)
******************************************

<f:section name="HeaderAssets">
    <!-- <head> content </head> -->
</f:section>

<f:section name="Main">
	<v:page.header name="defaultHeader">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</v:page.header>
	<v:page.header.meta name="keywords" content="{page.keywords}" />
	<v:page.header.meta name="description" content="{page.description}" />
	<v:page.header.meta name="og:title" content="{page.title}" />
	<v:page.header.meta name="og:type" content="article" />
	<v:page.header.meta name="og:url" content="{v:page.absoluteUrl()}" />
	<v:page.header.meta name="og:description" content="{page.description}" />
	<v:page.header.meta name="apple-mobile-web-app-capable" content="yes" />
	<v:asset.style path="{f:uri.resource(path: 'CSS/style.css')}" group="fluidcontentyoutube" name="style" />
	<v:asset.style path="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" name="bootstrap-css" external="1" />
	<v:asset.style path="EXT:rico_provider/Resources/Public/Fonts/font-awesome-4.7.0/css/font-awesome.css" name="font-awesome" />
	<v:asset.style path="EXT:rico_provider_oxid/Resources/Public/Css/custom.css" name="custom-css" />
	<v:asset.script standalone="1" external="1" path="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" name="jquery" />
	<v:asset.script standalone="0" dependencies="jquery" name="searchbox-js">
	$(function() {
		$(window).scroll(function() { });
	});	
	</v:asset.script>
</f:section>

<f:section name="FooterAssets">
    <!-- content </body> -->
</f:section>

******************************************
* Условия
******************************************

<f:if condition="{v:page.info(field: 'uid')} == '21'">
   <f:then>
      Shows only if page ID equals 21.
   </f:then>
</f:if>

<f:if condition="{var} > 100">
   <f:then> Overflow! </f:then>
   <f:else if="{var} > 90"> Danger! </f:else>
   <f:else if="{var} > 50"> Careful now. </f:else>
   <f:else> All good! </f:else>
</f:if>

{f:if(condition: file.properties.title, then: file.properties.title, else: file.properties.name)}
{f:if(condition: item.current, then: ' active')}

******************************************
* Конструкции выбора
******************************************

<f:switch expression="{person.gender}">
   <f:case value="male">Mr.</f:case>
   <f:case value="female">Mrs.</f:case>
   <f:defaultCase>Mr. / Mrs.</f:defaultCase>
</f:switch>

******************************************
* Циклы
******************************************

<f:for each="{rows}" as="row" key="itemkey">
   <a href="<f:uri.image src='{row.file.uid}' />">
      {itemkey+1}.
      <f:image src="{row.file.uid}" alt="alt text" width="100" />
      <br />
   </a>
</f:for>

******************************************
* Комментарий
******************************************

<f:comment>
   ---
</f:comment>

******************************************
* Включения
******************************************

<f:render partial="Pages/Missing.html" optional="1" default="Partial 1 not found" />
<f:render partial="Pages/AlsoMissing.html" optional="1">
   Partial 2 not found
</f:render>

******************************************
* Ссылки
******************************************

<!-- Backend module-->
<f:be.link route="web_ts" parameters="{id: 92}">Go to module</f:be.link>

<!-- Page -->
<f:link.page pageUid="123" additionalParams="{foo:bar}">Klick me!</f:link.page>

<!-- Action -->
<f:link.action action="index" arguments="{page:1}">-- Link --</f:link.action>
<f:link.action action="edit" class="btn btn-default btn-sm">-- Link --</f:link.action>

<!-- 
   Tested only in TYPO3 v10!
   Switching to another controller is not easy!
   Route name: Ext.[***extension***].[***subfolder***].[***controller***].[***action***]
   Имя роутера - это pageId.controller.action. urlConverter преобразует в ЧПУ (как со страницами,
   что бы сделать ссылку, мы ищем ID-страницы, а не маршрут. Здесь же ищем контроллер и действие.
   // Todo: RouterList (продумать вывод списка маршрутов для отдалки)
-->
<f:link.action pageUid="1 || self" action="Ext.Myext.Pages.Default.index"> --TEXT-- </f:link.action>
<f:link.action pageUid="1 || self" action="Ext.Myext.Plugins.Default.travels"> --TEXT-- </f:link.action>
<f:link.action action="Ext.AirTable.Modules.Import.step2">444 Go to Module</f:link.action><br />
<f:uri.action action="Ext.Myext.Pages.Default.travelView" arguments="{uid:5}" />

******************************************
* Изображения, файлы
******************************************

<f:uri.image src="{row.propmedia_thumbnail.file.uid}" />
<a href="{f:uri.image(src:image.file.uid)}" data-fancybox="gallery"> </a>
<img src="{f:uri.image(src:image.file.uid, width:272, height:'300c')}">

******************************************
* Формы
******************************************

<f:form.checkboxproperty="cms" multiple="1" value="TYPO3" />
<f:form.checkboxproperty="cms" multiple="1" value="Word Press" />
<f:form.checkboxproperty="cms" multiple="1" value="Drupal" />

******************************************
* Другое
******************************************

<f:format.html parseFuncTSPath="lib.parseFunc">{bodytext}</f:format.html>
<f:format.date format="d.m.Y H:i">1265798455</f:format.date>
<f:variablename="myvariable">My variable's content</f:variable>
<f:variablename="myvariable" value="My variable's content"/>
{f:variable(name: 'myvariable', value: 'My variable\'s content')}
{myoriginalvariable -> f:variable.set(name: 'mynewvariable')}

******************************************
* Маркеры (статические блоки)
******************************************

<!-- Input, Text, Text.Rte, Text.Code.Html, Text.Code.TypoScript -->
<f:vhsExtAirTable.marker uid="3" />

<!-- Media_1, Media_M -->
<f:vhsExtAirTable.markerMedia uid="45" as="row || rows">
  <f:for each="{rows}" as="row" key="itemkey">
    <a href="<f:uri.image src='{row.file.uid}' />">
      {itemkey+1}.<f:image src="{row.file.uid}" alt="alt text" width="100" /><br />
    </a>
  </f:for>
</f:vhsExtAirTable.markerMedia>

******************************************
* Кнопки и панели для редактирования
******************************************

<f:vhsExtAirTable.adminPanel isFooter="0 || 1" />
<f:vhsExtAirTable.adminPanelTools />

<f:vhsExtAirTable.adminInfobox title="Infobox" type="warning || info || error"> 
  --- site admin content  ---
</f:vhsExtAirTable.adminInfobox>
<f:vhsExtAirTable.editWrap> 
  --- site content ---
</f:vhsExtAirTable.editWrap>

<f:vhsExtAirTable.editIcon model="Litovchenko\AirTable\Domain\Model\Content\Pages" recordId="100" title="Edit" />
<f:vhsExtAirTable.editIconInline model="Pages || TtContent || Data || Model" recordId="100" title="Edit" />
<f:vhsExtAirTable.editIconCenter model="Pages || TtContent || Data || Model" recordId="100" title="Edit" />
<f:vhsExtAirTable.editIconAbs model="Pages || TtContent || Data || Model" recordId="100" title="Edit" />

<f:vhsExtAirTable.newIcon model="Litovchenko\AirTable\Domain\Model\Content\Pages" pid="200" title="New" />
<f:vhsExtAirTable.newIconInline model="Pages || TtContent || Data || Model" pid="200" title="New" />
<f:vhsExtAirTable.newIconCenter model="Pages || TtContent || Data || Model" pid="200" title="New" />
<f:vhsExtAirTable.newIconAbs model="Pages || TtContent || Data || Model" pid="200" title="New" />

<!--editIcon & newIcon options-->
<f:vhsExtAirTable.editIcon ...
  defaultFieldsForNewRecord="{title:'New record',nav_title:'New record'}"
  copyFieldsForNewRecord="header,CType"
  editFieldsOnly="header,CType"
  hideNewIcon="1"
  hideDisableIcon="1"
  hideDeletedIcon="1"
  hideBufferIcon="1"
  allowedIcons="Edit,New,Deleted,Disabled" <!--todo-->
  styleLeft="10" <!--only for newIconAbs-->
  styleTop="10" <!--only for newIconAbs-->
  styleRight="10" <!--only for newIconAbs-->
  styleBottom="10" <!--only for newIconAbs-->
/>

<f:vhsExtAirTable.editIconsForMenu uidPattern="elem_*" styleDirection="left">
  <v:page.menu expandAll="0" levels="2" substElementUid="1" /> <!-- <li id="elem_id(page id)"><!-- INSERT EDIT ICON--><a href=""></a></li> -->
</f:vhsExtAirTable.editIconsForMenu>
```
