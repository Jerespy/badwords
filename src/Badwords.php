<?php namespace Buchin\Badwords;

/**
 * Updated by: Osah Prince
 *
 */
class Badwords
{
    const NEGATE = ["aint", "arent", "cannot", "cant", "couldnt", "darent", "didnt", "doesnt",
        "ain't", "aren't", "can't", "couldn't", "daren't", "didn't", "doesn't",
        "dont", "hadnt", "hasnt", "havent", "isnt", "mightnt", "mustnt", "neither",
        "don't", "hadn't", "hasn't", "haven't", "isn't", "mightn't", "mustn't",
        "neednt", "needn't", "never", "no", "none", "nope", "nor", "not", "nothing", "nowhere",
        "oughtnt", "shant", "shouldnt", "uhuh", "wasnt", "werent",
        "oughtn't", "shan't", "shouldn't", "uh-uh", "wasn't", "weren't",
        "without", "wont", "wouldnt", "won't", "wouldn't", "rarely", "seldom", "despite"];

    const ARTICLE = ["a", "an", "the"];


    public static function isDirty($string)
    {
        $words = explode(" ", $string);

        $bad_words = self::getBadWords();
        $bad_phrases = self::getBadPhrases();

        foreach ($bad_phrases as $bad_phrase) {
            if (strpos($string, $bad_phrase) !== false) {
                return true;
            }
        }

        foreach ($words as $word) {
            if (in_array(strtolower($word), $bad_words)) {
                return true;
            }
        }

        return 0;
    }

    public static function strip($string)
    {
        $words = explode(" ", $string);

        $bad_words = self::getBadWords();

        $new_words = [];

        foreach ($words as $word) {
            if (in_array(strtolower($word), $bad_words)) {
                $new_words[] = str_ireplace(
                    ["a", "i", "u", "e", "o", "4", "1", "3", "0"],
                    "*",
                    $word
                );
            } else {
                $new_words[] = $word;
            }
        }

        return implode(" ", $new_words);
    }

    public static function getBadWords()
    {
        return array_map(function ($item) {
            return strtolower(trim($item));
        }, explode("\n", file_get_contents(__DIR__ . "/badwords.txt")));
    }

    // Return a single bad word found in the sentence
    public static function getBadword($sentence)
    {
        $isDirty = self::isDirty($sentence);
        
        // List of badwords
        $getBadWords = self::getBadWords();
        if($isDirty == 1)
        {
        // Offensive word is found
        // return the word 
            $sentenceArray = explode(" ", strtolower($sentence));
            for($i = 0; $i < count($sentenceArray); $i++)
                    for ($k=0; $k < count($getBadWords); $k++) { 
                    
                    {
                            if ($sentenceArray[$i] == $getBadWords[$k]) {
                                return $getBadWords[$k];
                            }
                    }
            }


        }
        return -1;     
    }

    public static function isDirtyNegate($sentence)
    {
        // Output
        // -1 means NOT FOUND
        // 0 means found but no negator (const NEGATE) found before the offensive word
        // 1 means found with a negator (const NEGATE) before the offensive word
        
        // Bad word in the sentence 
        $sentence = preg_replace('/(\s\s+|\t|\n)/', ' ', $sentence);
        $getBadWord = Badwords::getBadword(strtolower(trim(($sentence))));
        if($getBadWord == -1)
        {
            return -1;
        }
        
        // Checking if the word b4 the offensive word is negator
        $findme = $getBadWord;
        $pos = strpos($sentence, $findme);
        $newSentence = explode(" ",trim(substr(strtolower($sentence), 0, $pos)));
        // Getting the last word in the array.
        $last_word = end($newSentence);

        // check if it's an article b4 the fowl word
        if(Badwords::checkWord($last_word,'ARTICLE') == 1)
        {
            //Checking the word after the ARTICLE
            array_pop($newSentence);
            $newLastWord = end($newSentence);
            // echo "NEGATE ($newLastWord) == ". Badwords::checkWord($newLastWord,'NEGATE');
            if (Badwords::checkWord($newLastWord,'NEGATE') == 1) {
                // Found with a negator
                return 1;
            }
            else
            {
                // Found without a negator
                return 0;
            }
        }
        else if(Badwords::checkWord($last_word,'NEGATE') == 1)
        {
            return 1;
        }
        return 0;
    }

    public static function checkWord($word,$const)
    {
        $constant = ($const == "NEGATE") ? self::NEGATE : self::ARTICLE;
        // print_r($constant);
        foreach($constant as $value)
        {
            if ($value == $word) {
                return 1;
            }
        }
        return 0;
    }

    public static function getBadPhrases()
    {
        $bad_words = self::getBadWords();

        $bad_phrases = [];

        foreach ($bad_words as $bad_word) {
            $words = explode(" ", $bad_word);

            if (count($words) > 1) {
                $bad_phrases[] = $bad_word;
            }
        }

        return $bad_phrases;
    }
}
