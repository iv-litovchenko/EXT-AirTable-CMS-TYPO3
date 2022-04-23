<?php
namespace Litovchenko\AirTable\Hooks\Backend;

class WizardItemsHook implements \TYPO3\CMS\Backend\Wizard\NewContentElementWizardHookInterface 
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Добавление кастомных переменных "defVals" при создании нового элемента содержимого через Wizard',
		'description' => '',
		'onlyBackend' => [
			'TYPO3_CONF_VARS|SC_OPTIONS|cms|db_new_content_el|wizardItemsHook'
		]
	];
	
    /**
     * Modifies WizardItems array
     *
     * @param array $wizardItems Array of Wizard Items
     * @param \TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController $parentObject Parent object New Content element wizard
     */
    public function manipulateWizardItems(&$wizardItems, &$parentObject)
	{
		#print "<pre>";
		#print_r(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET());
		#print "</pre>";
		#exit();
		$id = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id');
		$typeWizard = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('typeWizard');
		if($typeWizard == 'pages'){
			/*
			[common_header] => Array
				(
					[iconIdentifier] => content-header
					[title] => Header Only
					[description] => Adds a header only.
					[saveAndClose] => 
					[tt_content_defValues] => Array
						(
							[CType] => header
						)

					[params] => &defVals[tt_content][CType]=header
				)
			*/
			
			// Root: &edit[pages][0]=new&id=228&table=
			// Before: &edit[pages][-Страница перед которой вставить]=new&id=228&table=
			// After: &edit[pages][-228]=new&id=228&table=
			// Inside: &edit[pages][228]=new&id=228&table=
			
			// $wizardItems['page_after']['pages_defValues']['doktype'] = 1;
			// $wizardItems['page_after']['params'] .= '&defVals[pages][doktype]=1';
			
			// $page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
			// $root = array_pop($page->getRootLine($id));
			
			// Ищем предыдущую страницу
			$pagePid = \Litovchenko\AirTable\Domain\Model\Content\Pages::recSelect('first',$id);
			$pagePid = $pagePid['pid'];
			
			$idPrev = 0;
			$filter = [];
			$filter['select'] = ['uid'];
			$filter['where'] = ['pid',$pagePid];
			$filter['orderBy'] = ['sorting', 'asc'];
			$rows = \Litovchenko\AirTable\Domain\Model\Content\Pages::recSelect('get',$filter,'uid');
			foreach($rows as $k => $v){
				if($rows[$k+1] == $id){
					$idPrev = $v;
					break;
				}
				#print $k.'/'.$v.'<br />';
			}
			#print_r($rows);
			#exit();
			
			if($idPrev == 0){
				$idPrev = $pagePid;
			} else {
				$idPrev = '-'.$idPrev;
			}
			
			$wizardItems = [];
			$wizardItems['page']['header'] = 'Мастер создания страницы';
			
			// $wizardItems['page_root']['iconIdentifier'] = 'apps-pagetree-spacer-root';
			// $wizardItems['page_root']['title'] = 'Добавить в корень';
			// $wizardItems['page_root']['description'] = 'Создать страницу в корне сайта';
			// $wizardItems['page_root']['saveAndClose'] = '';
			// $wizardItems['page_root']['params'] .= '&edit[pages]['.$root.']=new';
			// $wizardItems['page_root']['params'] .= '&defVals[pages][title]=New page';
			// $wizardItems['page_root']['params'] .= '&defVals[pages][hidden]=0';
			
			$wizardItems['page_before']['iconIdentifier'] = 'apps-pagetree-drag-copy-above';
			$wizardItems['page_before']['title'] = 'Добавить страницу перед текущей';
			$wizardItems['page_before']['description'] = ''; // Создать аналогичную страницу перед текущей
			$wizardItems['page_before']['saveAndClose'] = '';
			$wizardItems['page_before']['params'] .= '&edit[pages]['.$idPrev.']=new';
			$wizardItems['page_before']['params'] .= '&defVals[pages][title]=New page';
			$wizardItems['page_before']['params'] .= '&defVals[pages][hidden]=0';
			
			$wizardItems['page_after']['iconIdentifier'] = 'apps-pagetree-drag-copy-below';
			$wizardItems['page_after']['title'] = 'Добавить страницу после текущей';
			$wizardItems['page_after']['description'] = ''; // Создать аналогичную страницу после текущей
			$wizardItems['page_after']['saveAndClose'] = '';
			$wizardItems['page_after']['params'] .= '&edit[pages][-'.$id.']=new';
			$wizardItems['page_after']['params'] .= '&defVals[pages][title]=New page';
			$wizardItems['page_after']['params'] .= '&defVals[pages][hidden]=0';
			
			$wizardItems['page_inside']['iconIdentifier'] = 'apps-pagetree-drag-new-inside';
			$wizardItems['page_inside']['title'] = 'Создать подраздел';
			$wizardItems['page_inside']['description'] = ''; // Создать аналогичную страницу внутри текущей
			$wizardItems['page_inside']['saveAndClose'] = '';
			$wizardItems['page_inside']['params'] .= '&edit[pages]['.$id.']=new';
			$wizardItems['page_inside']['params'] .= '&defVals[pages][title]=New page';
			$wizardItems['page_inside']['params'] .= '&defVals[pages][hidden]=0';
			
			// Значения по умолчанию
			$feEdit = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('feEdit');
			$defVals = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('defVals');
			foreach($defVals['pages'] as $key => $value){
				// $wizardItems['page_root']['params'] .= '&defVals[pages]['.$key.']='.$value;
				$wizardItems['page_before']['params'] .= '&defVals[pages]['.$key.']='.$value;
				$wizardItems['page_before']['params'] .= '&feEdit='.$feEdit;
				
				$wizardItems['page_after']['params'] .= '&defVals[pages]['.$key.']='.$value;
				$wizardItems['page_after']['params'] .= '&feEdit='.$feEdit;
				
				$wizardItems['page_inside']['params'] .= '&defVals[pages]['.$key.']='.$value;
				$wizardItems['page_inside']['params'] .= '&feEdit='.$feEdit;
			}
			
			#print "<pre>";
			#print_r($wizardItems);
			#exit();
			
		} else {
			$feEdit = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('feEdit');
			$defVals = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('defVals');
			foreach($wizardItems as $k => $v){
				$wizardItems[$k]['params'] .= '&defVals[tt_content][header]=New record';
				$wizardItems[$k]['params'] .= '&feEdit='.$feEdit;
				// if(isset($defVals['tt_content']['foreign_table'])){
				//	$wizardItems[$k]['params'] .= '&defVals[tt_content][pid]=0';
				// }
			}
		}
	}
}