<?php
namespace Litovchenko\AirTable\Domain\Model\Content;
use Litovchenko\AirTable\Utility\BaseUtility;

class DataType extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'keyInDatabase' => 'tx_data_type',
		'name' => 'Материал (тип)',
		'description' => '',
		'baseFields' => [
			'uidkey' => ['readOnly' => 1],
			'RType' => [
				'readOnly' => 1,
				'name' => 'Тип материала',
				'items' => [
					1 => 'Записи с поддержкой контроллера',
					2 => 'Только записи (для выборки)',
					3 => 'Только записи (технические)'
				]
			],
			'title' => ['readOnly' => 1],
			'sorting' => ['readOnly' => 1],
			'service_note' => ['readOnly' => 1],
			#'propref_attributes' => ['readOnly' => 1]
		],
		'dataFields' => [
			#'icon' => [
			#	'type' => 'Input',
			#	'name' => 'Иконка @typo3/icons class',
			#	'itemsProcFunc' => 'Litovchenko\AirTable\Domain\Model\Content\DataType->doItems',
			#	'position' => [
			#		'*' => 'props,1'
			#	]
			#],
			'props_default' => [
				'type' => 'Enum',
				'name' => 'Стандартные поля (для материала)',
				'show' => 1,
				// 'readOnly' => 1,
				'doNotCheck' => 1,
				'itemsProcFunc' => 'Litovchenko\AirTable\Domain\Model\Content\DataType->doItemsPropsDefault',
				'position' => '*|props|1'
			],
			'props_default_cat' => [
				'type' => 'Enum',
				'name' => 'Стандартные поля (для категорий)',
				'show' => 1,
				// 'readOnly' => 1,
				'doNotCheck' => 1,
				'itemsProcFunc' => 'Litovchenko\AirTable\Domain\Model\Content\DataType->doItemsPropsDefaultCat',
				'displayCond' => 'FIELD:props_default:IN:propref_categories',
				'position' => '*|props|3'
			],
			'prop_data_type_controller' => [
				'type' => 'Input',
				'name' => 'Контроллер-шаблон для обработки записей',
				'readOnly' => 1,
				'position' => '1|attrs|-3'
			],
			'prop_base_pages_row_id' => [
				'type' => 'Input',
				'name' => 'Родительская страница',
				'readOnly' => 1,
				'position' => '1|attrs|2'
			],
			'prop_urls' => [
				'type' => 'Input',
				'name' => 'Url-адреса',
				'readOnly' => 1,
				'position' => '1|attrs|3'
			],
			'prop_add_new_button' => [
				'type' => 'Flag',
				'name' => 'Добавить кнопку "Создать запись" на административной панели',
				'readOnly' => 1,
				'position' => '*|props|500'
			],
		],
		'relationalFields' => [
			'propref_group' => [
				'type' => 'Rel_Mto1',
				'name' => 'Группа',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Content\DataTypeGroup',
				'foreignKey' => 'proprefinv_datatypes',
				'show' => 1,
				'readOnly' => 1,
				'position' => '*|rels|10'
			],
			'propref_subtypes' => [
				'type' => 'Rel_1ToM',
				'name' => 'RTypeSub - Подтипы записей // Todo',
				'description' => 'Пример: новость, статья, ссылка',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Content\DataTypeSub',
				'foreignKey' => 'proprefinv_datatype',
				'show' => 1,
				'position' => '*|rels|20'
			],
			'propref_subtypescat' => [
				'type' => 'Rel_1ToM',
				'name' => 'RTypeSub - Подтипы записей для категорий // Todo',
				'description' => 'Пример: корень, портфель, дирректория',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Content\DataTypeSub',
				'foreignKey' => 'proprefinv_datatypeforcat',
				'show' => 1,
				'displayCond' => 'FIELD:props_default:IN:propref_categories',
				'position' => '*|rels|30'
			],
		]
	];
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		//$configuration['ctrl']['...'] = 1;
		$configuration['ctrl']['readOnly'] = 1;
		
		#$filter = [];
		#$filter['orderBy'] = ['sorting','desc'];
		#$rowsGet = \Litovchenko\AirTable\Domain\Model\Content\DataType::recSelect('get',$filter);
		#foreach($rowsGet as $k => $v){
		#	$configuration['ctrl']['typeicon_classes'][$v['uid']] = $v['icon'];
		#}
	}
	
    /**
     * Custom value set (user func)
     * It is possible to use a selection from the database
     * return $config
     */
    public static function doItems($config)
    {
        $itemList = []; // If database
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/air_table/Resources/Public/Icons/DataType/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."*") as $filename) {
			$config['items'][] = [
				basename($filename), 
				basename($filename),
				'EXT:air_table/Resources/Public/Icons/DataType/'.basename($filename)
			];
		}
        return $config;
    }
	
    /**
     * Custom value set (user func)
     * It is possible to use a selection from the database
     * return $config
     */
    public static function doItemsPropsDefault($config)
    {
        $itemList = []; // If database
		$class = 'Litovchenko\AirTable\Domain\Model\Content\Data';
		foreach($GLOBALS['TCA']['tx_data']['columns'] as $k => $v){
			$annotationDTCU = BaseUtility::getClassFieldAnnotationValueNew($class,$k,'AirTable\Field\DataTypeConditionUse');
			if(in_array('tx_data',explode(',',$annotationDTCU))){
				$postfix = '';
				if($k == 'propref_categories'){
					$postfix = ' - пересохраните форму, а также установите для категорий стандартное поле "Родительская запись"!';
				}
				$config['items'][] = [
					$GLOBALS['LANG']->sL($v['label']) . $postfix, 
					$k
				];
			}
		}
        return $config;
    }
	
    /**
     * Custom value set (user func)
     * It is possible to use a selection from the database
     * return $config
     */
    public static function doItemsPropsDefaultCat($config)
    {
        $itemList = []; // If database
		$class = 'Litovchenko\AirTable\Domain\Model\Content\DataCategory';
		foreach($GLOBALS['TCA']['tx_data_category']['columns'] as $k => $v){
			$annotationDTCU = BaseUtility::getClassFieldAnnotationValueNew($class,$k,'AirTable\Field\DataTypeConditionUse');
			if(in_array('tx_data_category',explode(',',$annotationDTCU))){
				$config['items'][] = [
					$GLOBALS['LANG']->sL($v['label']), 
					$k
				];
			}
		}
        return $config;
    }
}
?>