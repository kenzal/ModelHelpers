<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/28/18
 * Time: 16:34
 */

namespace Kenzal\ModelHelpers\Traits;

use Kenzal\ModelHelpers\Contracts\ReadOnlyableModel;
use Kenzal\ModelHelpers\Exceptions\ReadOnlyException;

trait ReadOnlyFlag
{
    protected $readOnlyFlag = false;

    protected static function bootReadOnlyFlag () {

        // This is the teeth, if the object isn't set up for event listeners, it won't do anything
        if(method_exists(static::class, 'saving')) {
            /** @noinspection PhpUndefinedMethodInspection We just checked this*/
            static::saving(
                function (ReadOnlyableModel $model) {
                    if ($model->isReadOnly()) {
                        throw new ReadOnlyException;
                    }
                }
            );
        }
    }

    public function isReadOnly(): bool
    {
        return (bool)$this->readOnlyFlag;
    }

    public function markReadOnly()
    {
        $this->readOnlyFlag = true;

        return $this;
    }

}
