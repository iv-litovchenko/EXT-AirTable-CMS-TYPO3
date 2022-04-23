<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class PaginationViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'PaginationViewHelper',
		'description' 		=> 'Пагинация',
		'registerArguments' => [
			'type' => ['string'],
		]
	];
	
    use CompileWithRenderStatic;
	
    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;
	
    /**
     * Renders FlashMessages and flushes the FlashMessage queue
     * Note: This disables the current page cache in order to prevent FlashMessage output
     * from being cached.
     *
     * @see \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::no_cache
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
		$as = $arguments['as'];
		
        $templateVariableContainer = $renderingContext->getVariableProvider();
        $templateVariableContainer->add($as, $r);
        $content = $renderChildrenClosure();
        $templateVariableContainer->remove($as);

        return $content;
    }
	
	// echo createLinks( 10, 'pagination pagination-sm' ); 
	/*
	<style>
	ul { list-style: none; }
	li.fffff {  list-style: none; display: inline-block; border: black 1px solid; padding: 5px; }
	</style>
	*/
	public static function createLinks( $links, $list_class = 'css-class') 
	{
		$sdvig = 5;
		$k = 1;
		$limit = 30;
		$total = 452;
		$currentPage = $GLOBALS['_GET']['page'];
		$allPages = ceil( $total / $limit );
		
		$html .= '<ul>';
		$html .= '<li class="fffff"><a href="?page=' . ( 1 ) . '">Start</a></li>'; // В начало
		$html .= '<li class="fffff"><a href="?page=' . ( $currentPage-1 ) . '">Previous</a></li>'; // Назад
			
		if($allPages < 10){
			
		} else {
			
			$plus = 0;
			for($i = $sdvig; $i >= 1; $i--){
				if($currentPage-$i > 0){
					$html .= '<li class="fffff"><a href="?page=' . ( $currentPage-$i ) . '">'.( $currentPage-$i ).'</a><br />'.$k.'</li>';
					$k++;
				} else {
				}
			}
			
			if($currentPage <= $allPages){
				$html .= '<li class="fffff" style="background: red;"><a href="?page=' . ( $currentPage ) . '">'.( $currentPage ).'</a><br />'.$k.'</li>';
				$k++;
			}
			
			$plus = ($sdvig*2)-$sdvig-$currentPage;
			if($plus < 0){
				$plus = 0;
			}
			for($i = 1; $i <= $sdvig + $plus; $i++){
				if($currentPage+$i > $allPages){
					break;
				}
				$html .= '<li class="fffff"><a href="?page=' . ( $currentPage+$i ) . '">'.( $currentPage+$i ).'</a><br />'.$k.'</li>';
				$k++;
			}
		}
		
		$html .= '<li class="fffff"><a href="?page=' . ( $currentPage+1 ) . '">Next</a></li>'; // Вперед
		$html .= '<li class="fffff"><a href="?page=' . ( $allPages ) . '">End</a></li>'; // В конец
	 
		#if ( $end < $last ) {
		#    $html   .= '<li class="disabled"><span>...</span></li>';
		#    $html   .= '<li><a href="?page=' . $last . '">' . $last . '</a></li>';
		#}
	 
		$html .= '</ul>';
		$html .= '<hr />All pages: '.$allPages.' Current page:'.$currentPage.' All records:'.$total; // Страница 6 из 247
		return $html;
	}
}