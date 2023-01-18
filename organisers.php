<?php
      include_once 'sms.php';


      class  Organiser{

            protected $phoneNumber;
            protected $Amount;
            protected $newBalance;
            protected $Pin;
      
            function __construct($phoneNumber){
                  $this->phoneNumber = $phoneNumber;
            }

            public function getphoneNumber(){
                  return $this->phoneNumber;
      
            }

            public function setAmount($Amount){
                  $this->Amount = $Amount;
            
            }

            public function getAmount(){
                  return $this->Amount;
      
            }

            public function setPin($Pin){
                  $this->Pin = $Pin;
            
            }

            public function getPin(){
                  return $this->Pin;
      
            }
            
            public function setStatus($Status){
                  $this->Status = $Status;
            
            }

            public function getStatus(){
                  return $this->Status;
      
            }

            public function setnewBalance($newBalance){
                  $this->newBalance = $newBalance;
            
            }

            public function getnewBalance(){
                  return $this->newBalance;
      
            }
            
            public function isOrganiser($pdo){

                  //read correct shortcode
                  $stmt = $pdo->prepare("SELECT phonenumber FROM organisers WHERE phonenumber = ?");
                  $stmt->execute([$this->getphoneNumber()]);
                  if(count($stmt->fetchAll()) > 0){
                        return true; 
                  }else{
                        return false;
                  }

            }
            
            public function readFullname($pdo){

                  //read fullname
                  $sql = "SELECT organisers.fullname FROM organisers INNER JOIN scheme ON organisers.id=scheme.org_id INNER JOIN nominees ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE organisers.phonenumber = ?";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([$this->getphoneNumber()]);
                  $row = $stmt->fetch();
                  return $row['fullname'] ?? null;
            }

            public function readScheme($pdo){

                  //read scheme
                  $sql = "SELECT title FROM organisers INNER JOIN scheme ON organisers.id=scheme.org_id INNER JOIN nominees ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE organisers.phonenumber = ?";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([$this->getphoneNumber()]);
                  $row = $stmt->fetch();
                  return $row['title'] ?? null;

            }

            public function readId($pdo){

                  //read scheme
                  $sql = "SELECT organisers.id FROM organisers INNER JOIN scheme ON organisers.id=scheme.org_id INNER JOIN nominees ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id GROUP BY organisers.phonenumber";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([$this->getphoneNumber()]);
                  $row = $stmt->fetch();
                  return $row['id'] ?? null;

            }
            
            public function senderName($pdo){

                  //read scheme
                  $sql = "SELECT organisers.id FROM organisers INNER JOIN scheme ON organisers.id=scheme.org_id INNER JOIN nominees ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id GROUP BY organisers.phonenumber";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([$this->getphoneNumber()]);
                  $row = $stmt->fetch();
                  return $row['sendername'] ?? "Smart Cast";

            }
                                        
            public function checkCashin($pdo){

                  //check balance
                  $sql = "SELECT ((((100 - charges) / 100) *(unitcost * totalvotes)) - cashout) as cashin FROM organisers INNER JOIN scheme ON organisers.id=scheme.org_id INNER JOIN nominees ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE organisers.phonenumber = ?";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([$this->getphoneNumber()]);
                  $row = $stmt->fetch();
                  return $row['cashin'] ?? null;
                  
            }

            public function checkCashout($pdo){

                  //check balance
                  $sql = "SELECT cashout FROM organisers WHERE phonenumber = ?";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([$this->getphoneNumber()]);
                  $row = $stmt->fetch();
                  return $row['cashout'] ?? null;
                  
            }

            public function checkStatus($pdo){

                  //read correct checkStatus
                  $sql = "SELECT scheme.status FROM organisers INNER JOIN scheme ON organisers.id=scheme.org_id INNER JOIN nominees ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE organisers.phonenumber = ?";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute([$this->getphoneNumber()]);
                  $row = $stmt->fetch();
                  return $row['status'] ?? null; 

            }

            public function changeStatus($pdo){

                  //read correct checkStatus
                  $stmt = $pdo->prepare("UPDATE organisers INNER JOIN scheme ON organisers.id=scheme.org_id SET status = ? WHERE phonenumber = ?");
                  $stmt->execute([$this->getStatus(),$this->getphoneNumber()]);
                  $row = $stmt->fetch();
            }
            
            public function correctPin($pdo){

                  //read correct checkStatus
                  $stmt = $pdo->prepare("SELECT pin FROM organisers WHERE phonenumber = ?");
                  $stmt->execute([$this->getphoneNumber()]);
                  $row = $stmt->fetch();

                  if($row == null){
                        
                        return false;
                  }

                  if(password_verify($this->getPin(),$row['pin'])){

                        return true;
                  }
                  
                  return false;

            }

            public function changePin($pdo){

                  //read correct checkStatus
                  $stmt = $pdo->prepare("UPDATE organisers SET pin = ? WHERE phonenumber = ?");
                  $stmt->execute([$this->getpin(),$this->getphoneNumber()]);
                  $row = $stmt->fetch();
            }

            public function updateCashout($pdo){

                  //read correct checkStatus
                  $stmt = $pdo->prepare("UPDATE organisers SET cashout = ? WHERE phonenumber = ?");
                  $stmt->execute([$this->getnewBalance(),$this->getphoneNumber()]);
                  $row = $stmt->fetch();

                  
            }

            public function updateTransactions($pdo){

                  //read correct checkStatus
                  $stmt = $pdo->prepare("INSERT INTO transactions (phonenumber,amount,status) VALUES(?,?,?) WHERE phonenumber = ?");
                  $stmt->execute([$this->getphoneNumber(),$this->getAmount(),1,getphoneNumber()]);
                  $row = $stmt->fetch();
            }

      }



