<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Domain\Model\Fields\Media;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialFalFileInfo extends AbstractField
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: информация о файле (FAL)'
	];
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'user';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'fileInfo';
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		#$GLOBALS['TCA'][$table]['ctrl'][''] = '';
	}
	
	// listController func
	public static function listControllerSqlBuilder(&$obj, &$q, $table, $field, $config){
		// $q->addSelect($table.'.'.$field);
		return $q;
	}
	
	// listController func
	public static function listControllerSqlBuilderWith(&$obj, &$q, $table, $field, $config)
	{
		if($table == 'sys_file_metadata'){
			return $q->with('file.storage');
		}
		return $q;
	}
	
	// listController func
	public static function listControllerSqlBuilderOrder(&$obj, $table, $field, $config){
		return false;
	}
	
	// listController func
	public static function listControllerHtmlFilter(&$obj, $table, $field, $config, &$uriBuilder){
		return '-';
	}
	
	// listController func
	public static function listControllerHtmlTh(&$obj, $table, $field, $config){
		return \Litovchenko\AirTable\Domain\Model\Fields\Input::listControllerHtmlTh($obj, $table, $field, $config);
	}
	
	// listController func
	public static function listControllerHtmlTd(&$obj, $table, $field, $config, $row, &$uriBuilder){
		if($table == 'sys_file_metadata') {
			$fileInfo = $row['file'];
			#if($row['uid'] == 95){
			#	print "<pre>";
			#	print_r($row);
			#	exit();
			#}
		} elseif($table == 'sys_file') {
			$fileInfo = $row;
		}

		//file_exists(PATH_site.$src) && $src != null && 
		$src = Media::FalFilePath($fileInfo);
		if(!file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/'.$src) && $src != null) {
			$tdContent1 = '<a href="/'.$src.'" target="_blank"><code>[? файл отсутствует на сервере]</code></a>';
		} elseif(strstr($fileInfo['mime_type'],'image/')) {
			$tdContent1 = '<a href="/'.$src.'" target="_blank"><img src="/'.$src.'" style="width: 150px; border: #ccc 1px solid; background: white; padding: 5px;"></a>';
		} else {
			$tdContent1 = '<a href="/'.$src.'" target="_blank">'.$fileInfo['name'].'</a>';
		}

		return '<td width="150" nowrap align="center" style="vertical-align: top; background: #eee;">'.$tdContent1.'</td>';
	}
}