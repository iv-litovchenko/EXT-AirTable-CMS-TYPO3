<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractFieldRelelation;
use Litovchenko\AirTable\Utility\BaseUtility;

class Rel_1ToM extends AbstractFieldRelelation
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> '1:M Связь один ко многим',
		'description' 	=> 'Информация о связи пишется в колонку внешней таблицы',
		'incEav' 		=> 0,
	];
	
	const IMPORT = true;
    const REQPOSTFIXCURRENTFIELD = ''; // _rows
    const REQPOSTFIXFOREIGNFIELD = ''; // _row_id
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		$sql = []; // Имеем в виду, что может быть связь "На себя"
		$sql[$table][$field] = 'int(11) DEFAULT \'0\' NOT NULL';
		$sql[parent::getForeignTableName($model,$field)][parent::getForeignFieldName($model,$field)] = 'int(11) DEFAULT \'0\' NOT NULL';
		return $sql;
    }
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $GLOBALS['TCA'][$table]['columns'][$field]['label'].' // 1-M';
		#if(self::isDoubleRelation($model, $field)){
		#	$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= 'D';
		#}

		self::buildConfigurationRelation($model, $table, $field);
	}
		
    /**
     * @return array
     */
    public static function buildConfigurationRelation($model, $table, $field)
    {
		if(BaseUtility::hasSpecialField(parent::getForeignModelName($model,$field),'sorting') == true){
			$enabledControls_useSortable = 1;
			$enabledControls_dragdrop = 1;
			$enabledControls_sort = 1;
			$sort = 'sorting';
		} else {
			$enabledControls_useSortable = 0;
			$enabledControls_dragdrop = 0;
			$enabledControls_sort = 0;
			$sort = '';
		}
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'inline';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = 0;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = 100;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['behaviour'] = [
			'enableCascadingDelete' => false, // Запрещяем каскадное удаление
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['appearance'] = [
			#'collapseAll' => 1,
			'expandSingle' => 0,
			'newRecordLinkAddTitle' => 1,
			'levelLinksPosition' => 'top',
			'showPossibleLocalizationRecords' => 0,
			'showRemovedLocalizationRecords' => 0,
			'showAllLocalizationLink' => 0,
			'showSynchronizationLink' => 0,
			'useSortable' => $enabledControls_useSortable,
			'enabledControls' => [
				'info' => 1,
				'new' => 1,
				'dragdrop' => $enabledControls_dragdrop,
				'sort' => $enabledControls_sort,
				'hide' => 1,
				'delete' => 1,
				'localize' => 0
			]
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table'] = parent::getForeignTableName($model,$field); // -> Устанавливать автоматически
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_field'] = parent::getForeignFieldName($model,$field); // -> Устанавливать автоматически
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_sortby'] = $sort; // -> Устанавливать автоматически
    }
	
	// Автоматизированная выборка связей 
	public function refProvider($obj, $class, $field){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		$fieldSort = $GLOBALS['TCA'][BaseUtility::getTableNameFromClass($annotationForeignModel)]['ctrl']['sortby'];
		if($fieldSort != ''){
			return $obj->hasMany($annotationForeignModel, $annotationForeignKey, 'uid')->orderBy($fieldSort,'asc');
		} else {
			return $obj->hasMany($annotationForeignModel, $annotationForeignKey, 'uid');
		}
	}
	
	// Создание связей
	public function refAttach($obj, $class, $field, $idTwo = null, $data = []){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		return $annotationForeignModel::refAttach($annotationForeignKey,$idTwo,$obj->uid);
	}
	
	// Удаление связей
	public function refDetach($obj, $class, $field, $idTwo = null, $data = []){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		return $annotationForeignModel::refDetach($annotationForeignKey,$idTwo);
	}

}