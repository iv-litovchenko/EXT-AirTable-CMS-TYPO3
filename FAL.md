```
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
  * https://stackoverflow.com/questions/59719142/how-to-upload-a-file-with-typo3-fluid-form-upload-and-pass-it-to-extbase-control
  * https://coderoad.ru/54051157/TYPO3-Extbase-%D0%BA%D0%B0%D0%BA-%D1%80%D0%B0%D0%B7%D0%BC%D0%B5%D1%81%D1%82%D0%B8%D1%82%D1%8C-%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%B5-%D1%84%D0%BE%D1%80%D0%BC%D1%8B-%D0%BD%D0%B0-%D0%BA%D0%BE%D0%BD%D1%82%D1%80%D0%BE%D0%BB%D0%BB%D0%B5%D1%80%D0%B5

```



```
Можно ли как-то преобразовать атрибут в объект FAL при выборке?




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
