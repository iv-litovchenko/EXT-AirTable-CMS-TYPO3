<?php
namespace Litovchenko\AirTable\Controller\Traits;

use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

trait Config
{
	// SiteConfig
	// https://docs.typo3.org/m/typo3/reference-coreapi/10.4/en-us/ApiOverview/SiteHandling/AccessingSiteConfiguration.html
	// https://docs.typo3.org/m/typo3/reference-coreapi/10.4/en-us/ApiOverview/SiteHandling/ExtendingSiteConfig.html
	public function getSiteConfig()
	{
		$currentPageId = $GLOBALS['TSFE']->id;
		if(class_exists($SiteFinder)){
			$site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId($currentPageId);
			return $site->getConfiguration();
		} else {
			return [];
		}
	}
	
	// Database row
	public function getDataRow()
	{
		$currentData = $this->configurationManager->getContentObject()->data;
		return $currentData;
	}
	
	// UserTsConfig.ts // todo
	public function getUserTSConfig()
	{
		// $userTsConfig = $GLOBALS['BE_USER']->getTSConfig();
	}
	
	// PageTsConfig.ts
	public function getPagesTSconfig()
	{
		#$currentPageId = $GLOBALS['TSFE']->id;
		#$pageTsConfig = BackendUtility::getPagesTSconfig($currentPageId); // PageTsConfig.ts
		#$typoScriptService = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Service\TypoScriptService::class); // TYPO3\CMS\Core\TypoScript\TypoScriptService v11
		#return $typoScriptService->convertTypoScriptArrayToPlainArray($pageTsConfig); // ['user.']['tx_air_table.']
	}
	
	// IncTypoScript > constants.ts, setup.ts
	// public function getSettings($extension, $plugin) -> https://blog.pixel-ink.de/typoscript-settings-viewhelper/
	public function getTsConfig() // getSetting()
	{
		// const CONFIGURATION_TYPE_FRAMEWORK = 'Framework';
		// const CONFIGURATION_TYPE_SETTINGS = 'Settings';
		// const CONFIGURATION_TYPE_FULL_TYPOSCRIPT = 'FullTypoScript';
		
		// $tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class, null, $pageUid, 0);
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
		$configurationManager = $objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::class); 
		
		// CONFIGURATION_TYPE_SETTINGS || CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		// $tsConfig = $setting['plugin.']['tx_xxxx.']['settings.']['storagePid'];
		#$setting = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);   
		#$typoScriptService = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Service\TypoScriptService::class); // TYPO3\CMS\Core\TypoScript\TypoScriptService v11
		
		// ['module.']['tx_xxxx.']['settings.']['storagePid'];
		// ['plugin.']['tx_xxxx.']['settings.']['storagePid']; 
		#return $typoScriptService->convertTypoScriptArrayToPlainArray($setting);
	}
	
	
}