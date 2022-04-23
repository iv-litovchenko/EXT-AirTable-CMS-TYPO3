<?php
namespace Litovchenko\AirTable\Provider;

///////////////////////
// https://www.medienreaktor.de/blog/dynamische-backend-formulare-in-typo3-mit-flux
///////////////////////

class PagesSubConfigurationProvider extends PagesConfigurationProvider
{
	use Traits\FullControllerNameTrait;
	use Traits\GetFormTrait;
	use Traits\GetGridTrait;
	
	protected $fullControllerName = null;
	protected $controllerAction = 'index';
    protected $pageObjectType = null;
	
	# protected $controllerName = 'Gallery';
	# protected $tableName = 'tt_content'; // tx_data // tx_products_domain_model_product
	# protected $fieldName = 'pi_flexform'; // prop_flexform // prop_flexform
	# protected $extensionKey = 'projiv';
	# protected $listType = 'projiv_plugins_firstplugincontroller';
	# protected $contentObjectType = 'projiv_gallery'; //  Fill with the "CType" value that should trigger this Provider.
	# protected $templatePathAndFilename = 'EXT:dlrg_seminare/Configuration/FlexForms/flexform_pi1.html';
	# protected $templatePaths = array(
	# 	'layoutRootPath' => 'EXT:dlrg_seminare/Resources/Private/Layouts/',
	# 	'templateRootPath' => 'EXT:dlrg_seminare/Resources/Private/Templates/',
	# 	'partialRootPath' => 'EXT:dlrg_seminare/Resources/Private/Partials/',
	# );
	
	 /**
	 * Creates objects inserted into this Form, resulting in
	 * a nested set of PHP objects that correspond exactly
	 * to what would come out of parsing a Flux template
	 */
	 # public function initializeObject() {
	 # // $this->createField('Input', 'test')->setDefault('default value')->setRequired(TRUE);
	 # }

    /**
     * Returns TRUE that this Provider should trigger if:
     *
     * - table matches 'pages'
     * - field is NULL or matches self::FIELD_NAME
     * - a selection was made in the "template for this page" field
     *
     * @param array $row
     * @param string $table
     * @param string $field
     * @param string|NULL $extensionKey
     * @return boolean
     */
    public function trigger(array $row, $table, $field, $extensionKey = null)
    {
		/**
		 *	Если "tx_fed_page_controller_action_sub" содержит
		 *	Vendor.Extension->action - отправляем на стандартный триггер
		 *	Иначе обрабатываем нашу сигнатуру
		*/
		
		$providerFieldName = $this->getFieldName($row);
		$providerTableName = $this->getTableName($row);
		$providerExtensionKey = $this->extensionKey;
		$providerPageObjectTypeName = $this->getPageObjectType();
		
		if($row['tx_fed_page_controller_action_sub'] == $providerPageObjectTypeName){
			return true;
		}
		
		if($row['tx_fed_page_controller_action_sub'][0] == $providerPageObjectTypeName){
			return true;
		}
		
		// if($row['tx_fed_page_controller_action_sub'] == '' && $providerPageObjectTypeName == ''){
		// 	return true;
		// }
		
		return false;
    }
}