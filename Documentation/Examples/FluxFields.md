# Flux fields (section name="Configuration")
```html
<f:layout name="Default" />

<f:section name="Configuration">

   <flux:form id="mycontentelement" label="My Content Element" description=" -- -- "  extensionName="Vendor.Extension">

      <!-- Настройки формы -->	
      <flux:form.option name="static" value="0" /> <!--1 когда форма польностью статичкаа и будет работать при кэшировании-->
      <flux:form.option.icon value="EXT:myext/Resources/Public/Icons/Module-Icon-Backup.png" />
      <flux:form.option.group value="special" /> <!-- Wizard group -->
      <flux:form.option.sorting value="1" /> <!-- Wizard sort ??? -->

      <!-- Вкладки -->		
      <flux:form.sheet name="options2" label="Twe">
         <!-- Поля -->
      </flux:form.sheet>

      <!-- Поля -->
      <flux:field type="input" exclude="0" config="{size: 3, eval: 'trim, int', default: 1}" /><!-- Можно конфигурацию передавать так-->
      <flux:field.DateTime name="DateTime" label="DateTime" required="0" clear="1" />
      <flux:field.input name="url" required="0" />
      <flux:field.text name="text" label="text" required="0" clear="1" />
      <flux:field.none name="none" label="none" required="0" clear="1" />
      <flux:field.checkbox name="settings.checkbox" label="checkbox" default="0" />
      <flux:field.select name="settings.select" label="select" items="left,right" default="left" emptyOption="2"/>
      <flux:field.radio name="settings.radio" label="radio" items="left,right" default="left" emptyOption="2"/>
      <flux:field.file name="file" label="file" useFalRelation="1" />
      <flux:field.inline.fal name="inline.fal" label="inline.fal" collapseAll="1" expandSingle="1" allowedExtensions="jpg,jpeg,png,svg" />
      <flux:field.inline name="inline" table="tt_content" />
      <flux:field.relation name="relation" table="tt_content" />
      <flux:field.MultiRelation name="MultiRelation" table="tt_content" />
      <flux:field.tree.category name="tree.category" label="tree.category" showThumbs="0" expandAll="1" />
      <flux:field.custom name="custom" label="" requestUpdate="1" userFunc="FluidTYPO3\\Flux\\UserFunction\\HtmlOutput->renderField}" />
      <flux:field.custom displayCond="REC:NEW:true" name="custom"> <!-- displayCond="FIELD:parentRec.uid:>:1" -->
         <div class="alert alert-info" role="alert">
            <h2>Hellow Word.</h2>
            <p>--- TEXT ---</p>
         </div>
      </flux:field.custom>
      <flux:field.userFunc name="" label="" extensionName="" userFunc="" />
      <flux:field.controllerActions name="" label="" extensionName="" controllerExtensionName="" pluginName="" controllerName="" actions="{foo: 'bar'}" />

      <!-- Поддержка исключена!!! -->
      <flux:form.container name="settings.name" label="Name">
         <!-- Поля -->
      </flux:form.container>

      <!-- Секции -->
      <flux:form.section name="settings.sectionObjectAsClass2" label="Telephone numbers 2">
         <flux:form.object name="custom">
            <!-- Поля -->
         </flux:form.object>
         <flux:form.object name="mobile" label="Mobile">
            <!-- Поля -->
         </flux:form.object>
         <flux:form.object name="landline" label="Landline">
            <!-- Поля -->
         </flux:form.object>
      </flux:form.section>
	  
      <!-- Примеры табов/аккордионов (бесконечное количество секций) -->
      <flux:form.sheet name="tabs">
         <flux:form.section name="tabs">
            <flux:form.object name="tab">
               <flux:field.input name="title" />
               <flux:field.input name="class" />
               <flux:field.checkbox name="active" />
            </flux:form.object>
         </flux:form.section>
      </flux:form.sheet>
      <f:if condition="{tabs}">
         <f:for each="{tabs}" as="tab" iteration="iteration">
            <flux:form.content name="content.{iteration.index}" label="Tab {iteration.cycle}" />
         </f:for>
      </f:if>
	  
   </flux:form>
   
   <!-- 1 Строчная сетка (использовать либо это, либо <flux:grid>) -->
   <flux:form.content name="content.{iteration.index}" label="Tab {iteration.cycle}" />
   <flux:form.content name="mycontent.1" label="mycontent1" />
   <flux:form.content name="mycontent.2" label="mycontent2" />
   <flux:form.content name="mycontent.3" label="mycontent3" />
   
   <!-- Произвольные сетки -->
   <flux:grid>
      <flux:grid.row>
         <flux:grid.column name="mycontentA" label="mycontentA" colPos="0">
            <flux:form.variable name="allowedContentTypes" value="textmedia"/>
            <flux:form.variable name="Fluidcontent" value="{allowedContentTypes: 'Vendor.ExtensionName:HeroImage.html'}" />
         </flux:grid.column>
         <flux:grid.column name="mycontentB" label="mycontentB" colPos="1" />
      </flux:grid.row>
      <flux:grid.row>
         <flux:grid.column name="mycontentC" label="mycontentC" colPos="2" colspan="2" rowspan="1" style="width: 300px; height: 300px;" />
      </flux:grid.row>
   </flux:grid>

   <!-- Не задокументированное (дополнительные варианты создания колонок) -->
   <flux:form.section name="columns" gridMode="rows || cols">
      <flux:form.object name="column" label="Column" contentContainer="1" />
   </flux:form.section>
   <flux:form.section name="columns">
      <flux:form.object name="column" label="Column">
         <flux:form.object.columnPosition />
      </flux:form.object>
   </flux:form.section>

</f:section>

<f:section name="Preview">

   <f:debug title="Debug" inline="true">{_all}</f:debug>
   <p>YouTube: {url}</p>

</f:section>

<f:section name="Main">

   <!-- Варианты как получить контент-->
   <v:content.render column="1" /> <!-- PAGE -->
   <flux:content.render area="mycontentB" /> <!-- CONTENT -->

   <!-- Примеры табов/аккордионов -->
   <div class="flux grid01Tabs">
      <f:render section="Tabs" arguments="{_all}" />
      <div class="tabs-content" data-tabs-content="tabs-{record.uid}">
         <f:if condition="{tabs}">
            <f:for each="{tabs}" as="tab" iteration="iteration">
               <div class="tabs-panel {f:if(condition: '{tab.tab.active} == 1', then: 'is-active')}" id="panel-{record.uid}-{iteration.index}">
                  <flux:content.render area="content.{iteration.index}" />
               </div>
            </f:for>
         </f:if>
      </div>
   </div>
   <!-- / tabWrap -->

</f:section>

<f:section name="Tabs">
   <f:if condition="{tabs}">
      <ul class="tabs" data-tabs id="tabs-{record.uid}">
         <f:for each="{tabs}" as="tab" iteration="iteration">
            <li class="tabs-title {f:if(condition: '{tab.tab.active} == 1', then: 'is-active')}">
               <a href="#panel-{record.uid}-{iteration.index}" aria-selected="true">{tab.tab.title}</a>
            </li>
         </f:for>
      </ul>
   </f:if>
</f:section>
```
