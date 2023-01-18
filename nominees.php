<?php
      include_once 'sms.php';


      class  Nominees{

            protected $ShortCode;
            protected $GrossVotes;
            
            function __construct($ShortCode){
                  $this->ShortCode = $ShortCode;
            }
            
            //all users
            public function getShortCode(){
                  return $this->ShortCode;
      
            }

            //all users gross set
            public function setGrossVotes($GrossVotes){
                  $this->GrossVotes = $GrossVotes;
            
            }

            //all users gross get
            public function getGrossVotes(){
                  return $this->GrossVotes;
      
            }

            //all users add votes
            public function addVotes($phoneNumber,$NumberOfVotes,$TotalAmountTopay,$Charges,$pdo){

                  $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,FALSE);

                  try{
                        $pdo->beginTransaction();
                        $stmtU = $pdo->prepare("UPDATE nominees SET totalvotes = ? WHERE shortcode = ?");
                        $stmtT = $pdo->prepare("INSERT INTO votes_log (shortcode, phonenumber, votes, amount, charges) VALUES (?,?,?,?,?)");

                        $stmtT->execute([$this->getShortCode(),$phoneNumber,$NumberOfVotes,$TotalAmountTopay,$Charges]);
                        $stmtU->execute([$this->getGrossVotes(),$this->getShortCode()]);

                        $pdo->commit();
                        return true;
      
                  }catch(Exception $e){
      
                        $pdo->rollBack();
                        return "an error was encountered";
                  }
    
            }
            
            //all users shortcode
            public function CorrectShortCode($pdo){
                  //read correct shortcode
                  $stmt = $pdo->prepare("SELECT shortcode FROM nominees WHERE shortcode = ?");
                  $stmt->execute([$this->getShortCode()]);
                  $row = $stmt->fetch(); 
                  if($row == null){
                        return false;
                  }

                  if($row['shortcode'] == $this->getShortCode()){
                        return true;
                  }
                  
                  return false;

            }

            //all users check status
            public function checkStatus($pdo){
                  //read correct checkStatus
                  $stmt = $pdo->prepare("SELECT status FROM nominees INNER JOIN scheme ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE shortcode = ?");
                  $stmt->execute([$this->getShortCode()]);
                  $row = $stmt->fetch();

                  if($row == null){
                        
                        return false;
                  }

                  if($row['status'] == "1"){

                        return true;
                  }
                  
                  return false;

            }
            
            //all users read fullname
            public function readFullname($pdo){
                  //read fullname
                  $stmt = $pdo->prepare("SELECT fullname FROM nominees WHERE shortcode = ?");
                  $stmt->execute([$this->getShortCode()]);
                  $row = $stmt->fetch();
                  return $row['fullname'] ?? null;
            }

            //all users read scheme
            public function readScheme($pdo){
                  //read scheme
                  $stmt = $pdo->prepare("SELECT title FROM nominees INNER JOIN scheme ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE shortcode = ?");
                  $stmt->execute([$this->getShortCode()]);
                  $row = $stmt->fetch();
                  return $row['title'] ?? null;

            }

            //all users category
            public function readCategory($pdo){
                  //read category
                  $stmt = $pdo->prepare("SELECT description FROM nominees INNER JOIN scheme ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE shortcode = ?");
                  $stmt->execute([$this->getShortCode()]);
                  $row = $stmt->fetch();
                  return $row['description'] ?? null;
                  
            }

            //all users read total votes
            public function readTotalVotes($pdo){
                  //read totalVotes
                  $stmt = $pdo->prepare("SELECT totalvotes FROM nominees INNER JOIN scheme ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE shortcode = ?");
                  $stmt->execute([$this->getShortCode()]);
                  $row = $stmt->fetch();
                  return $row['totalvotes'] ?? null;
                  
            }

            //all users read charges
            public function readCharges($pdo){
                  //read charges
                  $stmt = $pdo->prepare("SELECT charges FROM nominees INNER JOIN scheme ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE shortcode = ?");
                  $stmt->execute([$this->getShortCode()]);
                  $row = $stmt->fetch();
                  return $row['charges'] ?? null;
                  
            }

            //all users read unit cost
            public function readunitCost($pdo){
                  //read unitCost
                  $stmt = $pdo->prepare("SELECT unitcost FROM nominees INNER JOIN scheme ON nominees.sch_id=scheme.id INNER JOIN category ON nominees.cat_id=category.id WHERE shortcode = ?");
                  $stmt->execute([$this->getShortCode()]);
                  $row = $stmt->fetch();
                  return $row['unitcost'] ?? null;
                  
            }


      }



