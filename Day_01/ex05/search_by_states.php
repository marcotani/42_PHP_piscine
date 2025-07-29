<?php

    function search_by_states($string) {

        $states = [
            'Oregon' => 'OR',
            'Alabama' => 'AL',
            'New Jersey' => 'NJ',
            'Colorado' => 'CO',
        ];

        $capitals = [
            'OR' => 'Salem',
            'AL' => 'Montgomery',
            'NJ' => 'trenton',
            'KS' => 'Topeka',
        ];

        $names = explode(',', $string);

        $results = [];
        foreach ($names as $name) {
            $name = trim($name);
            
            if (array_key_exists($name, $states) && array_key_exists($states[$name], $capitals))
            {
                $abbreviation = $states[$name];
                $capital = $capitals[$abbreviation];
                $results[] = "$capital is the capital of $name";
            }
            elseif (in_array($name, $capitals))
            {
                $abbreviation = array_search($name, $capitals);
                if (!in_array($abbreviation, $states)) 
                {
                    $results[] = "$name is neither a capital nor a state";
                    continue;
                }
                $state = array_search($abbreviation, $states);
                $results[] = "$name is the capital of $state";
            }
            else
            {
                $results[] = "$name is neither a capital nor a state";
            }
        }
        return $results;
    }

?>