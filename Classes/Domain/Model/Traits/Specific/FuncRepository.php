<?php
namespace Litovchenko\AirTable\Domain\Model\Traits\Specific;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use Litovchenko\AirTable\Utility\BaseUtility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Capsule\Manager as DB;
use Closure;

trait FuncRepository
{
	// Выбрать запись(и)
	public static function _recSelectArFilter($model, $filter){
		foreach($filter as $fName => $fArgs){
			
			#$filter['where.10'] = ['uid','>=',1];
			#$filter['where.20'] = ['uid','>=',1];
			if(preg_match('/.([0-9]+)$/is',$fName)){
				$fName = preg_replace('/.([0-9]+)/is','',$fName);
			}
			
			#$filter['where'] = ['uid','>=',1];
			if(is_array($fArgs) && !empty($fArgs)){
				if(current($fArgs) instanceof Closure){ // with();!!!
					$model = $model->{$fName}($fArgs);
				} else {
					$model = call_user_func_array(array($model, $fName), array_values($fArgs));
				}
			
			#$filter['inRandomOrder'] = true;
			}elseif($fArgs === true){
				$model = $model->{$fName}();
			
			#$filter['select'] = 'uid';
			}elseif(!empty($fArgs)){
				$model = $model->{$fName}($fArgs);
				
			} else {
				
			}
		}
		#exit();
		return $model;
	}
	
	// Выбрать запись(и)
	// Проверяет существует ли таблица для модели?
	// В основном эти заморочки понадобились для момента установки расширения через менеджер расширений
	/*
	public static function recSelectSafe($method = 'get', $callback = null, $tables = ''){
		if(!class_exists('Illuminate\Support\Facades\Schema')){
			return false;
		}
		
		$tables = explode(',',$tables);
		sort($tables);
		
		// Cache
		$tables_cache_id = md5(serialize(array_values($tables)));
		if(!isset($GLOBALS['Litovchenko.AirTable.VarCache.recSelectSafe'][$tables_cache_id])){
			foreach($tables as $k => $v){
				if(!\Illuminate\Support\Facades\Schema::hasTable($v)){
					return false;
				}
			}
		}
		
		$GLOBALS['Litovchenko.AirTable.VarCache.recSelectSafe'][$tables_cache_id] = true;
		return self::recSelect($method,$callback);
	}
	*/
	
	// Выбрать запись(и)
	public static function recSelect($method = 'get', $callback = null, $pluck = null, $keyBy = null){
		$mResult = '';
		$class = get_called_class();
		
		// preg_match('/^getBy(.*)$/',$method)
		if(preg_match('/^getBy(.*)$/',$method)) {
			$model = $class::getModel();
		
		// $callback ($id)
		} elseif(is_numeric($callback)) {
			$model = $class::where('uid','=',$callback);
		
		// $callback ($filter)
		} elseif(is_array($callback)) {
			$model = $class::getModel()->newQueryWithoutRelationships();
			$model = self::_recSelectArFilter($model, $callback);
		
		// $callback
		} elseif ($callback instanceof Closure) {
			// call_user_func($q);
			$model = $class::getModel()->newQueryWithoutRelationships();
			$callback($model); // $callback($query = $model);
			
		// other
		} else {
			$model = $class::getModel();
		}
		
		if($model){
			if($method == 'obj' || $method == 'object'){
				return $model;
			}
			if($method == 'toSql'){
				return $model->toSql();
			}
			$manyMethods = explode(",",$method);
			if(count($manyMethods)>1){ // Отказался от этого
				print 100200300;
				exit();
			} else {
				// FixBug count() with limit && offset
				if($method == 'count'){
					$temp = clone($model);
					return $temp->limit(1)->offset(0)->{'count'}();
				} elseif(preg_match('/^getBy(.*)$/',$method)) {
					// $temp = $model->getById($model::query(),228)->get();
					$args = func_get_args();
					$args[0] = $model::query();
					$temp = call_user_func_array(array($model, $method), array_values($args)); // ->get()
				} else {
					$temp = $model->{$method}();
				}
				if(is_object($temp)){
					if($pluck != null){
						return $temp->pluck($pluck)->toArray();
					} elseif($keyBy != null){
						return $temp->keyBy($keyBy)->toArray();
					} else {
						return $temp->toArray();
					}
				} elseif(!empty($temp)){
					return $temp;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
	
	// Создать запись
	#public static function recInsert($data = []){
	#	$class = get_called_class();
	#	
	#	// Много строк для вставки
	#	$keys = implode(array_keys($data));
	#	if(preg_match("/^([0-9])+$/is",$keys)){
	#		return $class::getModel()->insert($data);
	#		
	#	// 1 строка для вставки
	#	} else {
	#		return $class::getModel()->insertGetId($data);  // insertId
	#	}
	#}
	
	// Создать запись (1 запись)
	public static function recInsert($data = [], $table = null){
		$class = get_called_class();
		if($table != null){
			return $class::getModel()->setTable($table)->insertGetId($data);  // insertId
			
		} else {
			return $class::getModel()->insertGetId($data);  // insertId
		}
	}
	
	// Создать запись (несколько записей)
	public static function recInsertMultiple($data = [], $table = null){
		$class = get_called_class();
		
		#$filter = [];
		#$filter['selectRaw'] = ' MAX(uid) as before_max_id ';
		#$filter['withoutGlobalScopes'] = true;
		#$before_max_id = self::recSelect('first',$filter);
		
		#$result = $class::getModel()->insert($data);
		
		#$filter = [];
		#$filter['selectRaw'] = ' MAX(uid) as after_max_id ';
		#$filter['withoutGlobalScopes'] = true;
		#$after_max_id = self::recSelect('first',$filter);
		
		#if($result == 1){
		#	$uidList = [];
		#	$filter = [];
		#	$filter['select'] = 'uid';
		#	$filter['where.Ar'] = [];
		#	$filter['where.Ar'][]  = ['uid','>',$before_max_id];
		#	$filter['where.Ar'][]  = ['uid','<=',$after_max_id];
		#	$filter['withoutGlobalScopes'] = true;
		#	$rows = self::recSelect('get',$filter);
		#	foreach($rows as $k => $v){
		#		$uidList[] = $v['uid'];
		#	}
		#	if(count($uidList)>0){
		#		return $uidList;
		#	}
		#	return 0;
		#}
		
		#return 0;
		
		if(count($data)>0 && !empty($data)){
			$filter = [];
			$filter['selectRaw'] = ' MAX(uid) as after_max_id ';
			$filter['withoutGlobalScopes'] = true;
			$after_max_id = self::recSelect('first',$filter);
			$after_max_id = $after_max_id['after_max_id'];
			
			$strForHash = serialize($data).$after_max_id.rand(1,1000000).microtime();
			$keyNumHash = intval(BaseUtility::numHash($strForHash,8));
			foreach($data as $k => $v){
				$data[$k]['insertuidshash'] = $keyNumHash;
			}
			
			if($table != null){
				$result = $class::getModel()->setTable($table)->insert($data);
			} else {
				$result = $class::getModel()->insert($data);
			}
			
			if($result == 1){
				$uidList = [];
				$filter = [];
				$filter['select'] = 'uid';
				$filter['withoutGlobalScopes'] = true;
				$filter['where'] = ['insertuidshash','=',$keyNumHash];
				$rows = self::recSelect('get',$filter);
				foreach($rows as $k => $v){
					$uidList[] = $v['uid'];
				}
				if(count($uidList)>0){
					return $uidList;
				}
			}
		}
		return 0;
	}
	
	// Обновить запись
	public static function recUpdate($id, $data = []){
		
		// Если полное обновление
		if($id == 'full'){
			$id = [];
			$id['withoutGlobalScopes'] = true;
			$id['where'] = ['uid','>',0];
		}
		
		$model = self::recSelect('obj',$id);
		if($model){
			$affectedCount = $model->update($data);
			if($affectedCount > 0){
				return $affectedCount;
			}
			return 0;
		} else {
			return 0;
		}
	}
	
	// Удалить запись
	public static function recDelete($id, $destroy = false){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$field = $GLOBALS['TCA'][$table]['ctrl']['delete'];
		
		// Если полное удаление
		if($id == 'full'){ // truncate
			$id = [];
			$id['withoutGlobalScopes'] = true;
			$id['where'] = ['uid','>',0];
		}
		
		if(!empty($field) && $destroy == false){
			return self::recUpdate($id,[$field=>1]);
		} else {
			$model = self::recSelect('obj',$id);
			if($model){
				$affectedCount = $model->delete();
				if($affectedCount > 0){
					return $affectedCount;
				}
				return 0;
			} else {
				return 0;
			}
		}
	}
	
	// Информаци о записи (запись удалена?)
	public static function recIsDeleted($id){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		
		$field = $GLOBALS['TCA'][$table]['ctrl']['delete'];
		if(!empty($field)){
			$filter = [];
			$filter['select'] = [];
			$filter['select'][] = $field;
			$filter['withoutGlobalScopes'] = true;
			$filter['userWhereUid'] = $id;
			$row = self::recSelect('first',$filter);
			if($row[$field] == 1){
				return true;
			}
		}
		
		return false;
	}
	
	// Информаци о записи (запись активна?)
	public static function recIsDisabled($id){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		
		$field = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'];
		if(!empty($field)){
			$filter = [];
			$filter['select'] = [];
			$filter['select'][] = $field;
			$filter['withoutGlobalScopes'] = true;
			$filter['userWhereUid'] = $id;
			$row = self::recSelect('first',$filter);
			if($row[$field] == 1){
				return true;
			}
		}
		
		return false;
	}
	
	// Информаци о записи (запись активна?)
	public static function recIsPublished($id){
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		
		$filter = [];
		$filter['select'] = [];
		$filter['select'][] = 'uid';
		$filter['userWhereUid'] = $id;
		
		$filter['withoutGlobalScope'] = [];
		foreach(static::$globalScopes[$class] as $scopeName => $scopeSettings){
			if($scopeName == "DateStart" || $scopeName == "DateEnd"){
				continue;
			}
			$filter['withoutGlobalScope'][] = [$scopeName];
		}
		
		$fieldDateStart = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['starttime'];
		$fieldDateEnd = $GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['endtime'];
		
		if(!empty($fieldDateStart)){
			$filter['select'][] = $fieldDateStart;
		}
		if(!empty($fieldDateEnd)){
			$filter['select'][] = $fieldDateEnd;
		}
		
		$exists = self::recSelect('exists',$filter);
		if($exists){
			return true;
		}
		
		return false;
	}
	
	#public static function recGlobalScopesOn(){
	#	$path = 'default';
	#	$class = get_called_class();
	#	$table = BaseUtility::getTableNameFromClass($class);
	#	$methods = get_class_methods($class);
	#	foreach($methods as $k => $method){
	#		if(preg_match('/^userGlobalScope/is',$method)){
	#			$methodName = preg_replace('/^userGlobalScope/is','',$method);
	#			$GLOBALS['TCA'][$table]['ctrl']['globalScopes'][$path][$methodName] = 'on';
	#		}
	#	}
	#}
	
	#public static function recGlobalScopeOn($scope){
	#	$path = 'default';
	#	$class = get_called_class();
	#	$table = BaseUtility::getTableNameFromClass($class);
	#	$GLOBALS['TCA'][$table]['ctrl']['globalScopes'][$path][$scope] = 'on';
	#}
	
	// function clearGlobalScopes()
	#public static function recGlobalScopesOff(){
	#	$path = 'default';
	#	$class = get_called_class();
	#	$table = BaseUtility::getTableNameFromClass($class);
	#	$methods = get_class_methods($class);
	#	foreach($methods as $k => $method){
	#		if(preg_match('/^userGlobalScope/is',$method)){
	#			$methodName = preg_replace('/^userGlobalScope/is','',$method);
	#			$GLOBALS['TCA'][$table]['ctrl']['globalScopes'][$path][$methodName] = 'off';
	#		}
	#	}
	#}
	
	#public static function recGlobalScopeOff($scope){
	#	$path = 'default';
	#	$class = get_called_class();
	#	$table = BaseUtility::getTableNameFromClass($class);
	#	$GLOBALS['TCA'][$table]['ctrl']['globalScopes'][$scope] = 'off';
	#}
	
	/**
	* @return $rows
	*/
	public function getById($id = 1, $fields = ['*'])
	{
		// recSelect();
		return self::where('uid','=',$id)->select($fields)->first();
	}
	
	/**
	* @return $rows
	*/
	public function getByList($fields = ['*'], $orderBy = 'uid', $limit = 30, $page = 1)
	{
		// recSelect(); ...
		// $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $groupBy, $orderBy, $limit);`
		return self::select($fields)->orderBy($orderBy)->limit($limit)->get();
	}
	
}