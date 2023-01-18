<?php
include_once 'menu.php';

// Read the data sent via POST from our AT API
$sessionId   = $_POST["session_id"];
$phoneNumber = $_POST["msisdn"];
$status = $_POST["msg_type"];
$networkCode = $_POST["nw_code"];
$serviceCode = $_POST["service_code"];
$text        = $_POST["ussd_body"];

$menu = new Menu();
$organiser = new Organiser($phoneNumber);
$db = new DBConnector();
$pdo = $db->connectToDB();
$text = $menu->middleware($text,$sessionId,$pdo);

if ($text == "" && $organiser->isOrganiser($pdo)) {
      //if text is empty and user is an organiser
      $msg_type = "1";
      echo $menu->organisersmainMenu($organiser->readFullname($pdo));
      
}else if($text == "" && !$organiser->isOrganiser($pdo)){
      //is text is empty and user is not an organiser
      $msg_type = "1";
      echo $msg_type . $menu->mainMenu();
      
}else if(!$organiser->isOrganiser($pdo)){
      //is text is not empty and user is not an organiser
      $textArray = explode("*", $text);
      switch ($textArray[0]) {
            case 1:
                  $menu->castvotes($textArray,$phoneNumber,$sessionId,$networkCode,$pdo);
                  break;
            case 2:
                  $menu->viewvotes($textArray,$sessionId,$pdo);
                  break;
            case 3:
                  $menu->contactus();
                  break;
            default:
                $ussdLevel = count($textArray) - 1;
                $menu->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                $msg_type = "1";
                echo "Inavalid menu\n" .$menu->mainMenu();
      }

}else{
      //is text is not empty and user is an organiser
      $textArray = explode("*", $text);
      switch ($textArray[0]) {
            case 1:
                  $menu->castvotes($textArray,$phoneNumber,$sessionId,$networkCode,$pdo);
                  break;
            case 2:
                  $menu->viewvotes($textArray,$sessionId,$pdo);
                  break;
            case 3:
                  $menu->control($textArray,$sessionId,$pdo,$organiser);
                  break;
            case 4:
                  $menu->checkBalance($textArray,$sessionId,$pdo,$organiser);
                  break;
            case 5:
                  $menu->makeWithdrawal($textArray,$sessionId,$networkCode,$pdo,$organiser);
                  break;
            case 6:
                  $menu->changePin($textArray,$sessionId,$pdo,$organiser);
                  break;
            case 7:
                  $menu->contactus();
                  break;
            default:
                $ussdLevel = count($textArray) - 1;
                $menu->persistInvalidEntry($sessionId,$ussdLevel,$pdo);
                $msg_type = "1";
                echo "Inavalid menu\n" .$menu->organisersmainMenu($organiser->readFullname($pdo));
      }
}


// Echo the response back to the API
header('Content-type: application/json');
echo $response;