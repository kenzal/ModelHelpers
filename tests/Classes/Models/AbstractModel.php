<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 15:33
 */

namespace Kenzal\ModelHelpers\Tests\Classes\Models;

use Illuminate\Database\Schema\Blueprint;

abstract class AbstractModel extends \Illuminate\Database\Eloquent\Model
{
    static function migrateUp()
    {
        static::resolveConnection()->getSchemaBuilder()->create(
            (new static)->getTable(),
            function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
            }
        );
    }

    static function migrateDown()
    {
        static::resolveConnection()->getSchemaBuilder()->dropIfExists((new static)->getTable());
    }

}
