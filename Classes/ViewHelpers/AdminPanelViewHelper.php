<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use Litovchenko\AirTable\Utility\BaseUtility;

class AdminPanelViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'AdminPanel',
		'registerArguments' => [
			'isFooter' => ['integer',0]
		]
	];
	
    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;
	
    public function render()
    {
		if($GLOBALS['BE_USER']->user['uid'] > 0) {
			if($this->arguments['isFooter'] == 1){
				return "<!--@@@ADMINPANEL-FOOTER@@@-->";
			} else {
				return "<!--@@@ADMINPANEL@@@-->";
			}
		} else {
			return '';
		}
    }
	
	public static function processOutput($addToFooter = false)
	{
		// CSS color editIcon
		if ($GLOBALS['BE_USER']->uc['phptemplate_editIcon_color'] <= 1) {
			$cssStyledColor = 1; // black
		} else {
			$cssStyledColor = 2; // white
		}

		$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
		
		$content = "";
		$content .= "<!--TYPO3_NOT_SEARCH_begin-->";
		$content .= "<link rel='stylesheet' type='text/css' href='".$srcAdmPath.'adminPanel.css'."' media='all'>";
		$content .= "<link rel='stylesheet' type='text/css' href='".$srcAdmPath.'Block/block-'.$cssStyledColor.'.css'."' media='all'>";
		$content .= "<link rel='stylesheet' type='text/css' href='".$srcAdmPath.'blockAlert-'.$cssStyledColor.'.css'."' media='all'>";
		$content .= "<link rel='stylesheet' type='text/css' href='".$srcAdmPath.'editIconColor-'.$cssStyledColor.'.css'."' media='all'>";
		$content .= "<script src='".$srcAdmPath.'adminPanel.js?'.time()."' type='text/javascript'></script>";
		
		// Url
		#$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
		#	'routeExtAirTable.Empty.Index',
		#	array(
		#		// 'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
		#	)
		#);
		
		// ".$srcAdmPath."adminPanelIframe.html
		if($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
			
			$content .= "<tagAdminPanelBody class='tagAdminPanelBody' id='tagAdminPanelBody'>";
			$content .= "</tagAdminPanelBody>";
			$content .= "<tagAdminPanelIframeWrap class='tagAdminPanelIframeWrap' id='tagAdminPanelIframeWrap'>";
			$content .= "<tagAdminPanelIframeTitle class='tagAdminPanelIframeTitle' id='tagAdminPanelIframeTitle'>";
			$content .= "---";
			$content .= "</tagAdminPanelIframeTitle>";
			$content .= "<tagAdminPanelIframeClose class='tagAdminPanelIframeClose' id='tagAdminPanelIframeClose' onclick='adminPanelPopupClose(); return false;'>";
			$content .= 'Закрыть [X]';
			$content .= "</tagAdminPanelIframeClose>";
			$content .= "<iframe id='tagAdminPanelIframe' class='tagAdminPanelIframe' src='".$srcAdmPath."adminPanelIframe.html' crossorigin='anonymous'></iframe>";
			$content .= "</tagAdminPanelIframeWrap>";
		}
		
		if($GLOBALS['BE_USER']->uc['phptemplate_checkOption'] == 1){
			$content .= "<tagAdminPanelWrap id='tagAdminPanelWrap' style='opacity: 0.5;'>";
		} else {
			$content .= "<tagAdminPanelWrap id='tagAdminPanelWrap'>";
		}
		
		if(BaseUtility::isPageVp() == true){
			$cssVpClass = 'virtual_page';
		}
		$content .= "<tagAdminPanelContainer class='phptemplate_tagAdminPanelContainer'></tagAdminPanelContainer>";
		$content .= "<tagAdminPanel class='".(($addToFooter == 1) ? 'phptemplate_tagAdminPanel_addedToFooter' : 'phptemplate_tagAdminPanel')." ".$cssVpClass."'>";
		
		// Left container - TYPO3 Logo
		$content .= "<tagAdminPanel_leftContainer class='phptemplate_leftContainer'>";
		$content .= "<a href='/'><img src='".$srcAdmPath."typo3logo_mini.png' width='80'></a>";
		$content .= "</tagAdminPanel_leftContainer>";

		// Right container - Buttons
		$content .= "<tagAdminPanel_rightContainer class='phptemplate_rightContainer'>";
		
			// Button "Exit"
			$content .= self::button(
				'phptemplate_plugin_adminPanel_buttonLogout', 
				'phptemplate_plugin_adminPanel_buttonLogout',
				'',
				'adminPanel_Exit.png', 
				'Выход',
				' if (confirm (\'Продолжить?\')) { document.forms[\'phptemplate_plugin_adminPanel_buttonLogout\'].submit(); } '
			);
		
			// Button "Go to Backend"
			$content .= self::button(
				'phptemplate_plugin_adminPanel_buttonBackend', 
				'phptemplate_plugin_adminPanel_buttonBackend',
				'',
				'adminPanel_Backend.png', 
				'Админка',
				' window.location.href=\'/typo3/\' ',
				'border-radius: 0 3px 3px 0;'
			);
			
			// Button "Event"
			#$content .= self::button(
			#	'', 
			#	'',
			#	'',
			#	'adminPanel_bell.png', 
			#	'10+',
			#	' ',
			#	' border-radius: 0 3px 3px 0; background: #f44e66 !important; color: white; ' // #cc0001
			#);
			
			// Button "Options"
			$optionUcValue = $GLOBALS['BE_USER']->uc['phptemplate_checkOption'];
			if ($optionUcValue == 1) {
				$optionValue = -1;
				$optionValueBgColor = "background: #f3f3f3 !important;";
			} else {
				$optionValue = 1;
				$optionValueBgColor = "";
			}
			$content .= self::button(
				'phptemplate_checkOption', // form id
				'phptemplate_checkOption', // form value
				$optionValue,
				'adminPanel_Options.png', // icon
				'', // label
				' document.forms[\'phptemplate_checkOption\'].submit(); ', // event
				'border-radius: 0 3px 3px 0;' . $optionValueBgColor // css style
			);

			// Button "No optimiz image"
			# $optionNoResizeImageValue = $GLOBALS['BE_USER']->uc['phptemplate_noResizeImage'];
			# if ($optionNoResizeImageValue == 1) {
			# 	$optionValue = -1;
			# 	$optionValueBgColor = "background: #f3f3f3 !important;";
			# } else {
			# 	$optionValue = 1;
			# 	$optionValueBgColor = "";
			# }
			# $content .= self::button(
			# 	'phptemplate_noResizeImage', // form id
			# 	'phptemplate_noResizeImage', // form value
			# 	$optionValue,
			# 	'adminPanel_Images.png', // icon
			# 	'', // label
			# 	' document.forms[\'phptemplate_noResizeImage\'].submit(); ', // event
			# 	$optionValueBgColor // css style
			# );
			
			// Button "Change color"
			$colorEditButton = $GLOBALS['BE_USER']->uc['phptemplate_editIcon_color'];
			if ($colorEditButton <= 1) {
				$optionValue = 2;
				$optionValueIcon = 1;
			} else {
				$optionValue = 1;
				$optionValueIcon = 2;
			}
			$content .= self::button(
				'phptemplate_editIcon_color', // form id
				'phptemplate_editIcon_color', // form value
				$optionValue, // form value 2
				'adminPanel_select_by_color_'.$optionValueIcon.'.png', // icon
				'', // label
				' document.forms[\'phptemplate_editIcon_color\'].submit(); ', // event
				'margin-right: 0; border-radius: 0; border-right: 0;' // css style
			);
			
			// Button "Image resize (on/off)"
			$optionUcValue = $GLOBALS['BE_USER']->uc['phptemplate_imagesResizeDisable'];
			if ($optionUcValue == 1) {
				$optionValue = -1;
				$optionValueBgColor = "background: #f3f3f3 !important;";
			} else {
				$optionValue = 1;
				$optionValueBgColor = "";
			}
			$content .= self::button(
				'phptemplate_imagesResizeDisable', // form id
				'phptemplate_imagesResizeDisable', // form value
				$optionValue,
				'adminPanel_Images.png', // icon
				'', // label
				' alert(\'Beta (показывать оригиналы изображений для их сохранения - выключается оптимизация и наложение водяных знаков)!\'); return false; ', // event
				'margin-right: 0; border-radius: 0; border-left: 0; border-right: 0;' . $optionValueBgColor // css style
			);
			
			// Button "Show/hide record"
			$optionUcValue = $GLOBALS['BE_USER']->uc['phptemplate_showHiddenRecords'];
			if ($optionUcValue == 1) {
				$optionValue = -1;
				$optionValueBgColor = "background: #f3f3f3 !important;";
			} else {
				$optionValue = 1;
				$optionValueBgColor = "";
			}
			$content .= self::button(
				'phptemplate_showHiddenRecords', // form id
				'phptemplate_showHiddenRecords', // form value
				$optionValue,
				'adminPanel_Visibility-off.png', // icon
				'', // label
				' document.forms[\'phptemplate_showHiddenRecords\'].submit(); ', // event
				'margin-right: 0; border-radius: 3px 0 0 3px; border-right: 0;' . $optionValueBgColor // css style
			);
			
			// Button "Mod editing"
			$modEdit = $GLOBALS['BE_USER']->uc['phptemplate_mode_editing'];
			if ($modEdit <= 1) {
				$buttonOnColor = "#494949 url(".$srcAdmPath."adminPanel_bg_buttonContainer.png)"; // black
				$buttonOffColor = "url(".$srcAdmPath."Switch-2.png) !important";
			} else {
				$buttonOnColor = "url(".$srcAdmPath."Switch-1.png) !important";
				$buttonOffColor = "#494949 url(".$srcAdmPath."adminPanel_bg_buttonContainer.png)"; // black
			}
			
				$content .= self::button(
					'phptemplate_mode_editing_on', // form id
					'phptemplate_mode_editing' , // form value event
					'2' , // form value 2
					'', // icon
					'Вкл', // label
					' document.forms[\'phptemplate_mode_editing_on\'].submit(); ', // event
					'	padding-right: 3px; 
						padding-left: 3px;
						border-radius: 0 3px 3px 0;
						background: '.$buttonOnColor.';
					' // css style
				);

				$content .= self::button(
					'phptemplate_mode_editing_off', // form id
					'phptemplate_mode_editing' , // form value
					'1', // form value 2
					'', // icon
					'Выкл', // label
					' document.forms[\'phptemplate_mode_editing_off\'].submit(); ', // event
					'	margin-right: 0;
						padding-right: 3px; 
						padding-left: 3px;
						border-radius: 0; 
						border-right: 0;
						border-left: 0;
						background: '.$buttonOffColor.';
					' // css style
				);
				
				$content .= self::button(
					'', // form id
					'', // form value
					'',
					'editIcon.png', // icon
					'', // label
					'', // event
					'	margin-right: 0;
						border-radius: 3px 0 0 3px; 
						background: #494949 url('.$srcAdmPath.'adminPanel_bg_buttonContainerSwitchMode.png) !important;
						cursor: default !important;
					' // css style
				);
			
			// Button "Clear all cache"
			$content .= self::button(
				'adminPanel_clearAllCache', // form id
				'adminPanel_clearAllCache', // form value
				'',
				'adminPanel_clearAllCache.png', // icon
				'', // label
				' if (confirm (\'Сбросить все кэши?\')) {  document.forms[\'adminPanel_clearAllCache\'].submit(); } ', // event
				'border-radius: 0 3px 3px 0;' // css style
			);
			
			// Button "Clear cache current page"
			$content .= self::button(
				'adminPanel_clearCacheCurrentPage', // form id
				'adminPanel_clearCacheCurrentPage', // form value
				'',
				'', // icon
				'&nbsp;&nbsp;&nbsp; Сбросить кэш &nbsp;&nbsp;&nbsp;', // label
				' document.forms[\'adminPanel_clearCacheCurrentPage\'].submit(); ', // event
				'margin-right: 0; border-radius: 3px 0 0 3px; border-right: 0;' // css style
			);
			
			// Button "Add record"
			// Решил отказаться, т.к. если какая-то сложная логика, то не понятно куда материал разместиться...
			/*
			if($addToFooter != 1 && $GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
				$hoverContent ='<tagAdminPanel_hoverContent class="tagAdminPanel_hoverContent">';
				$hoverContent .= '<tagAdminPanel_DataWrap class="tagAdminPanel_DataWrap">';
				$hoverContent .= self::getDataTypes();
				$hoverContent .= '</tagAdminPanel_DataWrap>';
				$hoverContent .= '<tagAdminPanel_DataAllCount class="tagAdminPanel_DataAllCount">';
				$hoverContent .= 'Всего материалов: ['.self::getDataAllCount().']';
				$hoverContent .= '</tagAdminPanel_DataAllCount>';
				$hoverContent .= '</tagAdminPanel_hoverContent>';
			
				$content .= self::button(
					'', // form id
					'', // form value
					'',
					'newIcon.png', // icon
					'&nbsp;'.'Добавить материал', // label
					'', // event
					' cursor: default !important; ', // css style
					$hoverContent, // hoverContent
					$addToFooter
				);
			}
			*/
			
			// Page info
			# if(Typo3Helpers::IsCHashPage() == true){
			# 	$pageInfoLabel = 'pageId: ' . $GLOBALS['TSFE']->id; // . $GLOBALS['TSFE']->cHash
			# 	$pageInfoBackgroundColor = '803d46';
			# } else {
			#	$pageInfoLabel = 'pageId: ' . $GLOBALS['TSFE']->id;
			#	$pageInfoBackgroundColor = '816797';
			# }
			#$content .= self::button(
			#	'', // form id
			#	'', // form value
			#	'',
			#	'', // icon // adminPanel_pageInfo.png
			#	$pageInfoLabel, // label
			#	'', // event
			#	'background: #'.$pageInfoBackgroundColor.' !important;' // css style
			#);
			
			$editIcon = new \Litovchenko\AirTable\ViewHelpers\EditIconInlineViewHelper;
			$editIcon->model = 'Litovchenko\AirTable\Domain\Model\Content\Pages';
			$editIcon->recordId = $GLOBALS['TSFE']->id;
			if(BaseUtility::isPageVp() == true){
				// Ищем в таблице кэш с такими тэгами...
				$cacheTag = 'pageVp_'.md5(serialize($GLOBALS['TSFE']->pageArguments->getRouteArguments())); // getPageId
				$filter = [];
				$filter['select'] = ['id','identifier','tag'];
				$filter['from'] = [T3_CACHE_TABLE_TAGS];
				$filter['where'] = ['tag','=',$cacheTag];
				$count = \Litovchenko\AirTable\Domain\Model\DynamicModelCrud::recSelect('count',$filter);
				if($count > 0){
					$editIcon->title = 'pageId: ' . $GLOBALS['TSFE']->id . ' (vPcache)';
				} else {
					$editIcon->title = 'pageId: ' . $GLOBALS['TSFE']->id . ' (vP)';
				}
			} else {
				$editIcon->title = 'pageId: ' . $GLOBALS['TSFE']->id;
			}
			$editIcon->reverse = 1; // AdminPanelViewHelper
			$editIcon->alwayShow  = 1; // AdminPanelViewHelper
			$editIcon->hideHoverInfo = 1;
			$editIcon->hoverInfoNewLine = 1;
			$editIcon->styleLeft = 0;
			$editIcon->styleTop = 10;
			if($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] != 2){
				$editIcon->hideNewIcon = 1;
				$editIcon->hideEditIcon = 1;
				$editIcon->hideDisableIcon = 1;
				$editIcon->hideDeletedIcon = 1;
			} else {
				// $editIcon->hideNewIcon = 1;
			}
			$editIcon->copyFieldsForNewRecord = [
				'doktype',
				'tx_fed_page_controller_action',
			];
			$editIconContent = $editIcon->processOutput();
			
			$content .='<tagAdminPanel_buttonContainer class="tagAdminPanel_buttonContainer_page_info" style="display: block; float: right; margin-top: 2px;">';
			$content .= $editIconContent;
			$content .='</tagAdminPanel_buttonContainer>';
			
			// User avatar
			$content .= "
				<tagAdminPanel_buttonContainer class='tagAdminPanel_container' id='tagAdminPanel_buttonContainer_UserName'>
					<img src='http://www.gravatar.com/avatar/".md5($GLOBALS['BE_USER']->user['email'])."?s=23' class='tagAdminPanel_avatar'>
					<img src='".$srcAdmPath."adminPanel_BeUser_Admin.png' class='tagAdminPanel_iconBeUser'>
					<tagAdminPanel_wrapUserName class='tagAdminPanel_wrapUserName'>
						".(($GLOBALS['BE_USER']->user["realName"] != null)?$GLOBALS['BE_USER']->user["realName"]:$GLOBALS['BE_USER']->user["username"])."
					</tagAdminPanel_wrapUserName>
				</tagAdminPanel_buttonContainer>
			";
		
		$content .= "</tagAdminPanel_rightContainer>";
		$content .= "</tagAdminPanel>";
		$content .= "<tagAdminPanel_clear style='clear: both;' /></tagAdminPanel_clear>";
		
		if($GLOBALS['BE_USER']->uc['phptemplate_checkOption'] == 1){
			# $content .= "<tagAdminPanel_GET class='tagAdminPanel_GET'>";
			# $content .= print_r($GLOBALS['_GET'],true);
			# $content .= "</tagAdminPanel_GET>";
		}
		
		$content .= "</tagAdminPanelWrap>";
		$content .= "<!--TYPO3_NOT_SEARCH_end-->";

		return $content;
    }
	
    public static function button($formId = '', $formValue = '', $formValue2 = '', $icon = '', $label = '', $event_onclick = '', $css_style = '', $hoverContent = '', $addToFooter = 1)
    {
		$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
		$tagLabelMarginLeft = false;
		
		$tagImg = '';
		if(!empty($icon)){
			$tagImg = '<tagAdminPanel_img style="float: left; display: block; width: 16px; height: 24px; border: red 0px solid; 
			background: url('.$srcAdmPath.$icon.') center center no-repeat;"></tagAdminPanel_img>';
			$tagLabelMarginLeft = true;
		}
		$tagLabel = '';
		if(!empty($label)){
			if($tagLabelMarginLeft == true){
				$cssMarginLeft = '5';
			} else {
				$cssMarginLeft = '0';
			}
			$tagLabel = '<tagAdminPanel_label style="float: left; display: block; height: 24px;
			margin-left: '.$cssMarginLeft.'px; line-height: 22px;">'.$label.'</tagAdminPanel_label>';
		}
		$content  = '';
		$content .='<tagAdminPanel_buttonContainer class="tagAdminPanel_buttonContainer" style="'.$css_style.'">';
		if($addToFooter == 1){
			// $content .= $hoverContent;
		}
		$content .='<form method="post" id="'.$formId.'" style="padding: 0 !important; margin: 0 !important;">
					<input type="hidden" name="EditAdminPanel[event]" value="'.$formValue.'">
					<input type="hidden" name="EditAdminPanel[varible]" value="'.$formValue2.'">
					<input type="hidden" name="EditAdminPanel[request_url]" value="'.\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL').'">
										
					<tagAdminPanel_a class="tagAdminPanel_a" onclick="'.$event_onclick.'">
						'.$tagImg.'
						'.$tagLabel.'
					</tagAdminPanel_a>
					</form>';
		
		if($addToFooter != 1){
			$content .= $hoverContent;
		}
		$content .='</tagAdminPanel_buttonContainer>';
		return $content;
	}
	
	public static function getDataTypes()
	{
		$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
		$content = '';
		$dataNotFound = 1;
		
		$filter = [];
		$filter['orderBy'] = ['sorting','desc'];
		$rowsGet = \Litovchenko\AirTable\Domain\Model\Content\DataTypeGroup::recSelect('get',$filter);
		foreach($rowsGet as $k => $v){
			$dataNotFound = 0;
			$temp = self::getData($v['uid']);
			if($temp != ''){
				$content .= '<tagAdminPanel_newDataGroup class="tagAdminPanel_newDataGroup">
					<!--<img src="'.$srcAdmPath.'dataGroupIcon.png">-->
					'.$v['title'].'
				</tagAdminPanel_newDataGroup>';
				$content .= $temp;
			}
		}
			
		// Без группы
		$temp = self::getData(0);
		if($temp != ''){
			$content .= '<tagAdminPanel_newDataGroup class="tagAdminPanel_newDataGroup">
				<!--<img src="'.$srcAdmPath.'dataGroupIcon.png">-->
				Без группы
			</tagAdminPanel_newDataGroup>';
			$dataNotFound = 0;
			$content .= $temp;
		}
			
		if($dataNotFound == 1){
			#$icon = $this->iconFactory->getIcon('apps-pagetree-page-default', Icon::SIZE_DEFAULT, 'overlay-missing');
			#$content .= '<div style="margin: 25px;"><h3 style="color: #f6f8f4;">'.$icon.' Материалы не найдены!</h3></div>';
		}
		
		return $content;
	}
	
	public function getData($groupId)
	{
		$content = '';
		
		$filter = [];
		$filter['orderBy'] = ['sorting','desc'];
		$filter['where.10'] = ['propref_group','=',$groupId];
		$filter['where.20'] = ['prop_add_new_button','=',1];
		$rowsGet = \Litovchenko\AirTable\Domain\Model\Content\DataType::recSelect('get',$filter);
		foreach($rowsGet as $k => $v){
			
			// Db count
			$filter = [];
			$filter['where'] = ['RType','=',$v['uid']];
			$filter['withoutGlobalScope'] = ['DataTypeDefault'];
			$dbCount = \Litovchenko\AirTable\Domain\Model\Content\Data::recSelect('count',$filter);
			
			// Url
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
				'record_edit',
				array(
					'edit[tx_data][0]' => 'new',
					'defVals' => [
						'tx_data' => [
							'RType' => $v['uidkey'],
							'cruser' => $GLOBALS['BE_USER']->user['uid'],
						]
					],
					'data' => 'open',
					'dataType' => $v['uid'],
					'paramExt' => '',
					'paramSubDomain' => '',
					'paramClass' => 'Litovchenko\AirTable\Domain\Model\Content\Data',
					'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
				)
			);
			
			// Url 2 (не заработала ссылка)
			#$backendLink2 = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
			#	'content_AirTableData',
			#	array(
			#		'data' => 'open',
			#		'dataType' => $v['uid'],
			#		'paramExt' => '',
			#		'paramSubDomain' => '',
			#		'paramClass' => 'Litovchenko\AirTable\Domain\Model\Content\Data'
			#	)
			#);
			
			// if($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
				
			// Access
			if(BaseUtility::BeUserAccessTableSelect('tx_data')){
				$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
				$popupTitle = 'Создание записи';
				$js = ' adminPanelPopup(\'\', 0, 0, \''.$backendLink.'\', \''.$popupTitle.'\', 1024, 640); return true; ';
				$content .= '
					<tagAdminPanel_newData class="tagAdminPanel_newData">
					<tagAdminPanel_newDataLeft class="tagAdminPanel_newDataLeft">
						<a href="#" onclick="'.$js.'" style="color: #f6f8f4;">
							<img src="'.$srcAdmPath.'newIcon.png">
							'.$v['title'].'
							<span style="color: #535353; text-shadow: none;">['.intval($dbCount).']</span>
						</a>
					</tagAdminPanel_newDataLeft class="tagAdminPanel_newDataLeft">
					<!--
					<tagAdminPanel_newDataRight class="tagAdminPanel_newDataRight">
						<span style="color: #535353;">[#'.$v['uidkey'].']</span>
						<a href="'.$backendLink2.'" target="_blank" onclick="alert(\'Todo - ссылка на модуль список...\'); return false;">
							<img src="'.$srcAdmPath.'dataModuleIcon.png">
						</a>
					</tagAdminPanel_newDataRight class="tagAdminPanel_newDataRight">
					-->
					</tagAdminPanel_newData>
					<tagAdminPanel_newDataClear class="tagAdminPanel_newDataClear">
					</tagAdminPanel_newDataClear>
				';
			}
			
		}
		
		return $content;
	}
	
	public static function getDataAllCount()
	{
		$filter = [];
		$rowsCount = \Litovchenko\AirTable\Domain\Model\Content\DataType::recSelect('count',$filter);
		return intval($rowsCount);
	}
	
}