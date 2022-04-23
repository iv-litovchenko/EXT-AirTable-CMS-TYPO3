<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractFieldRelelation;
use Litovchenko\AirTable\Utility\BaseUtility;

class Rel_Poly_1To1 extends AbstractFieldRelelation
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Полиморфная связь 1:1',
		'description' 	=> 'Информация о связи пишется в 4 колонки внешней таблицы',
		'incEav' 		=> 0,
	];
	
    const REQPOSTFIXCURRENTFIELD = ''; // _row
    const REQPOSTFIXFOREIGNFIELD = ''; // _row
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		$sql = []; // Имеем в виду, что может быть связь "На себя"
		$sql[$table][$field] = 'int(11) DEFAULT \'0\' NOT NULL';
		$sql[parent::getForeignTableName($model,$field)]['foreign_table'] = 'varchar(255) DEFAULT \'\' NOT NULL';
		$sql[parent::getForeignTableName($model,$field)]['foreign_field'] = 'varchar(255) DEFAULT \'\' NOT NULL';
		$sql[parent::getForeignTableName($model,$field)]['foreign_uid'] = 'int(11) DEFAULT \'0\' NOT NULL';
		$sql[parent::getForeignTableName($model,$field)]['foreign_sortby'] = 'int(11) DEFAULT \'0\' NOT NULL';
		$sql[parent::getForeignTableName($model,$field)][parent::getForeignFieldName($model,$field)] = 'int(11) DEFAULT \'0\' NOT NULL';
		return $sql;
    }
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		self::buildConfigurationRelation($model, $table, $field);
		
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $GLOBALS['TCA'][$table]['columns'][$field]['label'].' // Poly 1-1';
	}

    /**
     * @return array
     */
    public static function buildConfigurationRelation($model, $table, $field)
    {
		if(BaseUtility::hasSpecialField(parent::getForeignModelName($model,$field),'sorting') == true
			or parent::getForeignTableName($model,$field) == 'tt_content' // Специально для таблицы "tt_content" - устанавливаем сортировку по полю "sorting"
		){
			$sort = 'sorting';
		} else {
			$sort = '';
		}
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'inline';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = 0;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = 1;
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
			'useSortable' => 1,
			'enabledControls' => [
				'info' => 1,
				'new' => 1,
				'dragdrop' => 1,
				'sort' => 1,
				'hide' => 1,
				'delete' => 1,
				'localize' => 0
			]
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table'] = parent::getForeignTableName($model,$field); // -> Устанавливать автоматически
		
		// Лишнее поле - с ним не получилось записывать не таблицу, а модель
		// $GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_field'] = 'foreign_table'; // -> Поле куда пишется название таблицы
		
		// -> Сопоставление записи
		// Лишнее поле - с ним не получилось записывать не таблицу, а модель
		unset($GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_field']);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_match_fields'] = [
			'foreign_table' => $table,
			'foreign_field' => $field
		];
		
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_field'] = 'foreign_uid'; // -> Поле куда пишется ID
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_sortby'] = $sort; // -> Поле определения сортировки
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		// Добавляем информацию во внешнуюю таблицу
		if(BaseUtility::hasSpecialField(parent::getForeignModelName($model,$field),'Litovchenko\AirTable\Domain\Model\Traits\RelPolyInverse') == true){
			#$tableLabel = BaseUtility::getClassAnnotationValueNew($model,'AirTable\Label');
			#$GLOBALS['TCA'][parent::getForeignTableName($model,$field)]['columns']['foreign_table']['config']['items'][$model] = [
			#	0=>$tableLabel,
			#	1=>$model
			#];
		}
	}
	
	// Автоматизированная выборка связей 
	public function refProvider($obj, $class, $field){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		
		// User model:
		# public function profile()
		#{
		#	return $this->morphTo('profile', 'profile_type', 'profile_id');
		#}

		// Admin model:
		#public function user()
		#{
		#	return $this->morphOne(User::class, 'profile', 'profile_type', 'profile_id', 'id');
		#}

		// morphOne($related, $name, $type = null, $id = null, $localKey = null)
		return $obj->morphOne(
			$annotationForeignModel,		// 1 User::class
			$annotationForeignKey,			// 2 profile
			'foreign_table',				// 3 profile_type
			'foreign_uid',					// 4 profile_id
			'uid'							// 5 id
		)->where('foreign_field', '=', $field);
	}
	
	// Создание связей
	public function refAttach($obj, $class, $field, $idTwo = null, $data = []){} //Todo
	
	// Удаление связей
	public function refDetach($obj, $class, $field, $idTwo = null, $data = []){} //Todo

}