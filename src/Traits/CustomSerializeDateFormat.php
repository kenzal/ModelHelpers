<?php
/**
 * Created by PhpStorm.
 * User: khunter
 * Date: 9/24/18
 * Time: 09:38
 */

namespace Kenzal\ModelHelpers\Traits;

trait CustomSerializeDateFormat
{
    /**
     * @var null|string
     */
    protected $serializeDateFormat = null;

    abstract protected function getDateFormat();

    /**
     * @param null|string $serializeDateFormat
     *
     * @return $this
     */
    public function setSerializeDateFormat(?string $serializeDateFormat)
    {
        $this->serializeDateFormat = $serializeDateFormat;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSerializeDateFormat(): ?string
    {
        return $this->serializeDateFormat;
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTime $date
     *
     * @return string
     */
    public function serializeDate(\DateTime $date)
    {
        return $date->format($this->serializeDateFormat ?: $this->getDateFormat());
    }
}
