<?php
namespace Litovchenko\AirTable\Xclass;

use FluidTYPO3\Flux\Provider\AbstractProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Container\Grid;
use FluidTYPO3\Flux\Integration\PreviewView;
use FluidTYPO3\Flux\Service\FluxService;
use FluidTYPO3\Flux\Service\PageService;
use FluidTYPO3\Flux\Utility\ExtensionNamingUtility;
use FluidTYPO3\Flux\Utility\RecursiveArrayUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\View\TemplatePaths;
use Litovchenko\AirTable\Utility\BaseUtility;

class ExtFluxPageSubProvider extends \FluidTYPO3\Flux\Provider\SubPageProvider
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Xclass',
		'name' => '',
		'description' => '',
		'object' => 'FluidTYPO3\Flux\Provider\SubPageProvider'
	];
	
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
		
		if(strstr($row['tx_fed_page_controller_action_sub'],'_pages_')){
			return false;
		}
		
		if(strstr($row['tx_fed_page_controller_action_sub'][0],'_pages_')){
			return false;
		}
		
		if($row['tx_fed_page_controller_action_sub'][0] == ''){
			return false;
		}
		
		if($row['tx_fed_page_controller_action_sub'] == ''){
			return false;
		}
		
		return parent::trigger($row, $table, $field, $extensionKey);
    }
	
    /**
     * @param array $row
     * @return Form|null
     */
    public function getForm(array $row)
    {
		if(strstr($row['tx_fed_page_controller_action_sub'][0],'_pages_') || strstr($row['tx_fed_page_controller_action_sub'],'_pages_')){
			return [];
		} else {
			return parent::getForm($row);
		}
    }
}
