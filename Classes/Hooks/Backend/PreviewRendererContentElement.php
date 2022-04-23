<?php
namespace Litovchenko\AirTable\Hooks\Backend;

use \TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use \TYPO3\CMS\Backend\View\PageLayoutView;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class PreviewRendererContentElement implements PageLayoutViewDrawItemHookInterface
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
			// Register for hook to show preview of tt_content element of CType="yourextensionkey_newcontentelement" in page module
			// renders the backend preview for the extbase plugin and for the content element
			'TYPO3_CONF_VARS|SC_OPTIONS|cms/layout/class.tx_cms_layout.php|tt_content_drawItem'
		]
	];

	/**
	 * Preprocesses the preview rendering of a content element of type "My new content element"
	 *
	 * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
	 * @param bool $drawItem Whether to draw the item using the default functionalities
	 * @param string $headerContent Header content
	 * @param string $itemContent Item content
	 * @param array $row Record row of tt_content
	 *
	 * @return void
	 */
	public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row)
	{
		// content element
		if (strstr($row['CType'],'_elements_')) {
			$itemContent = '<strong>' . $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][$row['CType']][0] . '</strong><br />'.$itemContent;
			// $drawItem = false;
		}
		
		// content gridelement
		if (strstr($row['CType'],'_gridelements_')) {
			$itemContent = '<strong>' . $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][$row['CType']][0] . '</strong><br />'.$itemContent;
			// $drawItem = false;
		}
		
		// extbase plugin
		if ($row['CType'] == 'list') {
			// $itemContent .= $this->renderFluidStandAlone();
			$itemContent = '<strong>' . $GLOBALS['TCA']['tt_content']['columns']['list_type']['config']['items'][$row['list_type']][0] . '</strong><br />'.$itemContent;
			// $drawItem = false;
		}
		
		// Link edit
		// $itemContent .= $parentObject->linkEditContent('<img src="../typo3conf/ext/air_table/ext_icon.gif" width="180" alt=""/></br>', $row);
	}

	/**
	 * 
	 * @param string $templatePath
	 * @return string
	 */
	protected function renderFluidStandAlone($templatePath = 'Extensions/Preview/ContentElement.html')
	{
		/* @var $view \TYPO3\CMS\Fluid\View\StandaloneView */
		$view = GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
		$view->getRequest()->setControllerExtensionName('air_table'); // path the extension name to get translation work
		$view->setPartialRootPaths(array(100 => ExtensionManagementUtility::extPath('air_table') . 'Resources/Private/Partials/'));
		$view->setLayoutRootPaths(array(100 => ExtensionManagementUtility::extPath('air_table') . 'Resources/Private/Layouts/'));
		$view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('air_table') . 'Resources/Private/Templates/' . $templatePath);
		return $view->render();
	}
}
