<?php

namespace Negotiation;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class LanguageNegotiator extends AbstractNegotiator
{

    /**
     * @param strint $type
     *
     * @return AcceptLanguageHeader
     */
    static function typeFactory($type)
    {
        return new AcceptLanguageHeader($type);
    }

    /**
     * {@inheritdoc}
     */
    static function match(Header $acceptLanguageHeader, Header $priority, $index) {
        $ab = $acceptLanguageHeader->getBasePart();
        $pb = $priority->getBasePart();

        $as = $acceptLanguageHeader->getSubPart();
        $ps = $priority->getSubPart();

        $baseEqual = !strcasecmp($ab, $pb);
        $subEqual = !strcasecmp($as, $ps);

        if (($ab == '*' || $baseEqual) && ($as === null || $subEqual)) {
            $score = 10 * $baseEqual + $subEqual;
            return new Match($acceptLanguageHeader->getQuality(), $score, $index);
        }

        return null;
    }

}
