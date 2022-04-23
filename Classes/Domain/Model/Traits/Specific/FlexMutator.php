<?php
namespace Litovchenko\AirTable\Domain\Model\Traits\Specific;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Extbase\Object\ObjectManager;

trait FlexMutator
{
	public static function getFlexMutator($value)
	{
		// https://coding.musikinsnetz.de/typo3-extbase-fluid/general/flexform-use-section-for-indefinitely-repeatable-form-fields/
		// use TYPO3\CMS\Core\Utility\GeneralUtility::xml2array
		$flexformService = GeneralUtility::makeInstance(FlexFormService::class);
		$xmlArray = $flexformService->convertFlexFormContentToArray($value);
		return $xmlArray;
	}
	
	public static function setFlexMutator($value)
	{
		#return $xml;
	}
	
    /**
     * Get configuration (mutator).
     * SysFileStorage.
     *
     * @param  xml  $value
     * @return array
     */
	public function getConfigurationAttribute($value)
	{
		return self::getFlexMutator($value);
    }
	
    /**
     * Get flexform (mutator).
     * PAGES.
     *
     * @param  xml  $value
     * @return array
     */
	public function getTxFedPageFlexformAttribute($value)
	{
		return self::getFlexMutator($value);
    }
	
    /**
     * Get flexform (mutator).
     * TT_CONTENT.
     *
     * @param  xml  $value
     * @return array
     */
	public function getPiFlexformAttribute($value)
	{
		return self::getFlexMutator($value);
    }
	
    /**
     * Get flexform (mutator).
     * OTHER.
     *
     * @param  xml  $value
     * @return array
     */
	public function getPropFlexformAttribute($value)
	{
		return self::getFlexMutator($value);
    }
}