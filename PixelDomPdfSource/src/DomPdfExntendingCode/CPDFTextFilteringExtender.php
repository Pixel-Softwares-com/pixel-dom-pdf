<?php

namespace PixelDomPdf\DomPdfExntendingCode;

use PixelDomPdf\Lib\I18N\I18N_Arabic;

class CPDFTextFilteringExtender
{
  
    // private function reverseArabicKeepEnglish($text)
    // {
    //     $arr = explode(' ', $text);
    //     $englishMap = [];
    //     foreach ($arr as $index => $word) {
    //         if ($this->isEnglish($word)) {
    //             $englishMap[$index] = $word;
    //         }
    //     }

    //     // Filter out non-English words and reverse them
    //     $arabicWords = array_values(array_filter($arr, function ($word) {
    //         return !$this->isEnglish($word);
    //     }));
    //     $arabicWords = array_reverse($arabicWords);

    //     // Re-insert English words at their original positions
    //     $result = [];
    //     $arabicIndex = 0;
    //     for ($i = 0; $i < count($arr); $i++) {
    //         if (isset($englishMap[$i])) {
    //             $result[$i] = $englishMap[$i];
    //         } else {
    //             $result[$i] = $arabicWords[$arabicIndex];
    //             $arabicIndex++;
    //         }
    //     }

    //     return implode(' ', $result);
    // }

    //work perfectly when mixed text but it contains a bug with punctuation
    public static function fixTextWordsDirection($text)
    {
        $words = preg_split('/\s+/', $text);
        $segments = [];
        $currentSegment = [];
        $isRtl = false;
        $endPunctuation = '';

        // Check for ending punctuation
        if (preg_match('/([.!?،؛])$/', $text, $matches)) {
            $endPunctuation = $matches[1];
            $text = rtrim($text, $endPunctuation);
            $words = preg_split('/\s+/', $text);
        }

        foreach ($words as $word) {
            // Extract punctuation from the word
            $punctuation = '';
            if (preg_match('/([.!?،؛]+)$/', $word, $matches)) {
                $punctuation = $matches[1];
                $word = rtrim($word, $punctuation);
            }

            $type = preg_match('/[\p{Arabic}]/u', $word) ? 'arabic' : 'english';
            $currentSegment[] = ['word' => $word, 'type' => $type, 'punctuation' => $punctuation];
            if ($type === 'arabic') {
                $isRtl = true;
            }
        }

        if ($isRtl) {
            $currentSegment = array_reverse($currentSegment);
            $result = [];
            $tempEnglish = [];

            foreach ($currentSegment as $item) {
                if ($item['type'] === 'english') {
                    $tempEnglish[] = $item['word'] . $item['punctuation'];
                } else {
                    if (!empty($tempEnglish)) {
                        $result = array_merge($result, array_reverse($tempEnglish));
                        $tempEnglish = [];
                    }
                    $result[] = $item['punctuation'] . $item['word'];
                }
            }

            if (!empty($tempEnglish)) {
                $result = array_merge($result, array_reverse($tempEnglish));
            }

            // Add the ending punctuation to the beginning for RTL text
            if ($endPunctuation) {
                array_unshift($result, $endPunctuation);
            }
        } else {
            $result = array_map(function ($item) {
                return $item['word'] . $item['punctuation'];
            }, $currentSegment);
            // Add the ending punctuation to the end for LTR text
            if ($endPunctuation) {
                $result[] = $endPunctuation;
            }
        }

        return implode(' ', $result);
    }



    //this fucntion work perfectly when we need arabic or english only.
    /* private static function fixTextWordsDirection($text)
    {
        $englishWords = [];
        $arabicWords = [];
        //
        $arr = explode(' ', $text);
        // Separate English and Arabic words
        foreach ($arr as $word) {
            if (preg_match('/^[A-Za-z0-9]*$/', $word)) {
                $englishWords[] = $word;
            } else {
                $arabicWords[] = $word;
            }
        };

        //reverse arabic words to get readable text
        $arabicWords = array_reverse($arabicWords);

        $result = [];
        $i = 0;
        $j = 0;

        //get the first word to dicide text is rtl or ltr

        foreach ($arr as $word) {
            if (preg_match('/^[A-Za-z0-9]*$/', $word)) {
                $result[] = $englishWords[$i++];
            } else {
                $result[] = $arabicWords[$j++];
            }
        }

        return implode(' ', $result);
    } */

    private function isEnglish($word)
    {
        return preg_match('/^[A-Za-z0-9]*$/', $word);
    }
    public static function DoesContainArabicText($text)
    { 
        return preg_match('/(?:\p{Arabic}+[\p{Arabic}\s]*\.*)+/u', $text);
    }
     
    protected static function processCharactrers(string $textToProcess = "")
    {
        // Split the text into individual words
        $words = explode(' ', $textToProcess);
        // Initialize the Arabic text processing module
        
        $arabic = new I18N_Arabic('Glyphs');
        // Loop through each word and apply the glyph transformation if necessary
        foreach ($words as $i => $word) {
            // Check if the current word contains any Arabic text
            if (preg_match('/(?:\p{Arabic}+[\p{Arabic}\s]*\.*)+/u', $word, $matches)) {
            
                // Extract the Arabic text from the word
                $text = $matches[0];
                // Apply the glyph transformation to the Arabic text
                $arWord = $arabic->utf8Glyphs($text, 10000, false, false);
                
                // Replace the original Arabic text with the transformed version
                $words[$i] = str_replace($text, $arWord, $word);
                
            }
        }
        // Rebuild the text with the transformed Arabic text
        return implode(' ', $words);
    }

    public static function processText(string $textToProcess = "") : string
    {  
         // Check if the text contains any Arabic text
         if (static::DoesContainArabicText($textToProcess)) {
           
            $textToProcess = static::processCharactrers($textToProcess);
            return static::fixTextWordsDirection($textToProcess);
        }
        return $textToProcess;
    }
}