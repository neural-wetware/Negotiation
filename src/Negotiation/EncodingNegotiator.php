<?php

namespace Negotiation;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class EncodingNegotiator extends AbstractNegotiator
{

    /**
     * @param strint $type
     *
     * @return AcceptEncodingHeader
     */
    static function typeFactory($type)
    {
        return new AcceptEncodingHeader($type);
    }

    /**
     * {@inheritdoc}
     */
    static function match(Header $charsetHeader, Header $priority, $index) {
    #TODO check this against rfc!!!
        $ac = $charsetHeader->getType();
        $pc = $priority->getType();

        $equal = !strcasecmp($ac, $pc);

        if ($equal || $ac == '*') {
            $score = 1 * $equal;
            return new Match($charsetHeader->getQuality(), $score, $index);
        }

        return null;
    }

}
