<?php

namespace Checklists;

class Parser
{

    /* 
        Assuming example input string to parse:
        "
        [] Unchecked item 1
        [] Unchecked item 2
        [x] Checked item
        "
    */
    public function parse($toParse): string
    {
        $pattern = "/\[(x)*\]\s.*(\r\n|\r|\n)*/m";
        // check if the given string contains empty square brackers or crossed square brackets list item/s
        if (preg_match($pattern, $toParse) || preg_match($pattern, $toParse)) {
            $allItems = explode(PHP_EOL, $toParse);
            $parsedString = "<ul>";
            $unCheckStr = "[]";
            $checkStr = "[x]";
            foreach ($allItems as $item) {
                // if it is an unchecked list
                if (preg_match("/\[\]/s", $item)) {
                    $startsAt = strpos($item, $unCheckStr) + strlen($unCheckStr);
                    $itemText = trim(substr($item, $startsAt)); // get start and end of the list text string to put it inside the html tags
                    $parsedString .= "<li>{$itemText}</li>";
                }
                // if it is checked list
                if (preg_match("/\[x\]/s", $item)) {
                    $startsAt = strpos($item, $checkStr) + strlen($checkStr);
                    $itemText = trim(substr($item, $startsAt)); // get start and end of the list text string to put it inside the html tags
                    $parsedString .= "<li><s>{$itemText}</s></li>";
                }
            }
            $parsedString .= "</ul>";
            // print($parsedString);
            return $parsedString;
        }
        return $toParse;
    }
}
