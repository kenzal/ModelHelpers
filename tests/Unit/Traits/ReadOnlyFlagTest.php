<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 15:01
 */

namespace Kenzal\ModelHelpers\Tests\Unit\Traits;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Testing\Fakes\EventFake;
use Kenzal\ModelHelpers\Exceptions\ReadOnlyException;
use Kenzal\ModelHelpers\Tests\Classes\ReadOnlyDefault;
use Kenzal\ModelHelpers\Tests\Classes\ReadOnlyMarked;
use PHPUnit\Framework\TestCase;

class ReadOnlyFlagTest extends TestCase
{
    use \Kenzal\ModelHelpers\Tests\Helpers\TestDB;

    /** @var EventFake */
    protected $dispatcher;
    /** @var \Illuminate\Events\Dispatcher */
    protected $primaryDispatcher;

    public function setUp()
    {
        parent::setUp();
        $this->primaryDispatcher = new class extends \Illuminate\Events\Dispatcher {
            public function forgetAll() {
                $this->listeners = [];
                $this->wildcards = [];
            }
        };
        $this->dispatcher = new EventFake($this->primaryDispatcher);
        EloquentModel::setEventDispatcher($this->dispatcher);
        $this->migrateTables();
        $this->primaryDispatcher->forgetAll();
        EloquentModel::clearBootedModels();
    }

    public function testsModelInstanceCanBeMarkedReadOnly()
    {
        $model = new ReadOnlyDefault;
        $this->assertFalse($model->isReadOnly());
        $model->markReadOnly();
        $this->assertTrue($model->isReadOnly());
    }

    public function testModelCanBeMarkedReadOnlyByWhenExtendingFromABaseClassUsingTrait()
    {
        $model = new ReadOnlyMarked;
        $this->assertTrue($model->isReadOnly());
    }

    public function testSavingListenerIsSetOnBoot()
    {
        $savingEvent = $this->getEvent(ReadOnlyDefault::class);
//        var_dump($this->primaryDispatcher->getListeners($savingEvent)); die;
        $this->assertFalse($this->dispatcher->hasListeners($savingEvent));
        new ReadOnlyDefault;
        $this->assertTrue($this->dispatcher->hasListeners($savingEvent));
    }

    public function testListenerReturnsFalseOnReadOnly()
    {
        $event = $this->getEvent(ReadOnlyDefault::class);
        $model = new ReadOnlyDefault;
        /** @var \Closure $callable */
        $listener = array_first($this->primaryDispatcher->getListeners($event));
        $this->assertNull($listener($event, [$model]));
        $model->markReadOnly();
        $this->expectException(ReadOnlyException::class);
        $listener($event, [$model]);
    }

    protected function getEvent(string $class): string
    {
        return 'eloquent.saving: ' . $class;
    }

    protected function migrateTables(): void
    {
        ReadOnlyDefault::migrateUp();
        ReadOnlyMarked::migrateUp();
    }

}
