<?php
namespace Litovchenko\AirTable\Provider\Traits;

use FluidTYPO3\Flux\Provider\AbstractProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Container\Grid;
use FluidTYPO3\Flux\Integration\PreviewView;
use Litovchenko\AirTable\Utility\BaseUtility;

trait getFormTrait
{
    /**
     * @param array $row
     * @return \FluidTYPO3\Flux\Form|NULL
     */
    public function getFormTrait(array $row)
    {
		/*
		<flux:form id="myform">
		  <flux:field.input name="myField" label="My special field" />
		</flux:form>
		*/
		
		/** @var Tx_Flux_Form $form */
		# $form = $this->objectManager->get('Tx_Flux_Form');
		# $field = $form->createField('Input', 'myfield', 'My input field');
		# $field->setDefault('My default value')
		# 	->setRequestUpdate(TRUE)
		# 	->setValidate('trim,int')
		# $form->add($field);
		# $structure = $form->build();
		
		// $templateFile = $this->getTemplatePathAndFilename($row);
		#switch (basename($templateFile)) {
		#	case 'whatever.html':
		#		// $form = $this->objectManager->get('MyVendor\MyExt\Form\CustomForm');
		#	break;
		#}
		
		// $templateFile = $this->getTemplatePathAndFilename($row);
		// $ext = $this->getExtensionKey($row); // getControllerNameFromRecord // getListType
		
		#print "<pre>";
		#print_r($class);
		#exit();
		
		// $class = 'Litovchenko\Projiv\Controller\PagesElements\Gridelements\Cols3Controller';
		$class = $this->getFullControllerName();
		if(class_exists($class) && property_exists($class, 'TYPO3')){
			$annotationFluxFields = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FluxFields');
			$fluxFields = $annotationFluxFields;
			$form = Form::create();
			$form->setName('dynamicProperties');
			$form->createField('Input', 'class', 'Class: '.$class);
			// $form->setIcon('EXT:myext/ext_icon.png');
			// $form->setWizardTab('My tab');
			// $form->setOption(Form::OPTION_ICON, 'EXT:myext/ext_icon.png');
			// $form->setOption(Form::OPTION_GROUP, 'My tab');
			if(!empty($fluxFields)){
				$form = BaseUtility::readFluxFields($form, $fluxFields);
				return $form;
			}
		
		}
		
		// Empty
		$form = Form::create();
		$form->setName('dynamicPropertiesEmpty');
		$form->createField('Input', 'display_without_parameters', 'No parameters!')->setDefault('The record has no parameters!')->setEnabled(1);
		return $form;
    }
	
}