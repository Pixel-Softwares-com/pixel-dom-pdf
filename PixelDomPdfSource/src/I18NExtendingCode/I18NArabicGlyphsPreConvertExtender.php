<?php

namespace PixelDomPdf;

class I18NArabicGlyphsPreConvertExtender
{

    public static function checkCharsKeyExistingCondition(array $chars = [] , int $index ) : bool
    {
        return isset($chars[$index]);
    }
}