<?php

/***************************************
 * www.program-o.com
 * PROGRAM O
 * Version: 2.6.5
 * FILE: spell_checker/spell_checker.php
 * AUTHOR: Elizabeth Perreau and Dave Morton
 * DATE: MAY 17TH 2014
 * DETAILS: this file contains the addon library to spell check into before its matched in the database
 ***************************************/

if (!defined('SPELLCHECK_PATH'))
{
    $this_folder = dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR;
    define('SPELLCHECK_PATH', $this_folder);
}

if (empty($_SESSION['spellcheck_common_words']))
{
    $_SESSION['spellcheck_common_words'] = file(SPELLCHECK_PATH . 'spellcheck_common_words.dat', FILE_IGNORE_NEW_LINES);
}

$spellcheck_common_words = $_SESSION['commonWords'];

/**
 * function run_spellcheck_say()
 * A function to run the spellchecking of the userinput
 *
 * @param  string $say - The user's input
 * @return string $say (spellchecked)
 */
function run_spell_checker_say($say)
{
    global $bot_id;
    runDebug(__FILE__, __FUNCTION__, __LINE__, 'Starting function and setting timestamp.', 2);

    $sentence = '';
    $wordArr = preg_split('/([^\pL](?<!\x27))/u', $say, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

    foreach ($wordArr as $index => $word)
    {
        $sentence .= spell_check($word, $bot_id);
    }
    $sentence = trim($sentence);
    // Now send this to the input_harness function
    input_harness($sentence);
    // Return the spell checked sentence
    return $sentence;
}

/**
 * function input_harness()
 * This function will scan the input via patterns to identify any conversation info (who,what,when,where)
 * these details will be recorded on the Database for future refference.
 *
 * @param string  $userinput - The user's input spellchecked
 * @internal param $ [type] [variable used]
 */
function input_harness($userinput)
{

     
     
}

/**
 * function sentence_catergory()
 * This function will scan a given sentence and identify words or phrases in the sentence
 * and return the type of the match found
 * 
 * @param string $sentence
 * @return string $sentencetype
 */
 
 sentence_catergory('this is a bee talking to you');
 
 function sentence_catergory($sentence){
   // Init Vars
      // Set Condition Anaylsis Vars
        $Action=0; $Logical=0; $Normal=0; $Question=0; $Statement=0;
      // Convert the Sentence into lowercase
      $sentence = strtolower($sentence);
      // Load the word arrays - located in the sub-folder 'datasets' in the spellcheck dir          
         // Init Vars
           $files = glob(".\datasets\*.dat");
           $path = '.\datasets'.DIRECTORY_SEPARATOR;
         // Load all the dat files found in the $path
           foreach ($files as &$dataset) {
              $datasetname = str_replace('.dat', '',str_replace($path, '', $dataset));             
              $$datasetname = file ($dataset, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
              // echo "Dataset $datasetname loaded<br>";
           }
      // Explode the User Input Sentence into words
         $userwords = explode(' ',$sentence);
      // Is the word "IF" in the sentence?
         if (in_array('If', $spellcheck_common_words)){
            // Travel Word by word and perform comparision
            foreach ($userwords as &$searchword){
              // Search for action words  (perform task)
              if (in_array($searchword, $wrdlst_actionwords)){
                 $Action+10; $Logical+0; $Normal+0; $Question=0; $Statement=0;
              }
              // Search for logical words (question words)
              if (in_array($searchword, $wrdlst_logicalwords)){
                 $Action+0; $Logical+10; $Normal+0; $Question=0; $Statement=0;
              }
              // Search for famous people names
              if (in_array($searchword, $wrdlst_famouspeople)){
                 $Action+0; $Logical+10; $Normal+10; $Question=0; $Statement=0;
              }
              // Search for boysnames or girlsnames
              if (in_array($searchword, $wrdlst_boysnames) or in_array($searchword, $wrdlst_girlsnames)){
              
              }
            }
         }
 
         
           
         
 
 
 } 

/**
 * function spell_check()
 * Checks the given word against a list of commonly misspelled words, replacing it with a correction, if necessary.
 *
 * @param $word
 * @param $bot_id
 * @internal param $ [type] [variable used]
 * @return mixed|string [type] [return value]
 */
function spell_check($word, $bot_id)
{
    runDebug(__FILE__, __FUNCTION__, __LINE__, "Preforming a spel chek on $word.", 2);
    global $dbConn, $dbn, $spellcheck_common_words;

    if (strstr($word, "'"))
    {
        $word = str_replace("'", ' ', $word);
    }

    $test_word = _strtolower($word);

    if (!isset($_SESSION['spellcheck']))
    {
        load_spelling_list();
    }

    if (in_array($test_word, $spellcheck_common_words))
    {
        //runDebug(__FILE__, __FUNCTION__, __LINE__, "The word '$word' is a common word. Returning without checking.", 4);
        return $word;
    }

    if (in_array($test_word, array_keys($_SESSION['spellcheck'])))
    {
        $corrected_word = $_SESSION['spellcheck'][$test_word];
        runDebug(__FILE__, __FUNCTION__, __LINE__, "Misspelling found! Replaced $word with $corrected_word.", 4);
    }
    else {
        //runDebug(__FILE__, __FUNCTION__, __LINE__,'Spelling check passed.', 4);
        $corrected_word = $word;
    }

    switch ($word)
    {
        case _strtolower($word):
            $corrected_word = _strtolower($corrected_word);
            break;
        case _strtoupper($word):
            $corrected_word = _strtoupper($corrected_word);
            break;
        case _title_case($word):
            $corrected_word = _title_case($corrected_word);
            break;
        default:
    }

    return $corrected_word;
}

/**
 * function load_spelling_list
 * Gets all missspelled words and their corrections from the DB, loading them into a session variable.
 *
 * @internal param $ (none)
 * @return void (void)
 */
function load_spelling_list()
{
    runDebug(__FILE__, __FUNCTION__, __LINE__, 'Loading the spellcheck list from the DB.', 2);
    global $dbConn, $dbn;

    $_SESSION['spellcheck'] = array();
    $sql = "SELECT `missspelling`, `correction` FROM `$dbn`.`spellcheck`;";
    $result = db_fetchAll($sql, null, __FILE__, __FUNCTION__, __LINE__);
    $num_rows = count($result);

    if ($num_rows > 0)
    {
        foreach ($result as $row)
        {
            $missspelling = _strtolower($row['missspelling']);
            $correction = $row['correction'];
            $_SESSION['spellcheck'][$missspelling] = $correction;
        }
    }
}
