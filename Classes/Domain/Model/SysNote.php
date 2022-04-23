<?php
namespace Litovchenko\AirTable\Domain\Model;
use Litovchenko\AirTable\Utility\BaseUtility;

class SysNote extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModelCrudOverride',
		'keyInDatabase'	=> 'sys_note',
		'name' 			=> 'Заметка',
		'description' 	=> 'Регистрация модели в системе',
		'dataFields' => [
			'crdate' => [
				'name' => 'Дата создания',
				'type' => 'Date.DateTime',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'doNotCheck' => 1
			],
			'subject' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1
			],
			'message' => [
				'type' => 'Text',
				'show' => 1,
				'doNotSqlAnalyze' => 1
			],
			'prop_tx_airtable_modelname' => [ // -- NEW FIELD --
				'type' => 'Switcher',
				'name' => 'Комментарий к таблице',
				'items' => [
					0 => '-- Таблица не выбрана --'
				],
				'show' => 1
			],
		],
		'mediaFields' => [
			'propmedia_tx_airtable_files' => [ // -- NEW FIELD --
				'type' => 'Media_M',
				'name' => 'Файлы',
				'show' => 1
			],
		],
		'relationalFields' => [
			'cruser' => [ // -- NEW FIELD --
				'type' => 'Rel_MTo1',
				'name' => 'Автор записи',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Users\BeUsers',
				'foreignKey' => 'proprefinv_tx_airtable_sys_note',
				'doNotSqlAnalyze' => 1,
				'doNotCheck' => 1,
				'readOnly' => 1,
				'show' => 1
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
		// Разрешаем создание записей в корне дерева страниц сайта
		$configuration['ctrl']['rootLevel'] = -1;
		
		// Устанавливаем время по умолчанию
		$configuration['columns']['crdate']['config']['default'] = time();
		
		// Блокируем кнопку персонал (нужно будет создать условие scope())
		$configuration['columns']['personal']['config']['readOnly'] = 1;
		
		// Заполняем списком моделей
		foreach($GLOBALS['TCA'] as $k => $v){
			if(isset($v['ctrl']['AirTable.Class']) && class_exists($v['ctrl']['AirTable.Class'])){
				$tableLabel = BaseUtility::getClassAnnotationValueNew($v['ctrl']['AirTable.Class'],'AirTable\Label');
				$configuration['columns']['prop_tx_airtable_modelname']['config']['items'][] = [
					0=>$tableLabel,
					1=>$v['ctrl']['AirTable.Class']
				];
			}
		}
	}
	
}
?>