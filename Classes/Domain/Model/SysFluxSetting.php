<?php
namespace Litovchenko\AirTable\Domain\Model;

class SysFluxSetting extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModelCrud',
		'keyInDatabase'	=> 'sys_flux_setting',
		'name' 			=> 'Связь Flux (Poly 1-M)',
		'description' 	=> 'Значение настройки формы "Flux"',
		'formSettings' => [
			'tabs' => [
				'source' => 'Где выбрано?',
				'setting' => 'Параметр настройки',
			]
		],
		'dataFields' => [
			'source_table' => [
				'name' => 'Таблица',
				'type' => 'Input',
				'show' => 1,
				'readOnly' => 1,
				'doNotCheck' => 1,
				'position' => '*|source|1'
			],
			'source_field' => [
				'name' => 'Колонка',
				'type' => 'Input',
				'show' => 1,
				'doNotCheck' => 1,
				'position' => '*|source|2'
			],
			'source_signature' => [
				'name' => 'Сигнатура',
				'type' => 'Input',
				'show' => 1,
				'readOnly' => 1,
				'doNotCheck' => 1,
				'position' => '*|source|3'
			],
			'source_record' => [
				'name' => 'ID-записи',
				'type' => 'Input.Number',
				'show' => 1,
				'readOnly' => 1,
				'doNotCheck' => 1,
				'position' => '*|source|4'
			],
			'source_sorting' => [
				'name' => 'Сортировка',
				'type' => 'Input.Number',
				'show' => 1,
				'readOnly' => 1,
				'doNotCheck' => 1,
				'position' => '*|source|5'
			],
			'settingkey' => [
				'name' => 'Ключ настройки (settings.xxx)',
				'type' => 'Input',
				'show' => 1,
				'readOnly' => 1,
				'doNotCheck' => 1,
				'position' => '*|setting|1'
			],
			'settingvalue' => [
				'name' => 'Значение настройки (settings.xxx)',
				'type' => 'Text',
				'show' => 1,
				'doNotCheck' => 1,
				'position' => '*|setting|2'
			],
		]
	];
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		$configuration['ctrl']['label'] = 'source_table,settingskey';
	}
	
}
?>