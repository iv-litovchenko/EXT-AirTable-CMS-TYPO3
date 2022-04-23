<?php
namespace Litovchenko\AirTable\Domain\Model;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Builder;
use Litovchenko\AirTable\Utility\BaseUtility;

class CustomEloquentBuilder extends Builder 
{
    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
		$model = get_class($this->model);
		$classHasAttributes = false;
		if(BaseUtility::hasSpecialField($model,'propref_attributes') == true){
			$classHasAttributes = true;
		} elseif ( $model == 'Litovchenko\AirTable\Domain\Model\Content\Pages' ) {
			$classHasAttributes = true;
		} elseif ( $model == 'Litovchenko\AirTable\Domain\Model\Content\TtContent' ) {
			$classHasAttributes = true;
		}
		
		// Laravel EAV selectAttributeAll() // Выборка всех атрибутов...
		if($classHasAttributes == true){
			if($method == 'selectAttributeAll') {
				#$filter = [];
				#$filter['select'] = ['attr_key'];
				#$filter['where'] = ['entity_type','=','tx_data___1']; //...
				#$row = \Litovchenko\AirTable\Domain\Model\Eav\SysAttribute::recSelect('get',$filter);
				return $this; // Todo
			}
		}
		
		// Laravel EAV select() // Выборка конкретных атрибутов...
		// $filter['selectAttribute.10'] = ['attr_color']; // Сделать! 'attr.*'
		// (SELECT `value` FROM `default_int` WHERE `attribute_id` = 1  AND `tx_data`.`uid` = `entity_id`) AS `attr_status`
		// $subQ1 = '(SELECT attr_value FROM sys_value WHERE duplicate_attr_key = "attr_color" AND tx_data.uid = sys_value.entity_row_id) AS attr_color';
		// $subQ2 = '(SELECT attr_value FROM sys_value WHERE duplicate_attr_key = "attr_email" AND tx_data.uid = sys_value.entity_row_id) AS attr_email';
		// $filter['select'] = ['title',DB::raw($subQ1),DB::raw($subQ2)]; // ,'attr.*'
		// ->selectAttribute();
		if($classHasAttributes == true){
			if(preg_match('/select/is',$method) && preg_match('/Attribute$/is',$method)) {
				$table = $this->query->from; // tx_data
				$attr_key = $parameters[0];
				#if(!in_array($attr_key,$this->bindings['select'])){
					$subQ = '
						(SELECT attr_value FROM sys_value WHERE duplicate_attr_key = "'.$attr_key.'" 
							AND '.$table.'.uid = sys_value.entity_row_id
						) AS '.$attr_key;
					return $this->addSelect(DB::raw($subQ));
				#}
			}
		}
		
		// Laravel EAV whereAttribute() // Фильтрация...
		// $filter['whereAttribute.10'] = ['attr_color', '#000000'];
		// INNER JOIN `default_int` AS `attr_color_attr` ON `tx_data`.`uid` = `attr_color_attr`.`entity_id` AND `attr_color_attr`.`attribute_id` = 2
		// WHERE `tx_data`.`uid` = 30 AND `tx_data`.`deleted` = 0 AND `attr_color_attr`.`value` = 1)
		// -> whereAttribute
		// -> orWhereAttribute
		// -> ...
		if($classHasAttributes == true){
			if(preg_match('/where/is',$method) && preg_match('/Attribute$/is',$method)) {
				$funcBuilder = preg_replace('/Attribute$/is','',$method);
				$table = $this->query->from; // tx_data
				$attr_key = $parameters[0];
				$attr_value = $parameters[1];
				if(!in_array($attr_key,$this->query->bindings['join'])){
					$this->join('sys_value as '.$attr_key, function ($join) use ($table, $attr_key) {
						$join->on($table.'.uid', '=', $attr_key.'.entity_row_id');
						$join->where($attr_key.'.duplicate_attr_key', '=', $attr_key);
					});
				}
				$parameters[0] = $parameters[0].'.attr_value';
				return call_user_func_array(array($this, $funcBuilder), array_values($parameters)); // ->where(...)
				#return $this->$funcBuilder()
			}
		}
		
		// Laravel EAV orderBy() // Сортировка...
		// $filter['orderByAttribute.10'] = ['attr_color','desc'];
		if($classHasAttributes == true){
			if(preg_match('/orderBy/is',$method) && preg_match('/Attribute$/is',$method)) {
				$attr_key = $parameters[0];
				$attr_sort = $parameters[1];
				$this->selectAttribute($attr_key); // -> отсылка selectAttribute()
				$this->orderBy($attr_key,$attr_sort);
				return $this;
			}
		}
		
		return parent::__call($method, $parameters);
	}

    public function myMethod()
    {
        // some method things
    }
	
    /**
    * Get the SQL representation of the query.
    *
    * @return string
    */
	#public function toSql()
	#{
	#	#$model = get_class($this->model);
	#	#if(BaseUtility::hasSpecialField($model,'propref_attributes') == true){
	#	#	$this->fixColumns();
	#	#}
    #    return parent::toSql();
    #}
	
    protected function fixColumns()
    {
        $columns = $this->columns;
        if (is_null($columns)) {
            return $this;
        }
	}
	
    /**
     * @param  array                                          $columns
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $values
     * @return \Illuminate\Database\Query\Builder
    public function whereInMultiple(array $columns, $values)
    {
		$values = [
			['type_a', 1],
			['type_a', 2],
			['type_b', 1],
			['type_c', 1],
		];
        $values = array_map(function (array $value) {
            return "('".implode($value, "', '")."')"; 
        }, $values);

        return static::query()->whereRaw(
            '('.implode($columns, ', ').') in ('.implode($values, ', ').')'
        );
    }
     */

	/**
     * @param  array                                          $columns
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $values
     * @return \Illuminate\Database\Query\Builder
    public function whereNotInMultiple(array $columns, $values)
    {
		$values = [
			['type_a', 1],
			['type_a', 2],
			['type_b', 1],
			['type_c', 1],
		];
        $values = array_map(function (array $value) {
            return "('".implode($value, "', '")."')"; 
        }, $values);

        return static::query()->whereRaw(
            '('.implode($columns, ', ').') not in ('.implode($values, ', ').')'
        );
    }
     */
	
    /**
     * Scope a query to only include the last n days records
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
    public function whereDateBetween($query,$fieldName,$dateStart,$dateEnd)
    {
        return static::query()->whereDate($fieldName,'>=',$dateStart)->whereDate($fieldName,'<=',$dateEnd);
    }
     */
	
    /**
     * Scope a query to only include the last n days records
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
    public function whereDateNotBetween($query,$fieldName,$dateStart,$dateEnd)
    {
        return static::query()->whereDate($fieldName,'<=',$dateStart)->whereDate($fieldName,'<=',$dateEnd);
    }
     */
	
    /**
    public function whereTimestamp($columns, $operator = '=', $value = '')
    {
		// SELECT FROM_UNIXTIME(111885200, '%d') == 13
		// $filter['whereRaw'] = ['(uid > ? and uid < ?)', [1,1000]]; // DB::raw(1)
		// $filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%j') = ?", 11]; // %d -> with zero
		// $filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%n') = ?", 1]; // %m -> with zero
		// $filter['whereRaw'] = ["FROM_UNIXTIME(date_create, '%Y') = ?", 2021];
		
		$bind = [
			$columns,
			$operator.' '.intval($value)
		];
		
		return $this->whereRaw('FROM_UNIXTIME(?, "%Y") ? ',$bind);
	}
     */
}