<?php
/**
 * A function to change persian or arabic text from its logical condition to visual
 * NetParadis
 * @param        string    Main text you want to change it
 * @param        boolean    Apply e'raab characters or not? default is true
 * @param        boolean    Which encoding? default it "utf8"
 * @param        boolean    Do you want to change special characters like "allah" or "lam+alef" or "lam+hamza", default is true
 */
function persian_log2vis(&$str)
{
    include_once('bidi.php');
    
    $bidi = new bidi();
    
    $text = explode("\n", $str);
    
    $str = array();
    
    foreach($text as $line){
        $chars = $bidi->utf8Bidi($bidi->UTF8StringToArray($line), 'AL');
        $line = '';
        foreach($chars as $char){
            $line .= $bidi->unichr($char);
        }
        
        $str[] = $line;
    }
    
    $str = implode("\n", $str);
}

