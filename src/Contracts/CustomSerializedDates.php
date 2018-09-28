<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/28/18
 * Time: 17:24
 */

namespace Kenzal\ModelHelpers\Contracts;

interface CustomSerializedDates
{
    /**
     * @param null|string $serializeDateFormat
     *
     * @return $this
     */
    public function setSerializeDateFormat(?string $serializeDateFormat);

    /**
     * @return null|string
     */
    public function getSerializeDateFormat(): ?string;

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTime $date
     *
     * @return string
     */
    public function serializeDate(\DateTime $date);
}
