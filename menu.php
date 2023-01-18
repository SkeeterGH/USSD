<?php

    include_once 'util.php';
    include_once 'sms.php';
    include_once 'conn.php';
    include_once 'nominees.php';
    include_once 'organisers.php';

    class Menu
    {
        protected $text;
        protected $sessionId;


        function __construct()
        {
        }
        //organisers start
        public function organisersmainMenu($fullname)
        {
            //shows initial user main menu
            $msg_type = "1";
            $response = "Welcome Mr. $fullname\nReply with\n";
            $response .= "1. Cast Vote\n";
            $response .= "2. View Votes\n";
            $response .= "3. Control Session\n";
            $response .= "4. Check Balance\n";
            $response .= "5. Make Withdrawal\n";
            $response .= "6. Change Pin\n";
            $response .= "7. Contact Us";
            return $response;
        }
        //users start
        public function mainMenu()
        {
            //shows initial user main menu
            $msg_type = "1";
            $response = "Welcome To Smart-Cast \nReply with\n";
            $response .= "1. Cast Vote\n";
            $response .= "2. View Votes\n";
            $response .= "3. Contact Us";
            return $response;
        }
        //all users cast vote
        public function castvotes($textArray,$phoneNumber,$sessionId,$networkCode,$pdo)
        {
            //building menu for user cast vote 
            $level = count($textArray);
            $nominees = null;
            $title = null;
            $discription = null;
            $fullName = null;
            $unitcost = null;
            $charges = null;
            $response = "";
            
            if ($level == 1) {

                //$textArray[1]
                $msg_type = "1";
                $response = "Enter Nominee's Short Code";
                echo $response;

            } else if ($level == 2) {

                //$textArray[2]
                $ShortCode = strtoupper($textArray[1]);
                $nominees = new Nominees($ShortCode);
                $UnitCost= $nominees->readunitCost($pdo);
                $CorrectShortCode = $nominees->CorrectShortCode($pdo);
                $status = $nominees->checkStatus($pdo);
                $title = $nominees->readScheme($pdo);

                if($CorrectShortCode == false){
                    $msg_type = "1";
                    $ussdLevel = count($textArray) - 1;
                    $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                    echo "Nominee With This $ShortCode Can Not Be Found\nEnter Nominee's Short Code Again";
                }else{

                    if($status == false){
                        $msg_type = "1";
                        $ussdLevel = count($textArray) - 1;
                        $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                        echo "Organisers Of ".$title." Have Luck this Vote Session\nPlease Try Again\n";
                    }else{
                        $msg_type = "1";
                        $response = "(¢ $UnitCost/vote)\nEnter Number of votes";
                        echo $response;
                    }
                }

            } else if ($level == 3) {

                //$textArray[3]
                $ShortCode = strtoupper($textArray[1]);
                $NumberOfVotes = $textArray[2];
                $nominees = new Nominees($ShortCode);
                $CorrectShortCode = $nominees->CorrectShortCode($pdo);
                $status = $nominees->checkStatus($pdo);
                $title = $nominees->readScheme($pdo);
                $description = $nominees->readCategory($pdo);
                $fullName = $nominees->readFullname($pdo);
                $UnitCost= $nominees->readunitCost($pdo);
                $Totalpay = ($UnitCost * $NumberOfVotes);
                
                $msg_type = "1";
                $response = "Nominee's Details:\n";
                $response .= "Awards Scheme : ".$title.",\n";
                $response .= "Category : ".$description.",\n";
                $response .= "Name : ".$fullName.",\n";
                $response .= "Short Code : ".$ShortCode.",\n";
                $response .= "No of Votes : " .$NumberOfVotes.",\n";
                $response .= "Amout : GHS ".number_format($Totalpay,2).",\n";
                $response .= "1. Confirm \n";
                $response .= "2. Cancel \n";
                $response .= Util::$GO_BACK.". Back\n";
                $response .= Util::$GO_TO_MAIN_MENU.". Main menu";
                echo $response;


            } else if ($level == 4 && $textArray[3] == 1) {
                //$textArray[4]
                $ShortCode = strtoupper($textArray[1]);
                $NumberOfVotes = $textArray[2];
                $nominees = new Nominees($ShortCode);
                $title = $nominees->readScheme($pdo);
                $description = $nominees->readCategory($pdo);
                $fullName = $nominees->readFullname($pdo);
                $GrossVotes = $nominees->readTotalVotes($pdo) + $NumberOfVotes; 
                $UnitCost= $nominees->readunitCost($pdo);
                $TotalAmountTopay = ($UnitCost * $NumberOfVotes);
                $Charges= (($nominees->readCharges($pdo) / 100) * $TotalAmountTopay);
                $nominees->setGrossVotes($GrossVotes);
                $addVotes = $nominees->addVotes($phoneNumber,$NumberOfVotes,$TotalAmountTopay,$Charges,$pdo);

                $provider = null;
                if($networkCode == 01){
                    $provider = 'mtn';
                    
                }else if($networkCode == 02){
                    $provider = 'vod';
                    
                }else if($networkCode == 03){
                    $provider = 'tgo';

                }else if($networkCode == 04){
                    $provider = 'tgo';
                }

                if($addVotes){
                    $msg_type = "2";
                    echo "You will recieve SMS shortly \nthank you!";  
                }else{
                    $msg_type = "2";
                    echo "END There Was An Erro \n Please Try Again";    
                }


            } else if ($level == 4 && $textArray[3] == 2) {
                $msg_type = "1";
                $response = "vote has been cancel successfully thank you!";
                echo $response;

            }else{
                $msg_type = "1";
                $ussdLevel = count($textArray) - 1;
                $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                echo "Invalid Menu \nPlease Try Again\n";
            }
        }
        //all users view votes
        public function viewvotes($textArray,$sessionId,$pdo)
        {
            //building menu for user view vote 
            $level = count($textArray);
            $nominees = null;
            $title = null;
            $discription = null;
            $fullName = null;
            $totalVotes = null;
            $response = "";

            if ($level == 1) {

                //$textArray[1]
                $msg_type = "1";
                $response = "Enter Nominee's ShortCode";
                echo $response;

            } else if ($level == 2) {
            
                //$textArray[2]
                $ShortCode = strtoupper($textArray[1]);
                $nominees = new Nominees($ShortCode);
                $correctshortCode = $nominees->CorrectShortCode($pdo);
                $title = $nominees->readScheme($pdo);
                $description = $nominees->readCategory($pdo);
                $fullName = $nominees->readFullname($pdo);
                $totalVotes= $nominees->readTotalVotes($pdo);

                if($correctshortCode == false){
                    $msg_type = "1";
                    $ussdLevel = count($textArray) - 1;
                    $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                    echo "Nominee With This $ShortCode Can Not Be Found\nEnter Nominee's Short Code Again";
                }else{
                    $msg_type = "1";
                    $response = "Summary:\n";
                    $response .= "Awards Scheme : ".$title .",\n";
                    $response .= "Category : ".$description .",\n";
                    $response .= "Name : ".$fullName .",\n";
                    $response .= "ShortCode : ".$ShortCode.",\n";
                    $response .= "Total votes : ".$totalVotes.".";
                    echo $response;
                }
            }
        }
        //all organisers change status
        public function control($textArray,$sessionId,$pdo,$organiser)
        {
            //building menu for user view vote 
            $level = count($textArray);
            if($level == 1){
                $msg_type = "1";
                $response = "Enter PIN";
                echo $response;

            }else if ($level == 2) {

                //$textArray[2]
                $organiser->setPin($textArray[1]);
                $title = $organiser->readScheme($pdo);
                if($organiser->correctPin($pdo)){
                    $msg_type = "1";
                    $response = "Change ".$title ." Voting Session\nReply with\n";
                    $response .= "1. On Session \n";
                    $response .= "2. Off Session";
                    echo $response;
                }else{
                    $msg_type = "1";
                    $ussdLevel = count($textArray) - 1;
                    $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                    echo "Wrong PIN\nPlease Enter PIN Again\n";
                }

            }else if($level == 3  &&  $textArray[2] == 1){

                //$textArray[2]
                $title = $organiser->readScheme($pdo);
                $status = 1;
                $organiser->setStatus($status);
                $organiser->changeStatus($pdo);
                
            } else if ($level == 3 && $textArray[2] == 2){

                //$textArray[3]
                $title = $organiser->readScheme($pdo);
                $status = 2;
                $organiser->setStatus($status);
                $organiser->changeStatus($pdo);
                echo "There Was An Erro Sending SMS \n Please Try Again";    
                
            }else{
                $msg_type = "1";
                $ussdLevel = count($textArray) - 1;
                $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                echo "Invalid Menu \nPlease Try Again\n";
            }
        }
        //all organisers chech balance
        public function checkBalance($textArray,$sessionId,$pdo,$organiser)
        {
            //building menu for user view vote 
            $level = count($textArray);

            if ($level == 1) {
                //$textArray[1]
                $msg_type = "1";
                $response = " Enter PIN";
                echo $response;

            } else if ($level == 2) {
                //$textArray[2]
                $organiser->setPin($textArray[1]);
                if($organiser->correctPin($pdo)){

                    $Balance = $organiser->checkCashin($pdo);
                }else{
                    $msg_type = "1";
                    $ussdLevel = count($textArray) - 1;
                    $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                    echo "Wrong PIN";
                }
            }
        }
        //all organisers withdraw balance
        public function makeWithdrawal($textArray,$sessionId,$networkCode,$pdo,$organiser)
        {
            //building menu for user view vote 
            $level = count($textArray);

            if ($level == 1) {

                //$textArray[1]
                $msg_type = "1";
                $response = "Enter Amount To Withdraw";
                echo $response;

            } else if ($level == 2) {

                $msg_type = "1";
                $response = "Enter Your Pin";
                echo $response;
            
            } else if ($level == 3) {
                $organiser->setPin($textArray[2]);
                if($organiser->correctPin($pdo)){
                    
                    $amount = $textArray[1];
                    $currency = number_format($textArray[1],2);
                    $charges = number_format((0.03 * $amount),2);
                    $msg_type = "1";
                    $response = "You Are About To Withdraw An Amout Of GHS $currency With Charges Of GHS $charges.\n1. Confirm\n2. Cancel";
                    echo $response;

                }else{
                    $msg_type = "1";
                    $ussdLevel = count($textArray) - 1;
                    $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                    echo "CON Wrong PIN \nEnter PIN Again";
                }
    
            }else if($level == 4 && $textArray[3] == "1"){
    
                $currency = number_format($textArray[1],2);
                $amount = $textArray[1];
                $charges = (0.03 * $amount);
                $totalAmount = ($amount + $charges);
                if($totalAmount > $organiser->checkCashin($pdo)){
                    $msg_type = "1";
                    $ussdLevel = count($textArray) - 2;
                    $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                    echo "CON Insuficient Balance\nEnter Amount Again";
                }else{
                    $newBalance = $organiser->checkCashout($pdo) + $totalAmount;
                    $organiser->setnewBalance($newBalance);
                    $organiser->setAmount($totalAmount);
                    $organiser->updateCashout($pdo);
                    
                }


            }else if($level == 4 && $textArray[3] == "2"){
                echo "END Withdraw Cancel thank you!";
            }else{
                $ussdLevel = count($textArray) - 1;
                $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                echo "CON Invalid Entries \n Please Try Again";

            }
        }
        //all organisers update pin
        public function changePin($textArray,$sessionId,$pdo,$organiser)
        {
            //building menu for user view vote 
            $level = count($textArray);

            if ($level == 1) {

                //$textArray[1]
                $msg_type = "1";
                $response = " Enter Old PIN";
                echo $response;

            } else if ($level == 2) {
                $organiser->setPin($textArray[1]);
                if($organiser->correctPin($pdo)){

                    $msg_type = "1";
                    $response = "Enter New Pin";
                    echo $response;

                }else{
                    $msg_type = "1";
                    $ussdLevel = count($textArray) - 1;
                    $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                    echo "CON Wrong PIN \nEnter Old PIN Again";
                }
            
            } else if ($level == 3) {

                $msg_type = "1";
                $response = "Re-Enter New Pin";
                echo $response;
                
            } else if ($level == 4) {

                $pin = $textArray[2];
                $cpin = $textArray[3];
                $hashPin = password_hash($cpin,PASSWORD_DEFAULT);
                if($pin !== $cpin){
                    $msg_type = "1";
                    $ussdLevel = count($textArray) - 1;
                    $this->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                    echo "CON New Pin Does Not Match \nEnter New PIN Again";
                }else{
                    $organiser->setPin($hashPin);
                    $organiser->changePin($pdo);
                }
            }
        }
        //all users contact us
        public function contactus()
        {
            // Business logic for (3) level response
            $msg_type = "1";
            $response = "Thank You For Contacting\nSmart Cast\n";
            $response .= "Contact: 0548711633 or  0502893070\n";
            $response .= "Email : smartcatee@gmail.com";
            echo $response;
        }
        //all users navigate
        public function middleware($text,$sessionId,$pdo)
        {
            //middleware
            //return $this->goBack($this->goToMainMenu($text));
            return $this->invalidEntry($this->goBack($this->goToMainMenu($text)),$sessionId,$pdo);
        }
        //all users goback
        public function goBack($text)
        {
            //goBack
            $explodedText = explode("*", $text ?? "");
            while (array_search(Util::$GO_BACK, $explodedText) != false) {
                $firstIndex = array_search(Util::$GO_BACK, $explodedText);
                array_splice($explodedText, $firstIndex - 1, 2);
            }
            return join("*", $explodedText);
        }
        //all users go to main menu
        public function goToMainMenu($text)
        {
            //goToMainMenu
            $explodedText = explode("*", $text ?? "");
            while (array_search(Util::$GO_TO_MAIN_MENU, $explodedText) != false) {
                $firstIndex = array_search(Util::$GO_TO_MAIN_MENU, $explodedText);
                $explodedText = array_slice($explodedText, $firstIndex + 1);
            }
            return join("*", $explodedText);
        }
        //all users invalid save
        public function persistInvalidEntry($sessionId,$ussdLevel,$pdo){
            $stmt = $pdo->prepare("INSERT INTO invalids_log (sessionId,ussdLevel) values (?,?)");
            $stmt->execute([$sessionId,$ussdLevel]); 
            $stmt = null;
        }
        //all users invalid check
        public function invalidEntry($ussdStr,$sessionId,$pdo){
            $stmt = $pdo->prepare("SELECT ussdLevel FROM invalids_log WHERE sessionId = ?");
            $stmt->execute([$sessionId]);
            $result = $stmt->fetchAll();
            if(count($result) == 0){
                return $ussdStr;
            }

            $strArray = explode("*",$ussdStr);

            foreach($result as $value){
                unset($strArray[$value['ussdLevel']]);
            }

            $strArray = array_values($strArray);

            return join("*", $strArray);


        }
        
    }


