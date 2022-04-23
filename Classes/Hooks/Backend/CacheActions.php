<?php
namespace Litovchenko\AirTable\Hooks\Backend;

use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CacheActions implements ClearCacheActionsHookInterface
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Убираем лишние кнопки на форме редактирования',
		'description' => '',
		'onlyBackend' => [
			'TYPO3_CONF_VARS|SC_OPTIONS|additionalBackendItems|cacheActions'
		]
	];
	   
    /**
     * Add an entry to the CacheMenuItems array
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with
     *                            options.clearCache.identifier)
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
		if ($GLOBALS['BE_USER']->isAdmin()) {
			$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('unseen_AirTableModulesSql', []);
            $cacheActions[] = array(
                'id'             => 'dyncss',
                'title'          => 'LLL:EXT:air_table/Resources/Private/Language/locallang_core.xlf:cache.title',
                'description'    => 'LLL:EXT:air_table/Resources/Private/Language/locallang_core.xlf:cache.description',
                'href'           => $backendLink,
                'iconIdentifier' => 'extensions-tx-airtable-resources-public-icons-ActionSystemCacheClear'
            );
        }
    }
}
