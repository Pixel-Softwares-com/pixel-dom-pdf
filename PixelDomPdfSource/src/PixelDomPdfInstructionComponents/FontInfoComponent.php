<?php

namespace PixelDomPdf\PixelDomPdfInstructionComponents;

use Exception;
use JsonSerializable; 

class FontInfoComponent  implements JsonSerializable  
{
    
    protected string $fontFamily ;
    protected string | int $fontWeight ;
    protected string $fontStyle ;
    protected string $fontFileAbsolutePath;

    public function __construct(string $fontFamily , string | int $fontWeight , string $fontFileAbsolutePath , string $fontStyle = "normal")
    {
        $this->setFontFamily($fontFamily)->setFontWeight($fontWeight)->setFontFileAbsolutePath($fontFileAbsolutePath)->setFontStyle($fontStyle);
    }
    public static function create(string $fontFamily , string | int $fontWeight , string $fontFileAbsolutePath , string $fontStyle = "normal") : self
    {
        return new static($fontFamily , $fontWeight  , $fontFileAbsolutePath, $fontStyle);
    }

    public function setFontFamily(string $fontFamily) : self
    {
        $this->fontFamily = $fontFamily;
        return $this;
    }
    
    public function setFontWeight(string $fontWeight) : self
    {
        $this->fontWeight = $fontWeight;
        return $this;
    }
    
    public function setFontStyle(string $fontStyle) : self
    {
        $this->fontStyle = $fontStyle;
        return $this;
    }
    
    public function setFontFileAbsolutePath(string $fontFileAbsolutePath) : self
    {
        $this->fontFileAbsolutePath = $fontFileAbsolutePath;
        return $this;
    }
    public function getFontFamily() : string
    {
        return $this->fontFamily;
    }
    public function getFontWeight() : string|int
    {
        return $this->fontWeight;
    }
    public function getFontStyle() : string
    {
        return $this->fontStyle;
    }
    public function getFontFileAbsolutePath() : string
    {
        return $this->fontFileAbsolutePath;
    }

    protected function getSerlizingProps() : array
    {
        return [ 'fontFamily' ,'fontWeight' , 'fontFileAbsolutePath' ,'fontStyle'  ];
    }

    protected function getSerlizingPropValues() : array
    {
        $values = [];
        foreach($this->getSerlizingProps() as $prop)
        {
            $values[$prop] = $this->{$prop};
        }
        return $values;
    }

    public function jsonSerialize(): mixed
    {
        return $this->getSerlizingPropValues();
    } 

    public function __serialize(): array
    {
        return $this->getSerlizingPropValues();
    }

    protected static function throwUnerilizableObjectException() : void
    {
        throw new Exception("Failed to unserlize FontInfoComponent ... A wrong Serilized data string is passed !");
    }
    
    protected static function checkRequiredProps($data) : void
    {
        if(
            ! is_array($data) ||
            ! array_key_exists('fontFamily' , $data) ||
            ! array_key_exists('fontWeight' , $data) ||
            ! array_key_exists('fontFileAbsolutePath' , $data)  ||
            ! array_key_exists('fontStyle' , $data) 
          )
        {
            static::throwUnerilizableObjectException();
        }
    }

    protected function setUnserlizedProps($data)
    { 
        static::checkRequiredProps($data);

        $this->setFontFamily($data["fontFamily"])
             ->setFontWeight($data["fontWeight"])
             ->setFontFileAbsolutePath($data["fontFileAbsolutePath"])
             ->setFontStyle($data["fontStyle"]);
    } 

    // Rehydrate the object from serialized data
    public function __unserialize(array $data): void
    {
        $this->setUnserlizedProps($data);
    }

    public static function __set_state($data)
    {
        static::checkRequiredProps($data);  
        return static::create($data["fontFamily"] , $data["fontWeight"] ,$data["fontFileAbsolutePath"] , $data["fontStyle"]);
    }
}