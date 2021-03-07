```
Можно ли как-то преобразовать атрибут в объект FAL при выборке? https://laravel.com/docs/8.x/eloquent-mutators

https://github.com/Kephson/fe_upload_example
https://github.com/fabarea/media_upload
https://github.com/philippjbauer/h5upldr
https://github.com/helhum/upload_example

* FAL
  * http://www.typo3blog.ru/blog/kak-sozdat-fal-filereference-v-kontrollere-i-dobavit-ee-v-extbase-model/
  * https://www.koller-webprogramming.ch/tipps-tricks/typo3-extension-extbase/dateien-und-bilder-mit-extbase-61-uploaddownload-via-fal-file-abstraction-layer/
  * https://blog.scwebs.in/typo3-fal-file-abstraction-layer-in-extbasefluid-extension/
  * https://typo3blogger.de/extbase-file-upload/
  * https://gist.github.com/xperseguers/9076406





<?php declare(strict_types = 1);

namespace App\Domain\Entities;

use Dms\Core\Model\Object\ClassDefinition;
use Dms\Core\Model\Object\Entity;

class Vehicle extends Entity
{
    const ENGINE = 'engine';

    /**
     * @var Engine
     */
    public $engine;

    /**
     * Defines the structure of this entity.
     *
     * @param ClassDefinition $class
     */
    protected function defineEntity(ClassDefinition $class)
    {
        $class->property($this->engine)->asObject(Engine::class);
    }
}

```
