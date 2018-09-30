<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 15:01
 */

namespace Kenzal\ModelHelpers\Tests\Unit\Traits;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Kenzal\ModelHelpers\Exceptions\ReadOnlyException;
use Kenzal\ModelHelpers\Tests\Classes\Models\ReadOnlyDefault;
use Kenzal\ModelHelpers\Tests\Classes\Models\ReadOnlyMarked;
use PHPUnit\Framework\TestCase;

class ReadOnlyFlagTest extends TestCase
{
    use \Kenzal\ModelHelpers\Tests\Helpers\TestDB;
    use \Kenzal\ModelHelpers\Tests\Helpers\TestEvents;

    public function setUp()
    {
        parent::setUp();

        EloquentModel::setEventDispatcher($this->dispatcher);
        $this->migrateTables();
        $this->forgetAllListeners();
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
        $savingEvent = $this->getEvent(ReadOnlyDefault::class, 'saving');
//        var_dump($this->primaryDispatcher->getListeners($savingEvent)); die;
        $this->assertFalse($this->dispatcher->hasListeners($savingEvent));
        new ReadOnlyDefault;
        $this->assertTrue($this->dispatcher->hasListeners($savingEvent));
    }

    public function testListenerReturnsFalseOnReadOnly()
    {
        $event = $this->getEvent(ReadOnlyDefault::class, 'saving');
        $model = new ReadOnlyDefault;
        /** @var \Closure $callable */
        $listener = array_first($this->getListeners($event));
        $this->assertNull($listener($event, [$model]));
        $model->markReadOnly();
        $this->expectException(ReadOnlyException::class);
        $listener($event, [$model]);
    }

    protected function migrateTables(): void
    {
        ReadOnlyDefault::migrateUp();
        ReadOnlyMarked::migrateUp();
    }

}
