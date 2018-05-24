<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 18/05/18
 * Time: 23:12.
 */

namespace Happy\Service;

/**
 * Class AbstractService.
 */
abstract class AbstractService
{
    /**
     * @var null|string
     */
    private $lastError = null;

    /**
     * @param null|string $lastError
     */
    public function setLastError(?string $lastError): void
    {
        $this->lastError = $lastError;
    }

    /**
     * @return null|string
     */
    public function lastError()
    {
        return $this->lastError;
    }
}
