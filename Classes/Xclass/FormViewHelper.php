<?php
namespace Litovchenko\AirTable\Xclass;

class FormViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper
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
		'object' => 'TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper'
	];
	
    /**
     * Arguments initialization
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
	public function initializeArguments()
	{
		parent::initializeArguments();
		// $this->registerArgument('route', 'string', 'The name of the route');
		// $this->registerArgument('asErrors', 'string', '');
	}
	
    /**
     * Render the form.
     *
     * @return string rendered form
     */
    public function render()
    {
		$errorsAllArray = [];
		$formObject = $this->arguments['name']; // form || FormFeedBack???
		$formObjectErrors = $this->arguments['name'].'Errors'; // form || FormFeedBack???
		$variableProvider = $this->renderingContext->getVariableProvider();
		
		if (true === $variableProvider->exists($formObjectErrors)) {
			$errorsAllArray = $variableProvider->get($formObjectErrors);
			if(!empty($errorsAllArray)){
				// Set Form Errors manually  - get results from property mapper and add new errors
				$validationResults = $this->renderingContext->getcontrollerContext()->getRequest()->getOriginalRequestMappingResults();
				foreach($errorsAllArray as $kProperty => $vErrors){
					if(!$validationResults->forProperty($formObject)->forProperty($kProperty)->hasErrors()){
						#print $validator->errors()->get($kError)->getAttribute();
						foreach ($vErrors as $kError => $message){
							// \TYPO3\CMS\Extbase\Error\Error -> __construct (string $message, int $code, array $arguments=[], string $title='')
							// ->forProperty('begin')->setTypeConverterOption(DateTimeConverter::class, DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'd.m.Y');
							$title = ''; // $validator->getDisplayableAttribute($kProperty); // ->getAttributeList($values)
							$validationResults->forProperty($formObject)->forProperty($kProperty)->addError(new \TYPO3\CMS\Extbase\Error\Error($title.'>'.$message,hexdec(crc32($kProperty.$kError)),[],'-- TITLE --')); // Add validation errors
						}
					}
				}
				$this->renderingContext->getcontrollerContext()->getRequest()->setOriginalRequestMappingResults($validationResults);
			}
		}
		
		
		// Погружаем ключи (для ошибок типа массив
		/*
		Array
		(
			[image] => Array
				(
					[0] => Поле не заполнено
				)

			[images.0] => Array				-> Погружаем...
				(
					[0] => Error max
				)

			[images.1] => Array
				(
					[0] => Error max
				)

			[field] => Array
				(
					[0] => Something is wrong with this field - 1!
				)

		)
		*/
		
		if(!empty($errorsAllArray)){
			$newErrorsAllArray = $errorsAllArray;
			foreach($errorsAllArray as $k => $v){
				if(strstr($k,'.')){
					$arex = explode('.', $k); // "images.*", "images.1"
					$newErrorsAllArray[$arex[0].'_items'][$arex[1]] = $v;
					unset($newErrorsAllArray[$k]);
				}
			}
			$errorsAllArray = $newErrorsAllArray;
		}
		
		# print "<pre>";
		# print_r($errorsAllArray);
		# print "</pre>";
		
		$asErrors = 'errors'; // $this->arguments['asErrors'];
		$templateVariableContainer = $this->renderingContext->getVariableProvider();
		$templateVariableContainer->add($asErrors, $errorsAllArray);
        $output = parent::render();
        $templateVariableContainer->remove($asErrors);
		return $output;
    }
}
