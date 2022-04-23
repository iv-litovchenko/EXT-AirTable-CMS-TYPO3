<?php
namespace Litovchenko\AirTable\Domain\Model\Content;

use Litovchenko\AirTable\Utility\BaseUtility;

class Data extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'keyInDatabase' => 'tx_data',
		'name' => 'Материал',
		'description' => 'Содержимое разных типов информации на базе одной таблицы - произвольные типы записей-материалов',
		'baseFields' => [
			'pid',
			'RType' => [
				'items' => [
					'--div10--' => 'Текстовые фрагменты',
						'Marker.Input' => 'Строка',
						'Marker.Text' => 'Текст',
						'Marker.Text.Rte' => 'Текст (с редактором)',
						# 'Marker.Input.Link' => 'Ссылка', -> Отказался, нужно переводить на настройки!
					'--div20--' => 'Контент',
						# 'tt_content_row' => 'Элемент контента', -> Отказался!
						# 'tt_content_rows' => 'Элементы контента', -> Отказался!
					'--div30--' => 'Фрагменты кода',
						'Marker.Text.Code.Html' => 'HTML-код',
						'Marker.Text.Code.TypoScript' => 'TypoScript-код',
					'--div40--' => 'Наборы файлов',
						'Marker.Media_1' => 'Изображение (файл)', // -> <f:marker id="6" as="files" /> files.uid... </f:marker>
						'Marker.Media_M' => 'Изображения (файлы)',
						# 'inline' => 'Inline вариант // Todo',			--> это есть произвольные типы flux
						# 'flexform' => 'Гибкие параметры // Todo',		--> это есть произвольные типы flux
						# 'teaser' => 'Набор полей // Todo',			--> это есть произвольные типы flux
					'--div50--' => 'Произвольные типы',
						# 'tx_marker_type_1' => 'Произвольный тип 1 // Todo',
						# 'tx_marker_type_2' => 'Произвольный тип 2 // Todo',
						# 'tx_marker_type_3' => 'Произвольный тип 3 // Todo',
				]
			],
			#'RTypeSub',
			'title',
			'alt_title',
			'status', // disabled, deleted
			'date_create',
			'date_update',
			'date_start',
			'date_end',
			'sorting',
			'service_note',
			'propmedia_files',
			'propmedia_thumbnail',
			'propref_beauthor',
			'propref_content',
			#'propref_attributes',
			'propref_parent',
			'propref_categories',
			'bodytext_preview',
			'bodytext_detail',
			'propmedia_pic_preview',
			'propmedia_pic_detail',
			'keywords',
			'description',
			'slug'
		],
		'dataFields' => [
			'prop_flexform' => [
				'type' => 'FlexForm',
				'name' => 'FlexForm',
				'position' => '*|main|5',
				'selectMinimizeInc' => 1,
			],
			'prop_input' => [
				'type' => 'Input',
				'name' => 'Содержимое (однострочное)',
				'position' => 'Marker.Input|main|5',
				'selectMinimizeInc' => 1,
			],
			'prop_text' => [
				'type' => 'Text',
				'name' => 'Содержимое (многострочное)',
				'position' => 'Marker.Text|main|5',
				'selectMinimizeInc' => 1
			],
			'prop_text_rte' => [
				'type' => 'Text.Rte',
				'name' => 'Визуальный редактор',
				'position' => 'Marker.Text.Rte|main|5',
				'selectMinimizeInc' => 1
			],
			#'tt_content_row' => [
			#	'type' => 'Rel_Poly_1To1',
			#	'name' => 'Элемент содержимого',
			#	'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Content\TtContent',
			#	'foreignKey' => 'marker_row',
			#	'position' => [
			#		'Marker.tt_content_row|main|5'
			#	]
			#],
			#'tt_content_rows' => [
			#	'type' => 'Rel_Poly_1ToM',
			#	'name' => 'Содержимое из элементов контента',
			#	'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Content\TtContent',
			#	'foreignKey' => 'marker_rows',
			#	'position' => [
			#		'Marker.tt_content_rows|main|5'
			#	]
			#],
			'prop_text_code_html' => [
				'type' => 'Text.Code',
				'name' => 'Код',
				'format' => 'html',
				'position' => 'Marker.Text.Code.Html|main|5',
				'selectMinimizeInc' => 1
			],
			'prop_text_code_ts' => [
				'type' => 'Text.Code',
				'name' => 'Код',
				'format' => 'typoscript',
				'position' => 'Marker.Text.Code.TypoScript|main|5',
				'selectMinimizeInc' => 1
			],
			#'prop_input_link' => [
			#	'type' => 'Input.Link',
			#	'name' => 'Ссылка',
			#	'SelectMinimizeInc' => 1,
			#	'position' => [
			#		'Marker.Input.Link' => 'main,5'
			#	]
			#],
		],
		'mediaFields' => [
			'propmedia_media_1' => [
				'type' => 'Media_1.Mix',
				'name' => 'Изображение (документ)',
				'position' => 'Marker.Media_1|main|5',
				'maxItems' => 1
			],
			'propmedia_media_m' => [
				'type' => 'Media_M.Mix',
				'name' => 'Изображения (документы)',
				'position' => 'Marker.Media_M|main|5'
			]
		]
	];
	
	# const ENTITY  = 'tx_data'; // Eav
	
    /**
     * This is an optional feature.
     * Record types similar to "doktype (pages)" and "CType (tt_content)"
     * @return array
     */
    public static function baseRTypes()
    {
		$j = 1;
		$listTypes = parent::baseRTypes();
		foreach($listTypes as $k => $v){
			if(preg_match('/--div(.*)--/is',$k,$match)){
				$j = $match[1] + 10; // Так ищем максимальный div...
			}
		}
		
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."*") as $filename) {
			$extName = basename($filename);
			if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extName)){
				$listTypes['--div'.$j.'--'] = 'EXT:'.$extName;
				if(file_exists($filename.'/Configuration/DataTypeList.php')){
					$temp = require($filename.'/Configuration/DataTypeList.php');
					if(!empty($temp)){
						foreach($temp as $k => $v){
							$extNameCheck = str_replace('_','',$extName);
							if(!preg_match('/^tx-'.$extNameCheck.'-(.*)$/is',$k,$match)){	
								$listTypes[$k] = 'Группа >> '.$v['name'] . ' (формат ключа типа материала не соответствует шаблону: "tx-[extname]-[typename]")';
							} else {
								$listTypes[$k] = 'Группа >> '.$v['name'];
							}
						}
					}
				}
				$j++;
			}
		}
		return $listTypes;
    }
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		//$configuration['ctrl']['...'] = 1;
		$configuration['ctrl']['typeicon_column'] = 'RType';
		$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
		if(!empty($dataType)){
			$configuration['columns']['RType']['config']['default'] = $dataType;
		} else {
			$configuration['columns']['RType']['config']['default'] = 'Marker.Input';
		}
		$configuration['columns']['RType']['config']['renderType'] = 'selectSingle';
		// $configuration['columns']['RType']['config']['fieldWizard']['selectIcons']['disabled'] = '';
		// $configuration['columns']['RType']['config']['readOnly'] = 1;
		
		// subtypes_excludelist
		foreach($configuration['columns']['RType']['config']['items'] as $k => $v){
			if(preg_match('/^Marker./is',$v[1])){
				$configuration['types'][$v[1]]['subtype_value_field'] = 'RType';
				$configuration['types'][$v[1]]['subtypes_excludelist'][$v[1]] = 'date_start,date_end,disabled,propmedia_thumbnail,prop_flexform';
				$configuration['types'][$v[1]]['subtypes_addlist'][$v[1]] = ''; // be_users_row_id
			}
		}
		
		######################################
		# linkHandler
		
		#https://usetypo3.com/linkhandler.html
		#https://docs.typo3.org/typo3cms/extensions/core/8.7/Changelog/8.6/Feature-79626-IntegrateRecordLinkHandler.html
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
			TCEMAIN.linkHandler.tx_data {
				handler = TYPO3\CMS\Recordlist\LinkHandler\RecordLinkHandler
				label = Data (материал) // Todo
				configuration {
					table = tx_data
					hidePageTree = 1
					storagePid = 0
				}
				scanAfter = page
			}
		');
			
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('air_table','setup','
			config.recordLinks.tx_data {
				#typolink {
				#	parameter = 98
				#	additionalParams = &tx_news_pi1[news]=777
				#	useCacheHash = 1
				#	forceLink = 1
				#}
				typolink {
					parameter.data = parameters : allParams
					parameter.postUserFunc = tx_yii2_tinymce_parsehtml->render
					parameter.postUserFunc.function = linkHandler
					parameter.postUserFunc.key = tx_data
				}
			}
		');
	}
	
	/**
	* Условие по умолчанию (тип записи для админки)
	* @return query
	*/
    public static function globalScopeDataTypeDefault($builder)
    {
		if(TYPO3_MODE === 'BE'){
			$class = get_called_class();
			$table = BaseUtility::getTableNameFromClass($class);
			$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
			if(isset($dataType)){
				$builder->where($table.'.Rtype', '=', $dataType);
			}
		}
	}
}
?>