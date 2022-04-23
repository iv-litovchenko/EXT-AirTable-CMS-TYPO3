<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractFieldRelelationInverse;
use Litovchenko\AirTable\Utility\BaseUtility;

class Rel_Poly_1To1_Inverse extends AbstractFieldRelelationInverse
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Полиморфная связь 1:1 (реверс)',
		'description' 	=> 'Для создания двухсторонней связи'
	];
	
    const REQPOSTFIXCURRENTFIELD = ''; // _row
    const REQPOSTFIXFOREIGNFIELD = ''; // _row
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = 'Poly: 5 ' . $GLOBALS['TCA'][$table]['columns'][$field]['label'];
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= ' // 1-1';
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] .= ' Inverse';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'passthrough';
	}
	
	// Автоматизированная выборка связей 
	public function refProvider($obj, $class, $field){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$annotationForeignKey = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignKey');
		
		// User model:
		# public function profile()
		#{
		#	return $this->morphTo('profile', 'profile_type', 'profile_id');
		#}

		// Admin model:
		#public function user()
		#{
		#	return $this->morphOne(User::class, 'profile', 'profile_type', 'profile_id', 'id');
		#}

		// morphTo($name = null, $type = null, $id = null, $ownerKey = null)
		return $obj->morphTo(
			$field,							// 2 profile
			'foreign_table',				// 3 profile_type
			'foreign_uid',					// 4 profile_id
			'uid'
		);
	}
	
	// Создание связей
	public function refAttach($obj, $class, $field, $idTwo = null, $data = []){}
	
	// Удаление связей
	public function refDetach($obj, $class, $field, $idTwo = null, $data = []){}

}
