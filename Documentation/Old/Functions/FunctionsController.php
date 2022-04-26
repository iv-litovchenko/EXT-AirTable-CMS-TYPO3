<?php
namespace Litovchenko\AirTable\Controller;

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
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use Illuminate\Database\Capsule\Manager as DB;
use Litovchenko\AirTable\Controller\AbstractBackendController;
use Litovchenko\AirTable\Utility\BaseUtility;

/**
 * @AirTable\Label:<Функции>
 * @AirTable\Position:<content,bottom>
 */
class FunctionsController extends AbstractBackendController
{
    /**
     * TsConfig configuration
     *
     * @var array
     */
    protected $tsConfiguration = [];

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * Function will be called before every other action
     *
     */
    public function initializeAction()
    {
        parent::initializeAction();
    }

    /**
     * BackendTemplateContainer
     *
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    /**
     * Backend Template Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Set up the doc header properly here
     *
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        /** @var BackendTemplateView $view */
        parent::initializeView($view);
        $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);
        $pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
		$this->createMenu();
		$this->createButtons();
    }

    /**
     * Create menu
     *
     */
    protected function createMenu()
    {
    }

    /**
     * Create the panel of buttons
     *
     */
    protected function createButtons()
    {
        $buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        // Refresh
        
        $refreshButton = $buttonBar->makeLinkButton()
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle('Обновить')
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }


    /**
     * Main action for administration
     */
    public function indexAction()
    {
		//$GLOBALS['TSFE']->additionalHeaderData['extJs2'] = '<script type="text/javascript">alert(22);</script>';
		$_GET = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET();
		$DomainModel = $_GET['DomainModel'];
		
		if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tab_active') < 1){
			$contentTab1 = '';
			$contentTab2 = $this->getContentTab2();
		} 
		if(\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tab_active') == 1) {
			$contentTab1 = $this->getContentTab1();
			$contentTab2 = '';
		}
		
        $assignedValues = [
			'tab_active' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tab_active'),
			'page' => $this->pageUid,
			
            #'contentTab1' => $contentTab1,
            #'contentTab2' => $contentTab2,
			#'DomainModel' => $DomainModel
        ];
        $this->view->assignMultiple($assignedValues);
    }

    /**
     * Get content tab 2
     *
     * @return string
     */
    protected function getContentTab1()
    {
		$html = '';
		$rows = \air_table\models\Storage::find()->where(['RType'=>'extension'])->asArray()->all();
		foreach($rows as $k => $v){
			//$getArgs = [ 'DomainModel' => $extKey ];
			//$uriBuilder = $this->controllerContext->getUriBuilder();
			//$uriBuilder->setArguments($getArgs)->uriFor('index', [], 'Check');
			
			#$rowsChildren = \air_table\models\Storage::find()->where(['ext'=>$v['identificator']])->andWhere(['domain'=>'examples5'])->andWhere(['in','RType',['table_record','table_record_tree']])->asArray()->all();
			#$rowsChildren = \air_table\models\Storage::find()->where(['ext'=>$v['identificator']])->andWhere(['in','RType',['table_record','table_record_tree']])->asArray()->all();
			
			#print 'Осноновился на том также что если таблицы не существует на которую идет ссылка добавить ее как класс что бы табличка тоже отобразилась!';
			#exit();
			
			$html .= "<h2>".$v['title'].'</h2>';
			$rowsInDomain = \air_table\models\Storage::find()->select('uid,title,identificator')->where(['parent_id'=>$v['uid']])->andWhere(['RType'=>'domain_model'])->asArray()->all();
			foreach($rowsInDomain as $k2 => $v2){
				$rowsTable = \air_table\models\Storage::find()
					->where(['ext'=>$v['identificator']])
					->andWhere(['domain'=>$v2['identificator']])
					->andWhere(['in','RType',['table_record','table_record_tree']])
					->asArray()
					->all();
				$html .= $this->umlCode($v2['uid'], $v2['title'].'',$rowsTable);
			}
			#$rowsWithoutDomain = \air_table\models\Storage::find()->select('uid,title,identificator')->where(['parent_id'=>$v['uid']])->andWhere(['=','domain',''])->asArray()->all();
			$rowsTable = \air_table\models\Storage::find()
					->where(['ext'=>$v['identificator']])
					->andWhere(['domain'=>''])
					->andWhere(['in','RType',['table_record','table_record_tree']])
					->asArray()
					->all();
			$html .= $this->umlCode($v['uid'],'...',$rowsTable);
		}
		return $html;
	}
	
	protected function umlCode($id = 0, $label = '', $rows = []){
		$this->umlCode = '';
		$this->umlCodeRelation = '';
		$this->modelList = [];
		$this->modelTableList = [];
		foreach($rows as $k => $v){
			$tableName = $v['identificator'];
			if($this->modelList[$GLOBALS['TCA'][$tableName]['ctrl']['AirTableModel']] == 0){
				$this->modelList[$GLOBALS['TCA'][$tableName]['ctrl']['AirTableModel']] = 1;
			}
			$this->umlCodeClass($tableName);
		}
	foreach($this->modelList as $k => $v){
		if($v == 0){
			$tableName = $this->modelTableList[$k];
			$this->umlCodeClass($tableName, 1);
		}
	}
	return '
		<script type="text/javascript">
		function myFunction_'.$id.'() {
		  var x = document.getElementById("myPRE_'.$id.'");
		  if (x.style.display === "none") {
			x.style.display = "block";
		  } else {
			x.style.display = "none";
		  }
		}
		</script>
		<a onclick="myFunction_'.$id.'(); return false;" class="btn btn-success" style="
				border-radius: 2px 0 0 2px;
				white-space: normal; 
				text-align: left;
			">
			'.$label.'
		</a><!--a onclick="myFunction_'.$id.'(); return false;" class="btn btn-default" style="border-radius: 0;">'.count($rows).'</a>--><a 
		href="http://www.plantuml.com/plantuml/uml/" class="btn btn-primary" target="_blank" style="
		
			border-radius: 0 2px 2px 0;
			white-space: normal; 
			text-align: left;
		">
			PlantUML Web Server
		</a>
		<br />
		<pre id="myPRE_'.$id.'" style="display: none; background: #32383e; color: wheat; border: 0; border-top-left-radius: 0px;">@startuml
'.htmlspecialchars($this->umlCode.$this->umlCodeRelation).'@enduml</pre><br />';
	}
	
	protected function umlCodeClass($tableName = '', $relDisable = 0){
		print 123;
		exit();
		
		$this->umlCode .= "class ".$GLOBALS['TCA'][$tableName]['ctrl']['AirTableModel']." {
".$tableName."
==\n";
		$columns = [];
		$columns['uid']['label'] = 'uid';
		$columns['uid']['AirTableConfig'] = 'INPUT_NUMBER';
		$columns += $GLOBALS['TCA'][$tableName]['columns'];
		foreach($columns as $kColumn => $vColumn){
			if($vColumn['AirTableConfig'] == 'REL_Morph_fal' || $vColumn['AirTableConfig'] == 'REL_Morph' || $kColumn == 'cruser_id'){
				continue;
			}elseif(strstr($vColumn['AirTableConfig'],'REL_') && $relDisable == 0){
				$this->umlCode .= "+ ".$kColumn." &#8599; \n"; // <!--int(10)--> -- // -- ".htmlspecialchars($GLOBALS['LANG']->sL($vColumn['label']))
				#$umlCode .= "
				#+ В рамках модели
				## Внешние
				#~ На себя
				#";
				$relName = $vColumn['AirTableConfig'];
				$relName = str_replace('REL_','',$relName);
				$relName = explode('to',$relName);
				$this->umlCodeRelation .= $GLOBALS['TCA'][$tableName]['ctrl']['AirTableModel'];
				$this->umlCodeRelation .= ' "'.$relName[0].'" --o "'.$relName[1].'" ';
				$this->umlCodeRelation .= $vColumn['config']['foreign_model'];
				$this->umlCodeRelation .= ":\"".$kColumn."\\n".$vColumn['AirTableConfig']."\"\n";
				if($this->modelList[$vColumn['config']['foreign_model']] != 1){
					$this->modelList[$vColumn['config']['foreign_model']] = 0;
					$this->modelTableList[$vColumn['config']['foreign_model']] = $vColumn['config']['foreign_table'];
				}
			}elseif(strstr($vColumn['AirTableConfig'],'Inverse_')){
				$this->umlCode .= "+ ".$kColumn." &#8601; \n"; // <!--int(10)--> -- // -- ".htmlspecialchars($GLOBALS['LANG']->sL($vColumn['label']))
			}else{
				if($kColumn == 'uid' || @in_array($kColumn, $GLOBALS['TCA'][$tableName]['ctrl']['fieldsDefault'])){
					$this->umlCode .= "- ".$kColumn."\n"; // <!--int(10)--> -- // -- ".htmlspecialchars($GLOBALS['LANG']->sL($vColumn['label']))
				}
			}
		}
		$this->umlCode .= "}\n";
	}
	
    /**
     * Get content tab 2
     *
     * @return string
     */
    protected function getContentTab2()
    {
		/*
		$content = [];

		// 2. Todo -> Вадилация на предмет дублирования
		#$rows = \Yii::$app->db->createCommand("
		#	SELECT identifier,uid, COUNT(*) AS c FROM sys_file_uploads 
		#	GROUP BY BINARY identifier HAVING c > 1;
		#")->queryAll();
		#if (count($rows) > 0){
		#	foreach($rows as $k => $row){
		#		#$this->error['SYS_FILE_UPLOADS'][ $ardamp['identifier'] ] = $ardamp['identifier'];
		#		#$this->count_error ++;
		#	}
		#}
		
		// PID_Kill (кол-во утерянных страниц в дереве (встраницы в не нерева))
		$content['PID_Kill'] = '';
		$rows = \Yii::$app->db->createCommand("
		
			SELECT * FROM pages
			WHERE pid NOT IN (SELECT uid FROM pages) AND pid > 0
		
		")->queryAll();
		if (count($rows) > 0){
			$idList = [];
			foreach($rows as $k => $row){
				$backendLink = (
					'record_edit', [
						'columnsOnly' => 'doktype,title',
						'edit[pages]['.$row['uid'].']' => 'edit',
						'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
					]
				);
				$idList[] = '<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a>';
			}
			$content['PID_Kill'] .= '
			<tr>
				<td style="vertical-align:top;">PID_Kill</td>
				<td style="vertical-align:top;"><code>pages</code></td>
				<td style="vertical-align:top;"><code>pid</code></td>
				<td style="vertical-align:top;">'.implode(', ', $idList).'</td>
				<td style="vertical-align:top;">'.count($idList).'</td>
			</tr>
			';
		}
		
		// Rel_1to1
		$content['Rel_1to1'] = '';
		foreach($GLOBALS['TCA'] as $tableName => $tableValue){
			foreach($tableValue['columns'] as $kColumn => $vColumn){
				if($vColumn['AirTableConfig'] == 'Rel_1to1'){
					$relTableName = $vColumn['config']['foreign_table'];
					$relFieldName = $vColumn['config']['foreign_field'];
					$rows = \Yii::$app->db->createCommand("
						SELECT 

							".$relTableName.".uid, t2.uid AS 'uidBlog'

						FROM ".$relTableName."

						LEFT JOIN ".$tableName." AS t2 ON t2.uid = ".$relTableName.".".$relFieldName."

						WHERE t2.uid IS NULL AND ".$relTableName.".".$relFieldName.">0

						GROUP BY ".$relTableName.".uid
					")->queryAll();
					if (count($rows) > 0){
						$idList = [];
						foreach($rows as $k => $row){
							$label = $GLOBALS['TCA'][$relTableName]['ctrl']['label'];
							$backendLink = (
								'record_edit', [
									'columnsOnly' => $label.','.$relFieldName,
									'edit['.$relTableName.']['.$row['uid'].']' => 'edit',
									'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
								]
							);
							$idList[] = '<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a>';
						}
						$content['Rel_1to1'] .= '
						<tr>
							<td style="vertical-align:top;">Rel_1to1</td>
							<td style="vertical-align:top;"><code>'.$tableName.'</code><br /><code>'.$relTableName.'</code></td>
							<td style="vertical-align:top;"><code>'.$kColumn.'</code><br /><code>'.$relFieldName.'</code></td>
							<td style="vertical-align:top;"><code style="background: #cacaca; color: black;">Таблица: '.$relTableName.'</code><br />'.implode(', ', $idList).'</td>
							<td style="vertical-align:top;">'.count($idList).'</td>
						</tr>
						';
					}
				}
			}
		}
		
		// Rel_1to1 duplicate
		$content['Rel_1to1_duplicate'] = '';
		foreach($GLOBALS['TCA'] as $tableName => $tableValue){
			foreach($tableValue['columns'] as $kColumn => $vColumn){
				if($vColumn['AirTableConfig'] == 'Rel_1to1'){
					$relTableName = $vColumn['config']['foreign_table'];
					$relFieldName = $vColumn['config']['foreign_field'];
					$rows = \Yii::$app->db->createCommand("
						SELECT uid,".$relFieldName."
						FROM ".$relTableName."
						WHERE 
						".$relFieldName." > 0 AND 
						".$relFieldName." IN (
							SELECT ".$relFieldName."
							FROM ".$relTableName."
							GROUP BY ".$relFieldName."
							HAVING COUNT( * ) > 1
						)
						ORDER BY ".$relFieldName."
					")->queryAll();
					if (count($rows) > 0){
						$idList = [];
						foreach($rows as $k => $row){
							$label = $GLOBALS['TCA'][$relTableName]['ctrl']['label'];
							$backendLink = (
								'record_edit', [
									'columnsOnly' => $label.','.$relFieldName,
									'edit['.$relTableName.']['.$row['uid'].']' => 'edit',
									'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
								]
							);
							$idList[] = '<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a> ' . '('.$row[$relFieldName].')';
						}
						$content['Rel_1to1_duplicate'] .= '
						<tr>
							<td style="vertical-align:top;">Rel_1to1 <br />duplicate</td>
							<td style="vertical-align:top;"><code>'.$tableName.'</code><br /><code>'.$relTableName.'</code></td>
							<td style="vertical-align:top;"><code>'.$kColumn.'</code><br /><code>'.$relFieldName.'</code></td>
							<td style="vertical-align:top;"><code style="background: #cacaca; color: black;">Таблица: '.$relTableName.'</code><br />'.implode(', ', $idList).'</td>
							<td style="vertical-align:top;">'.count($idList).'</td>
						</tr>
						';
					}
				}
			}
		}
		
		// Rel_1toM
		$content['Rel_1toM'] = '';
		foreach($GLOBALS['TCA'] as $tableName => $tableValue){
			foreach($tableValue['columns'] as $kColumn => $vColumn){
				if($vColumn['AirTableConfig'] == 'Rel_1toM'){
					$relTableName = $vColumn['config']['foreign_table'];
					$relFieldName = $vColumn['config']['foreign_field'];
					$rows = \Yii::$app->db->createCommand("
						SELECT 

						".$relTableName.".uid,
						t2.uid AS 'uidBlog'

						FROM ".$relTableName."

						LEFT JOIN ".$tableName." AS t2 ON t2.uid = ".$relTableName.".".$relFieldName."

						WHERE t2.uid IS NULL AND ".$relTableName.".".$relFieldName.">0

						GROUP BY ".$relTableName.".uid
					")->queryAll();
					if (count($rows) > 0){
						$idList = [];
						foreach($rows as $k => $row){
							$label = $GLOBALS['TCA'][$relTableName]['ctrl']['label'];
							$backendLink = (
								'record_edit', [
									'columnsOnly' => $label.','.$relFieldName,
									'edit['.$relTableName.']['.$row['uid'].']' => 'edit',
									'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
								]
							);
							$idList[] = '<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a>';
						}
						$content['Rel_1toM'] .= '
						<tr>
							<td style="vertical-align:top;">Rel_1toM</td>
							<td style="vertical-align:top;"><code>'.$tableName.'</code><br /><code>'.$relTableName.'</code></td>
							<td style="vertical-align:top;"><code>'.$kColumn.'</code><br /><code>'.$relFieldName.'</code></td>
							<td style="vertical-align:top;"><code style="background: #cacaca; color: black;">Таблица: '.$relTableName.'</code><br />'.implode(', ', $idList).'</td>
							<td style="vertical-align:top;">'.count($idList).'</td>
						</tr>
						';
					}
				}
			}
		}
		
		// REL_Mto1
		$content['REL_Mto1'] = '';
		foreach($GLOBALS['TCA'] as $tableName => $tableValue){
			foreach($tableValue['columns'] as $kColumn => $vColumn){
				if($vColumn['AirTableConfig'] == 'REL_Mto1'){
					$relTableName = $vColumn['config']['foreign_table'];
					$relFieldName = $vColumn['config']['foreign_field_info'];
					$rows = \Yii::$app->db->createCommand("
						SELECT 

						".$tableName.".uid,
						".$tableName.".".$kColumn.",
						t2.uid AS 'uidType'

						FROM ".$tableName."

						LEFT JOIN ".$relTableName." AS t2 ON t2.uid = ".$tableName.".".$kColumn."

						WHERE ".$tableName.".".$kColumn." != 0 
						AND
						t2.uid IS NULL

						GROUP BY ".$tableName.".uid
					")->queryAll();
					if (count($rows) > 0){
						$idList = [];
						foreach($rows as $k => $row){
							$label = $GLOBALS['TCA'][$tableName]['ctrl']['label'];
							$backendLink = (
								'record_edit', [
									'columnsOnly' => $label.','.$kColumn,
									'edit['.$tableName.']['.$row['uid'].']' => 'edit',
									'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
								]
							);
							$idList[] = '<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a>';
						}
						$content['REL_Mto1'] .= '
						<tr>
							<td style="vertical-align:top;">REL_Mto1</td>
							<td style="vertical-align:top;"><code>'.$tableName.'</code><br /><code>'.$relTableName.'</code></td>
							<td style="vertical-align:top;"><code>'.$kColumn.'</code><br /><code>'.$relFieldName.'</code></td>
							<td style="vertical-align:top;"><code style="background: #cacaca; color: black;">Таблица: '.$tableName.'</code><br />'.implode(', ', $idList).'</td>
							<td style="vertical-align:top;">'.count($idList).'</td>
						</tr>
						';
					}
				}
			}
		}
		
		// REL_MtoM
		$content['REL_MtoM'] = '';
		foreach($GLOBALS['TCA'] as $tableName => $tableValue){
			foreach($tableValue['columns'] as $kColumn => $vColumn){
				if($vColumn['AirTableConfig'] == 'REL_MtoM'){
					$relTableName = $vColumn['config']['foreign_table'];
					$rows = \Yii::$app->db->createCommand("
						SELECT 

						sys_mm.uid

						FROM sys_mm

						LEFT JOIN ".$tableName." AS t2 ON t2.uid = sys_mm.uid_local
						LEFT JOIN ".$relTableName." AS t3 ON t3.uid = sys_mm.uid_foreign

						WHERE 

						sys_mm.tablename = '".$tableName."' AND
						sys_mm.fieldname = '".$kColumn."' AND
						(
							t2.uid IS NULL
							OR
							t3.uid IS NULL
						)

						GROUP BY sys_mm.uid
					")->queryAll();
					if (count($rows) > 0){
						$idList = [];
						foreach($rows as $k => $row){
							$backendLink = (
								'record_edit', [
									'edit[sys_mm]['.$row['uid'].']' => 'edit',
									'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
								]
							);
							$idList[] = '<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a>';
						}
						$content['REL_MtoM'] .= '
						<tr>
							<td style="vertical-align:top;">REL_MtoM</td>
							<td style="vertical-align:top;"><code>'.$tableName.'</code><br /><code>'.$relTableName.'</code></td>
							<td style="vertical-align:top;"><code>'.$kColumn.'</code><br /><code>'.$relFieldName.'</code></td>
							<td style="vertical-align:top;"><code style="background: #cacaca; color: black;">Таблица: sys_mm (проверяйте локальный и внешний ключи)</code><br />'.implode(', ', $idList).'</td>
							<td style="vertical-align:top;">'.count($idList).'</td>
						</tr>
						';
					}
				}
			}
		}
		
		// REL_Morph
		$content['REL_Morph'] = '';
		foreach($GLOBALS['TCA'] as $tableName => $tableValue){
			foreach($tableValue['columns'] as $kColumn => $vColumn){
				if($vColumn['AirTableConfig'] == 'REL_Morph'){
					$relTableName = $vColumn['config']['foreign_table'];
					$rows = \Yii::$app->db->createCommand("
						SELECT 

						".$relTableName.".uid,
						t2.uid AS 'uidT2'

						FROM ".$relTableName."

						LEFT JOIN ".$tableName." t2 ON t2.uid = ".$relTableName.".foreign_uid

						WHERE 
						foreign_table = '".$tableName."' AND
						foreign_field = '".$kColumn."' AND
						t2.uid IS NULL
					")->queryAll();
					if (count($rows) > 0){
						$idList = [];
						foreach($rows as $k => $row){
							$label = $GLOBALS['TCA'][$relTableName]['ctrl']['label'];
							$backendLink = (
								'record_edit', [
									'columnsOnly' => $label.',foreign_table,foreign_field,foreign_uid,foreign_sortby,foreign_info',
									'edit['.$relTableName.']['.$row['uid'].']' => 'edit',
									'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
								]
							);
							$idList[] = '<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a>';
						}
						$content['REL_Morph'] .= '
						<tr>
							<td style="vertical-align:top;">REL_Morph</td>
							<td style="vertical-align:top;"><code>'.$tableName.'</code><br /><code>'.$relTableName.'</code></td>
							<td style="vertical-align:top;"><code>'.$kColumn.'</code><br /><code>'.$relFieldName.'</code></td>
							<td style="vertical-align:top;"><code style="background: #cacaca; color: black;">Таблица: '.$relTableName.'</code><br />'.implode(', ', $idList).'</td>
							<td style="vertical-align:top;">'.count($idList).'</td>
						</tr>
						';
					}
				}
			}
		}
		
		// UPLOAD
		$content['UPLOAD'] = '';
		foreach($GLOBALS['TCA'] as $tableName => $tableValue){
			foreach($tableValue['columns'] as $kColumn => $vColumn){
				$arConfig = ['IMAGE_UPLOAD','IMAGES_UPLOAD','FILE_UPLOAD','FILES_UPLOAD'];
				if(in_array($vColumn['AirTableConfig'],$arConfig)){
					$rows = \Yii::$app->db->createCommand("SELECT uid,".$kColumn." FROM ".$tableName." WHERE ".$kColumn." <> ''")->queryAll();
					if (count($rows) > 0){
						$idList = [];
						foreach($rows as $k => $row){
							$backendLink = (
								'record_edit', [
									'edit['.$table.']['.$row['uid'].']' => 'edit',
									'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
								]
							);
							$modelName = $GLOBALS['TCA'][$tableName]['ctrl']['AirTableModel'];
							$fileUpload = \Typo3Helpers::FileUploadPath($modelName,$kColumn,$row[$kColumn]);
							foreach($fileUpload as $kFile => $pathFile){
								if(!file_exists(PATH_site.$pathFile)){
									$idList[] = '
										<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a>
										<a href="/'.$pathFile.'" target="_blank" class="label label-danger label-space-left">'.basename($pathFile).'</a>
										<br />
									';
								}
							}
						}
						if (count($idList) > 0){
							$content['UPLOAD'] .= '
							<tr>
								<td style="vertical-align:top;">UPLOAD</td>
								<td style="vertical-align:top;"><code>'.$tableName.'</code></td>
								<td style="vertical-align:top;"><code>'.$kColumn.'</code></td>
								<td style="vertical-align:top;">'.implode('', $idList).'</td>
								<td style="vertical-align:top;">'.count($idList).'</td>
							</tr>
							';
						}
					}
				}
			}
		}
		
		// FAL
		$content['FAL'] = '';
		#foreach($GLOBALS['TCA'] as $tableName => $tableValue){
		#	foreach($tableValue['columns'] as $kColumn => $vColumn){
		#		$arConfig = ['IMAGE','IMAGES','FILE','FILES'];
		#		if(in_array($vColumn['AirTableConfig'],$arConfig)){
					$rows = \app\models\SysFile::find()
						->setIgnoreEnableFields()
						->select('uid,name,identifier,storage')
						->with(['storage' => function($q){
							$q->setIgnoreEnableFields();
							$q->select('uid,configuration');
						}])
						->orderBy('uid ASC')
						->asArray()
						->all();
					if (count($rows) > 0){
						$idList = [];
						foreach($rows as $k => $row){
							$backendLink = (
								'record_edit', [
									'edit['.$table.']['.$row['uid'].']' => 'edit',
									'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
								]
							);
							$fileFalPath = \Typo3Helpers::FileFalPath($row);
							if(!file_exists(PATH_site.$fileFalPath)){
								$idList[] = '
									<a href="'.$backendLink.'" style="color: blue;">'.$row['uid'].'</a>
									<a href="/'.$fileFalPath.'" target="_blank" class="label label-danger label-space-left">'.basename($fileFalPath).'</a>
									<br />
								';
							}
						}
						if (count($idList) > 0){
							$content['FAL'] .= '
							<tr>
								<td style="vertical-align:top;">FAL</td>
								<td style="vertical-align:top;"><code>'.$tableName.'</code></td>
								<td style="vertical-align:top;"><code>'.$kColumn.'</code></td>
								<td style="vertical-align:top;">'.implode('', $idList).'</td>
								<td style="vertical-align:top;">'.count($idList).'</td>
							</tr>
							';
						}
					}
		#		}
		#	}
		#}

		if(empty(array_filter($content))){
			unset($content);
			$content = [];
			$content['GOOD'] = '
			<tr>
				<td style="vertical-align:top;">-</td>
				<td style="vertical-align:top;">-</td>
				<td style="vertical-align:top;">-</td>
				<td style="vertical-align:top;">-</td>
				<td style="vertical-align:top;">-</td>
			</tr>
		';
		}
		*/

		return $content;
	}

}
