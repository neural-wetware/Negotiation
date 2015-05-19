<?php

namespace Negotiation;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class AcceptHeader extends AbstractHeader
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var float
     */
    protected $quality;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        list($type, $parameters) = $this->parseParameters($value);

        $quality = 1.0;
        if (isset($parameters['q'])) {
            $quality = (float)$parameters['q'];
            unset($parameters['q']);
        }

        $this->value      = $type . ($parameters ? ";" . $this->buildParametersString($parameters, null, ';') : '');
        $this->type       = $type;
        $this->quality    = $quality;
        $this->parameters = $parameters;

        $parts = explode('/', $type);

        if (count($parts) == 2) {
            $this->baseType   = $parts[0];
            $this->subType    = $parts[1];
        } if (count($parts) == 1) {
            $this->baseType   = $parts[0];
        } else {
            throw new Exception('invalid media type in header.');
        }
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return string|null
     */
    public function getParameter($key, $default = null)
    {
        return $this->hasParameter($key) ? $this->parameters[$key] : $default;
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function hasParameter($key)
    {
        return isset($this->parameters[$key]);
    }

    /**
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @return boolean
     */
    public function isMediaRange()
    {
        return false !== strpos($this->mediaType, '*');
    }
}
