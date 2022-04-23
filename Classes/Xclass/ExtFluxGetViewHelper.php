<?php
namespace Litovchenko\AirTable\Xclass;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use FluidTYPO3\Flux\ViewHelpers\FormViewHelper;

class ExtFluxGetViewHelper extends \FluidTYPO3\Flux\ViewHelpers\Content\RenderViewHelper
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
		'object' => 'FluidTYPO3\Flux\ViewHelpers\Content\RenderViewHelper'
	];
	
    /**
     * Arguments initialization
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('editIconsEnable', 'boolean', '', false, 1);
	}
	
    /**
     * Default implementation for use in compiled templates
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
		/*
			Array
			(
				[area] => 1
				[limit] => 
				[offset] => 0
				[order] => sorting
				[sortDirection] => ASC
				[as] => 
				[loadRegister] => 
				[render] => 1
				[editIcon] => 
			)
		*/
	
		$record = (array) $renderingContext->getViewHelperVariableContainer()->get(FormViewHelper::class, 'record');
		$pid = $GLOBALS['TSFE']->id;
		$colPos = $record['uid'].'0'.intval($arguments['area']);
		
		$emptyId = 'emptyColPos_'.$colPos;
		$getContent = parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
		return ExtVhsRenderViewHelper::wrap(
			$arguments['editIconsEnable'],
			$emptyId,
			$getContent,
			'Контент сетки',
			'pid: ' . $pid . ', area: ' . $colPos,
			['pid' => $pid, 'colPos' => $colPos]
		);
    }
}
