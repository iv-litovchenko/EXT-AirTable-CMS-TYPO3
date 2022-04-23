<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class ExampleTable extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Таблица с примерами',
		'description' => 'Примеры доступных полей для редактирования',
		'formSettings' => [
			'tabs' => [
				'Rel_Test' => 'Rel Test (###COUNT###)',
				'Rel_1To1' => '1:1 (###COUNT###)',
				'Rel_1ToM' => '1:M (###COUNT###)',
				'Rel_MTo1' => 'M:1 (###COUNT###)',
				'Rel_MToM' => 'M:M (###COUNT###)',
				'Rel_Poly_1To1' => 'Poly 1:1 (###COUNT###)',
				'Rel_Poly_1ToM' => 'Poly 1:M (###COUNT###)',
				'Rel_Poly_MToM' => 'Poly M:M (###COUNT###)'
			],
		],
		'baseFields' => [
			'pid',
			'RType',
			#'RTypeSub',
			'title' => [
				'required' => 1
			],
			'alt_title' => [
				'required' => 1
			],
			'service_note',
			'date_create',
			'date_update',
			'date_start',
			'date_end',
			'sorting',
			'propmedia_files',
			'propmedia_thumbnail',
			'deleted',
			'disabled',
			'status',
			#'propref_beauthor',
			#'propref_content',
			#'propref_attributes',
			#'propref_parent',
			'propref_categories', // propref_category
			'bodytext_preview',
			'bodytext_detail',
			'propmedia_pic_preview',
			'propmedia_pic_detail',
			'keywords',
			'description',
			'slug'
		],
		'dataFields' => [
			'prop_test' => [
				'type' => 'Text',
				'name' => 'Включиться с ошибкой №А',
			],
			'prop_test2' => [
				'type' => 'Text',
				'name' => 'Включиться с ошибкой №Б',
				'position' => '1|props2|0'
			],
			'prop_test3' => [
				'type' => 'Text',
				'name' => 'Включиться с ошибкой №В',
				'position' => '57|nubkj|0'
			],
			'prop_test4' => [
				'type' => 'Text',
				'name' => 'Включиться с ошибкой №Г',
				'position' => [
					'334|props|0', 
					'44|props|0',
					'gre4|props|0',
				]
			],
			'prop_test5' => [
				'type' => 'Text',
				'name' => 'Включиться с ошибкой №Г',
				'position' => [
					'334|nubkj|0',
					'44|nubkj|0',
					'gre4|nubkj|0',
				]
			],
			'prop_test5' => [
				'type' => 'Text',
				'name' => 'Включиться с ошибкой №Д',
				'position' => '*|props2|0'
			],
			'prop_input' => [
				'type' => 'Input',
				'name' => 'Строка',
				'description' => 'Однострочное поле',
				'placeholder' => 'Однострочное поле',
				'liveSearch' => 1,
				'max' => 100,
				'size' => 24,
				'default' => 35,
				'required' => 1,
				'position' => '*|props|0'
			],
			'prop_text' => [
				'type' => 'Text',
				'name' => 'Тест',
				'description' => 'Многострочное поле',
				'required' => 1,
				'position' => '*|props|0'
			],
			'prop_folder' => [
				'type' => 'Folder',
				'name' => 'Folder',
				'position' => '*|props|0',
			],
			'prop_flag' => [
				'type' => 'Flag',
				'name' => 'Флаг',
				'position' => '*|props|0',
				'items' => [
					1 => 'Отмечен',
				],
			],
			'prop_switch' => [
				'type' => 'Switcher.Int',
				'name' => 'Переключатель',
				'position' => '*|props|0',
				'itemsProcFunc' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable->doItems',
				'items' => [
					0 => 'Без значения',
					1 => 'Значение 1',
					2 => 'Значение 2',
					3 => 'Значение 3',
					4 => 'Значение 4',
					5 => 'Значение 5',
				],
			],
			'prop_enum' => [
				'type' => 'Enum',
				'name' => 'Список значений',
				'position' => '*|props|0',
				'itemsProcFunc' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable->doItems',
				'items' => [
					1 => 'Один',
					2 => 'Два',
					3 => 'Три',
				],
			],
			'prop_date' => [
				'type' => 'Date',
				'name' => 'Дата',
				'position' => '*|props|0'
			],
			'prop_test_flag' => [
				'type' => 'Flag',
				'name' => 'Показать дополнительные поля',
				'position' => '*|props|0',
				'onChangeReload' => 1,
				'items' => [
					1 => 'Показать дополнительные поля',
				],
			],
			'prop_test_field_1' => [
				'type' => 'Input',
				'name' => 'Дополнительное поле 1',
				'position' => '*|props|0',
				'displayCond' => 'FIELD:prop_test_flag:=:1',
			],
			'prop_test_field_2' => [
				'type' => 'Input',
				'name' => 'Дополнительное поле 2',
				'position' => '*|props|0',
				'displayCond' => 'FIELD:prop_test_flag:=:1',
			],
			'prop_test_field_3' => [
				'type' => 'Input',
				'name' => 'Дополнительное поле 3',
				'position' => '*|props|0',
				'displayCond' => 'FIELD:prop_test_flag:=:1',
			]
		],
		'mediaFields' => [
			'propmedia_media' => [
				'type' => 'Media_M.Mix',
				'name' => 'Файлы',
				'position' => '*|props|0',
				'maxItems' => 10,
			]
		],
		'relationalFields' => [
		
			// 1-1
			'propref_exampletable1' => [
				'type' => 'Rel_1To1',
				'name' => 'Тест связи',
				'position' => '*|Rel_1To1|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable1',
				'foreignKey' => 'proprefinv_exampletable',
				'show' => 1,
			],
			'propref_exampletable1b' => [
				'type' => 'Rel_1To1',
				'name' => 'Тест связи (аналогично B)',
				'position' => '*|Rel_1To1|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable1',
				'foreignKey' => 'proprefinv_exampletableb',
				'show' => 1,
			],
			'propref_exampletable1c' => [
				'type' => 'Rel_1To1',
				'name' => 'Тест связи (аналогично C)',
				'position' => '*|Rel_1To1|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable1',
				'foreignKey' => 'proprefinv_exampletablec',
				'show' => 1,
			],
			
			// 1-M
			'propref_exampletable2' => [
				'type' => 'Rel_1ToM',
				'name' => 'Тест связи',
				'position' => '*|Rel_1ToM|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable2',
				'foreignKey' => 'proprefinv_exampletable',
				'show' => 1,
			],
			'propref_exampletable2b' => [
				'type' => 'Rel_1ToM',
				'name' => 'Тест связи (аналогично B)',
				'position' => '*|Rel_1ToM|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable2',
				'foreignKey' => 'proprefinv_exampletableb',
				'show' => 1,
			],
			'propref_exampletable2c' => [
				'type' => 'Rel_1ToM',
				'name' => 'Тест связи (аналогично C)',
				'position' => '*|Rel_1ToM|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable2',
				'foreignKey' => 'proprefinv_exampletablec',
				'show' => 1
			],
			'propref_exampletable2d' => [
				'type' => 'Rel_1ToM',
				'name' => 'Тест связи (аналогично D)',
				'position' => '*|Rel_1ToM|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable2',
				'foreignKey' => 'proprefinv_exampletabled',
				'show' => 1
			],
			
			// M-1
			'propref_exampletable3' => [
				'type' => 'Rel_MTo1',
				'name' => 'Тест связи',
				'position' => '*|Rel_MTo1|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable3',
				'foreignKey' => 'proprefinv_exampletable',
				'show' => 1,
			],
			'propref_exampletable3b' => [
				'type' => 'Rel_MTo1',
				'name' => 'Тест связи (аналогично B)',
				'position' => '*|Rel_MTo1|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable3',
				'foreignKey' => 'proprefinv_exampletableb',
				'show' => 1,
			],
			'propref_exampletable3c' => [
				'type' => 'Rel_MTo1',
				'name' => 'Тест связи (аналогично C)',
				'position' => '*|Rel_MTo1|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable3',
				'foreignKey' => 'proprefinv_exampletablec',
				'show' => 1
			],
			
			// M-M
			'propref_exampletable4' => [
				'type' => 'Rel_MToM.Large',
				'name' => 'Тест связи',
				'position' => '*|Rel_MToM|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable4',
				'foreignKey' => 'proprefinv_exampletable',
				'show' => 1,
			],
			'propref_exampletable4b' => [
				'type' => 'Rel_MToM.Large',
				'name' => 'Тест связи (аналогично B)',
				'position' => '*|Rel_MToM|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable4',
				'foreignKey' => 'proprefinv_exampletableb',
				'show' => 1,
			],
			'propref_exampletable4c' => [
				'type' => 'Rel_MToM.Large',
				'position' => '*|Rel_MToM|0',
				'name' => 'Тест связи (аналогично C)',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable4',
				'foreignKey' => 'proprefinv_exampletablec',
				'show' => 1,
			],
			
			// Poly 1-1
			'propref_exampletable5' => [
				'type' => 'Rel_Poly_1To1',
				'name' => 'Тест связи №1',
				'position' => '*|Rel_Poly_1To1|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable5',
				'foreignKey' => 'proprefinv_exampletable',
			],
			
			// Poly 1-M
			'propref_exampletable6' => [
				'type' => 'Rel_Poly_1ToM',
				'name' => 'Тест связи №1',
				'position' => '*|Rel_Poly_1ToM|0',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable6',
				'foreignKey' => 'proprefinv_exampletable',
			],
			/*
			// Poly M-M
			#'propref_exampletable7_rows' => [
			#	'type' => 'Rel_Poly_MToM',
			#	'name' => 'Тест связи №1',
			#	'position' => '*|Rel_Poly_MToM|0',
			#	'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable7',
			#	'foreignKey' => 'proprefinv_exampletable_rows',
			#	'show' => 1
			#]
			*/
		]
	];
	
	/**
	* Типы записей по умолчанию
	* @return array
	*/
    public static function baseRTypes()
    {
		return [
			1 => 'Тип 1',
			2 => 'Тип 2',
			3 => 'Тип 3'
		];
	}
	
	/**
	* Пользовательские значения для полей типа Switch, Enum
	* Возможно использовать выборку из БД
	*/
	public static function doItems($config){
		$itemList = [];
		$config['items'][] = [100, 'New item 100'];
		$config['items'][] = [200, 'New item 200'];
		$config['items'][] = [300, 'New item 300'];
		return $config;
	}
	
    /**
     * A set of rules for context-aware validation 
     * @return array
     */
	// public function getRulesArray(): array
	public static function validationRules($params = [])
	{
		$rules = [
			'checkPreInsert' => [
				'title' => [
					'name' => 'Имя',
					'required' => 'Поле не заполнено',
					'min:5' => 'Минипум 5 символов'
				]
			]
		];
		return $rules;
	}
	
	/**
	* Debug content
	* @return content
	*/
    public static function userDebugСontent()
	{
		// Создание связей
		$content = 'User debug content';
		return $content;
	}

	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		//$configuration['ctrl']['...'] = 1;
	}
	
	/**
	* Пользовательское поле
	*/
	public static function specialField($PA, $fObj) {
		$color = (isset($PA['parameters']['Color'])) ? $PA['parameters']['Color'] : 'red';
		$formField  = '
		<div class="btn-group btn-group-justified">
  <a href="#" class="btn btn-primary">Apple</a>
  <a href="#" class="btn btn-primary">Samsung</a>
  <a href="#" class="btn btn-primary">Sony</a>
</div>
<div class="list-group">
  <a href="#" class="list-group-item active">First item</a>
  <a href="#" class="list-group-item">Second item</a>
  <a href="#" class="list-group-item">Third item</a>
</div>
<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-body">Panel Content</div>
  </div>
  <div class="panel panel-default">
    <div class="panel-body">Panel Content</div>
  </div>
</div>

<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading active">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapse1">Collapsible panel</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse">
      <div class="panel-body">Panel Body</div>
      <div class="panel-footer">Panel Footer</div>
    </div>
  </div>
</div>
		<div role="alert alert-warning"><h3>Вкладка!</h3></div>
		<div style="padding: 5px; background-color: ' . $color . ';">';
		$formField .= '<input type="text" name="' . $PA['itemFormElName'] . '"';
		$formField .= ' value="' . htmlspecialchars($PA['itemFormElValue']) . '"';
		$formField .= ' onchange="' . htmlspecialchars(implode('', $PA['fieldChangeFunc'])) . '"';
		$formField .= $PA['onFocus'];
		$formField .= ' /></div>';
		return $formField;
	}
	
	/**
	* Событие создания записи (до/после)
	* @return '';
	*/
    public static function cmdInsert($when, &$table, $id, &$fieldArray)
    {
		self::flashMessage('Создание записи ('.$when.')');
	}
	
	/**
	* Событие обновления записи (до/после)
	* @return '';
	*/
    public static function cmdUpdate($when, &$table, $id, &$fieldArray)
    {
		self::flashMessage('Обновление записи ('.$when.')');
	}
	
	/**
	* Событие удаления записи (до/после)
	* @return '';
	*/
    public static function cmdDelete($when, &$table, $id, &$fieldArray)
    {
		self::flashMessage('Удаление записи ('.$when.')');
	}
	
	// builderUserGlobalScope[Name]($builder) -> GSFlagDeleted
	public function builderGsCustomNameGlobalCondition($builder){
		$builder->orderBy('uid','Desc');
	}
	
	// builderUserLocalScope[Name]($builder) -> userCondPagination()
	public function builderLsCustomNameCondition($agr1 = 5, $arg2 = 4){
		return $this->where('uid','>',55)->select(['uid','title']);
	}
	
	// builderUserRef[Name]($builder) -> RefCountry()...
	public function builderRefCustomNameRelationship(){
		return $this->refProvider('propref_exampletable4_rows');
	}
}
?>