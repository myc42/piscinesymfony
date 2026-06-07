<?php

$states = [
    'Oregon' => 'OR',
    'Alabama' => 'AL',
    'New Jersey' => 'NJ',
    'Colorado' => 'CO',
];

$capitals = [
    'OR' => 'Salem',
    'AL' => 'Montgomery',
    'NJ' => 'Trenton',
    'KS' => 'Topeka',
];

function search_by_states($fullstates)
{
    global $states, $capitals;

    $items = explode(',', $fullstates);

    foreach ($items as $item)
    {
        $item = trim($item);

    
        if (isset($states[$item]))
        {
            $abbr = $states[$item];
            echo $capitals[$abbr] . " is the capital of " . $item . ".\n";
        }
        else
        {
            $found = false;

        
            foreach ($capitals as $abbr => $capital)
            {
                if (strcasecmp($capital, $item) == 0)
                {
                    $state = array_search($abbr, $states);

                    if ($state !== false)
                    {
                        echo $capital . " is the capital of " . $state . ".\n";
                    }
                    else
                    {
                        echo $item . " is neither a capital nor a state.\n";
                    }

                    $found = true;
                    break;
                }
            }

            if (!$found)
            {
                echo $item . " is neither a capital nor a state.\n";
            }
        }
    }
}

?>