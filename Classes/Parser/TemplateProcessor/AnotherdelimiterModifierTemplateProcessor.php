<?php
namespace Litovchenko\AirTable\Parser\TemplateProcessor;

use TYPO3Fluid\Fluid\Core\Parser\Exception;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessorInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class AnotherdelimiterModifierTemplateProcessor implements TemplateProcessorInterface
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
        if (strpos($templateSource, '{fluidanotherdelimiter') === false) {
            return $templateSource;
        } else {
			// -- OLD --
			#$templateSource = str_replace('[[[', '&#91;&#91;', $templateSource); //+
			#$templateSource = str_replace(']]]', '&#93;&#93;', $templateSource); //+
			#$templateSource = str_replace('{{{ ', '&#123;&#123;', $templateSource); //+
			#$templateSource = str_replace(' }}}', '&#125;&#125;', $templateSource); //+
            #$templateSource = preg_replace('/=`([^`]*)`/is', '="\\1"', $templateSource); //+
            #$templateSource = str_replace('[[', '<', $templateSource); //+
            #$templateSource = str_replace(']]', '>', $templateSource); //+
			#$templateSource = preg_replace('/\{\{ \$([^\}]*) \}\}/is', '{\\1}', $templateSource);
			
			// -- NEW --
			$templateSource = preg_replace('/\{\{\{ \$([^}]*) \}\}\}/is', '&#123;&#123; \$\\1 &#125;&#125;', $templateSource); // Заменяем {{{ $var }}} на спец.символы (игнорирование обработки)
			$templateSource = preg_replace('/\{\{ \$([^}]*) \}\}/is', '{\\1}', $templateSource); // Заменяем {{ $var }} на {var}
			$templateSource = preg_replace('/\[\[([\/]{0,1})f:([^\]]*)\]\]/is', '&#91;\\1f:\\2&#93;', $templateSource); // Заменяем [[ ]] на спец.символы (игнорирование обработки)
			$templateSource = preg_replace('/\[([\/]{0,1})f:([^\]]*)\]/is', '<\\1f:\\2>', $templateSource); // Заменяем [f: ] -> на <f: ]
			$templateSource = preg_replace_callback(
				'/<([^<>]+)>/',
				function ($matches) {
					// return str_replace('`', '"',$matches[0]);
					return preg_replace('/=`([^`]*)`/is', '="\\1"', $matches[0]); // Заменяем ` на "
				},
				$templateSource
			);
			return $templateSource;
		}
    }
}
