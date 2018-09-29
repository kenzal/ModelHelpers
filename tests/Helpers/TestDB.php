<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 16:16
 */

namespace Kenzal\ModelHelpers\Tests\Helpers;

use Illuminate\Database\Capsule\Manager as DB;

trait TestDB
{
    /**
     * @before
     */
    protected function setUpDatabase()
    {
        $database = new DB;
        $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $database->bootEloquent();
        $database->setAsGlobal();
    }
}
