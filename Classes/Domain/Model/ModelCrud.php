<?php
namespace Litovchenko\AirTable\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

use Litovchenko\AirTable\Utility\BaseUtility;

use Illuminate\Database\Eloquent\Model; // Eav\Model
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Str;
// use Javoscript\MacroableModels\Facades\MacroableModels;
use Closure;

class ModelCrud extends Model implements FormDataProviderInterface
{
	// use \Litovchenko\AirTable\Domain\Model\Traits\Specific\AttributeErrorException;
	use \Litovchenko\AirTable\Domain\Model\Traits\Specific\FuncDynamicRelationships;
	use \Litovchenko\AirTable\Domain\Model\Traits\Specific\FuncRepository;
	use \Litovchenko\AirTable\Domain\Model\Traits\Specific\FuncValidate;
	use \Litovchenko\AirTable\Domain\Model\Traits\Specific\QueryScopes;
	use \Litovchenko\AirTable\Domain\Model\Traits\Specific\TCEmainHook;
	use \Litovchenko\AirTable\Domain\Model\Traits\Specific\FlexMutator;
	
	# const ENTITY  = 'default'; // Eav
	
	protected $validator;
	
    protected $keyExists = false;
	
	protected $primaryKey = 'uid';
	
	protected $dateFormat = 'U';

	public $timestamps = false;
	
    protected $fillable = []; // какие поля могут быть заполнены выполняя User::create()
	protected $guarded = [];
	
	# protected $dates = ['created_at', 'deleted_at']; // какие поля будут типа Carbon
	
	# protected $appends = ['field1', 'field2']; // доп значения возвращаемые в JSON
	
	# use SoftDeletes;
	
	# protected $attributes = [
    #    'delayed' => false // The model's default values for attributes.
    # ];
	
    /**
     * The "booting" method of the model.
	 * User::withoutGlobalScope('TestScope')->get();
	 * User::withoutGlobalScopes()->get();
     *
     * @return void
     */
    protected static function boot()
    {
		parent::boot();
		
		// use Illuminate\Database\Eloquent\Relations\Relation;
		// Пользовательские полиморфные типы
		// Таким образом сделали возможность использовать
		// название таблицы, а не модели в поле "tablenames"
		$classes_list_map = [];
		$allClasses = BaseUtility::getLoaderClasses2();
		$classes = array_merge((array)$allClasses['BackendModelCrud'],(array)$allClasses['BackendModelCrudOverride']);
		foreach($classes as $class) {
			$table = BaseUtility::getTableNameFromClass($class);
			$classes_list_map[$table] = $class;
		}
				
		\Illuminate\Database\Eloquent\Relations\Relation::morphMap($classes_list_map);
		#\Illuminate\Database\Eloquent\Relations\Relation::morphMap([
		#	# 'posts' => 'App\Post',
		#	# 'videos' => 'App\Video',
		#	#'tx_air_table_examples_exampletable' => 'Litovchenko\AirTableExamples\Domain\Model\ExampleTable',
		#	#$table => $class
		#]);
		
		$class = get_called_class();
		$table = BaseUtility::getTableNameFromClass($class);
		$methods = get_class_methods($class);
		foreach($methods as $k => $method){
			
			// Laravel User GL (global scope)
			if(preg_match('/^globalScope/is',$method)){
				// static::addGlobalScope(fn ($query) => $query->orderBy('name'));
				$methodShortName = preg_replace('/^globalScope/is','',$method);
				static::addGlobalScope($methodShortName, function (Builder $queryBuilder) use ($class, $method, $methodShortName) {
					#MacroableModels::addMacro($class, $methodShortName, function() use ($method, $queryBuilder) {
					#	return $this->where('uid','>',5)->where('uid','<',8)->select(['uid','title']);
					#});
					// self::$methodShortName();
					self::$method($queryBuilder);
				});
			}
		
			// Laravel User LS (local scopes)
			#if(preg_match('/^scope/is',$method)){
			#	$methodShortName = preg_replace('/^scope/is','',$method);
			#	$methodShortName = 'custom'.$methodShortName;
			#	MacroableModels::addMacro($class, $methodShortName, function() use ($method) {
			#		// return "Hello, {$this->name}!";
			#		// return $this->where('uid','>',5);
			#		return call_user_func_array(array($this, $method), array_values(func_get_args()));
			#	});
			#}
			
			// Laravel User Ref
			# if(preg_match('/^builderRefCustom/is',$method)){
			# 	$methodShortName = preg_replace('/^builderRefCustom/is','',$method);
			# 	$methodShortName = 'custom'.$methodShortName;
			# 	MacroableModels::addMacro($class, $methodShortName, function() use ($method) {
			# 		// return "Hello, {$this->name}!";
			# 		// return $this->where('uid','>',5);
			# 		return $this->{$method}();
			# 	});
			# }
			
		}
		
		// Laravel whereInMultiple()
		# MacroableModels::addMacro($class, 'whereInMultiple', function($columns, $values) {
		# 	$values = array_map(function (array $value) {
		# 		return "('".implode($value, "', '")."')"; 
		# 	}, $values);
		#
		# 	return $this->whereRaw(
		#		'('.implode($columns, ', ').') in ('.implode($values, ', ').')'
		#	);
		#});
		
		// https://modzone.ru/blog/2017/05/10/eloquent-model-events/
		// https://github.com/gokure/laravel-has-model-callback/blob/master/src/Eloquent/Concerns/HasModelCallback.php
		// https://gist.github.com/simonhamp/2f4fd2e483353fc2de98ddda430330cf
		// https://overcoder.net/q/5099/laravel-model-events-%D1%8F-%D0%BD%D0%B5%D0%BC%D0%BD%D0%BE%D0%B3%D0%BE-%D0%BE%D0%B7%D0%B0%D0%B4%D0%B0%D1%87%D0%B5%D0%BD-%D1%82%D0%B5%D0%BC-%D0%BA%D1%83%D0%B4%D0%B0-%D0%BE%D0%BD%D0%B8-%D0%B4%D0%BE%D0%BB%D0%B6%D0%BD%D1%8B-%D0%B8%D0%B4%D1%82%D0%B8
		// protected static function registerModelEvent($event, $callback)
		# public function saveWithoutEvents(array $options=[])
		# {
		#	return static::withoutEvents(function() use ($options) {
		#		return $this->save($options);
		#	});
		# }
		
		/**
		 * Callbacks of model events, trigger around with create/update/delete or restore.
		 *
		 * Perform sequence will stopped when before* events return `false`.
		 *
		 * | For creates    | For updates       | For deletes       | For restores      |
		 * |----------------|-------------------|-------------------|-------------------|
		 * | beforeSave()   | beforeSave()      | beforeDelete()    | beforeRestore()   |
		 * | beforeCreate() | beforeUpdate()    | *delete()         | beforeSave()      |
		 * | *insert()      | *update()         | afterDelete()     | beforeUpdate()    |
		 * | afterCreate()  | afterUpdate()     |                   | *update()         |
		 * | afterSave()    | afterSave()       |                   | afterUpdate()     |
		 * |                |                   |                   | afterSave()       |
		 * |                |                   |                   | afterRestore()    |
		 *
		 * To call Builder method
		 */
        $hooks = array(
            'saving'    => 'beforeSave',		// #saving : before a record is saved (either created or updated).
            'creating'  => 'beforeCreate',		// #creating : before a record has been created.
            'updating'  => 'beforeUpdate',		// #updating : before a record is updated.
            'deleting'  => 'beforeDelete',		// #deleting : before a record is deleted or soft-deleted.
            // 'restoring' => 'beforeRestore',	// #restoring : before a soft-deleted record is going to be restored.
            'saved'     => 'afterSave',			// #saved : after a record has been saved (either created or updated).
            'created'   => 'afterCreate',		// #created : after a record has been created.
            'updated'   => 'afterUpdate',		// #updated : after a record has been updated.
            'deleted'   => 'afterDelete',		// #deleted : after a record has been deleted or soft-deleted.
            // 'restored'  => 'afterRestore',	// #restored : after a soft-deleted record has been restored.
			// #retrieved : after a record has been retrieved.
        );
        #$class = static::class;
		#foreach ($hooks as $hook => $method) {
		#	if (method_exists($class, 'cmdEvent')) {
		#		static::$hook(function ($model) use ($method) { // static::saving(function ($model) use ($method) {
		#			#if (!$model->isValid()) return 555;
		#			// выполнить какую-нибудь логику
		#			// переопределить какое-нибудь свойство, например $model->something = transform($something);
		#			// $model->uuid = (string)Uuid::generate();
		#			// Validator::validate($model->toArray(), static::$rules);
		#			// Validator::validate($model->toArray(), $model->getRulesArray());
		#			// Validator::validate($model->toArray(), static::$rules);
		#			return $model->cmdEvent('FE',$method); //     return $model->beforeSave();
		#		});
		#	}
        #}
    }
	
	/**
	 * Автоматизированная выборка связей (если не создана функция)
	*/
    public function __call($name, $arguments)
	{
		// '/^getDefaultRelation_(.*?)_Method$/is'
		#if(preg_match('/^(.*?)_func$/is',$name,$matchField)){
		#	$field = $matchField[1];
		#	if($arguments[0] == 'withoutGlobalScopes'){
		#		return $this->refProvider($field)->withoutGlobalScopes();
		#	} else {
		#		return $this->refProvider($field);
		#	}
		#}
		
		// '/^getDefaultRelation_(.*?)_Method$/is'
		#if(preg_match('/^propref_(.*?)$/is',$name,$matchField) 
		#	|| preg_match('/^proprefinv_(.*?)$/is',$name,$matchField) 
		#		|| preg_match('/^propmedia_(.*?)$/is',$name,$matchField)){
		
		$model = get_class($this);
		if(
			property_exists($model, 'TYPO3')
			&&
		    (
				(
					in_array($name, $model::$TYPO3['baseFields'])
					||
					array_key_exists($name, $model::$TYPO3['baseFields'])
				)
				// && 
				// preg_match('/^propmedia_(.*?)$/is',$name)
			)
			|| 
			isset($model::$TYPO3['mediaFields'][$name])
			|| 
			isset($model::$TYPO3['relationalFields'][$name])
		)
		{
			$field = $name;
			if($arguments[0] == 'withoutGlobalScopes'){
				return $this->refProvider($field)->withoutGlobalScopes();
			} else {
				return $this->refProvider($field);
			}
		}
		
		if($model == 'Litovchenko\AirTable\Domain\Model\Fal\SysFileReference' && $name == 'file'){
			$field = 'uid_local';
			if($arguments[0] == 'withoutGlobalScopes'){
				return $this->refProvider($field)->withoutGlobalScopes();
			} else {
				return $this->refProvider($field);
			}
		}
		
		return parent::__call($name, $arguments);
	}
	
    public function newEloquentBuilder($query)
    {
       return new \Litovchenko\AirTable\Domain\Model\CustomEloquentBuilder($query);
    }
	
	#public function newQuery(){
	#	return parent::newQuery()->where('cat', '=', 6);
	#}
	
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
		if(!empty($this->table)){
			$table = $this->table;
		} else {
			$table = BaseUtility::getTableNameFromClass(get_called_class());
		}
		return $table;
	}
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
	}
	
    /**
     * Record event (before / after) - // Todo https://laravel.ru/posts/338
     * @return ''; // $when, &$table, $id, &$fieldArray
     */
    public function cmdEvent($mode = 'FE', $command)
    {
		print $command;
		exit();
		// self::flashMessage('Создание записи ('.$when.')');
        // self::flashMessage('Создание записи ('.$when.')');
		$command = 'insert || update || delete';
        if ($when == 'before') {
            //
        } else  {
            //
        }
    }
	
    /**
     * Checks for already existing mm relation of tx_myext_object
     * Returns true, if no mm relation found
     * 
     * @param array $configuration
     * @param \TYPO3\CMS\Backend\Form\FormDataProvider\EvaluateDisplayConditions $evaluateDisplayConditions
     * @return bool
     */
    public function isVisibleDisplayConditionMatcher(array $configuration, $evaluateDisplayConditions = null)
    {
        $result = false;
        if (isset($configuration['conditionParameters'][0])) {
			$table = $configuration['conditionParameters'][0];
			$field = $configuration['conditionParameters'][1];
			$id = $configuration['record']['uid'];
			$RType = $configuration['record']['RType'][0];
			
			// При создании новой записи будет ошибка сохранения Eav, т.к. uid-еще не существует NEW601fee50e8c80091179941...
			#if($field == 'propref_attributes' && strstr($id,'NEW')) {
			#	$result = false;
			#
			#} else {
				if($table == 'tx_data') {
					$filter = [];
					$filter['where'] = ['uid','=',$RType];
					$filter['orWhere'] = ['uidkey','=',$RType];
					$row = \Litovchenko\AirTable\Domain\Model\Content\DataType::recSelect('first',$filter);
					$props_default = $row['props_default'];
					$props_default = explode(',',$props_default);
					if(in_array($field,$props_default)){
						$result = true;
					}
				} elseif($table == 'tx_data_category') {
					$filter = [];
					$filter['where'] = ['uid','=',$RType];
					$filter['orWhere'] = ['uidkey','=',$RType];
					$row = \Litovchenko\AirTable\Domain\Model\Content\DataType::recSelect('first',$filter);
					$props_default_cat = $row['props_default_cat'];
					$props_default_cat = explode(',',$props_default_cat);
					if(in_array($field,$props_default_cat)){
						$result = true;
					}
				}
			#}
        }
        return $result;
    }
	
	/**
	* Типы записей по умолчанию
	* @return array
	*/
    public static function baseRTypes()
    {
		$class = get_called_class();
		$thisIs = $class::$TYPO3['thisIs'];
		if ( $thisIs == 'BackendModelCrudOverride' ) {
			$table = BaseUtility::getTableNameFromClass($class);
			if(isset($GLOBALS['TCA'][$table]['ctrl']['type'])){
				// $typeColumn = $GLOBALS['TCA'][$table]['ctrl']['type'];
				// $typeColumnConfig = $GLOBALS['TCA'][$table]['columns'][$typeColumn];
				$array = [];
				// $array[0] = 'Тип по умолчанию';
				foreach($GLOBALS['TCA'][$table]['types'] as $kT => $vT){
					if($GLOBALS['LANG']){
						$array[$kT] = $GLOBALS['LANG']->sL($vT[0]);
					} else {
						$array[$kT] = '-';
					}
				}
				return $array;
			} else {
				return [
					0 => 'Тип по умолчанию'
				];
			}
			
		} elseif ( $thisIs == 'BackendModelCrud' ) {
			
			// Пользовательские типы...
			if( count($class::$TYPO3['baseFields']['RType']['items']) > 0 ){
				return $class::$TYPO3['baseFields']['RType']['items'];
				
			} else {
				return [
					0 => 'Тип по умолчанию'
				];
			}
			
		} elseif ( $thisIs == 'BackendModelExtending' ) {
			
			// $table = BaseUtility::getTableNameFromClass($class);
			// foreach($GLOBALS['TCA'][$table]['types'] as $kT => $vT){
			// 	$array[$kT] = '-';
			// }
			// return $array;
			if( count($class::$TYPO3['baseFields']['RType']['items']) > 0 ){
				$pRTypes = get_parent_class($class)::baseRTypes();
				$cRTypes = $class::$TYPO3['baseFields']['RType']['items'];
				return $pRTypes + $cRTypes;
			} else {
				return get_parent_class($class)::baseRTypes();
			}
			
		}
	}
	
	/**
	* Табы по умолчанию
	* @return array
	*/
    public static function baseTabs()
    {
		$class = get_called_class();
		$thisIs = $class::$TYPO3['thisIs'];
		if ( $thisIs == 'BackendModelCrudOverride' ) {
			return [
				// 'extended' => 'Расширенное',
			];
		
		} elseif ( $thisIs == 'BackendModelCrud' ) {
			
			$tabs = [
				'main' => 'Основное',
				'access' => 'Доступ',
				'preview' => 'Анонс',
				'detail' => 'Подробно',
				'content' => 'Содержимое',
				'files' => 'Файлы',
				'props' => 'Свойства (###COUNT###)',
				'media' => 'Медиа (###COUNT###)',
				'rels' => 'Связи (###COUNT###)',
				'attrs' => 'Характеристики', // 'props_flexform' => 'Гибкие свойства',
			];
			
			// Пользовательские табы...
			if( count($class::$TYPO3['formSettings']['tabs']) > 0 ){
				$tabs += $class::$TYPO3['formSettings']['tabs'];
			}
			
			$tabs += [
				'cat' => 'Категоризация',
				'extended' => 'Расширенное',
			];
			
			return $tabs;
		
		} elseif ( $thisIs == 'BackendModelExtending' ) {
			if( count($class::$TYPO3['formSettings']['tabs']) > 0 ){
				$pTabs = get_parent_class($class)::baseTabs();
				$cTabs = $class::$TYPO3['formSettings']['tabs'];
				return $pTabs + $cTabs;
			} else {
				return get_parent_class($class)::baseTabs();
			}
		}
	}
	
    /**
     * Get media (mutator).
     *
     * @param  string  $value
     * @return string
     */
	#public function getMediaAttribute($value)
	#{
	#	// $recordId = isset($row['_LOCALIZED_UID']) ? intval($row['_LOCALIZED_UID']) : intval($row['uid']);
	#	
	#	/** @var $fileRepository \TYPO3\CMS\Core\Resource\FileRepository */
	#	$fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
	#	$files = $fileRepository->findByRelation('pages', 'media', $this->uid);
	#	return $files;
    #}
	
    #public function toArray()
    #{
    #    $array = parent::toArray();
    #    foreach ($this->getMutatedAttributes() as $key)
    #    {
    #        if ( ! array_key_exists($key, $array)) {
    #            $array[$key] = $this->prop_flexform;   
    #        }
    #    }
    #    return $array;
    #}
	
}