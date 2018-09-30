<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 17:55
 */

namespace Kenzal\ModelHelpers\Tests\Classes;

class TestingDispatcher extends \Illuminate\Events\Dispatcher
{
    public function forgetAll()
    {
        $this->listeners = [];
        $this->wildcards = [];
    }

    public function getListenerCount()
    {
        return count($this->listeners) + count($this->wildcards);
    }
}
