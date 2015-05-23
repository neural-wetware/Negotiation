<?php

namespace Negotiation;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
abstract class AbstractNegotiator
{
    /**
     * @param string $header     A string containing an `Accept|Accept-*` header.
     * @param array  $priorities A set of server priorities.
     *
     * @return Header best matching type
     */
    public function getBest($header, array $priorities)
    {
        if (!$priorities) {
            throw new \Exception('no priorities given'); 
        }

        if (!$header) {
            throw new \Exception('empty header given'); 
        }

        $headers = $this->parseHeader($header);

        $headers = $this->mapHeaders($headers);
        $priorities = $this->mapHeaders($priorities);

        $matches = $this->findMatches($headers, $priorities);

        # TODO what if only 1 or 2 items. will usort() work? read somewhere it won't.
        usort($matches, array($this, 'compare'));

        $match = array_shift($matches);
        if ($match === null) {
            return null;
        }

        return $priorities[$match->index];
    }

    /**
     * @param string $header A string that contains an `Accept-*` header.
     *
     * @return Header[]
     */
    private function parseHeader($header)
    {
        $header      = preg_replace('/\s+/', '', $header);
        $acceptParts = preg_split('/\s*(?:,*("[^"]+"),*|,*(\'[^\']+\'),*|,+)\s*/', $header, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE); # TODO quoted param values can contain ",". does this accout for that? unit tests?

        if (!$acceptParts) {
            throw new \Exception('failed to parse Accept-Languge header');
        }

        return $acceptParts;
    }

    /**
     * @param array $priorities list of server priorities
     *
     * @return Header[]
     */
    private function mapHeaders($priorities)
    {
        return array_map(function($p) { return $this->typeFactory($p); }, $priorities);
    }

    /**
     * @param Header[]      $headers
     * @param Priority[]    $priorities    Configured priorities
     *
     * @return Match[] Headers matched
     */
    protected function findMatches(array $headerParts, array $priorities) {
        $matches = array();

        foreach ($priorities as $index => $p) {
            foreach ($headerParts as $a) {
                if ($match = $this->match($a, $p, $index))
                    $matches[] = $match;
            }
        }

        return $matches;
    }

    /**
     * @param Match[] $a
     * @param Match[] $b
     *
     * @return int
     */
    private static function compare(Match $a, Match $b) {
        if ($a->quality > $b->quality) {
            return -1;
        } else if ($a->quality < $b->quality) {
            return 1;
        }

        # priority goes to to more specific match
        if ($a->type == $b->type) {
            if ($a->score < $b->score) {
                return 1;
            } else if ($a->score > $b->score) {
                return -1;
            }
        }
            
        if ($a->index < $b->index) {
            return 1;
        } else if ($a->index > $b->index) {
            return -1;
        }

        return 0;
    }

    /**
     * @param Header $header
     * @param Header $priority
     *
     * @return Match Headers matched
     */
    abstract protected function match(Header $header, Header $priority, $index);

    /**
     * TODO
     */
    abstract protected function typeFactory($header);

}
