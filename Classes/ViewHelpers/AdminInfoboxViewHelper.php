<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use Litovchenko\AirTable\Utility\BaseUtility;

class AdminInfoboxViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'AdminInfobox',
		'registerArguments' => [
			'type*' => ['string',null],
			'title*' => ['string',null]
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
			$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/Block/Mess/';
			$content = $renderChildrenClosure();
			return "
					<tagWrapBlock class='tagWrapBlock'>
						<tagWrapBlockLabel class='tagWrapBlockLabel'>
							<tagWrapBlockIcon class='tagWrapBlockIcon'>
								<img src='".$srcAdmPath."".$arguments['type'].".png'>
							</tagWrapBlockIcon>
							".htmlspecialchars($arguments['title'])."
						</tagWrapBlockLabel>
						<tagWrapBlockText class='tagWrapBlockText'>
							".$content."
						</tagWrapBlockText>
					</tagWrapBlock>
			";
		} else {
			return '';
		}
    }
}