<?php
namespace Ipol\OzonPay\SDK\Responses;

use Ipol\OzonPay\SDK\Exceptions\OzonBadResponseException;
use Ipol\OzonPay\SDK\Responses\Entity\AbstractEntity;

abstract class AbstractResponse extends AbstractEntity
{

    /**
     * @var string
     */
    protected $origin;
    /**
     * @var mixed
     */
    protected $decoded;
    /**
     * @var bool
     */
    protected $Success;

    /**
     * AbstractResponse constructor.
     * @param $json
     * @throws BadResponseException
     * @throws OzonBadResponseException
     * @throws OzonBadResponseException
     */
    function __construct($json)
    {
        $this->origin = $json;
        if (empty($json)) {
            throw new OzonBadResponseException('Empty server answer ' . __CLASS__);
        }
        $this->setDecoded(json_decode($json));
        if (is_null($this->decoded)) {
            throw new OzonBadResponseException('Incorrect server answer ' . __CLASS__);
        }
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return AbstractResponse
     */
    public function setOrigin(string $origin): AbstractResponse
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDecoded()
    {
        return $this->decoded;
    }

    /**
     * @param mixed $decoded
     * @return AbstractResponse
     */
    public function setDecoded($decoded)
    {
        $this->decoded = $decoded;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->Success;
    }

    /**
     * @param bool $Success
     * @return AbstractResponse
     */
    public function setSuccess(bool $Success): AbstractResponse
    {
        $this->Success = $Success;
        return $this;
    }

}