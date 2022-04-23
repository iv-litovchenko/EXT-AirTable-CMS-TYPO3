<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use Illuminate\Support\Facades\DB;
use Litovchenko\AirTable\Utility\BaseUtility;

class EditIconViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'EditIcon',
		'registerArguments' => [
			'model*' => ['string'],
			'recordId' => ['integer'],
			'pid' => ['integer'],
			'title' => ['string'],
			
			'defaultFieldsForNewRecord' => ['mixed'],
			'copyFieldsForNewRecord' => ['string'],
			'editFieldsOnly' => ['string'],
			
			'onlyIcons' => ['string'],		// new
			'hideHoverInfo' => ['integer',false],	// new
			
			'hideEditIcon' => ['integer',false],
			'hideNewIcon' => ['integer',false],
			'hideDisableIcon' => ['integer',false],
			'hideDeletedIcon' => ['integer',false],
			'hideBufferIcon' => ['integer',false],
			
			'styleLeft' => ['integer', null],
			'styleTop' => ['integer', null],
			'styleRight' => ['integer', null],
			'styleBottom' => ['integer', null]
		]
	];
	
    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;
	
	public $processOutput = false; // см. class tx_yii2_processOutput
	public $table, $model;
	public $recordId, $pid;
	public $title;
	public $panelType; // для отдельных случаев
	
	public $cssClass; // Block, Abs, Inline, Center
	
	public $defaultFieldsForNewRecord; // значение полей по умолчанию при создании новой записи
	public $copyFieldsForNewRecord; // список колонок для копирования с создаваемой записи для кнопок "Создать новую запись"
	public $editFieldsOnly; // поля доступные для редактирования
	
	public $changeFieldsForMoveRecord; // специально для "tt_content"
	
	public $onlyIcons;
	public $hideEditIcon;
	public $hideNewIcon;
	public $hideDisableIcon;
	public $hideDeletedIcon;
	public $hideBufferIcon;

	public $hideHoverInfo;
	public $hoverInfoNewLine;
	
	public $reverse; // AdminPanelViewHelper
	public $alwayShow; // AdminPanelViewHelper
	
	public $styleTop; // Abs, Inline
	public $styleLeft; // Abs, Inline
	public $styleRight; // Abs, Inline
	public $styleBottom; // Abs, Inline
	
	public $icon; // link event // Beta
	public $url; // link event
	
	// Принудительный показ "лампочки" - вкл./выкл
	public $displayDisableIcon = false;
	
    public function render()
    {
		if (TYPO3_MODE === 'BE') {
			$srcAdmPath = 'EXT:air_table/Resources/Public/Admin/';
			$cssStyledColor = 1;
			
			$renderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
			$renderer->addCssFile($srcAdmPath.'adminPanel.css');
			$renderer->addCssFile($srcAdmPath.'blockAlert-'.$cssStyledColor.'.css');
			$renderer->addCssFile($srcAdmPath.'editIconColor-'.$cssStyledColor.'.css');
			$renderer->addJsFile($srcAdmPath.'adminPanel.js', 'text/javascript');
			
			foreach($this->arguments as $k => $v){
				$this->{$k} = $v;
			}
			return $this->processOutput();
		} else {
			if($GLOBALS['BE_USER']->user['uid'] > 0) {
				if($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
					$this->arguments['cssClass'] = $this->cssClass;
					$this->arguments['panelType'] = $this->panelType;
					if(isset($this->arguments['copyFieldsForNewRecord'])){
						$this->arguments['copyFieldsForNewRecord'] = explode(',',$this->arguments['copyFieldsForNewRecord']);
					}
					$temp = serialize($this->arguments);
					$temp = str_replace('{','--@[--',$temp);
					$temp = str_replace('}','--]@--',$temp);
					return "<!--@@@EDITICON ".$temp."@@@-->";
				} else {
					return '';
				}
			} else {
				return '';
			}
		}
    }
	
	public function processOutput()
	{
		// Если включен режим просмотра вставок
		if($GLOBALS['BE_USER']->uc['phptemplate_checkOption'] == 1){
			return '';
		}
		
		// Специальное для "tt_content" (перемещение записей между колонками)
		if($GLOBALS['BE_USER']->uc['isMoveRecord_tt_content'] == 1){
			// пропускаем тек. перемещяемую запись
			if($GLOBALS['BE_USER']->uc['isMoveRecord_tt_content_varible']['uid'] == $this->recordId){
				return '';
			}
			if($this->panelType == 'newRecord_tt_content'){
				return '';
			}
			if($this->panelType == 'editRecord_tt_content'){
				return '';
			}
			// пропускаем дочерние записи "gridelements_pi1" // собираем родителей
			if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('gridelements') && $this->panelType == 'moveRecord_tt_content'){
				if($this->changeFieldsForMoveRecord['tx_gridelements_container'] == $GLOBALS['BE_USER']->uc['isMoveRecord_tt_content_varible']['uid']) {
					return '';
				} elseif ($this->changeFieldsForMoveRecord['tx_gridelements_container']) {
					$rowData = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord(
						'tt_content',$this->changeFieldsForMoveRecord['tx_gridelements_container'],'uid,CType,tx_gridelements_container','',false
					);
				} else {
					$rowData = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord(
						'tt_content',$this->recordId,'uid,CType,tx_gridelements_container','',false
					);
				}
				$findParentRecordId = $rowData['tx_gridelements_container'];
				$CType = '';
				if($findParentRecordId > 0){
					while(1){
						$rowData = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord(
							'tt_content',$findParentRecordId,'uid,CType,tx_gridelements_container','',false
						);
						$findParentRecordId = $rowData['tx_gridelements_container'];
						$CType = $rowData['CType'];
						if($CType == 'gridelements_pi1'){
							if($GLOBALS['BE_USER']->uc['isMoveRecord_tt_content_varible']['uid'] == $rowData['uid']){
								return '';
							}
							if($GLOBALS['BE_USER']->uc['isMoveRecord_tt_content_varible']['uid'] == $rowData['tx_gridelements_container']){
								return '';
							}
						}
						if($findParentRecordId == 0){
							break;
						}
					}
				}
			}
		} else {
			if($this->panelType == 'moveRecord_tt_content'){
				return '';
			}
		}
		
		// Сокращенные названия моделей
		switch($this->model){
			case 'Pages':
				$this->model = 'Litovchenko\AirTable\Domain\Model\Content\Pages';
			break;
			case 'TtContent':
				$this->model = 'Litovchenko\AirTable\Domain\Model\Content\TtContent';
			break;
			case 'Data':
				$this->model = 'Litovchenko\AirTable\Domain\Model\Content\Data';
			break;
			default:
				
			break;
		}
		
		// Название таблицы берется из модели!
		if(class_exists($this->model)){
			$this->table = \Litovchenko\AirTable\Utility\BaseUtility::getTableNameFromClass($this->model);
		}
		
		// Только иконки // Todo
		#if($this->onlyIcons == ){
			#$this->hideNewIcon = true;
			#$this->hideDeletedIcon = true;
			#$this->hideDisableIcon = true;
		#}
		
		$disableColumn = $GLOBALS['TCA'][$this->table]['ctrl']['enablecolumns']['disabled'];
		$deletedColumn = $GLOBALS['TCA'][$this->table]['ctrl']['delete'];
		$typeColumn = $GLOBALS['TCA'][$this->table]['ctrl']['type'];
		$sortColumn = $GLOBALS['TCA'][$this->table]['ctrl']['sortby'];

		$sqlSelect = 'uid,pid';
		if(!empty($disableColumn)){
			$sqlSelect .= ','.$disableColumn;
		}
	
		if(!empty($disableColumn)){
			$sqlSelect .= ','.$deletedColumn;
		}
	
		if(!empty($this->copyFieldsForNewRecord)){
			$sqlSelect .= ','.implode(',',$this->copyFieldsForNewRecord);
		}
		
		switch($this->table){
			case 'pages':
				$sqlSelect .= ',doktype';
				if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('flux')){
					$sqlSelect .= ',tx_fed_page_controller_action';
				}
			break;
			case 'tt_content':
				$sqlSelect .= ',CType,list_type,colPos,foreign_table,foreign_field,foreign_uid,foreign_sortby';
				if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('gridelements')){
					$sqlSelect .= ',tx_gridelements_container,tx_gridelements_columns,tx_gridelements_backend_layout'; // EXT: gridelements
				}
				$sqlSelect .= ',list_type';
			break;
			default:
				if(!empty($typeColumn) && $this->table != 'sys_file_reference'){
					$sqlSelect .= ','.$typeColumn;
				}
				if(!empty($sortColumn)){
					$sqlSelect .= ','.$sortColumn;
				}
			break;
		}
		
		// Получаем информацию о записи
		$rowData = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord(
			$this->table, 
			$this->recordId, 
			$sqlSelect,
			'',
			false
		);

		// Специальное для "tx_air_table_marks" (убираем кнопку новая запись!)
		if($this->model == 'Litovchenko\AirTable\Domain\Model\Content\Data'){ // tx_marker
			if(strstr($rowData['RType'],'Marker.')){ // tx_marker
				$this->hideNewIcon = true;
				$this->hideDeletedIcon = true;
				$this->hideDisableIcon = true;
			}
		}
		
		// Корректировки для элементов содержимого
		if($this->table == 'tt_content' && $this->panelType == 'editRecord_tt_content') {
			// Если тип элемента контента - "Вставить записи (Insert Records)" или
			// "Вставить расширение (Insert Plugin)" - cssClass ставим в "Block"
			// т.к. иначе будет наложение кнопок редактирования друг на друга
			if ($rowData['CType'] == "shortcut" || $rowData['CType'] == "list") {
				$this->cssClass = 'Block';
			}
			// Если тип элемента "Сетки-контейнер" - cssClass ставим в "Block"
			// т.к. иначе будет наложение кнопок редактирования друг на друга
			// EXT: gridelements
			if (strstr($rowData['CType'],'gridelements_pi1')) {
				$this->cssClass = 'Block';
			}
			if (strstr($rowData['CType'],'_gridelements_')) {
				$this->cssClass = 'Block';
			}
		}
		
		// Если запись выключена - всегда показываем лапмочку
		if($rowData[$disableColumn]){
			$this->hideDisableIcon = false;
			$this->displayDisableIcon = true;
		}
		
		$content = $this->panel($rowData);
		return $content;
    }
	
	public function panel($rowData = array()){

		// CSS class wrap 
		if($this->cssClass == 'Center'){
			$cssClassWrap = "phptemplate_tagEditIcon_wrap_center";
		} elseif($this->cssClass == 'Inline'){
			$cssClassWrap = "phptemplate_tagEditIcon_wrap_inline";
		} elseif ($this->cssClass == 'Abs') {
			$cssClassWrap = "phptemplate_tagEditIcon_wrap_absolute";
		} elseif ($this->cssClass == 'Width100') {
			$cssClassWrap = "phptemplate_tagEditIcon_wrap_w100";
		} else {
			$cssClassWrap = "phptemplate_tagEditIcon_wrap_block";
		}
		
		// CSS - style top, left, right, bottom
		$styleTopAndLeft = '';
		if ($this->cssClass == 'Abs') {
			if($this->styleTop !== null){ //  > 0
				$styleTopAndLeft .= ' top: '.$this->styleTop.'px !important; ';
			}
			if($this->styleLeft !== null){ //  > 0
				$styleTopAndLeft .= ' left: '.$this->styleLeft.'px !important; ';
			}
			if($this->styleRight !== null){ //  > 0
				$styleTopAndLeft .= ' right: '.$this->styleRight.'px !important; ';
			}
			if($this->styleBottom !== null){ //  > 0
				$styleTopAndLeft .= ' bottom: '.$this->styleBottom.'px !important; ';
			}
		}
		
		switch($this->table){
			case 'pages':
				$cssColor = '#FF69B4';
			break;
			case 'tt_content':
				$cssColor = '#778899'; // a2aab8
			break;
			case 'tx_data':
				if(strstr($rowData['RType'],'Marker.')){ // tx_marker
					$cssColor = '#1E90FF';
				} else {
					$cssColor = '#FFA500';
				}
			break;
			#case 'tx_marker':
			#	$cssColor = '#1E90FF';
			#break;
			default:
				$cssColor = '#6B8E23';
			break;
		}
		
		$content = '';
		$content .= "<!--TYPO3_NOT_SEARCH_begin-->";
		$content .= "<tagEditIcon_wrap class='".$cssClassWrap."'>";
		$content .= "<tagEditIcon class='phptemplate_tagEditIcon' style='border: ".$cssColor." 1px solid; border-left: ".$cssColor." 3px solid; ".$styleTopAndLeft."'>";
		$content .= "<nobr>";
		$content .= $this->panel_elements($rowData, $this->panelType);
		$content .= "</nobr>";
		$content .= "</tagEditIcon>";
		$content .= "</tagEditIcon_wrap>";
		$content .= "<tagEditIcon_br style='clear: both;'></tagEditIcon_br>";
		$content .= "<!--TYPO3_NOT_SEARCH_end-->";
		
		if($this->cssClass == 'Center'){
			 $content = '<center>'.$content.'</center>';
		}
		
		return $content;
	}
	
	public function panel_elements($rowData = array(), $type=''){
		$content = '';
		switch($type){
			
			case 'link': // event
			
				$content .= $this->panel_element(1, array(), 'link');
				$content .= $this->title($this->title);
				// $content .= $this->hoverStr('link');
			
			break;
			
			case 'goToListModule':
			
				$content .= $this->panel_element(1, array(), 'goToListModule');
				$content .= $this->title($this->title);
				// $content .= $this->hoverStr('goToListModule');
			
			break;
			
			case 'onlyNewRecord':
			
				if(!isset($GLOBALS['TCA'][$this->table])){
					$content .= $this->panel_element(1, array(), 'unknown');
					$content .= $this->title('Таблица не существует');
					$content .= $this->hoverStr($this->table);
				
				} else {
			
					// Если pid не определен (пробуем выбрать последний)
					$pidList = [];
					$res = DB::table($this->table)->select('pid')->groupBy('pid')->get();
					foreach($res as $k => $v){
						$pidList[] = $v->pid;
					}
					
					// Если не удалось установить хранилище (если pid много различных)
					if (count($pidList) > 1 && !isset($this->pid)) {
					
						// New icon error
						$content .= $this->panel_element(1, array(), 'unknown');
						$content .= $this->title($GLOBALS['LANG']->sL('LLL:EXT:air_table/Resources/Private/Language/Localling.EditAdmin.xml:plugins.editIcon.unableToEstablishTheRepository'));
						$content .= $this->hoverStr($this->table);
					
					} else {
						
						// Pid save (если не задан pid, берем из похоже записи
						if (!isset($this->pid)) {
							$this->pid = 0;
						}
				
						$content .= $this->panel_element(1, $rowData, 'new');
						$content .= $this->title($this->title);
						$content .= $this->hoverStr($this->table.", save to pid: ". $this->pid);
					}
					
				}
				
			break;
			
			case 'newRecord_tt_content':
				
				$content .= $this->panel_element(1, $rowData, 'new');
				$content .= $this->title($this->title);
				
				$ar = [];
				foreach($this->defaultFieldsForNewRecord as $k => $v){
					if(trim($v) != ''){
						if($k != 'uid' && trim($v) != ''){
							if($k == 'colPos' && $v == -1){
								continue;
							}
							$nameKey = str_replace(['tx_gridelements_container','tx_gridelements_columns'],['gridContainerId','gridColumn'],$k);
							$ar[] = $nameKey.': ' . $v; 
						}
					}
				}
				$content .= $this->hoverStr(implode(', ', $ar)); // <br />
			
			break;
			
			case 'editRecord_tt_content':
				
				// $content .= $this->locked($this->table, $this->recordId);
				$content .= $this->panel_element(1, $rowData, 'edit');
				// $content .= $this->panel_element(2, $rowData, 'new');
				$content .= $this->panel_element(3, $rowData, 'hide');
				$content .= $this->panel_element(4, $rowData, 'delete');
				$content .= $this->panel_element(5, $rowData, 'move');
				// $content .= $this->panel_element(6, $rowData, 'buffer');
				$content .= $this->title($this->title);
				$content .= $this->hover($rowData);
				
			break;
			
			case 'moveRecord_tt_content':
			
				$content .= $this->panel_element(1, $rowData, 'move_enter');
				$content .= $this->title($this->title);
				
				$ar = [];
				foreach($this->changeFieldsForMoveRecord as $k => $v){
					if(!empty($v)){
						$ar[] = $k.': ' . $v; 
					}
				}
				$content .= $this->hoverStr($this->table.', ' . implode('<br />', $ar));
				
			break;
			
			default:
				
				$deletedColumn = $GLOBALS['TCA'][$this->table]['ctrl']['delete'];
				
				if(!isset($GLOBALS['TCA'][$this->table])){
					$content .= $this->panel_element(1, array(), 'unknown');
					$content .= $this->title('Таблица не существует');
					$content .= $this->hoverStr($this->table);
				
				} elseif(!isset($rowData['uid'])) {
					$content .= $this->panel_element(1, array(), 'unknown');
					$content .= $this->title('Запись не существует');
					$content .= $this->hoverStr($this->table . ': ' . $this->recordId);
				
				} elseif($rowData[$deletedColumn] == 1) {
					$content .= $this->panel_element(1, $rowData, 'delete');
					$content .= $this->title('Восстановить запись');
					$content .= $this->hover($rowData);
					
				} else {
					
					// $content .= $this->locked($this->table, $this->recordId);
					// $content .= $this->panel_element($rowData, 'up-down');
					// $content .= $this->panel_element(1, $rowData, 'multiple');
					
					if($this->reverse == true){
						$content .= $this->title($this->title);
						$content .= $this->hover($rowData);
					}
					
					if($this->hideEditIcon != 1) {
						$content .= $this->panel_element(1, $rowData, 'edit');
					}
					
					$content .= $this->panel_element(2, $rowData, 'new');
					$content .= $this->panel_element(3, $rowData, 'hide');
					$content .= $this->panel_element(4, $rowData, 'delete');
					// $content .= $this->panel_element(5, $rowData, 'buffer');
					
					if($this->reverse == false){
						$content .= $this->title($this->title);
						$content .= $this->hover($rowData);
					}
				
				}

			break;
		}
		return $content;
	}
	
	public function panel_element($counter, $rowData = array(), $type=''){
		$content = '';
		switch($type){
			
	
			//////////////////////////////////////////////////////////
			// Link
			//////////////////////////////////////////////////////////
			case 'link':
				
				$url = $this->url;
				$content = $this->button($counter, $this->urlJsLocation($type, $rowData, $url), 'linkIcon.png');
			
			break;
			
			//////////////////////////////////////////////////////////
			// Go to module link
			//////////////////////////////////////////////////////////
			case 'goToListModule':
				
				$urlParameters = array();
				$urlParameters['id'] = $this->recordId;
				$urlParameters['table'] = $this->table;
				$urlParameters['returnUrl'] = $this->clean_url_qs();
				$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('web_list', $urlParameters);
				
				$content = $this->button($counter, $this->urlJsLocation($type, $rowData, $url, 4), 'goToListModuleIcon.png');
			
			break;
			
			//////////////////////////////////////////////////////////
			// New icon
			//////////////////////////////////////////////////////////
			case 'new':
				if (isset($this->editFieldsOnly)){
					// $this->editFieldsOnly = implode(',',$this->editFieldsOnly);
				}
				
				if($this->hideNewIcon == FALSE){
					$defVals = [];
					
					$typeColumn = $GLOBALS['TCA'][$this->table]['ctrl']['type'];
					if(isset($typeColumn)){
						$defVals[$this->table][$typeColumn] = $rowData[$typeColumn];
					}
					
					$sortColumn = $GLOBALS['TCA'][$this->table]['ctrl']['sortby'];
					if(isset($sortColumn)){
						$defVals[$this->table][$sortColumn] = $rowData[$sortColumn];
					}
					
					if(isset($this->copyFieldsForNewRecord)){
						foreach($this->copyFieldsForNewRecord as $k => $v){
							$defVals[$this->table][$v] = $rowData[$v];
						}
					}
					
					if(isset($this->defaultFieldsForNewRecord)){
						foreach($this->defaultFieldsForNewRecord as $k => $v){
							$defVals[$this->table][$k] = $v;
						}
					}
					
					// pid
					if(isset($this->pid)){
						$pid = $this->pid;
					} else {
						$pid = $rowData['pid'];
					}
					
					// PAGES
					if($this->table == 'pages') {
						$buttonTitle = "Мастер создания страницы: pages";
						/*
							Example link:
								&id=87
								&sys_language_uid=
								&uid_pid=87
						*/
						$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('new_content_element', [
							'typeWizard' => 'pages',
							'id' => $rowData['uid'],
							'sys_language_uid' => 0,
							'colPos' => 0, // Без этого параметра включается "Анимация вправо"
							'defVals' => $defVals, // Hook "tx_yii2_wizardItems"
							'columnsOnly' => $this->editFieldsOnly,
							'returnUrl' => $this->clean_url_qs()
						]);
				
					// TT_CONTENT
					} elseif($this->table == 'tt_content') {
						$buttonTitle = "Мастер создания элемента содержимого: tt_content";
						/*
							Example link:
								&id=87
								&colPos=-1
								&tx_gridelements_container=564
								&tx_gridelements_columns=0
								&sys_language_uid=
								&uid_pid=87
								&foreign_table=
								&foreign_field=
								&foreign_uid=
								&foreign_sortby
						*/
						// if ($rowData['colPos']['tx_gridelements_container'] > 0)
						
						if(isset($this->defaultFieldsForNewRecord)){
							$resultDefVals = $defVals;
							unset($resultDefVals['tt_content']['uid']);
							unset($resultDefVals['tt_content']['pid']);
							unset($resultDefVals['tt_content']['colPos']);
							unset($resultDefVals['tt_content']['tx_gridelements_container']);
							unset($resultDefVals['tt_content']['tx_gridelements_columns']);
							$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('new_content_element', [
								'typeWizard' => 'tt_content',
								'id' => $this->defaultFieldsForNewRecord['pid'],
								'colPos' => $this->defaultFieldsForNewRecord['colPos'],
								'tx_gridelements_container' => $this->defaultFieldsForNewRecord['tx_gridelements_container'],
								'tx_gridelements_columns' => $this->defaultFieldsForNewRecord['tx_gridelements_columns'],
								'sys_language_uid' => 0,
								'uid_pid' => $this->defaultFieldsForNewRecord['uid'],
								'defVals' => $resultDefVals, // Hook "tx_yii2_wizardItems"
								'columnsOnly' => $this->editFieldsOnly,
								'returnUrl' => $this->clean_url_qs()
							]);
							// Так сделали вставить после...
							if($this->defaultFieldsForNewRecord['pid'] != $this->defaultFieldsForNewRecord['uid']){
								$rowData['uid'] = preg_replace('/^\-/is','',$this->defaultFieldsForNewRecord['uid']);
							}
						} else {
							$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('new_content_element', [
								'typeWizard' => 'tt_content',
								'id' => $pid,
								'colPos' => $rowData['colPos'],
								'tx_gridelements_container' => $rowData['tx_gridelements_container'],
								'tx_gridelements_columns' => $rowData['tx_gridelements_columns'],
								'sys_language_uid' => 0,
								'uid_pid' => '-'.$rowData['uid'],
								'defVals' => $defVals, // Hook "tx_yii2_wizardItems"
								'columnsOnly' => $this->editFieldsOnly,
								'returnUrl' => $this->clean_url_qs()
							]);
						}

					} else {
						$buttonTitle = "Мастер создания записи: ".$this->table;
						$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('record_edit', [
							'edit[' . $this->table . '][' . $pid. ']' => 'new',
							'defVals' => $defVals,
							'columnsOnly' => $this->editFieldsOnly,
							'returnUrl' => $this->clean_url_qs()
						]);
					}
					$content = $this->button($counter, $this->urlJsLocation($type, $rowData, $url, 4, $buttonTitle), 'newIcon.png');
				}
			break;
			
			//////////////////////////////////////////////////////////
			// Edit icon
			//////////////////////////////////////////////////////////
			case 'edit':
				if (isset($this->editFieldsOnly)){
					// $this->editFieldsOnly = implode(',',$this->editFieldsOnly);
				}
			
				$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('record_edit', [
					'edit[' . $this->table . '][' . $this->recordId . ']' => 'edit',
					'columnsOnly' => $this->editFieldsOnly,
					'returnUrl' => $this->clean_url_qs()
				]);
				
				$buttonTitle = "Редактирование записи: ".$this->table;
				$content = $this->button($counter, $this->urlJsLocation($type, $rowData, $url, 4, $buttonTitle), 'editIcon.png');
			break;
			
			//////////////////////////////////////////////////////////
			// Hide icon
			//////////////////////////////////////////////////////////
			case 'hide';
				$disableColumn = $GLOBALS['TCA'][$this->table]['ctrl']['enablecolumns']['disabled'];
				if(isset($disableColumn) && $this->hideDisableIcon == FALSE){
					$typeIcon = $rowData[$disableColumn] == 1 ? 'undisableIcon' : 'disableIcon';
					$disabledValue = $rowData[$disableColumn] == 1 ? 0 : 1;
					$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('tce_db', [
						'data['.$this->table.']['.$this->recordId.']['.$disableColumn.']' => $disabledValue,
						'redirect' => $this->clean_url_qs(),
						'prErr' => 1,
						'uPT' => 1,
					]);
					$temp = true;
					if($this->displayDisableIcon == true){
						$temp = false;
					}
					$content .= $this->button($counter, $this->urlJsLocation($type, $rowData, $url,1), $typeIcon.'.png', $temp);
				}
			break;
			
			//////////////////////////////////////////////////////////
			// Delete icon
			//////////////////////////////////////////////////////////
			case 'delete';
				$deletedColumn = $GLOBALS['TCA'][$this->table]['ctrl']['delete'];
				if(isset($deletedColumn) && $this->hideDeletedIcon == FALSE){
					$typeIcon = $rowData[$deletedColumn] == 1 ? 'undeletedIcon' : 'deletedIcon';
					$deletedCmd = $rowData[$deletedColumn] == 1 ? 'undelete' : 'delete';
					$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('tce_db', [
						'cmd['.$this->table.']['.$this->recordId.']['.$deletedCmd.']' => 1,
						'redirect' => $this->clean_url_qs(),
						'prErr' => 1,
						'uPT' => 1,
					]);
					$content .= $this->button($counter, $this->urlJsLocation($type, $rowData, $url,1), $typeIcon.'.png');
				}
			break;
			
			//////////////////////////////////////////////////////////
			// Multiple record edit (Beta)
			//////////////////////////////////////////////////////////
			case 'multiple';
			/*
			
				$content .= "<tagEditIconA class='tagEditIconA'>";
				$content .= "<form id=\"phptemplate_plugin_moveRecordTtContent_".$formId."\" method=\"post\" style=\"\">";
				$content .= "<input type='checkbox' style='vertical-align: top; margin-top: 3px;'>";
				$content .= "</form>";
				$content .= "</tagEditIconA>";
			
			*/
			break;
			
			//////////////////////////////////////////////////////////
			// Up/Down move (Beta)
			//////////////////////////////////////////////////////////
			case 'up-down';
				// if($this->hideBufferIcon == FALSE){
					#$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('tce_db', [
					#	'cmd['.$this->table.']['.$this->recordId.']['.$deletedValue.']' => 1,
					#	'returnUrl' => $this->clean_url_qs()
					#]);
					$content .= $this->button($counter, $this->urlJsLocation($type, $rowData, $url,2), 'upIcon.png');
					
					#$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('tce_db', [
					#	'cmd['.$this->table.']['.$this->recordId.']['.$deletedValue.']' => 1,
					#	'returnUrl' => $this->clean_url_qs()
					#]);
					$content .= $this->button($counter, $this->urlJsLocation($type, $rowData, $url,2), 'downIcon.png');
				// }
			break;
			
			//////////////////////////////////////////////////////////
			// Buffer icon: copy, cyt (Beta)
			//////////////////////////////////////////////////////////
			case 'buffer';
				if($this->hideBufferIcon == FALSE){
					#$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('tce_db', [
					#	'cmd['.$this->table.']['.$this->recordId.']['.$deletedValue.']' => 1,
					#	'returnUrl' => $this->clean_url_qs()
					#]);
					$content .= $this->button($counter, $this->urlJsLocation($type, $rowData, $url,2), 'copyIcon.png');
					
					#$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('tce_db', [
					#	'cmd['.$this->table.']['.$this->recordId.']['.$deletedValue.']' => 1,
					#	'returnUrl' => $this->clean_url_qs()
					#]);
					$content .= $this->button($counter, $this->urlJsLocation($type, $rowData, $url,2), 'cutIcon.png');
				}
			break;
			
			//////////////////////////////////////////////////////////
			// Move icon (colPos - only "tt_content")
			//////////////////////////////////////////////////////////
			case 'move';
				if($this->table == 'tt_content'){
					$formId = md5(serialize($this)); 
					
					$content .= "<form id=\"phptemplate_plugin_moveRecordTtContent_".$formId."\" method=\"post\" style=\"display: none;\">";
					$content .= "	<input name=\"EditAdminPanel[event]\" type=\"hidden\" value=\"isMoveRecord_tt_content\">";
					$content .= "	<input name=\"EditAdminPanel[varible][uid]\" type=\"hidden\" value=\"".$rowData['uid']."\">";
					$content .= "	<input name=\"EditAdminPanel[varible][pid]\" type=\"hidden\" value=\"".$rowData['pid']."\">";
					$content .= "	<input name=\"EditAdminPanel[varible][colPos]\" type=\"hidden\" value=\"".$rowData['colPos']."\">";
					$content .= "	<input name=\"EditAdminPanel[varible][tx_gridelements_container]\" type=\"hidden\" value=\"".$rowData['tx_gridelements_container']."\">";
					$content .= "	<input name=\"EditAdminPanel[varible][tx_gridelements_columns]\" type=\"hidden\" value=\"".$rowData['tx_gridelements_columns']."\">";
					$content .= "	<input name=\"EditAdminPanel[varible][foreign_table]\" type=\"hidden\" value=\"".$rowData['foreign_table']."\">";
					$content .= "	<input name=\"EditAdminPanel[varible][foreign_field]\" type=\"hidden\" value=\"".$rowData['foreign_field']."\">";
					$content .= "	<input name=\"EditAdminPanel[varible][foreign_uid]\" type=\"hidden\" value=\"".$rowData['foreign_uid']."\">";
					$content .= "	<input name=\"EditAdminPanel[request_url]\" type=\"hidden\" value=\"".$this->clean_url_qs()."\">";
					$content .= "</form>";
					
					$url = "document.forms[\"phptemplate_plugin_moveRecordTtContent_".$formId."\"].submit();";
					$content .= $this->button($counter, $this->urlJsLocation($type, $rowData, $url,3), 'moveIcon.png');
				}
			break;
			
			case 'move_enter':
				
				$url = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl('tce_db', [
						'cmd['.$this->table.']['.$this->changeFieldsForMoveRecord['uid'].'][move]' => $this->changeFieldsForMoveRecord['uidAfter'],
						'data['.$this->table.']['.$this->changeFieldsForMoveRecord['uid'].'][pid]' => $this->changeFieldsForMoveRecord['pid'],
						'data['.$this->table.']['.$this->changeFieldsForMoveRecord['uid'].'][colPos]' => $this->changeFieldsForMoveRecord['colPos'],
						'data['.$this->table.']['.$this->changeFieldsForMoveRecord['uid'].'][tx_gridelements_container]' => $this->changeFieldsForMoveRecord['tx_gridelements_container'],
						'data['.$this->table.']['.$this->changeFieldsForMoveRecord['uid'].'][tx_gridelements_columns]' => $this->changeFieldsForMoveRecord['tx_gridelements_columns'],
						'data['.$this->table.']['.$this->changeFieldsForMoveRecord['uid'].'][foreign_table]' => $this->changeFieldsForMoveRecord['foreign_table'],
						'data['.$this->table.']['.$this->changeFieldsForMoveRecord['uid'].'][foreign_field]' => $this->changeFieldsForMoveRecord['foreign_field'],
						'data['.$this->table.']['.$this->changeFieldsForMoveRecord['uid'].'][foreign_uid]' => $this->changeFieldsForMoveRecord['foreign_uid'],
						'redirect' => $this->clean_url_qs(),
						'prErr' => 1,
						'uPT' => 1,
					]);
				$content = $this->button($counter, $this->urlJsLocation($type, $rowData, $url,1), 'moveIcon.png');
			
			break;
			
			//////////////////////////////////////////////////////////
			// Unknown icon
			//////////////////////////////////////////////////////////
			case 'unknown':
				
				$content = $this->button($counter, false, 'unknownIcon.png');
				
			break;
				
		}
		return $content;
	}
	
	// Здесь не забудь бобавить класс float: right -> ориентация...
	public function locked($table = '', $recordId = 0) {
		if ($lockInfo = \TYPO3\CMS\Backend\Utility\BackendUtility::isRecordLocked($table, $recordId)) {
			$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
			$content = '';
			$content .= "<tagEditIconA class='tagEditIconA' onclick=\"alert('Пользователь ".$lockInfo['username']." редактирует запись!')\">";
			$content .= "<tagEditIconImg class='tagEditIconImg' style='background-image: url(".$srcAdmPath."warningInUseIcon.png);'></tagEditIconImg>";
			$content .= "</tagEditIconA>";
			return $content;
		}
		return '';
	}
	
    public function button($counter, $url = '', $icon = '', $displayNone = true)
    {
		if($this->hideNewIcon == 1) { $counter--; }
		if($this->hideDisableIcon == 1) { $counter--; }
		if($this->hideDeletedIcon == 1) { $counter--; }
		if($this->hideBufferIcon == 1) { $counter--; }
		
		$hidden = '';
		if($counter > 1){
			if($displayNone == true && $this->alwayShow == false){
				$hidden = ' display: none; '; 
			}
		}
		
		// Ориентация...
		$floatRight = '';
		if($this->styleRight !== null){
			$floatRight = ' float: right; ';
		}
		
		$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
		if($url){
			$content  = "<tagEditIconA class='tagEditIconA' onclick='".($url)."' style='".$hidden." ".$floatRight."'>";
		} else {
			$content  = "<tagEditIconA class='tagEditIconA' style='".$hidden." ".$floatRight."'>";
		}
		$content .= "<tagEditIconImg class='tagEditIconImg' style='background-image: url(".$srcAdmPath.$icon.");'></tagEditIconImg>";
		$content .= "</tagEditIconA>";
		return $content;
	}
	
	public function title($t){
		return trim($t) != null ? "" . $t . " &nbsp;" : "";
	}
	
	public function hover($rowData){
		if($this->hideHoverInfo == 1){
			return '';
		}
		
		if($this->table == 'tt_content'){
			if($rowData['CType'] == 'list'){
				// $this->hoverInfoNewLine = 1;
			}
			if($rowData['CType'] == 'gridelements_pi1'){
				// $this->hoverInfoNewLine = 1;
			}
		}
		
		// Ориентация...
		$floatRight = '';
		if($this->styleRight !== null){
			$floatRight = ' float: right; ';
		}

		$content = '';
		$content .= "<tagEditIconSpan class='phptemplate_tagEditIconSpan ".(($this->hoverInfoNewLine==1)?'hoverInfoNewLine':'')."' style='".$floatRight."'>";
		$content .= $this->table.": ".(($rowData['uid'] > 0)?$rowData['uid']:0);
		
		$typeColumn = $GLOBALS['TCA'][$this->table]['ctrl']['type'];
		if (!empty($rowData[$typeColumn]) && $this->table != 'pages') {
			$content .= ", ".$typeColumn.": ".$rowData[$typeColumn];
		}
		
		if($this->table == 'pages'){
			// $content .= ", ".str_replace('pagets__','',$rowData['tx_fed_page_controller_action']);
		}
		
		if($this->table == 'tt_content'){
			if($rowData['CType'] == 'list'){
				$content .= /*"<br />" . */ ' | ' . $rowData['list_type'];
			}
			if($rowData['CType'] == 'gridelements_pi1'){
				$content .= /*"<br />" . */ ' | ' . $rowData['tx_gridelements_backend_layout'];
			}
		}
		
		$content .= "</tagEditIconSpan>";
		return $content;
	}
	
	public function hoverStr($string = ''){
		if($this->hideHoverInfo == 1){
			return '';
		}
		
		$content = '';
		$content .= "<tagEditIconSpan class='phptemplate_tagEditIconSpan ".(($this->hoverInfoNewLine==1)?'hoverInfoNewLine':'')."'>";
		$content .= $string;
		$content .= "</tagEditIconSpan>";

		return $content;
	}

	public function urlJsLocation($type, $rowData, $url, $mode = 0, $popupTitle = ''){
		switch($mode){
			case 0: // default
				$js = ' window.location.href="'.$url.'"; return false; ';
			break;
			
			case 4: // default (new window)
				if (TYPO3_MODE === 'BE') {
					$js = ' window.location.href="'.$url.'"; return false; ';
				} else {
					if($type == 'new' && empty($rowData)){
						$type = 'newTop';
						/*
						Array
						(
							[pid] => 228
							[colPos] => 0
							[tx_gridelements_container] => 
							[tx_gridelements_columns] => 
							[foreign_table] => 
							[foreign_field] => 
							[foreign_uid] => 
						)
						*/
						// if($this->defaultFieldsForNewRecord['foreign_table'] != ''){
							// $idRecord = 'emptyModelPos_'.md5($this->defaultFieldsForNewRecord['foreign_table']);
						// } elseif ($this->defaultFieldsForNewRecord['tx_gridelements_container'] > 0){
							// $idRecord = 'emptyGridPos_'.$this->defaultFieldsForNewRecord['tx_gridelements_container'].'_'.$this->defaultFieldsForNewRecord['tx_gridelements_columns'];
						// } else {
							$idRecord = 'emptyColPos_'.$this->defaultFieldsForNewRecord['colPos'];
						// }
					} else {
						$idRecord = $rowData['uid'];
					}
					// vHWin=window.open('\/typo3\/index.php?route=/record/edit\u0026token=1849646bc997c28f1a040c669651b03f17110ee5\u0026edit[tt_content][3090]=edit\u0026noView=0\u0026feEdit=1\u0026returnUrl=/typo3/sysext/backend/Resources/Public/Html/Close.html','FEquickEditWindow','width=690,height=500,status=0,menubar=0,scrollbars=1,resizable=1');vHWin.focus();return false;
					// $js = ' vHWin=window.open("'.$url.'", "FEquickEditWindow","width=690,height=500,status=0,menubar=0,scrollbars=1,resizable=1"); vHWin.focus(); return false; ';
					// $js = ' adminPanelPopup("'.$url.'", "edit_window", 1024, 640); return true; ';
					$js = ' adminPanelPopup("'.$type.'", "'.$this->table.'", "'.$idRecord.'", "'.$url.'", "'.$popupTitle.'", 1024, 640); return true; ';
				}
			break;
			
			#case 5:
			#	$js = ' if (confirm("Выполнить действие?")) { document.forms["'.$url.'"].submit(); } ';
			#break;
			
			case 1: // with confirm
				$js = ' if (confirm("Выполнить действие?")) window.location.href="'.$url.'"; else return false; ';
			break; 
			
			case 2: // beta
				$js = ' alert("Beta!"); return false; ';
			break;
			
			case 3: // user js
				$js = $url;
			break;
		}
		return $js;
	}
	
	// remove $qs_key from query string of $url, return modified url value
	function clean_url_qs() {
		$url = $this->clean_url_qs_func(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI'), 'eIdAjax');
		return $this->clean_url_qs_func($url, 'eIdAjaxContentById');
	}
	
	// remove $qs_key from query string of $url, return modified url value
	function clean_url_qs_func($url, $qs_key) {

		// first split the url in two parts (at most)
		$parts = explode('?', $url, 2);

		// check whether query string is passed        
		if (isset($parts[1])) {
			// parse the query string into $params
			parse_str($parts[1], $params);

			// unset if $params contains $qs_key
			if (array_key_exists($qs_key, $params)) {
				// remove key
				unset($params[$qs_key]);
					// rebuild the url
					return $parts[0] . 
						(count($params) ? '?' . http_build_query($params) : '');
			}
			
		}
		
		// no change required
		return $url;

	}
	
}

class EditIconCenterViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = 'Center'; // Block, Abs, Inline, Center
}

class EditIconInlineViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = 'Inline'; // Block, Abs, Inline, Center
}

class EditIconAbsViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = 'Abs'; // Block, Abs, Inline, Center
}

class EditIconW100ViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = 'Width100'; // Block, Abs, Inline, Center
}