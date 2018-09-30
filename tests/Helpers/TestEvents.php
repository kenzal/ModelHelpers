<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 17:50
 */

namespace Kenzal\ModelHelpers\Tests\Helpers;

use Illuminate\Support\Testing\Fakes\EventFake;
use Kenzal\ModelHelpers\Tests\Classes\TestingDispatcher;

/**
 * Will log event dispatches but prevent them from taking place
 *
 * @package Kenzal\ModelHelpers\Tests\Helpers
 */
trait TestEvents
{

    /** @var TestingDispatcher */
    private $finalDispatcher;
    /** @var EventFake */
    protected $dispatcher;

    /**
     * @before
     */
    public function setUpEventDispatchers()
    {
        $this->finalDispatcher = new TestingDispatcher;

        $this->dispatcher = new EventFake($this->finalDispatcher);

    }

    protected function getEvent(string $class, string $listener): string
    {
        return "eloquent.{$listener}: {$class}";
    }

    protected function forgetAllListeners()
    {
        $this->finalDispatcher->forgetAll();
    }

    protected function getListeners($event)
    {
        return $this->finalDispatcher->getListeners($event);
    }

    protected function getListenerCount() {
        return $this->finalDispatcher->getListenerCount();
    }
}
