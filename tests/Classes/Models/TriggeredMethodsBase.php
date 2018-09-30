<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 15:33
 */

namespace Kenzal\ModelHelpers\Tests\Classes\Models;

use Illuminate\Support\Str;
use Kenzal\ModelHelpers\Traits\TriggeredMethods;

class TriggeredMethodsBase extends AbstractModel
{
    use TriggeredMethods;

    /** @var int */
    protected $flag = 0;

    public static function flag(self $model) {
        $model->flag++;
    }

    public static function doubleFlag(self $model) {
        $model->flag+=2;
    }

    public function isFlagged(): bool {
        return (bool)$this->flag;
    }

    public function getFlagCount(): bool {
        return $this->flag;
    }

    public function resetFlag() {
        $this->flag = 0;
        return $this;
    }

    public function getTable()
    {
        if (! isset($this->table)) {
            return str_replace(
                ['\\', '.'], '', Str::snake(Str::plural(class_basename($this)))
            );
        }

        return $this->table;
    }
}
