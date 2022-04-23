<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Domain\Model\Fields\Media;
use Litovchenko\AirTable\Utility\BaseUtility;

class Media_1 extends Media
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Файл, изображение',
		'subTypes' 		=> 'Image,Mix',
		'incEav' 		=> 1,
		'_propertyAnnotations' => [
			'minItems' => 'int',
			'maxItems' => 'int'
		]
	];
	
	const TABDEFAULT = 8; // media
	const SECTION = 'mediaFields';
	const EXPORT = false;
	const IMPORT = false;

    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $GLOBALS['TCA'][$table]['columns'][$field]['label'].'-1';
		
		// Минимальное кол-во
		$required = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Required');
		if(!empty($required)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = 1;
		}
		
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = 1;
    }
}