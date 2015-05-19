<?php

namespace Negotiation;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class AbstractHeader
{
    /**
     * @var string
     */
    protected $quality;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $baseType = null;

    /**
     * @var string|null
     */
    protected $subType = null;

    /**
     * @param string $type
     *
     * @return array
     */

    protected static function parseParameters($acceptPart)
    {
        $parts = explode(';', preg_replace('/\s+/', '', $acceptPart));

        $type = array_shift($parts);

        $parameters = array();

        foreach ($parts as $part) {
            $part = explode('=', $part);

            if (2 !== count($part)) {
                continue;
            }

            $key = strtolower($part[0]);
            $parameters[$key] = $part[1];
        }

        return array($type, $parameters);
    }

    /**
     * @param string $parameters
     *
     * @return string
     */

    protected static function buildParametersString($params) {
        $parts = array();

        foreach ($params as $key => $val) {
            $parts[] = "$key=$val";
        }

        return implode(";", $parts);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return float
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @return string
     */
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * @return string
     */
    public function getBaseType()
    {
        return $this->baseType;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
