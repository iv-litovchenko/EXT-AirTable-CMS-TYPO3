<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractFieldRelelationInverse;
use Litovchenko\AirTable\Domain\Model\Fields\Rel_MTo1;
use Litovchenko\AirTable\Utility\BaseUtility;

class Rel_1ToM_Inverse extends AbstractFieldRelelationInverse
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> '1:M Связь один ко многим (реверс)',
		'description' 	=> 'Для создания двухсторонней связи'
	];
	
	const IMPORT = true;
    const REQPOSTFIXCURRENTFIELD = ''; // _row_id
    const REQPOSTFIXFOREIGNFIELD = ''; // _rows
	
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
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= ' Inverse';

		#$_foreignModelName = self::getForeignModelName($model, $field); // foreign model
		#$_foreignTableName = self::getForeignTableName($model, $field); // foreign table
		#$_foreignFieldName = self::getForeignFieldName($model, $field); // foreign key
		
		// $GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
		// $GLOBALS['TCA'][$table]['columns'][$field]['config']['readOnly'] = 1;
		Rel_MTo1::buildConfigurationRelationSelect($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['readOnly'] = 1;
	}
	
	// Автоматизированная выборка связей 
	public function refProvider($obj, $class, $field){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		return $obj->belongsTo($annotationForeignModel, $field, 'uid');
	}
	
	// Создание связей
	public function refAttach($obj, $class, $field, $idTwo = null, $data = []){
		$obj->{$field}('withoutGlobalScopes')->associate(intval($idTwo)); // belongsTo
		return $obj->save();
	}
	
	// Удаление связей
	public function refDetach($obj, $class, $field, $idTwo = null, $data = []){
		$obj->{$field}('withoutGlobalScopes')->dissociate(); // belongsTo
		return $obj->save();
	}

}
