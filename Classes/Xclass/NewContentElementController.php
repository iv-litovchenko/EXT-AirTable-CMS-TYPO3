<?php
namespace Litovchenko\AirTable\Xclass;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class NewContentElementController extends \TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController
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
		'object' => 'TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController'
	];
	
    /**
     * Create on-click event value.
     *
     * @param string $clientContext
     * @return string
     */
    protected function onClickInsertRecord(string $clientContext): string
    {
		$typeWizard = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('typeWizard');
		if($typeWizard == 'pages'){
			// Root: &edit[pages][-Страница перед которой вставить]=new&id=228&table=
			// After: &edit[pages][-228]=new&id=228&table=
			// Inside: &edit[pages][228]=new&id=228&table=
			$_id = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('_id');
			$location = (string)$this->uriBuilder->buildUriFromRoute('record_edit', [
				// 'edit[pages][' . $_id . ']' => 'new',
				'defVals[pages][sys_language_uid]' => $this->sys_language,
				'returnUrl' => GeneralUtility::_GP('returnUrl')
			]);
			return $clientContext . '.location.href=' . GeneralUtility::quoteJSvalue($location) . '+document.editForm.defValues.value; return false;';
		} else {
			return parent::onClickInsertRecord($clientContext);
		}
    }
	
    /**
     * @param array $itemConf
     * @return array
     */
    protected function getWizardItem(array $itemConf): array
    {
		$this->moduleTemplate->getPageRenderer()->addCssInlineBlock('size24','.media-left span { width: 24px; height: 24px; }');
		$this->moduleTemplate->getPageRenderer()->addCssInlineBlock('lineheight24','.media-body strong { line-height: 22px; }');
		
        $itemConf['description'] = '';
        return parent::getWizardItem($itemConf);
    }
}
