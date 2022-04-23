<?php
namespace Litovchenko\AirTable\Provider\Traits;

use FluidTYPO3\Flux\Provider\AbstractProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Container\Grid;
use FluidTYPO3\Flux\Integration\PreviewView;
use Litovchenko\AirTable\Utility\BaseUtility;

trait getGridTrait
{
    /**
     * @param array $row
     * @return Grid
     */
    public function getGridTrait(array $row)
    {
		/*
		   <flux:grid>
			  <flux:grid.row>
				 <flux:grid.column name="mycontentA" label="mycontentA" colPos="0">
					<flux:form.variable name="allowedContentTypes" value="textmedia"/>
					<flux:form.variable name="Fluidcontent" value="{allowedContentTypes: 'Vendor.ExtensionName:HeroImage.html'}" />
				 </flux:grid.column>
				 <flux:grid.column name="mycontentB" label="mycontentB" colPos="1" />
			  </flux:grid.row>
			  <flux:grid.row>
				 <flux:grid.column name="mycontentC" label="mycontentC" colPos="2" colspan="2" rowspan="1" style="width: 300px; height: 300px;" />
			  </flux:grid.row>
		   </flux:grid>
		*/
		
		// $templateFile = $this->getTemplatePathAndFilename($row);
		// $ext = $this->getExtensionKey($row); // getControllerNameFromRecord // getListType
		
		#print "<pre>";
		#print_r($class);
		#exit();
		
		// $class = 'Litovchenko\Projiv\Controller\PagesElements\Gridelements\Cols3Controller';
		$class = $this->getFullControllerName();
		if(class_exists($class) && property_exists($class, 'TYPO3')){
			$annotationFluxGrids = BaseUtility::getClassAnnotationValueNew($class,'AirTable\FluxGrids');
			$fluxGrids = $annotationFluxGrids;
			if(!empty($fluxGrids)){
				$grid = BaseUtility::readFluxGrids(Grid::create(), $fluxGrids);
				return $grid;
			}
		}
		
		// Empty
		$grid = Grid::create();
		$gridRow = $grid->createContainer('Row', '1');
		$gridColumn = $gridRow->createContainer('Column', 0, 0);
		$gridColumn->setColumnPosition(0);
		return $grid;
    }
	
}