<?php
namespace Litovchenko\AirTable\Controller\Traits;

use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ExtensionService;

trait DefaultAssign
{
	public function initializeAction()
	{
		#Зарезервированные постоянные переменные:
		#Примечание! Данные переменные всегда поставляются в шаблон (не зависимо от того, кэширован он или нет). Даже если у нас user_INT-плагин (условно говоря) – то данные переменные все равно будут доступны.
		#Постоянные переменные:

		// Дата страницы 
		// Если вывод идет в режиме "eIdAjax" if (TYPO3_MODE_eIdAjax == 1) { return 1; } else { return 0; } 
		#{$t3_mode_eIdAjax} - 1 - да, 0 - нет (в основном нужно для создания <div>-оберток, хотя лучше это делать на основе jQuery  
		#{env name="TYPO3_SITE_URL"} // Получить значение TYPO3-константы - (в примере обычно для используется для <base href="">)
		#{data source="page:title"} // – получить название страницы     
		#{data source="DB:tt_content_gallery:1:title"} // – получиь из таблицы     
		#{data source="DB:TSFE:lang"} // – получить из масива 
		
		$typo3Vars = [
			'TM_FILENAME_BASE' => '', // TM_FILENAME_BASE
			'CSS-KEY', // id-ext-name
			'JS-KEY', // id-ext-name
			#'t3_current' => [
			#	'controllerName',
			#	'extensionName',
			#],
			't3_page' => $GLOBALS['TSFE']->page, // Содержит данные о текущей странице (оставлено – и есть альтернатива
			't3_data' => $this->configurationManager->getContentObject()->data['uid'], // Дата элемента контента  – содержит данные текущей выборки  
			't3_formats' => [
				'date' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
				'time' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm']
			],
			't3_systemConfiguration' => $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS'],
		];
		
		if(class_exists('TYPO3\CMS\Core\Information\Typo3Information')){
			// $typo3Vars['t3_information'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Information::class);
		}
		
		#print "<pre>";
		#print_r($typo3Vars);
		#exit();

		#$this->view->assign('typo3', $typo3Vars);
		#$this->view->assign('t3_***', $typo3Vars);
		parent::initializeAction();
	}
}