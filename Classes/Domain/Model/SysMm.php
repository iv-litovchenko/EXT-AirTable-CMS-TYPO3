<?php
namespace Litovchenko\AirTable\Domain\Model;

class SysMm extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModelCrud',
		'keyInDatabase'	=> 'sys_mm',
		'name' 			=> 'Связь M-M',
		'description' 	=> '',
		'formSettings' => [
			'tabs' => [
				'local' => 'Локальные данные (где выбрано?)',
				'foreign' => 'Внешние данные (что выбрано?)',
				'indent' => 'Дополнительные данные'
			]
		],
		'dataFields' => [
			'tablename' => [
				'name' => 'Таблица локальная',
				'type' => 'Input',
				'show' => 1,
				'readOnly' => 0,
				'doNotCheck' => 1,
				'position' => '*|local|0'
			],
			'fieldname' => [
				'name' => 'Колонка локальная',
				'type' => 'Input',
				'show' => 1,
				'doNotCheck' => 1,
				'position' => '*|local|1'
			],
			'uid_local' => [
				'name' => 'Ключ (ID) локальный',
				'type' => 'Input.Number',
				'show' => 1,
				'doNotCheck' => 1,
				'position' => '*|local|2'
			],
			'sorting' => [
				'name' => 'Сортировка локальная',
				'type' => 'Input.Number',
				'show' => 1,
				'doNotCheck' => 1,
				'position' => '*|local|3'
			],
			'uid_foreign' => [
				'name' => 'Ключ (ID) внешний',
				'type' => 'Input.Number',
				'show' => 1,
				'doNotCheck' => 1,
				'position' => '*|foreign|1'
			],
			'sorting_foreign' => [
				'name' => 'Сортировка внешняя',
				'type' => 'Input.Number',
				'show' => 1,
				'doNotCheck' => 1,
				'position' => '*|foreign|2'
			],
			'ident' => [
				'name' => 'Дополнительные данные',
				'type' => 'Text',
				'doNotCheck' => 1,
				'position' => '*|indent|0'
			]
		]
	];
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		$configuration['ctrl']['label'] = 'tablename';
		
		// Для полиморфизма Poly-M:M - Todo
		// Если не идет процесс редактирования записи в таблице "sys_mm" устанавливаем Select-конфиги
		#if(!isset($GLOBALS['_GET']['edit']['sys_mm'])){
			#$configuration['columns']['uid_local']['config']['type'] = 'select';
			#$configuration['columns']['uid_local']['config']['renderType'] = 'selectSingle';
			#$configuration['columns']['uid_local']['config']['foreign_table'] = 'tx_air_table_examples1_exampletable';
			#$configuration['columns']['uid_local']['config']['items'][] = [0=>'-- Выберите значение --',1=>''];
			#$configuration['columns']['uid_foreign']['config']['type'] = 'select';
			#$configuration['columns']['uid_foreign']['config']['renderType'] = 'selectSingle';
			#$configuration['columns']['uid_foreign']['config']['foreign_table'] = 'tx_air_table_examples1_exampletable7';
			#$configuration['columns']['uid_foreign']['config']['items'][] = [0=>'-- Выберите значение --',1=>''];
		#}
	}
	
}
?>