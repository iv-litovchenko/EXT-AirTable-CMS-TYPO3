<?php
namespace Litovchenko\AirTable\Hooks\Backend;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

class PreviewRendererPage
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Информация о шаблоне',
		'description' => '',
		'onlyBackend' => [
			// Register page layout hooks to display additional information for posts.
			'TYPO3_CONF_VARS|SC_OPTIONS|cms/layout/db_layout.php|drawHeaderHook::drawHeader',
			'TYPO3_CONF_VARS|SC_OPTIONS|recordlist/Modules/Recordlist/index.php|drawHeaderHook::drawHeader',
		]
	];
	
    /**
     * @return string
     */
    public function drawHeader()
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $pageUid = (int)($request->getParsedBody()['id'] ?? $request->getQueryParams()['id'] ?? 0);
        $pageInfo = BackendUtility::readPageAccess($pageUid, $GLOBALS['BE_USER']->getPagePermsClause(Permission::PAGE_SHOW));

        // Early exit for non-blog pages
        #if ($pageInfo['doktype'] !== Constants::DOKTYPE_BLOG_POST) {
        #    return '';
        #}

        # $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        # $pageRenderer->addCssFile('EXT:blog/Resources/Public/Css/pagelayout.min.css', 'stylesheet', 'all', '', false);
		# $GLOBALS['TCA']['pages']['columns']['tx_fed_page_controller_action']['config']['items']
		
		#$templatePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('air_table','Resources/Private/Templates/Extensions/Preview/Page.html');
        #$view = GeneralUtility::makeInstance(StandaloneView::class);
        #$view->getRenderingContext()->getTemplatePaths()->fillDefaultsByPackageName('air_table');
		#$view->setLayoutRootPaths([0=>\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('air_table','Resources/Private/Layouts/')]);
		#$view->setPartialRootPaths([0=>\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('air_table','Resources/Private/Partials/')]);
		#$view->setTemplatePathAndFileName($templatePath);
        #$view->assignMultiple([
        #    'pageUid' => $pageUid,
        #    'pageInfo' => $pageInfo,
        #    'post' => $post,
        #]);

        # return $view->render();
		
		$item = $GLOBALS['TCA']['pages']['columns']['tx_fed_page_controller_action']['config']['items'][$pageInfo['tx_fed_page_controller_action']][0];
		return '<div class="alert alert-info">Шаблон: '.($item?$item:'-').'</div>';
    }
}
