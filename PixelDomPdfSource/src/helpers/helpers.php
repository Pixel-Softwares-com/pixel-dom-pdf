<?php

use PixelDomPdf\DomPdfExntendingCode\PixelDomPdf;

if(! function_exists("pixelDefaultFontsPath"))
{

    function pixelDefaultFontsPath(?string $path = null) : string
    {

        if($path)
        {
            return PixelDomPdf::PixelDefaultFontFolder . "/" . trim($path , "/");
        }

        return PixelDomPdf::PixelDefaultFontFolder ;
    }
}