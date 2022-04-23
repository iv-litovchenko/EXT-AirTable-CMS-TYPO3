<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use Litovchenko\AirTable\Utility\BaseUtility;

class EditWrapViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'EditWrap',
		'registerArguments' => [
			'inline' => ['integer']
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
		// При условии, что это режим редактирования
		if ($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2 || TYPO3_MODE === 'BE') {
			$cssClassAdd = ($arguments['inline'] == TRUE) ? 'vInline' : '';
			$content = $renderChildrenClosure();
			return "
					<tagWrapTtContentElement class='tagWrapTtContentElement ".$cssClassAdd."'>
						<tagWrapTtContentElement_dashed_Top		class='tagWrapTtContentElement_dashed_Top'></tagWrapTtContentElement_dashed_Top>
						<tagWrapTtContentElement_dashed_Right	class='tagWrapTtContentElement_dashed_Right'></tagWrapTtContentElement_dashed_Right>
						<tagWrapTtContentElement_dashed_Bottom	class='tagWrapTtContentElement_dashed_Bottom'></tagWrapTtContentElement_dashed_Bottom>
						<tagWrapTtContentElement_dashed_Left	class='tagWrapTtContentElement_dashed_Left'></tagWrapTtContentElement_dashed_Left>
					".$content."
					</tagWrapTtContentElement>
			";
		} else {
			$content = $renderChildrenClosure();
			return $content;
		}
    }
}