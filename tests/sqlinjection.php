<?php
/* 
 * Test file for comparing the security performance of various MySQL escaping/encoding methods.
 * Author: ampedup [amphetamine@tormail.org]
 * Last Modifed: 18 Nov 2012
 */
 
//Generate the SQL "string from hell" - all 24-bit numbers one after another.
function generate_string_from_hell()
{
    //TODO: Implement function
    return "";
}

function test_addslashes()
{
    //Get the string from hell
    $data = generate_string_from_hell();
    
    //Build the query
    $query = "SELECT '".addslashes($data)."'";
    
    //TODO: Write the rest of the code to run the query and verify the result
}
 

?>