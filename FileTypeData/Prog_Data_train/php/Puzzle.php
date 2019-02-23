<?php

namespace me\adamcameron\puzzle;

class Puzzle
{
    function getSubsetsWithTotal($array, $sum)
    {
        sort($array);

        $results = $this->getSubsAfterSort($array, $sum);

        usort($results, function($e1, $e2){
            return count($e2) - count($e1);
        });

        $deduped = [];
        foreach ($results as $value) {
            if (in_array($value, $deduped)) {
                continue;
            }
            $deduped[] = $value;
        }

        return $deduped;
    }

    private function getSubsAfterSort($array, $sum)
    {
        $results = [];
        foreach ($array as $i => $v) {
            if ($v == $sum) {
                $results[] = [$v];
                break;
            }
            if ($v > $sum) {
                break;
            }
            $restFromHere = array_slice($array, $i+1);

            $subs = $this->getSubsetsWithTotal($restFromHere, $sum-$v);
            if (!count($subs)){
                continue;
            }
            $results = array_merge($results, array_map(function($sub) use ($v){
                array_unshift($sub, $v);
                return $sub;
            }, $subs));
        }
        return $results;
    }
}
