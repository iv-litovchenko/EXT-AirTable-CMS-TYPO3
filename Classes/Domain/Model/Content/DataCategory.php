<?php
namespace Litovchenko\AirTable\Domain\Model\Content;

use Litovchenko\AirTable\Utility\BaseUtility;

class DataCategory extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'keyInDatabase' => 'tx_data_category',
		'name' => 'Материал (категория)',
		'description' => 'Категории для материалов',
		'baseFields' => [
			'pid',
			'RType',
			#'RTypeSub',
			'title',
			'alt_title',
			'status', // disabled, deleted
			'date_create',
			'date_update',
			'date_start',
			'date_end',
			'sorting',
			'service_note',
			'propmedia_files',
			'propmedia_thumbnail',
			'propref_beauthor',
			#'propref_attributes',
			'propref_parent',
			'bodytext_preview',
			'bodytext_detail',
			'propmedia_pic_preview',
			'propmedia_pic_detail',
			'keywords',
			'description',
			'slug'
		],
		'dataFields' => []
	];
	
	# const ENTITY  = 'tx_data_category'; // Eav
	
    /**
     * This is an optional feature.
     * Record types similar to "doktype (pages)" and "CType (tt_content)"
     * @return array
     */
    public static function baseRTypes()
    {
		return \Litovchenko\AirTable\Domain\Model\Content\Data::baseRTypes();
    }
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		//$configuration['ctrl']['...'] = 1;
		$configuration['ctrl']['typeicon_column'] = 'RType';
		$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
		if(!empty($dataType)){
			$configuration['columns']['RType']['config']['default'] = $dataType;
		}
		$configuration['columns']['RType']['config']['readOnly'] = 1;
		$configuration['columns']['RType']['config']['renderType'] = 'selectSingle';
		// $configuration['columns']['RType']['config']['fieldWizard']['selectIcons']['disabled'] = '';
	}
	
	/**
	* Условие по умолчанию (тип записи для админки)
	* @return query
	*/
    public static function globalScopeDataTypeDefault($builder)
    {
		if(TYPO3_MODE === 'BE'){
			$class = get_called_class();
			$table = BaseUtility::getTableNameFromClass($class);
			$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
			if(isset($dataType)){
				$builder->where($table.'.Rtype', '=', $dataType);
			}
		}
	}
}
?>