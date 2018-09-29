<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/28/18
 * Time: 16:46
 */

namespace Kenzal\ModelHelpers\Contracts;

interface ReadOnlyableModel
{
    public function isReadOnly(): bool;

    /**
     * @return $this
     */
    public function markReadOnly();
}
