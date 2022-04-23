<?php
declare(strict_types = 1);
namespace Litovchenko\AirTable\Domain\Model\Fields;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class FieldErrorDisplay extends AbstractFormElement
{
    public function render()
    {
        // Custom TCA properties and other data can be found in $this->data, for example the above
        // parameters are available in $this->data['parameterArray']['fieldConf']['config']['parameters']
        $result = $this->initializeResultArray();
		
		$result['html'] = '<strong>Поле ['.$this->data['parameterArray']['fieldConf']['config']['parameters']['field'].'] содержит ошибки в конфигурации. ';
        $result['html'] .= 'Пожалуйста, исправте следующие параметры конфигурации для данного поля:</strong><br />';
        $result['html'] .= '<ul>'.implode('',$this->data['parameterArray']['fieldConf']['config']['parameters']['message']).'</ul>';
        $result['html'] = '<div class="alert alert-warning">'.$result['html'].'</div>';
		
        return $result;
    }
}