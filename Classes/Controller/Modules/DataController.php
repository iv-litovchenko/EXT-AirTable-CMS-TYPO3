<?php
namespace Litovchenko\AirTable\Controller\Modules;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use Illuminate\Database\Capsule\Manager as DB;
use Litovchenko\AirTable\Controller\Modules\ListController;
use Litovchenko\AirTable\Utility\BaseUtility;

define("LAST_COLUMN", 'width: auto; border-top: 0; padding: 0; margin: 0;');
define("HIDE_CHECKBOX", 'display: none;');
define("HIDE_CHECKBOX_TD_NEXT", 'border-left: 0;');
define("STYLE_EMPTY_FIELD", 'background: #f0f0f0; color: #ccc;');

class DataController extends ListController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModule',
		'name' 			=> 'Материалы',
		'description' 	=> 'Управление записями материалов',
		'access' 		=> 'user,group',
		'section'		=> 'content',
		'position'		=> '10'
	];
}