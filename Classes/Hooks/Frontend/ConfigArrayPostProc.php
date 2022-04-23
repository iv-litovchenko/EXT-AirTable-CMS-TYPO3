<?php
namespace Litovchenko\AirTable\Hooks\Frontend;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Litovchenko\AirTable\Domain\Model\DynamicModelCrud;
use Litovchenko\AirTable\Utility\BaseUtility;

class ConfigArrayPostProc
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Манипуляции с конфигом после сборки Typoscript-шаблона',
		'description' => 'Обработка _POST данных при нажатии на кнопках панелей администрирования и редактирования',
		'onlyFrontend' => [
			'TYPO3_CONF_VARS|SC_OPTIONS|tslib/class.tslib_fe.php|configArrayPostProc::configArrayPostProc'
		]
	];
	
    /**
     * @param array $params
     * @param TypoScriptFrontendController $parentObject
     */
    public function configArrayPostProc(array $params, TypoScriptFrontendController $parentObject)
    {
		// eIdAjax
		$eIdAjax = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('eIdAjax');
		if(!empty($eIdAjax)){
			
			// Запрещяем кэширование
			# $GLOBALS['TSFE']->set_no_cache();
			
			// Вырезаем со страницы все объекты
			# $pSetup_tmp = $parentObject->pSetup;
			# foreach ($parentObject->pSetup as $key => $value) {
			# 	if (is_numeric($key)){
			# 		unset($pSetup_tmp[$key]);
			# 		unset($pSetup_tmp[$key."."]);
			# 	}
			# }
				
			// Выключаем кэширование, убираем шапку
			# $parentObject->config['config']['no_cache'] = 1;
			# $parentObject->config['config']['disableAllHeaderCode'] = 1;
			# $parentObject->config['config']['disablePrefixComment'] = 1;
			# $parentObject->config['config']['contentObjectExceptionHandler'] = 0;
			
			// Создаем объект на странице
			// Выключаем кэширование, убираем шапку
			# $parentObject->pSetup = $pSetup_tmp;
			
			// -> Перенес в AjaxViewHelper.php
			
			# $parentObject->pSetup['20'] = 'TEXT';
			# $parentObject->pSetup['20.']['value'] = $controller;
			# $parentObject->pSetup['30'] = 'TEXT';
			# $parentObject->pSetup['30.']['value'] = $action;
		
		} else {
			
			
		}
		
		// Save Url for backend
		if($GLOBALS['BE_USER']->user['uid'] > 0) {
			$url = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
			$GLOBALS['BE_USER']->uc['phptemplate_frontend_request_url'] = $url;
			$GLOBALS['BE_USER']->overrideUC(); 
			$GLOBALS['BE_USER']->writeUC();	
		}
		
		// Event admin panel
		if($GLOBALS['BE_USER']->user['uid'] > 0) {
			$ap = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST('EditAdminPanel');
			if(!empty($ap)){
				switch($ap['event']){
					case 'adminPanel_clearCacheCurrentPage':
						#$typo3CacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Cache\CacheManager');
						#$typo3CacheManager->getCache(T3_CACHE_TABLE)->flushByTag('pageId_' . $GLOBALS['TSFE']->id);
						if(BaseUtility::isPageVp() == true){
							// Ищем в таблице кэш с такими тэгами...
							$tag = 'pageVp_' . md5(serialize($GLOBALS['TSFE']->pageArguments->getRouteArguments()));
							$filter = [];
							$filter['select'] = ['id','identifier','tag'];
							$filter['from'] = [T3_CACHE_TABLE_TAGS];
							$filter['where'] = ['tag','=',$tag];
							$identifiers = DynamicModelCrud::recSelect('get',$filter,'identifier');
							if(count($identifiers) > 0){
								$filter = [];
								$filter['from'] = [T3_CACHE_TABLE];
								$filter['whereIn'] = ['identifier',$identifiers];
								$affectedCount = DynamicModelCrud::recDelete($filter);
								$filter = [];
								$filter['from'] = [T3_CACHE_TABLE_TAGS];
								$filter['whereIn'] = ['identifier',$identifiers];
								$affectedCount = DynamicModelCrud::recDelete($filter);
							}
						} else {
							// Ищем в таблице кэш с такими тэгами...
							$tag = 'pageId_' . $GLOBALS['TSFE']->id;
							$filter = [];
							$filter['select'] = ['id','identifier','tag'];
							$filter['from'] = [T3_CACHE_TABLE_TAGS];
							$filter['where'] = ['tag','=',$tag];
							$identifiers = DynamicModelCrud::recSelect('get',$filter,'identifier');
							if(count($identifiers) > 0){
								$filter = [];
								$filter['from'] = [T3_CACHE_TABLE];
								$filter['whereIn'] = ['identifier',$identifiers];
								$affectedCount = DynamicModelCrud::recDelete($filter);
								$filter = [];
								$filter['from'] = [T3_CACHE_TABLE_TAGS];
								$filter['whereIn'] = ['identifier',$identifiers];
								$affectedCount = DynamicModelCrud::recDelete($filter);
							}
						}
						header("Location: " . $ap['request_url']);
						exit();
					break;
					case 'adminPanel_clearAllCache':
						#$typo3CacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Cache\CacheManager');
						#$typo3CacheManager->getCache(T3_CACHE_TABLE)->flush();
						$filter = [];
						$filter['from'] = [T3_CACHE_TABLE];
						$affectedCount = DynamicModelCrud::recDelete($filter);
						$filter = [];
						$filter['from'] = [T3_CACHE_TABLE_TAGS];
						$affectedCount = DynamicModelCrud::recDelete($filter);
						header("Location: " . $ap['request_url']);
						exit();
					break;
					case 'phptemplate_mode_editing':
						$GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] = $ap['varible'];
						$GLOBALS['BE_USER']->overrideUC(); 
						$GLOBALS['BE_USER']->writeUC();		
						header("Location: " . $ap['request_url']);
						exit();
					break;
					case 'phptemplate_editIcon_color':
						$GLOBALS['BE_USER']->uc['phptemplate_editIcon_color'] = $ap['varible'];
						$GLOBALS['BE_USER']->overrideUC(); 
						$GLOBALS['BE_USER']->writeUC();		
						header("Location: " . $ap['request_url']);
						exit();
					break;
					case 'phptemplate_noResizeImage':
						$GLOBALS['BE_USER']->uc['phptemplate_noResizeImage'] = $ap['varible'];
						$GLOBALS['BE_USER']->overrideUC(); 
						$GLOBALS['BE_USER']->writeUC();		
						header("Location: " . $ap['request_url']);
						exit();
					break;
					case 'phptemplate_checkOption':
						$GLOBALS['BE_USER']->uc['phptemplate_checkOption'] = $ap['varible'];
						$GLOBALS['BE_USER']->overrideUC(); 
						$GLOBALS['BE_USER']->writeUC();		
						header("Location: " . $ap['request_url']);
						exit();
					break;
					case 'phptemplate_showHiddenRecords':
						/*
							http://t3club.com/index.php?TSFE_ADMIN_PANEL[DUMMY]=&id=1&TSFE_ADMIN_PANEL[display_top]=1&TSFE_ADMIN_PANEL[display_preview]=1
							&TSFE_ADMIN_PANEL[preview_showHiddenPages]=1
							&TSFE_ADMIN_PANEL[preview_showHiddenRecords]=0
							<input type="hidden" name="TSFE_ADMIN_PANEL[preview_showHiddenPages]" value="0" />
							<input type="hidden" name="TSFE_ADMIN_PANEL[preview_showHiddenRecords]" value="0" />
						*/
						if($ap['varible'] == 1) {
							$GLOBALS['BE_USER']->uc['phptemplate_showHiddenRecords'] = 1;
							// $GLOBALS['BE_USER']->uc['TSFE_adminConfig']['preview_showHiddenPages'] = 0; // 1 убрал показ скрытых страниц
							// $GLOBALS['BE_USER']->uc['TSFE_adminConfig']['preview_showHiddenRecords'] = 1;
							// $GLOBALS['BE_USER']->uc['TSFE_adminConfig']['display_top'] = 1; // раскрыть панель adminPanel - без этого не работает!
							// $GLOBALS['BE_USER']->uc['TSFE_adminConfig']['display_preview'] = 1; // без этого не работает!
						} else {
							$GLOBALS['BE_USER']->uc['phptemplate_showHiddenRecords'] = -1;
							// $GLOBALS['BE_USER']->uc['TSFE_adminConfig'] = [];
						}
						$GLOBALS['BE_USER']->overrideUC(); 
						$GLOBALS['BE_USER']->writeUC();		
						header("Location: " . $ap['request_url']);
						exit();
					break;
					case 'phptemplate_plugin_adminPanel_buttonLogout':
						$GLOBALS['BE_USER']->logoff();
						header("Location: " . $ap['request_url']);
						exit();
					break;
					case 'isMoveRecord_tt_content':
						$GLOBALS['BE_USER']->uc['isMoveRecord_tt_content'] = 1;
						$GLOBALS['BE_USER']->uc['isMoveRecord_tt_content_varible'] = $ap['varible'];
						$GLOBALS['BE_USER']->overrideUC(); 
						$GLOBALS['BE_USER']->writeUC();
						header("Location: " . $ap['request_url']);
						exit();
					break;
				}
			}
		}
		
		// edit mode
		if($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
			
			// Константа, что работает режим редактирования
			define('TYPO3_EDIT_MODE', TRUE);
			
		} else {
			
			// Константа, что работает режим редактирования
			define('TYPO3_EDIT_MODE', FALSE);
		}
		
    }
}