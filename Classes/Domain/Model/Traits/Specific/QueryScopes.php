<?php
namespace Litovchenko\AirTable\Domain\Model\Traits\Specific;

use Litovchenko\AirTable\Utility\BaseUtility;

trait QueryScopes
{
	/**
	* Условие по умолчанию (корзина)
	* @return query
	*/
    public static function globalScopeFlagDeleted($builder)
    {
		// if(BaseUtility::hasSpecialField($class,'deleted')){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['delete'];
		if(TYPO3_MODE === 'BE' && !empty($field)){
			#if(!defined("restrictionDeletedIgnore")){
			if(!BaseUtility::_GETuc('restrictionDeleted',0,'ListController')){
				$builder->where($table.'.'.$field, '=', 0);
			}
			#}
		}elseif(TYPO3_MODE === 'FE' && !empty($field)){
			$builder->where($table.'.'.$field, '=', 0);
		}
	}
	
	/**
	* Условие по умолчанию (активность записи)
	* @return query
	*/
    public static function globalScopeFlagDisabled($builder)
    {
		// if(BaseUtility::hasSpecialField($class,'disabled')){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'];
		if(TYPO3_MODE === 'FE' && !empty($field)){
			if($GLOBALS['BE_USER']->uc['phptemplate_showHiddenRecords'] != 1){
				$builder->where($table.'.'.$field, '=', 0);
			}
		}
	}
	
	/**
	* Условие по умолчанию (статус активности-доступности)
	* @return query
	*/
    public static function globalScopeSwitcherStatus($builder)
    {
		// if(BaseUtility::hasSpecialField($class,'status')){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['status'];
		if(TYPO3_MODE === 'BE' && !empty($field)){
			#if(!defined("restrictionDeletedIgnore")){
			if(!BaseUtility::_GETuc('restrictionDeleted',0,'ListController')){
				$builder->where($table.'.'.$field, '!=', -3);
			#}
			}
		}elseif(TYPO3_MODE === 'FE' && !empty($field)){
			if($GLOBALS['BE_USER']->uc['phptemplate_showHiddenRecords'] == 1){
				$builder->where($table.'.'.$field, '!=', -3);
			} else {
				$builder->where($table.'.'.$field, '=', 0);
			}
		}
	}
	
	/**
	* Условие по умолчанию (начало публикации)
	* @return query
	*/
    public static function globalScopeDateStart($builder)
    {
		// if(BaseUtility::hasSpecialField($class,'date_start')){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['starttime'];
		if(TYPO3_MODE === 'FE' && !empty($field)){
			$builder->where($table.'.'.$field, '<=', $GLOBALS['SIM_EXEC_TIME']);
		}
	}
	
	/**
	* Условие по умолчанию (окончание публикации)
	* @return query
	*/
    public static function globalScopeDateEnd($builder)
    {
		// if(BaseUtility::hasSpecialField($class,'date_end')){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['endtime'];
		if(TYPO3_MODE === 'FE' && !empty($field)){
			$builder->where($table.'.'.$field, '=', 0);
			$builder->orWhere($table.'.'.$field, '>', $GLOBALS['SIM_EXEC_TIME']);
		}
	}
	
	/**
	* Условие по умолчанию (сортировка)
	* @return query
	*/
    public static function globalScopeSorting($builder)
    {
		// if(BaseUtility::hasSpecialField($class,'sorting')){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['sortby'];
		if(!empty($field) && !empty($field)){
			#if(TYPO3_MODE === 'FE'){
			#	$builder->orderBy($table.'.'.$field, 'asc');
			#}
		}
	}
	
	/**
	* Локальный скоуп // withTrashed()
	* Локальный скоуп // onlyTrashed()
	* @return $query
	*/
	public function scopeMyWhereFlagDeletedIn($query, $flag)
    {
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['delete'];
		if(isset($flag)){
			if(is_array($flag)){
				return $query->withoutGlobalScope('FlagDeleted')
								->whereIn($field, $flag);
			} else {
				return $query->withoutGlobalScope('FlagDeleted')
								->where($field, '=', $flag);
			}
		}
		return $query;
    }
	
	/**
	* Локальный скоуп // scopeVisible()
	* Локальный скоуп // scopePublished()
	* Локальный скоуп // scopeDraft()
	* Локальный скоуп // scopeFinished()
	* Локальный скоуп // scopeDeactivated()
	* @return $query
	*/
	public function scopeMyWhereFlagDisabledIn($query, $flag)
    {
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'];
		if(isset($flag)){
			if(is_array($flag)){
				return $query->withoutGlobalScope('FlagDisabled')
								->whereIn($field, $flag);
			} else {
				return $query->withoutGlobalScope('FlagDisabled')
								->where($field, '=', $flag);
			}
		}
		return $query;
    }
	
	/**
	* Локальный скоуп
	* Для постраничного листера
	* @return query
	*/
	#function scopeMySelectMinimize($query)
	#{
	#	// SelectMinimizeInc
	#	#if($annotationSelectMinimizeInc == 1){
	#	#	$GLOBALS['TCA'][$table]['ctrl']['AirTable.SelectMinimizeInc'][] = $field;
	#	#}
	#	
	#	// SelectMinimize
	#	$class = get_called_class();
	#	$table = BaseUtility::getTableNameFromClass($class);
	#	
	#	$selectMinimizeIncList = [];
	#	foreach($GLOBALS['TCA'][$table]['columns'] as $kCol => $vConf){
	#		$annotationSelectMinimizeInc = BaseUtility::getClassFieldAnnotationValueNew($class,$kCol,'AirTable\Field\SelectMinimizeInc');
	#		if(preg_match("/_rows$/is",$kCol) || preg_match("/_row$/is",$kCol) || preg_match("/_row_id$/is",$kCol)){
	#			$selectMinimizeIncList[] = $kCol;
	#		}elseif($annotationSelectMinimizeInc == 1){
	#			$selectMinimizeIncList[] = $kCol;
	#		}
	#	}
	#	
	#	return $query->select($selectMinimizeIncList);
	#}
	
	/**
	* Локальный скоуп
	* Для постраничного листера
	* @return $this
	*/
	public function scopeMyPagination($query, $pagePosition = 1, $limit = 30)
	{
		if ($pagePosition <= 0) {
			$pagePosition = 1;
		}
		if (!empty($pagePosition) && $pagePosition > 0) {
			$offsetValue = ($pagePosition-1)*$limit;
		} else {
			$offsetValue = 0;
		}
		return $query->limit(intval($limit))->offset(intval($offsetValue));
	}
	
}