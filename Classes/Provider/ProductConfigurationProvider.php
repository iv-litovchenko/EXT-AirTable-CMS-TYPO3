<?php
namespace Litovchenko\AirTable\Provider;

use FluidTYPO3\Flux\Provider\AbstractProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;

///////////////////////
// https://www.medienreaktor.de/blog/dynamische-backend-formulare-in-typo3-mit-flux
///////////////////////

class ProductConfigurationProvider extends AbstractProvider implements ProviderInterface
{
	public static $TYPO3_Zzz = [
		'thisIs' 		=> 'FrontendContentElement',
		'name' 			=> 'Дочерние страницы (красивый список)',
		'description' 	=> '',
		'fluxFields' 	=> [
            'Sheet|sheet1|Имя|Описание' => [
				'Input|attr_field1|Имя1|req:1',
				'Input|attr_field2|Имя2',
				'Input|attr_field3|Имя3',
				'Section|phones|Секция' => [
					'SectionObject|sec1|Телефон' => [
						'Input|attr_field1a|ИмяА|req:1',
						'Input|attr_field2b|ИмяБ',
						'Input|attr_field3c|ИмяВ',
					],
					'SectionObject|sec2|Телефон2' => [
						'Input|attr_field1a|ИмяА|req:1',
						'Input|attr_field2b|ИмяБ',
						'Input|attr_field3c|ИмяВ',
					],
					'SectionObject|sec3|Телефон3' => [
						'Input|attr_field1a|ИмяА|req:1',
						'Input|attr_field2b|ИмяБ',
						'Input|attr_field3c|ИмяВ',
					]
				]
			],
            'Sheet|sheet2|Имя2|Описание' => [
				'Input|attr_field1a|ИмяА|req:1',
				'Input|attr_field2b|ИмяБ',
				'Input|attr_field3c|ИмяВ',
			],
			'Input|field1|Имя|req(1)',
			'Input|field2|Имя2',
			'Input|field3|Имя3'
		]
	];
	
    /**
     * @var string
     */
    # protected $tableName = 'tt_content'; // tx_data // tx_products_domain_model_product

    /**
     * @var string
     */
    # protected $fieldName = 'pi_flexform'; // prop_flexform // prop_flexform
	
    /**
	* @var string
	*/
	# protected $extensionKey = 'projiv';
	
	/**
	* @var string
	*/
	# protected $listType = 'projiv_plugins_firstplugincontroller';

	#protected $templatePaths = array(
	#	'layoutRootPath' => 'EXT:dlrg_seminare/Resources/Private/Layouts/',
	#	'templateRootPath' => 'EXT:dlrg_seminare/Resources/Private/Templates/',
	#	'partialRootPath' => 'EXT:dlrg_seminare/Resources/Private/Partials/',
	#);
	
	/**
	* @var string
	*/
	#protected $templatePathAndFilename = 'EXT:dlrg_seminare/Configuration/FlexForms/flexform_pi1.html';

    /**
     * @param array $row
     * @return \FluidTYPO3\Flux\Form|NULL
     */
    public function getForm(array $row)
    {
		# $this->setExtensionKey('projiv');
		# $this->setListType('projiv_plugins_firstplugincontroller');
		
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

        $form = \FluidTYPO3\Flux\Form::create();
        $form->setName('dynamicProperties');
		
		$fluxFields = self::$TYPO3['fluxFields'];
		if(!empty($fluxFields)){
			$form = self::creadFluxFields($form, $fluxFields);
		} else {
			$form->createField('Input', 'display_without_parameters', 'No parameters!')
			->setDefault('The record has no parameters!')->setEnabled(1);
		}
		
		#$form->createField('Input', 'myfield1', 'My input field')->setDefault('My default value');
		#$form->createField('Input', 'myfield2', 'My input field')->setDefault('My default value');
		#$form->createField('Input', 'myfield3', 'My input field')->setDefault('My default value');
		
		#$sheet = $form->createContainer('Sheet', '--DESCRIPTION 1--', 'dwq');
		#$sheet->createField('Input', 'myfield1', 'FEfe22');
		#$form->add($sheet);
		
		#$sheet2 = $form->createContainer('Sheet', '--DESCRIPTION 2--', 'dwq 2');
		#$sheet2->createField('Input', 'myfield7', 'FEfe22');
		
		#$section = $sheet->createContainer('Section', 'mysec', 'FEfe22');
		#$sectionObj1 = $section->createContainer('SectionObject', 'mysubsec', 'FEFE');
		#$sectionObj1->createField('Text', 'myfield7', 'FEfe7');
		#$sectionObj1->createField('Text', 'myfield8', 'FEfe9');
		#$form->add($sectionObj1);
		
		/*
		<?php
		namespace Medienreaktor\Products\Domain\Model;

		class Product extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
		{
			...

			public function getDynamicProperties() {
				$flexFormContent = $this->piFlexform;
				$flexFormService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\FlexFormService');
				$fields = $flexFormService->convertFlexFormContentToArray($flexFormContent);

				return $fields;
			}

			...
		}

        $product = $this->productRepository->findByUid($row['uid']);

        if ($product) {
            $productGroup = $product->getProductGroup();

            // Create a sheet for each property group
            foreach ($productGroup->getPropertyGroups() as $group) {
               $sheet = $form->createContainer(
                  'Sheet',
                  $group->getUid(),
                  $group->getName()
               );

               // Create a field for each property
               foreach ($group->getProperties() as $property) {

                  // The property holds the field type, e.g. 'Input'
                  $sheet->createField(
                     $property->getType(),
                     $property->getUid(),
                     $property->getName()
                  );
               }
            }
        }

		*/
		
        return $form;
    }
	
	protected static function creadFluxFields($form, $fluxFields = [])
	{
		foreach($fluxFields as $k => $v){
			$arexK = explode('|',$k);
			$arexV = explode('|',$v);
			if($arexK[0] == 'Sheet'){
				$sheet = $form->createContainer('Sheet', $arexK[1], $arexK[2]);
				if(isset($arexK[3])){
					$sheet->setDescription($arexK[3]);
					$sheet->setShortDescription($arexK[3]);
				}
				$form->add(self::creadFluxFields($sheet, $v));
				
			/*
			<flux:form.section name="settings.sectionObjectAsClass2" label="Telephone numbers 2">
				<flux:form.object name="custom">
					<flux:field.input name="propertyFoo" default="Foo" label="Property value: Foo" />
					<flux:field.input name="propertyBar" default="Bar" label="Property value: Bar" />
					<flux:field.input name="propertyBar2" default="Bar2" label="Property value: Bar" />
				</flux:form.object>
				<flux:form.object name="mobile" label="Mobile">
					<flux:field.input name="number"/>
				</flux:form.object>
				<flux:form.object name="landline" label="Landline">
					<flux:field.input name="number"/>
				</flux:form.object>
			</flux:form.section>
			*/
			
			}elseif($arexK[0] == 'Section'){
				$section = $form->createContainer('Section', $arexK[1], $arexK[2]);
				$form->add(self::creadFluxFields($section, $v));
			
			}elseif($arexK[0] == 'SectionObject'){
				$sectionObject = $form->createContainer('SectionObject', $arexK[1], $arexK[2]);
				$form->add(self::creadFluxFields($sectionObject, $v));
				
			} elseif($arexV[0] == 'Input'){
				$form->createField('Input', $arexV[1], $arexV[2]); // ->setDefault('My default value')
				
			}
		}
		return $form;
	}
	
}