<?php
namespace Litovchenko\AirTable\Domain\Model\Traits\Specific;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use Litovchenko\AirTable\Utility\BaseUtility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Closure;

trait FuncDynamicRelationships
{
	// Автоматизированная выборка связей 
	public function refProvider($field)
	{
		$class = get_called_class(); 
		$annotationRelationType = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field');
		$classField = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationRelationType;
		if(!class_exists($classField)){
			throw new \Exception(
				sprintf(
					'The link type field [%s] was not found. See class: [%s]',
					$field,
					$class
				)
			);
		}
		return $classField::refProvider($this,$class,$field);
	}
	
	// Создание связей (Media)
	public static function mediaAttach($field, $idOne = null, $idTwo = null, $data = [])
	{
		return self::refAttach($field, $idOne, $idTwo, $data);
	}
	
	// Создание связей
	public static function refAttach($field, $idOne = null, $idTwo = null, $data = [])
	{
		$class = get_called_class();
		$modelFind = static::withoutGlobalScopes()->where('uid','=',$idOne)->first();
		if($modelFind){
			$fieldWithoutFunc = $field; //preg_replace("/_func$/is",'',$field);
			$annotationRelationType = BaseUtility::getClassFieldAnnotationValueNew($class,$fieldWithoutFunc,'AirTable\Field');
			$classField = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationRelationType;
			if(is_array($idTwo) && !empty($idTwo)){
				foreach($idTwo as $k => $v){
					$classField::refAttach($modelFind,$class,$fieldWithoutFunc,$v,$data);
				}
				return true;
			} else {
				return $classField::refAttach($modelFind,$class,$fieldWithoutFunc,$idTwo,$data);
			}
		} else {
			return false;
		}
	}
	
	// Сортировка связей // todo
	public static function refSort($field, $idOne = null, $idTwo = null, $data = [])
	{
	}
	
	// Создание связей
	public static function mediaDetach($field, $idOne = null, $idTwo = null, $data = [])
	{
		return self::refDetach($field, $idOne, $idTwo, $data);
	}
	
	// Удаление связей
	public static function refDetach($field, $idOne = null, $idTwo = null, $data = [])
	{
		$class = get_called_class();
		$modelFind = static::withoutGlobalScopes()->where('uid','=',$idOne)->first();
		if($modelFind){
			$fieldWithoutFunc = $field; //preg_replace("/_func$/is",'',$field);
			$annotationRelationType = BaseUtility::getClassFieldAnnotationValueNew($class,$fieldWithoutFunc,'AirTable\Field');
			$classField = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationRelationType;
			if(is_array($idTwo) && !empty($idTwo)) {
				foreach($idTwo as $k => $v){
					$classField::refDetach($modelFind,$class,$fieldWithoutFunc,$v,$data);
				}
				return true;
			} elseif($idTwo == 'all') {
				return $classField::refDetach($modelFind,$class,$fieldWithoutFunc,null,$data);
			} else {
				return $classField::refDetach($modelFind,$class,$fieldWithoutFunc,$idTwo,$data);
			}
		} else {
			return false;
		}
	}
	
	// Список Uid в связи
	public static function refCollection($field, $id)
	{
		$uidList = [];
		$fieldWithoutFunc = $field; //preg_replace("/_func$/is",'',$field);
		
		$class = get_called_class();
		$annotationField = BaseUtility::getClassFieldAnnotationValueNew($class,$fieldWithoutFunc,'AirTable\Field');
		$annotationFieldForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$fieldWithoutFunc,'AirTable\Field\ForeignModel');
		$annotationFieldForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$fieldWithoutFunc,'AirTable\Field\ForeignKey');
		
		// ->select(['uid',$fieldWithoutFunc])
		$modelFind = static::withoutGlobalScopes()->where('uid','=',$id)
		->with([$field => function($q) use ($annotationField, $annotationFieldForeignModel, $annotationFieldForeignKey) {
			$table = BaseUtility::getTableNameFromClass($annotationFieldForeignModel);
			$q->withoutGlobalScopes();
			/*
			if(preg_match('/^Rel_Poly_/is',$annotationField) && !preg_match('/_Inverse$/is',$annotationField)){
				$q->select([
						$table.'.uid',
						$table.'.'.$annotationFieldForeignKey,
						$table.'.foreign_table',
						$table.'.foreign_field',
						$table.'.foreign_uid',
						$table.'.foreign_sortby'
					]
				);
			} else {
				$q->select([
						$table.'.uid',
						$table.'.'.$annotationFieldForeignKey
					]
				);
			}
			*/
		}])->first();
		
		if($modelFind){
			$toArray = $modelFind->toArray();
			if(isset($toArray[$field]['uid'])){
				$uidList[] = $toArray[$field]['uid'];
			} else {
				foreach($toArray[$field] as $k => $v){
					$uidList[] = $v['uid'];
				}
			}
		}
		return $uidList;
	}
	
	// Проверяем есть ли связь // todo
	public static function refBroken($field)
	{
		#$class = get_called_class();
		#$fieldWithoutMethod = preg_replace('/_Method$/is','',$field);
		#$_foreignModelName = BaseUtility::getClassFieldAnnotationValueNew($class,$fieldWithoutMethod,'AirTable\Field\ForeignModel');
		#$_foreignKeyName = BaseUtility::getClassFieldAnnotationValueNew($class,$fieldWithoutMethod,'AirTable\Field\ForeignKey');
		#
		#print $_foreignModelName.'<br />';
		#print $_foreignTableName;
		#
		#SELECT uid, title, country_id FROM `tx_air_table_examples5_city`
		#WHERE country_id NOT IN (SELECT uid FROM tx_air_table_examples5_country) AND country_id > 0
		#
		#$modelFind = $class::where($fieldWithoutMethod,'>',0)->whereNotIn($fieldWithoutMethod,function($qSub){
		#	$qSub->select('uid')->from('tx_air_table_examples5_country');
		#})->get()->toArray();
		#print "<pre>";
		#print_r($modelFind);
		#exit();
		#if($modelFind){
		#	
		#} else {
		#	return '';
		#}
	}
}