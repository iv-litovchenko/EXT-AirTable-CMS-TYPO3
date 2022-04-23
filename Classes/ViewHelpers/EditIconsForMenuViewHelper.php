<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class EditIconsForMenuViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'EditIconsForMenu',
		'registerArguments' => [
			'uidPattern*' => ['string'],
			'styleMargins' => ['string'], // styleLeft, styleTop
			'styleDirection' => ['string','left']
		]
	];
	
    use CompileWithRenderStatic;
	
    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;
	
    /**
     * Renders FlashMessages and flushes the FlashMessage queue
     * Note: This disables the current page cache in order to prevent FlashMessage output
     * from being cached.
     *
     * @see \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::no_cache
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
		// Если нет авторизованного пользователя и нет режима редактирования возвращяем ориг. контент
		if($GLOBALS['BE_USER']->user['uid'] < 1 || $GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] != 2) {
			$content = $renderChildrenClosure();
			return $content;
		}
					
		// $id = $arguments['uid'];
		// $as = $arguments['as'];
		
        // $templateVariableContainer = $renderingContext->getVariableProvider();
        // $templateVariableContainer->add($as, $r);
        // $content = $renderChildrenClosure();
        // $templateVariableContainer->remove($as);
		
		/*
		// http://www.nusphere.com/kb/phpmanual/function.domelement-tagname.htm
		$dom = new \DOMDocument();
		$dom->loadHTML($content);
		foreach($dom->getElementsByTagName('*') as $tag ){
			if($tag->hasAttribute('id')){
				if(preg_match('/elem_(.*)$/is',$tag->getAttribute('id'),$match)){
					$elemId = $match[1]; // page id
					#if($tag->tagName == 'a'){
					#	// $tag->
					#} else {
						// echo $tag->getAttribute('id')."\n<br />"; // .' | '.$tag->nodeValue."<br />"
						// print_r($elemId);
						// $tag->getElementsByTagName('a')[0].
						// exit();
					#}
					#$element = $dom->createElement('newnode');
					$tag->setAttribute('data-custom','true')
					$dom->saveHTML($tag);
					#print 1;
				}
				// echo $tag->tagName;
			}
			#$array[] = $dom->saveHTML($node);
		}
		$html = $dom->SaveHTML();

		#print "<pre>";
		#print_r($array);
		
		exit();
		*/
		
		$idPattern = $arguments['uidPattern'];
		$idPattern = str_replace('*','([0-9]+)',$idPattern);
		$styleDirection = $arguments['styleDirection'];
		// $arex = explode(',',$arguments['styleMargins']);
		
		$content = $renderChildrenClosure();
		$content = preg_replace_callback('/'.$idPattern.'([^\>]*)\>/is', function($matches) use ($styleDirection)
		{
			$editIcon = new \Litovchenko\AirTable\ViewHelpers\EditIconAbsViewHelper;
			$editIcon->model = 'Litovchenko\AirTable\Domain\Model\Content\Pages';
			$editIcon->recordId = $matches[1];
			$editIcon->panelType = 'editRecord_pages';
			if($styleDirection == 'left'){
				$editIcon->styleLeft = 0;
			} else {
				$editIcon->styleRight = 0;
			}
			$editIcon->styleTop = 0;
			$editIcon->hideNewIcon = 1;
			#$editIcon->copyFieldsForNewRecord = [
			#	'doktype',
			#	'tx_fed_page_controller_action',
			#];
			$editIconContent = $editIcon->processOutput();
			return $matches[0].$editIconContent;
		}, 
		$content);
		
		#$content = str_replace('<a',$editIconContent.'<a',$content);
        return $content;
    }
}