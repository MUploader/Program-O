<?php
/*
    *--- Before Condition Anaylsis ---*
    
    CA1. Perform SpellCheck
    CA2. Perform InputAnalysis
         2.1 "If" * ~actionword *  >> This must be an action task
         2.2 "If" * what is *      >> This must be a logical problem
         2.3 '?' or '.' or '!'   >> This is a question, this is a sentence, this is a statement
         2.4 Find 'boynames' or 'girlnames'  >> This can't be an action
         2.5 Map and Record $lastreffered-person,place,thing,action... (Must be cleared within 5 volleys)
   
    CA3. Read CurrentDateTime, UserShedule, DeviceType, CurrentLocation --> Refer Condition Anaylsis Possibities Map
          * If CurrentDateTime=LateNight && UserShedule=Busy OR UserShedule=Normal
                    respond with ShortReplies. Only on User Request or Alarm Activity
          * If CurrentDateTime=LateNight && UserShedule=Holiday
                    respond with Playful Mode. On time-based activity or User Request
          * If Current...
                                                 
    CA4. Make Report --> 
                If CA2.1     >>  ActionInput 25, LogicalInput 25, NormalConversation 25, NormalQuestion 0
                If CA2.3='.' >>  ActionInput +10, LogicalInput -5, NormalConversation +10, NormalQuestion +10
                If CA2.3='?' >>  ActionInput -10, LogicalInput +10, NormalConversation -10, NormalQuestion +10
                If CA2.3='!' >>  ActionInput -10, LogicalInput -15, NormalConversation -10, NormalQuestion -10
                If CA2.4='Y' >>  ActionInput -10, LogicalInput +10, NormalConversation +25, NormalQuestion -10
                If CA2.4='N' >>  ActionInput +10, LogicalInput +10, NormalConversation +10, NormalQuestion +10
    *--- Condition Anaylsis Complete ---*
    *--- Begining Sentence Processing ---*
        
    SP1. If CA-Report the greater the value selects the side
    
*/
            ConditionAnaylsis('abc');

//  function oldConditionAnaylsis($inputtext){
    // Connect to DB 'ChatBot.Vocabulary' and read the ActionWords,GirlsNames,BoysNames into seperate arrays
    // Connect to DB 'ChatBot.ClientDetails' and read the $LastRefferedFirstPerson,$LastRefferedSecondPerson,$LastRefferedPlace,$LastRefferedThing,$LastRefferedAction into seperate vars
    // Connect to DB 'ChatBot.ClientDetails' and read the $CurrentDateTime, $UserShedule, $UserMood, $DeviceType, $CurrentLocation into seperate vars

    // Here we need to do the basic processing and identification of the user input.
    // 0. Bring In the needed Documents {Include Files}
       include('external_functions/index.php');      //Imports all the external function php files.
       include('response.php');                      //Contains some response functions which contain user chat returns usually of external functions
    
    // 1. Read the Client's Current Settings
       // We need to know the Client's Mood. Mood Reason. Previous Mood. CurrentLocation. Enivoriment(Alone|Family|Friends|Unknown).
       


  function ConditionAnaylsis($sentence){
     // Init Vars
      $returnpossible = 0;
      $ActionInput = 0; $LogicalInput = 0; $NormalConversation = 0; $NormalQuestion = 0;
     // Fetch Lists
      $GestureWords=SelectSql("SELECT * FROM `*dbdn`.`dic_wrds` WHERE `type`='gesturewords';",'gesturewords');
      $ActionWords=SelectSql("SELECT * FROM `*dbdn`.`dic_wrds` WHERE `type`='actionwords';",'actionwords');   
      $GirlsNames=SelectSql("SELECT * FROM `*dbdn`.`dic_names` WHERE `type`='girlsnames';",'girlsnames');
      $BoysNames=SelectSql("SELECT * FROM `*dbdn`.`dic_names` WHERE `type`='boysnames';",'boysnames');
      $FamousNames=SelectSql("SELECT * FROM `*dbdn`.`dic_names` WHERE `type`='famousnames';",'famousnames');

    // Is it a Question or a Statement
        
        if (strpos($sentence, '.') !== false) {  // check for ? mark - mark as Q
            $ActionInput=$ActionInput+10; $LogicalInput=$LogicalInput-5; $NormalConversation=$NormalConversation+10; $NormalQuestion=$NormalQuestion+10;
        } else if (strpos($sentence, '?') !== false) {   // check for ? mark - mark as Q
            $ActionInput=$ActionInput-10; $LogicalInput=$LogicalInput+25; $NormalConversation=$NormalConversation+25; $NormalQuestion=$NormalQuestion+0;
        } else if (strpos($sentence, '!') !== false) {  // check for ! mark - mark as S
            $ActionInput=$ActionInput+0; $LogicalInput=$LogicalInput+0; $NormalConversation=$NormalConversation+0; $NormalQuestion=$NormalQuestion+0;
        } else {
            $ActionInput=$ActionInput+0; $LogicalInput=$LogicalInput+0; $NormalConversation=$NormalConversation+0; $NormalQuestion=$NormalQuestion+0;
        }
        
    // Does the input contain actionwords    
        if (array_key_exists('first', $search_array)) {  // if not set ($chk) check for Q words -> T mark as Q else S
        
        } else {
        
        }
        
    // Does it contain command words
        // check for action_words ->
          //  T check if action word task is being performed
          //  T mark return as possible
          //  return that is alreadying being done *clientsalute*
    // Does it contain gesture words
        // check for gesture words -> T check if gestureword was set
          // if gesture word has not been set return msg to user
          // who | what are you talking about / reffering to etc
          // mark return as possible
    // Does it contain pop_quiz words
        //  check for pop_quiz words -> 
          // T check for famousnames -> 
          // T mark as Quiz
    
  }

  sentence_catergory('this is a bee talking to you');
 
 function sentence_catergory($sentence){
   // Init Vars
      $this_folder = dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR;
      define('SPELLCHECK_PATH', $this_folder);
      // Load the word arrays - located in the sub-folder 'datasets' in the spellcheck dir
                                                                                                    */
         $files = glob(".\datasets\*.dat");
         var_dump($files);
         foreach ($files as &$datasetnames) {
            $dataset[$datasetnames] = file ($datasetnames, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
         }
         var_dump($dataset);

?>
