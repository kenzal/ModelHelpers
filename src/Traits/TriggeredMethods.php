<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/28/18
 * Time: 16:30
 */

namespace Kenzal\ModelHelpers\Traits;

trait TriggeredMethods
{
    /**
     * @var array
     *
     * Array of Methods (key) and which class triggers (value) they should be added to.
     */
    protected static $triggerMethods = [
        //'setCalculatedFields' => 'saving',
    ];

    protected static function bootTriggeredMethods() {
        foreach (static::$triggerMethods as $method => $trigger) {
            if (method_exists(static::class, $method) && is_callable(static::class . "::{$method}")) {
                static::$trigger(
                    function ($model) use ($method) {
                        static::$method($model);
                    }
                );
            }
        }
    }
}
