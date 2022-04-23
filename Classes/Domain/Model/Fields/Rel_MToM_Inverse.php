<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractFieldRelelationInverse;
use Litovchenko\AirTable\Domain\Model\Fields\Rel_MToM;
use Litovchenko\AirTable\Utility\BaseUtility;

class Rel_MToM_Inverse extends AbstractFieldRelelationInverse
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'M:M Связь многие ко многим (реверс)',
		'description' 	=> 'Для создания двухсторонней связи'
	];
	
	const IMPORT = true;
    const REQPOSTFIXCURRENTFIELD = ''; // _rows
    const REQPOSTFIXFOREIGNFIELD = ''; // _rows
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $GLOBALS['TCA'][$table]['columns'][$field]['label'].' // M-M';
		#if(self::isDoubleRelation($model, $field)){
		#	$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= '↔';
		#}
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= ' Inverse';

		$_foreignModelName = self::getForeignModelName($model, $field); // foreign model
		$_foreignTableName = self::getForeignTableName($model, $field); // foreign table
		$_foreignFieldName = self::getForeignFieldName($model, $field); // foreign key
		
		// $GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
		// $GLOBALS['TCA'][$table]['columns'][$field]['config']['readOnly'] = 1;
		Rel_MToM::buildConfigurationRelationSelect($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['readOnly'] = 1;
		
		if(BaseUtility::hasSpecialField($_foreignModelName,'RType') == true) {
			if(BaseUtility::hasSpecialField($_foreignModelName,'title') == 1){
				$sort = ' '.$_foreignTableName.'.RType ASC, '.$_foreignTableName.'.title ASC ';
			} else {
				$sort = ' '.$_foreignTableName.'.RType ASC, '.$_foreignTableName.'.uid ASC ';
			}
		} elseif(BaseUtility::hasSpecialField($_foreignModelName,'title') == true) {
			$sort = ' '.$_foreignTableName.'.title ASC ';
		} else {
			$sort = ' '.$_foreignTableName.'.uid ASC ';
		}
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table'] = $_foreignTableName;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_where']['orderBy'][] = $sort;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_match_fields']['tablename'] = $_foreignTableName;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_match_fields']['fieldname'] = $_foreignFieldName;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_opposite_field'] = $_foreignFieldName; // Реверс
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['fieldControl']['addRecord']['options']['table'] = $_foreignTableName;
		unset($GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems']);
		unset($GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems']);
		
		#if($table == 'tx_air_table_examples6_ingredient'){
		#	print "<pre>";
		#	print_r($GLOBALS['TCA'][$table]['columns'][$field]);
		#	exit();
		#}
	}
	
	// Автоматизированная выборка связей 
	public function refProvider($obj, $class, $field){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		return $obj->belongsToMany($annotationForeignModel, 'sys_mm', 'uid_foreign', 'uid_local')
			->where('sys_mm.tablename', '=', BaseUtility::getTableNameFromClass($annotationForeignModel))
				->where('sys_mm.fieldname', '=', $annotationForeignKey)
					->orderBy('sys_mm.uid_foreign', 'asc');
	}
	
	// Создание связей
	public function refAttach($obj, $class, $field, $idTwo = null, $data = []){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		
		// belongsToMany
		$arSync = [];
		$indentArray = [
			'tablename' => BaseUtility::getTableNameFromClass($annotationForeignModel), 
			'fieldname' => $annotationForeignKey
		];
		#if(is_array($idTwo)){
		#	foreach($idTwo as $k => $id){
		#		$arSync[$id] = $indentArray;
		#		$arSync[$id]['sorting'] = $k;
		#	}
		#} else {
			$arSync[$idTwo] = $indentArray;
			$arSync[$idTwo]['sorting'] = 1;
		#}
		$obj->{$field}('withoutGlobalScopes')->syncWithoutDetaching($arSync);
		return $obj->save();
	}
	
	// Удаление связей
	public function refDetach($obj, $class, $field, $idTwo = null, $data = []){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		
		// belongsToMany
		$obj->{$field}('withoutGlobalScopes')
			->wherePivot('tablename', '=', BaseUtility::getTableNameFromClass($annotationForeignModel))
				->wherePivot('fieldname', '=', $annotationForeignKey)
					->detach($idTwo); // Если = "0" - удаляеться все связи
		return $obj->save();
	}

}
