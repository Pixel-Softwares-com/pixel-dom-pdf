<?php
 
namespace PixelDomPdf\Commands;

use Dompdf\FontMetrics;
use Illuminate\Console\Command;
use PixelDomPdf\DomPdfExntendingCode\PixelDomPdf;
use PixelDomPdf\PixelDomPdfInstructionComponents\FontInfoComponent;

class RegisterPixelDomPdfFonts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pixel-dom-pdf:register-fonts';

    
    protected function registerPixelFont(FontMetrics $fontMetrics , FontInfoComponent $fontComponent) : void
    {
        $fontMetrics->registerFont(
                                    [ 
                                        "family" => $fontComponent->getFontFamily() ,
                                        "weight" => $fontComponent->getFontWeight() , 
                                        "style" => $fontComponent->getFontStyle() 
                                    ],
                                    $fontComponent->getFontFileAbsolutePath()
                                );
    }
    
    protected function registerPixelFonts(FontMetrics $fontMetrics) : void
    {  
        foreach($this->getPixelFonts() as $fontComponent)
        {
           $this->registerPixelFont($fontMetrics , $fontComponent);
        }
    }

    protected function getConfigPixelFonts() : array
    {
        return  config("pixel-dompdf.pixel-fonts" , []);
    }
    protected function getPixelFonts()  :array
    { 
        return array_filter($this->getConfigPixelFonts()  , function($fontComponent)
        {
            return $fontComponent instanceof FontInfoComponent;
        });
    }
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'loads fonts  a font from ttf form to ufm or afm ... to be able to use by CPDF';
 
    protected function initPixelDomPdf() : PixelDomPdf
    {
        return app("dompdf");
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dompdf = $this->initPixelDomPdf();
        $fontMetrics = $dompdf->getFontMetrics();
        $this->registerPixelFonts($fontMetrics);
    }

}