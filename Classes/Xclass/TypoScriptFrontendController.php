<?php
namespace Litovchenko\AirTable\Xclass;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\PageTitle\PageTitleProviderManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TypoScriptFrontendController extends \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Xclass',
		'name' => '',
		'description' => '',
		'object' => 'TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController'
	];

	// 
	public function setPageNotFoundAndExit($message = '')
	{
		$response = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\Controller\ErrorController::class)->pageNotFoundAction(
			$GLOBALS['TYPO3_REQUEST'],
			$message
		);
		throw new \TYPO3\CMS\Core\Http\ImmediateResponseException($response);	
	}
	
    /**
     * Enable caching of the current page.
     *
     * @internal
     */
	protected function enableCache()
	{
		// Добавляем тэг в страницу
		$cacheTag = 'pageVp_'.md5(serialize($GLOBALS['TSFE']->pageArguments->getRouteArguments()));
		$GLOBALS['TSFE']->addCacheTags([$cacheTag]);
		
		// Включаем кэширование страницы
		$this->no_cache = false;
		$this->no_cacheBeforePageGen = false;
		
		// Добавляем виртуальный тэг (для USER_INT)
		$filter = [];
		$filter['select'] = ['id','identifier','tag'];
		$filter['from'] = [T3_CACHE_TABLE_TAGS];
		$filter['where.10'] = ['identifier','=','-- none --'];
		$filter['where.20'] = ['tag','=',$cacheTag];
		$count = \Litovchenko\AirTable\Domain\Model\DynamicModelCrud::recSelect('count',$filter);
		if($count == 0){
			$data = [];
			$data['identifier'] = '-- none --';
			$data['tag'] = $cacheTag;
			$insertId = \Litovchenko\AirTable\Domain\Model\DynamicModelCrud::recInsert($data,T3_CACHE_TABLE_TAGS);
		}
	}
	
    /**
     * Sets the cache-flag to 0.
     *
     */
	public function set_cache()
	{
		if(!$this->config['config']['no_cache']) {
			$this->enableCache();
		}
	}
}
