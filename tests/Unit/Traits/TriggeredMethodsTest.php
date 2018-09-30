<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 15:01
 */

namespace Kenzal\ModelHelpers\Tests\Unit\Traits;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Kenzal\ModelHelpers\Tests\Classes\Models\TriggeredMethodsBase;
use PHPUnit\Framework\TestCase;

class TriggeredMethodsTest extends TestCase
{
    use \Kenzal\ModelHelpers\Tests\Helpers\TestDB;
    use \Kenzal\ModelHelpers\Tests\Helpers\TestEvents;

    public function setUp()
    {
        parent::setUp();

        EloquentModel::setEventDispatcher($this->dispatcher);
        $this->forgetAllListeners();
        EloquentModel::clearBootedModels();
    }

    public function testSingleTriggeredListener()
    {
        $this->assertEmpty($this->getListenerCount());
        $onSaving = new class extends TriggeredMethodsBase {
            protected static $triggerMethods = [
                'flag' => 'saving',
            ];
        };
        $onSaving::migrateUp();
        $event = $this->getEvent(get_class($onSaving), 'saving');
        $this->assertEquals(1, $this->getListenerCount());
        $this->assertCount(1, $this->getListeners($event));
        $this->assertFalse($onSaving->isFlagged());
        $this->assertFalse($onSaving->exists);

        //Dispatcher logs the calls to the listeners, but doesn't run them
        //  We just need to verify that the appropriate methods are called for the event

        $listener = array_first($this->getListeners($event));
        $this->assertInstanceOf(\Closure::class, $listener);
        $listener($event, [$onSaving]);
        $this->assertTrue($onSaving->isFlagged());
    }

    public function testDoubleTriggeredListener()
    {
        $this->assertEmpty($this->getListenerCount());
        $onSaving = new class extends TriggeredMethodsBase {
            protected static $triggerMethods = [
                'flag' => 'saving',
                'doubleFlag' => 'saving',
            ];
        };
        $onSaving::migrateUp();
        $event = $this->getEvent(get_class($onSaving), 'saving');
        $this->assertEquals(1, $this->getListenerCount());
        $this->assertCount(2, $this->getListeners($event));
        $this->assertFalse($onSaving->isFlagged());
        $this->assertFalse($onSaving->exists);
        foreach($this->getListeners($event) as $listener) {
            $this->assertInstanceOf(\Closure::class, $listener);
            $listener($event, [$onSaving]);
        }
        $this->assertTrue($onSaving->isFlagged());
        $this->assertEquals(3, $onSaving->getFlagCount());
    }



    public function testMultipleTriggeredListener()
    {
        $this->assertEmpty($this->getListenerCount());
        $model = new class extends TriggeredMethodsBase {
            protected static $triggerMethods = [
                'flag' => 'saving',
                'doubleFlag' => 'updating',
            ];
        };
        $model::migrateUp();
        $savingEvent = $this->getEvent(get_class($model), 'saving');
        $updatingEvent = $this->getEvent(get_class($model), 'updating');
        $this->assertEquals(2, $this->getListenerCount());
        $this->assertCount(1, $this->getListeners($savingEvent));
        $this->assertCount(1, $this->getListeners($updatingEvent));
        $this->assertFalse($model->isFlagged());
        $this->assertFalse($model->exists);
        $savingListener = array_first($this->getListeners($savingEvent));
        $updatingListener = array_first($this->getListeners($savingEvent));
        $this->assertInstanceOf(\Closure::class, $savingListener);
        $this->assertInstanceOf(\Closure::class, $updatingListener);
        $savingListener($savingEvent, [$model]);
        $this->assertTrue($model->isFlagged());
        $this->assertEquals(1, $model->getFlagCount());
        $updatingListener($updatingEvent, [$model]);
        $this->assertEquals(3, $model->getFlagCount());
        $model->resetFlag();
        $updatingListener($updatingEvent, [$model]);
        $this->assertEquals(2, $model->getFlagCount());

    }

}
