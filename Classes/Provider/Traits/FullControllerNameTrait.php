<?php
namespace Litovchenko\AirTable\Provider\Traits;

use FluidTYPO3\Flux\Provider\AbstractProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Container\Grid;
use FluidTYPO3\Flux\Integration\PreviewView;
use Litovchenko\AirTable\Utility\BaseUtility;

trait FullControllerNameTrait
{
    /**
     * @param string $fullControllerName
     * @return string
     */
    public function setFullControllerName($c)
    {
        $this->fullControllerName = $c;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullControllerName()
    {
        return $this->fullControllerName;
    }
}