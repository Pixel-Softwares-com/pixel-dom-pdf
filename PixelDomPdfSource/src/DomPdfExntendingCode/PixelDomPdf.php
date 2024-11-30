<?php

namespace PixelDomPdf\DomPdfExntendingCode;

use Dompdf\Dompdf;
use PixelDomPdf\Interfaces\PixelPdfNeedsProvider;
use Illuminate\Contracts\View\View;

class PixelDomPdf extends Dompdf implements PixelPdfNeedsProvider
{
    const PixelDefaultFontFolder = __DIR__ . "/../PixelDefaultFonts";
    
    protected bool $needsRendering = true;

    protected function hasRendered() : void
    {
        $this->needsRendering = false;
    }
    protected function needsRendering() : void
    {
        $this->needsRendering = true;
    }
    protected function DoesItNeedRendering() : bool
    {
        return $this->needsRendering;
    }
    public function loadView(View $view)
    {
        $html = $view->render();
        $this->loadHtml($html);
    }

    public function loadHtml($str, $encoding = null)
    {
        parent::loadHtml($str, $encoding );
        $this->needsRendering();
    }

    public function stream($filename = "document.pdf", $options = [])
    {
        if($this->DoesItNeedRendering())
        {
            $this->render();  
        }
        parent::stream($filename , $options);
    }

    public function output($options = [])
    {
        if($this->DoesItNeedRendering())
        {
            $this->render();  
        }
        return parent::output($options);
    }
     /**
     * Renders the HTML to PDF
     */
    public function render()
    {
        parent::render();
        $this->hasRendered();
    }
     
}