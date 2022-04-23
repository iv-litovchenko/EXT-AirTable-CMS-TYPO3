<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractFieldRelelationInverse;
use Litovchenko\AirTable\Domain\Model\Fields\Rel_1ToM;
use Litovchenko\AirTable\Utility\BaseUtility;

class Rel_MTo1_Inverse extends AbstractFieldRelelationInverse
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'M:1 Связь многие к одному (реверс)',
		'description' 	=> 'Для создания двухсторонней связи'
	];
	
	const IMPORT = true;
    const REQPOSTFIXCURRENTFIELD = ''; // _rows
    const REQPOSTFIXFOREIGNFIELD = ''; // _row_id
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $GLOBALS['TCA'][$table]['columns'][$field]['label'].' // M-1';
		#if(self::isDoubleRelation($model, $field)){
		#	$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= '↔';
		#}
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= ' Inverse';

		$_foreignModelName = self::getForeignModelName($model, $field); // foreign model
		$_foreignTableName = self::getForeignTableName($model, $field); // foreign table
		$_foreignFieldName = self::getForeignFieldName($model, $field); // foreign key
		
		// $GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
		// $GLOBALS['TCA'][$table]['columns'][$field]['config']['readOnly'] = 1;
		Rel_1ToM::buildConfigurationRelation($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['readOnly'] = 1;
		unset($GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems']);
		unset($GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems']);
	}
	
	// Автоматизированная выборка связей 
	public function refProvider($obj, $class, $field){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		return $obj->hasMany($annotationForeignModel, $annotationForeignKey, 'uid');
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
