<?php
namespace Litovchenko\AirTable\Controller\Traits;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait Debug
{
	public function debug($ar){
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($ar);
		// \TYPO3\CMS\Core\Utility\DebugUtility::debug($ar);
	}
	
}