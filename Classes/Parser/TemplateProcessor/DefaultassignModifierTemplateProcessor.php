<?php
namespace Litovchenko\AirTable\Parser\TemplateProcessor;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3Fluid\Fluid\Core\Parser\Exception;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessorInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class DefaultassignModifierTemplateProcessor implements TemplateProcessorInterface
{
    /**
     * @var RenderingContextInterface
     */
    protected $renderingContext;

    /**
     * @param RenderingContextInterface $renderingContext
     */
    public function setRenderingContext(RenderingContextInterface $renderingContext)
    {
        $this->renderingContext = $renderingContext;
    }

    /**
     * Pre-process the template source before it is
     * returned to the TemplateParser or passed to
     * the next TemplateProcessorInterface instance.
     *
     * @param string $templateSource
     * @return string
     */
    public function preProcessSource($templateSource)
    {
		// Не подошло - при повторном вызове пропадает,
		// и нет возможности получить доступ к $this->configurationManager->getContentObject()->data['uid']
        if (strpos($templateSource, '{defaultassign') !== false) {
			// $this->renderingContext->getVariableProvider()->add('t3page', 'fefew');
			// $this->renderingContext->getVariableProvider()->add('t3data', 'ff'); // $this->configurationManager->getContentObject()->data['uid']
			// $this->renderingContext->getVariableProvider()->add('TMP_NAME', 'ff');
		}
		return $templateSource;
    }
}
