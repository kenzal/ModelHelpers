<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 13:45
 */

namespace Kenzal\ModelHelpers\Tests\Unit\Traits;

use Carbon\Carbon;
use Kenzal\ModelHelpers\Traits\CustomSerializeDateFormat;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class CustomSerializeDateFormatTest
 *
 * @package Kenzal\ModelHelpers\Tests\Unit\Traits
 */
class CustomSerializeDateFormatTest extends \PHPUnit\Framework\TestCase
{
    const FORMAT_DEFAULT = Carbon::DEFAULT_TO_STRING_FORMAT;
    const FORMAT_TEST    = Carbon::ATOM;

    /** @var CustomSerializeDateFormat|MockObject $mock */
    protected $model;

    /**
     * @throws \ReflectionException
     */
    public function setUp()
    {
        parent::setUp();
        $this->model = $this->getMockForTrait(CustomSerializeDateFormat::class);
        $this->model->expects($this->any())
                    ->method('getDateFormat')
                    ->will($this->returnValue(self::FORMAT_DEFAULT));
    }

    public function testsTestingFormatIsDifferentFromDefault()
    {
        $time = new Carbon;
        $this->assertNotSame(self::FORMAT_DEFAULT, self::FORMAT_TEST);
        $this->assertNotSame($time->format(self::FORMAT_DEFAULT), $time->format(self::FORMAT_TEST));
    }

    public function testWillReturnDefaultFormattedTimeWhenNotOtherwiseSet()
    {
        $this->assertNull($this->model->getSerializeDateFormat());
        $this->model->setSerializeDateFormat(self::FORMAT_TEST);
        $this->assertSame(self::FORMAT_TEST, $this->model->getSerializeDateFormat());
    }

    public function testCustomFormatCanBeSetAndRetrieved()
    {
        $this->assertNull($this->model->getSerializeDateFormat());
        $this->model->setSerializeDateFormat(self::FORMAT_TEST);
        $this->assertSame(self::FORMAT_TEST, $this->model->getSerializeDateFormat());
    }

    public function testSetSerializedDateFormatIsFluent()
    {
        $this->assertSame($this->model, $this->model->setSerializeDateFormat(self::FORMAT_TEST));
    }

    public function testSerializeDateUsesSpecifiedFormat()
    {
        $time = Carbon::now();
        $this->model->setSerializeDateFormat(self::FORMAT_TEST);
        $this->assertSame($time->format(self::FORMAT_TEST), $this->model->serializeDate($time));
    }

    public function testFormatCanBeUnset()
    {
        $default = $this->model->getSerializeDateFormat();
        $this->model->setSerializeDateFormat(self::FORMAT_TEST);
        $this->assertNotSame($default, $this->model->getSerializeDateFormat());
        $this->model->setSerializeDateFormat(null);
        $this->assertSame($default, $this->model->getSerializeDateFormat());
    }
}
