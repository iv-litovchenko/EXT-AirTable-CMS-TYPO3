<?php
namespace Litovchenko\AirTable\Controller\Traits;

use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ExtensionService;

trait Route
{
	public function toUrl($ar){
		// https://bitbucket.org/reelworx/rx_scheduled_social/src/8a2844d76b2e443e52b23a1eae131a7bbca86109/Classes/Utility/LinkUtility.php
		#$site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId($pageUid);
		#$arguments = [
		#	'foo' => 1,
		#];
		#$uri = (string)$site->getRouter()->generateUri((string)$pageUid, $arguments);
		#$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		#$extensionService = $objectManager->get(ExtensionService::class);
		#$argumentsPrefix = $extensionService->getPluginNamespace($extensionName, $pluginName);
		#$arguments = [
		#	$argumentsPrefix => [
		#	  'action' => $actionName,
		#	  'controller' => $controllerName,
		#	],
		#];
	}
	
}