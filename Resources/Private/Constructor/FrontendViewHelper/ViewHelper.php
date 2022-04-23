<?php
namespace ###NAMESPACE_1###\###NAMESPACE_2###\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

// class If###KEY###ViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper
class ###KEY###ViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendViewHelper',
        'name' => '###NAME###',
        'description' => '###DESCRIPTION###',
        'registerArguments' => [
            'testArg1*' => ['string','Default value','Description'], // integer || string || mixed || boolean || array
            'testArg2' => ['string']
        ]
    ];

    public function render()
    {
        $testArg1 = $this->arguments['testArg1'];
        $testArg2 = $this->arguments['testArg2'];
        return 'Hello world - ' . $testArg1 . ',' . $testArg2;
    }
	
    /**
     * This method decides if the condition is TRUE or FALSE. It can be overridden in extending viewhelpers to adjust functionality.
     *
     * @param array $arguments ViewHelper arguments to evaluate the condition for this ViewHelper, allows for flexibility in overriding this method.
     * @return bool
     */
	# protected static function evaluateCondition($arguments = null)
    # {
    # }
}

// Run -> <f:vhsExt###NAMESPACE_2###.###KEY### testArg1="100" testArg2="200" />
// Run -> <f:vhsExt###NAMESPACE_2###.If###KEY### testArg1="Administrator" />
//           --- CONTENT CODE ---
//        </f:vhsExt###NAMESPACE_2###.If###KEY### />
// Todo: example "CompileWithRenderStatic"