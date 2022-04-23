<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class MarkerMediaViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'MarkerMedia',
		'description' 		=> 'Вывод небольшого содержимого хранящегося в БД',
		'registerArguments' => [
			'uid*' => ['string',0],
			'type' => ['string'], // Todo (зделать жестким тип маркера - <f:marker uid="" type="input" />
			'as*' => ['string']
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
		$id = $arguments['uid'];
		$as = $arguments['as'];
		
		$filter = [];
		$filter['withoutGlobalScopes'] = true;
		$filter['where.10'] = ['uid',$id];
		$filter['where.20'] = ['RType','like','Marker.%'];
		$filter['with.10'] = 'propmedia_media_1';
		$filter['with.20'] = 'propmedia_media_m';
		$row = \Litovchenko\AirTable\Domain\Model\Content\Data::recSelect('first',$filter);
		if(empty($row)){
			return self::errorWrap('Data Marker with id "'.$id.'" not found!');
		}
		switch(strtolower($row['RType'])){
			default:
				$content = '?';
			break;
			
			// row.thumbnail_func.0.uid_local
			case 'marker.media_1':
				if(empty($row['propmedia_media_1'])){
					return self::errorWrap('Data Marker with id "'.$id.'" not file!');
				} else {
					$r = $row['propmedia_media_1'];
				}
			break;
			
			// row.thumbnail_func.0.uid_local
			case 'marker.media_m':
				if(count($row['propmedia_media_m']) == 0){
					return self::errorWrap('Data Marker with id "'.$id.'" not files!');
				} else {
					$r = $row['propmedia_media_m'];
				}
			break;
		}
		
        $templateVariableContainer = $renderingContext->getVariableProvider();
        $templateVariableContainer->add($as, $r);
        $content = $renderChildrenClosure();
        $templateVariableContainer->remove($as);

        return $content;
    }
	
	/**
		$content
	*/
	public static function errorWrap($content = '') {
		return "<tagErrorWrap style='display: block; background-color: red; padding: 5px 10px 5px 10px; color: white;'>".$content."</tagErrorWrap>";
	}
}