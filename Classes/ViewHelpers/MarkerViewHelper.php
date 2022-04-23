<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class MarkerViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'Marker',
		'description' 		=> 'Вывод небольшого содержимого хранящегося в БД',
		'registerArguments' => [
			'uid*' => ['string',0],
			'type' => ['string'] // Todo (зделать жестким тип маркера - <f:marker uid="" type="input" />
		]
	];
	
    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;
	
    public function render()
    {
		$id = $this->arguments['uid'];
		$as = $arguments['as'];
		
		$filter = [];
		$filter['withoutGlobalScopes'] = true;
		$filter['where.10'] = ['uid',$id];
		$filter['where.20'] = ['RType','like','Marker.%'];
		$row = \Litovchenko\AirTable\Domain\Model\Content\Data::recSelect('first',$filter);
		if(empty($row)){
			return self::errorWrap('Data Marker with id "'.$id.'" not found!');
		}
		switch(strtolower($row['RType'])){
			default:
				$content = '?';
			break;
			case 'marker.input':
				$content = $row['prop_input'];
			break;
			case 'marker.text':
				$content = $row['prop_text'];
			break;
			case 'marker.text.rte':
				$parseFuncTSPath = 'lib.parseFunc_RTE';
				$contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
				$contentObject->start([]);
				$content = $contentObject->parseFunc($row['prop_text_rte'], [], '< ' . $parseFuncTSPath);
				// $content = Typo3Helpers::ParseFuncRte($rowMarker->bodytext_rte___bodytext_rte);
			break;
			# case 'marker.input.link':
			# 	$content = $row['prop_input_link'];
			# break;
			case 'marker.text.code.html':
				$content = $row['prop_text_code_html'];
			break;
			case 'marker.text.code.typoscript':
				$tsParser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser::class);
				$tsParser->parse($row['prop_text_code_ts']);
				$content = $GLOBALS['TSFE']->cObj->cObjGetSingle("COA", $tsParser->setup); // tsConf
			break;
		}
		return $content;
    }
	
	/**
		$content
	*/
	public static function errorWrap($content = '') {
		return "<tagErrorWrap style='display: block; background-color: red; padding: 5px 10px 5px 10px; color: white;'>".$content."</tagErrorWrap>";
	}
}