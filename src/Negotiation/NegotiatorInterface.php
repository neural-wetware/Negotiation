<?php

namespace Negotiation;

interface NegotiatorInterface {

    /**
     * @param Header $header
     * @param Header $priority
     *
     * @return Match Headers matched
     */
    static function match(Header $header, Header $priority, $index);

    /**
     * TODO doc
     */
    static function typeFactory($header);


}
