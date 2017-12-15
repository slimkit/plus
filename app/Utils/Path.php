<?php

namespace Zhiyi\Plus\Utils;

class Path
{
    /**
     * relative PATH.
     *
     * @param string $fromPath
     * @param string $toPath
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function relative(string $fromPath, string $toPath): string
    {
        $fromPath = str_replace('\\', '/', realpath($fromPath));
        $toPath = str_replace('\\', '/', realpath($toPath));

        $fromParts = explode('/', $fromPath);
        $toParts = explode('/', $toPath);

        $length = min(count($fromParts), count($toParts));
        $samePartsLength = $length;
        for ($i = 0; $i < $length; $i++) {
            if ($fromParts[$i] !== $toParts[$i]) {
                $samePartsLength = $i;
                break;
            }
        }

        $outputParts = [];
        for ($i = $samePartsLength; $i < count($fromParts); $i++) {
            array_push($outputParts, '..');
        }

        $outputParts = array_merge($outputParts, array_slice($toParts, $samePartsLength) ?: []);

        return implode('/', $outputParts);
    }
}
