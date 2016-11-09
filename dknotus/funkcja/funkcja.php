<?php

/**
 * @param array $aNumbers
 * @return array
 */
function filterDivBy3(array $aNumbers) : array
{
    return array_filter($aNumbers, function ($iNumber) {
        if ($iNumber % 3 == 0) {
            return $iNumber;
        }
    });
}

$aNumbers = range(1, 30, 1);

echo '<pre>';
print_r(filterDivBy3($aNumbers));