<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/29/18
 * Time: 15:33
 */

namespace Kenzal\ModelHelpers\Tests\Classes;

use Kenzal\ModelHelpers\Contracts\ReadOnlyableModel;

class ReadOnlyDefault extends BaseModel implements ReadOnlyableModel
{
    use \Kenzal\ModelHelpers\Traits\ReadOnlyFlag;

}
