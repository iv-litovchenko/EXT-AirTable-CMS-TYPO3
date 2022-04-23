<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Input;
use Litovchenko\AirTable\Utility\BaseUtility;

class Text extends Input
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Многострочный текст',
		'subTypes' 		=> 'Rte,Code,Table,Invisible',
		'incEav' 		=> 1,
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string',
			'format' => 'string',
			'preset' => 'string'
		]
	];
	
    /**
     * @var string
     */
    protected $format;
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
        return [$table=>[
			$field => 'text'
		]];
    }
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model,$table,$field);
		
		$RenderType = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field',true);
		switch(strtolower($RenderType)){
			default:
			case 'text':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'text';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] .= '';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['cols'] = 30;
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['rows'] = 10;
			break;
			case 'code':
				$format = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Format');
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'text';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 't3editor';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['format'] = $format; // "mixed" not work in v9!?
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] .= '';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['cols'] = 30;
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['rows'] = 10;
			break;
			case 'rte':
				$preset = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Preset');
				#$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'user';
				#$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'textTinyMCE';
				
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'text';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['enableRichtext'] = true;
				if(!empty($preset)){
					$GLOBALS['TCA'][$table]['columns'][$field]['config']['richtextConfiguration'] = $preset;
				} else {
					$GLOBALS['TCA'][$table]['columns'][$field]['config']['richtextConfiguration'] = 'minimal';
				}
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] .= '';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['cols'] = 30;
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['rows'] = 10;
				
				$publicPath = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
				\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($publicPath.'/fileadmin/user_upload/wysiwyg/');
				\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($publicPath.'/fileadmin/user_upload/wysiwyg/'.date('Y').'/');
				\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir($publicPath.'/fileadmin/user_upload/wysiwyg/'.date('Y').'/'.date('m').'/');
			break;
			case 'table':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'text';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'textTable';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] .= '';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['cols'] = 30;
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['rows'] = 10;
			break;
			case 'passthrough':
			case 'invisible':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'passthrough';
			break;
		}
		
		// Значение по умолчанию
		$default = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Default');
		if(!empty($default)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = $default;
		}
	}
	
	// listController func
	public static function listControllerHtmlTd(&$obj, $table, $field, $config, $row, &$uriBuilder){
		$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
			'record_edit', [
				'columnsOnly' => $field,
				'edit['.$table.']['.$row['uid'].']' => 'edit',
				'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
			]
		);
		if($row[$field] == NULL){
			$rowValue = '<code style="'.STYLE_EMPTY_FIELD.'">Заполнить</code>';
		} else {
			$rowValue = nl2br(htmlspecialchars($row[$field]));
		}
		return '<td style="vertical-align: top;"><a href="'.$backendLink.'">'.$rowValue.'</a></td>';
	}
}
