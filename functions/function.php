<?php
//live
date_default_timezone_set('Asia/Manila');
ini_set('display_errors', 1);
include_once 'helpers.php';

use assets\vendor\PHPMailer\PHPMailer\PHPMailer;

//notes

try {
  $mysqli = new PDO('mysql:host=127.0.0.1;dbname=pds_db', 'root', '');
  // $mysqli = new PDO('mysql:host=10.0.0.231;dbname=pds_test', 'admin', 'datos@bar2021');
  // See the "errors" folder for details...
  $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo $e->getMessage();
}

session_start();

function phpAlert($msg)
{
  echo '<script type="text/javascript">alert("' . $msg . '")</script>';
  return true;
}

function userlogin($mysqli, $username, $password, $access)
{

  //SQL Statement
  $stmt = $mysqli->prepare("SELECT * FROM credentials WHERE username = :w AND password = :x AND disabled = :y");

  //Execute the statement
  $stmt->execute(array(
    ":w" => $username,
    ":x" => $password,
    ":y" => $access
  ));

  //Fetch the result
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  //Compare the result with user input
  if ($row) {
    if ($row['username'] == $username && $row['password'] == $password && $row['disabled'] == $access) {
      $_SESSION['userID'] = $row['userID'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['section'] = $row['section'];
      //return the result if input is correct
      // echo '<script type="text/javascript">alert("WALA NAMANG SESSION POTA")</script>';
      return $row;
    } else {
  
      //return false if input not correct
      return false;
    }
  }
}

function recordActivity($mysqli, $userID)
{
  //this will be updated because right now this s only for login
  $sql = "INSERT INTO activitylogs (userID,Login)
  VALUES (:userid,NOW())";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userid' => $userID,
    )
  );
}

function downloadAttachment($mysqli, $id){
  $sql = "SELECT * FROM attachments WHERE id=:id";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":id" => $id['id']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


function get_accomplish_user($mysqli)
{
  $sql = "SELECT credentials.employeeID,
  personal_info.lastName,
  personal_info.firstName,
  personal_info.middleName,
  personal_info.birthdate,
  personal_info.gender,
  personal_info.civilStatus,
  personal_info.citizenship,
  parents.FatherLastName,
  parents.FatherFirstName,
  parents.MotherMaidenLastName,
  parents.MotherFirstName,
  education.collegeSchoolName,
  other.num35_a,
  other.num35_b,
  other.num36,
  other.num37,
  other.num38_a,
  other.num38_b,
  issuedid.IDName,
  issuedid.IDNo,
  division.division,
  section.section
  FROM credentials
  INNER JOIN division ON division.divisionID = credentials.division
  INNER JOIN section ON section.sectionID = credentials.section
  INNER JOIN personal_info ON personal_info.userID = credentials.userID 
  INNER JOIN parents ON parents.userID = credentials.userID 
  INNER JOIN education ON education.userID = credentials.userID 
  INNER JOIN other ON other.userID = credentials.userID 
  INNER JOIN issuedid ON issuedid.userID = credentials.userID ";

  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function get_incomplete_user($mysqli)
{ //incomplete pds function
  $sql = "SELECT credentials.employeeID,
  credentials.username,
  personal_info.lastName,
  personal_info.firstName,
  personal_info.middleName,
  personal_info.birthdate,
  personal_info.gender,
  personal_info.civilStatus,
  personal_info.citizenship,
  parents.FatherLastName,
  parents.FatherFirstName,
  parents.MotherMaidenLastName,
  parents.MotherFirstName,
  education.collegeSchoolName,
  other.num35_a,
  other.num35_b,
  other.num36,
  other.num37,
  other.num38_a,
  other.num38_b,
  -- reference.referenceID,
  issuedid.IDName,
  issuedid.IDNo,
  division.division,
  section.section
  FROM credentials
  LEFT JOIN division ON division.divisionID = credentials.division
  LEFT JOIN section ON section.sectionID = credentials.section
  LEFT OUTER JOIN personal_info ON personal_info.userID = credentials.userID AND credentials.userID = 0
  LEFT OUTER JOIN parents ON parents.userID = credentials.userID AND credentials.userID = 0
  LEFT OUTER JOIN education ON education.userID = credentials.userID AND credentials.userID = 0
  LEFT OUTER JOIN other ON other.userID = credentials.userID AND credentials.userID = 0
  -- LEFT OUTER JOIN reference ON reference.userID = credentials.userID AND credentials.userID = 0
  LEFT OUTER JOIN issuedid ON issuedid.userID = credentials.userID AND credentials.userID = 0";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


function saveReport($mysqli, $username, $description)
{
  $sql = "INSERT INTO tbl_reportedissues(username,description)
  VALUES (:username,:description)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':username' => $username,
      ':description' => $description
    )
  );
}

function checkIfReportExists($mysqli, $username, $description)
{
  $sql = "SELECT COUNT(id) FROM tbl_reportedissues WHERE username = ? AND description = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([$username, $description]);

  return $stmt->fetchColumn();
}

function update_credentials($username, $password, $mysqli)
{
  $sql = "UPDATE credentials SET
    username=:username,
    password=:password
    WHERE userID=:userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':username' => $username,
      ':password' => $password,
      ':userID' => $_SESSION['userID']
    )
  );
}

function get_credential_info($mysqli)
{
  $sql = "SELECT * FROM credentials WHERE userID=:userID";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":userID" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function get_bug_reports($mysqli)
{
  $sql = "SELECT * FROM tbl_reportedissues";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

//  ============== START PAGE 1 ==============
// View All
function get_user_info($mysqli)
{

  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM personal_info WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET INQUIRIES

function updatePersonalInfo($mysqli)
{

  $sql = "UPDATE personal_info SET
      lastName= :lastName,
      firstName= :firstName,
      middleName=:middleName,
      extensionName=:extensionName,

      birthdate= :birthdate,
      birthplace= :birthplace,
      gender= :gender,
      civilStatus= :civilStatus,
      height= :height,
      weight= :weight,

      bloodType= :bloodType,
      gsis= :gsis,
      pagibig= :pagibig,
      philhealth= :philhealth,
      sss= :sss,
      tin= :tin,
      agencyEmployeeNum= :agencyEmployeeNum,
      citizenship= :citizenship,
      citizenBy= :citizenBy,
      dualCitizenshipCountry= :dualCitizenshipCountry,

      Res_block= :Res_Block,
      Res_street= :Res_Street,
      Res_village= :Res_Village,
      Res_brgy= :Res_Brgy,
      Res_city= :Res_City,
      Res_province= :Res_Province,
      Res_zip= :Res_Zip,

      Per_block= :Per_Block,
      Per_street= :Per_Street,
      Per_village= :Per_Village,
      Per_brgy= :Per_Brgy,
      Per_city= :Per_City,
      Per_province= :Per_Province,
      Per_zip= :Per_Zip,

      telephone= :telephone,
      mobile= :mobile,
      email= :email
      WHERE userID= :userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':lastName' => $_POST['lastName'],
      ':firstName' => $_POST['firstName'],
      ':middleName' => $_POST['middleName'],
      ':extensionName' => $_POST['extensionName'],

      ':birthdate' => $_POST['birthdate'],
      ':birthplace' => $_POST['birthplace'],
      ':gender' => $_POST['gender'],
      ':civilStatus' => $_POST['civilStatus'],
      ':height' => $_POST['height'],
      ':weight' => $_POST['weight'],

      ':bloodType' => $_POST['bloodType'],
      ':gsis' => $_POST['gsis'],
      ':pagibig' => $_POST['pagibig'],
      ':philhealth' => $_POST['philhealth'],
      ':sss' => $_POST['sss'],
      ':tin' => $_POST['tin'],
      ':agencyEmployeeNum' => $_POST['agencyEmployeeNum'],

      ':citizenship' => isset($_POST['citizenship']) ? $_POST['citizenship'] : "",
      ':citizenBy' => isset($_POST['citizenBy']) ? $_POST['citizenBy'] : "",
      ':dualCitizenshipCountry' => $_POST['dualCitizenshipCountry'],

      ':Res_Block' => $_POST['resBlock'],
      ':Res_Street' => $_POST['resStreet'],
      ':Res_Village' => $_POST['resVillage'],
      ':Res_Brgy' => $_POST['resBrgy'],
      ':Res_City' => $_POST['resCity'],
      ':Res_Province' => $_POST['resProvince'],
      ':Res_Zip' => $_POST['resZip'],

      ':Per_Block' => $_POST['perBlock'],
      ':Per_Street' => $_POST['perStreet'],
      ':Per_Village' => $_POST['perVillage'],
      ':Per_Brgy' => $_POST['perBrgy'],
      ':Per_City' => $_POST['perCity'],
      ':Per_Province' => $_POST['perProvince'],
      ':Per_Zip' => $_POST['perZip'],

      ':telephone' => $_POST['telephone'],
      ':mobile' => $_POST['mobile'],
      ':email' => $_POST['email']


    )
  );
} //END UPDATE PERSONAL INFO

function insertPersonalInfo($mysqli)
{

  $sql = "INSERT INTO personal_info(userID,lastName,firstName,middleName,extensionName,birthdate,birthplace,gender,civilStatus,height,weight,bloodType,gsis,pagibig,philhealth,sss,tin,agencyEmployeeNum,citizenship,citizenBy,dualCitizenshipCountry,Res_Block,Res_Street,Res_Village,Res_Brgy,Res_City,Res_Province,Res_Zip,Per_Block,Per_Street,Per_Village,Per_Brgy,Per_City,Per_Province,Per_Zip,telephone,mobile,email
        )
        VALUES (:userID,:lastName,:firstName,:middleName,:extensionName,:birthdate,:birthplace,:gender,:civilStatus,:height,:weight,:bloodType,:gsis,:pagibig,:philhealth,:sss,:tin,:agencyEmployeeNum,:citizenship,:citizenBy,:dualCitizenshipCountry,:Res_Block,:Res_Street,:Res_Village,:Res_Brgy,:Res_City,:Res_Province,:Res_Zip,:Per_Block,:Per_Street,:Per_Village,:Per_Brgy,:Per_City,:Per_Province,:Per_Zip,:telephone,:mobile,:email)";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':lastName' => $_POST['lastName'],
      ':firstName' => $_POST['firstName'],
      ':middleName' => $_POST['middleName'],
      ':extensionName' => $_POST['extensionName'],

      ':birthdate' => $_POST['birthdate'],
      ':birthplace' => $_POST['birthplace'],
      ':gender' => $_POST['gender'],
      ':civilStatus' => $_POST['civilStatus'],
      ':height' => $_POST['height'],
      ':weight' => $_POST['weight'],

      ':bloodType' => $_POST['bloodType'],
      ':gsis' => $_POST['gsis'],
      ':pagibig' => $_POST['pagibig'],
      ':philhealth' => $_POST['philhealth'],
      ':sss' => $_POST['sss'],
      ':tin' => $_POST['tin'],
      ':agencyEmployeeNum' => $_POST['agencyEmployeeNum'],
      ':citizenship' => $_POST['citizenship'],
      ':citizenBy' => isset($_POST['citizenBy']) ? $_POST['citizenBy'] : "",
      ':dualCitizenshipCountry' => isset($_POST['dualCitizenshipCountry']) ? $_POST['dualCitizenshipCountry'] : "",

      ':Res_Block' => $_POST['resBlock'],
      ':Res_Street' => $_POST['resStreet'],
      ':Res_Village' => $_POST['resVillage'],
      ':Res_Brgy' => $_POST['resBrgy'],
      ':Res_City' => $_POST['resCity'],
      ':Res_Province' => $_POST['resProvince'],
      ':Res_Zip' => $_POST['resZip'],

      ':Per_Block' => $_POST['perBlock'],
      ':Per_Street' => $_POST['perStreet'],
      ':Per_Village' => $_POST['perVillage'],
      ':Per_Brgy' => $_POST['perBrgy'],
      ':Per_City' => $_POST['perCity'],
      ':Per_Province' => $_POST['perProvince'],
      ':Per_Zip' => $_POST['perZip'],

      ':telephone' => $_POST['telephone'],
      ':mobile' => $_POST['mobile'],
      ':email' => $_POST['email']
    )
  );
}
//  ============== END PAGE 1 ==============

//  ============== START PAGE 2 ==============
function getSpouseInfo($mysqli)
{
  // $sql="SELECT * FROM spouse WHERE userID='".$_SESSION['userID']."'";
  // $temp = array();
  // $query = $mysqli->query($sql);
  // while($res = mysqli_fetch_assoc($query)){
  //   $temp[] = $res;
  // }return $temp;
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM spouse WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET SPOUSE INFO

function updateSpouseInfo($mysqli)
{

  $sql = "UPDATE spouse SET
          lastName=:lastName,
          firstName=:firstName,
          middleName=:middleName,
          extensionName=:extensionName,

          occupation=:occupation,
          employer=:employerbusiness,
          businessAddress=:businessAddress,
          telephone=:telephone
          WHERE userID=:userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':lastName' => $_POST['lastName'],
      ':firstName' => $_POST['firstName'],
      ':middleName' => $_POST['middleName'],
      ':extensionName' => $_POST['extensionName'],

      ':occupation' => $_POST['occupation'],
      ':employerbusiness' => $_POST['employer-business'],
      ':businessAddress' => $_POST['businessAddress'],
      ':telephone' => $_POST['telephone']
    )
  );
} // END UPDATE SPOUSE INFO

function insertSpouseInfo($mysqli)
{

  $sql = "INSERT INTO spouse(`userID`,`lastName`,`firstName`,`middleName`,`extensionName`,`occupation`,`employer`,`businessAddress`,`telephone`)
            VALUES (:userID,:lastName,:firstName,:middleName,:extensionName,:occupation,
              :employerbusiness,:businessAddress,:telephone)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':lastName' => $_POST['lastName'],
      ':firstName' => $_POST['firstName'],
      ':middleName' => $_POST['middleName'],
      ':extensionName' => $_POST['extensionName'],
      ':occupation' => $_POST['occupation'],
      ':employerbusiness' => $_POST['employer-business'],
      ':businessAddress' => $_POST['businessAddress'],
      ':telephone' => $_POST['telephone']
    )
  );
} // END INSERT SPOUSE INFO

function getChildInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM children WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}  // END GET CHILDREN INFO

function deleteChildInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM children WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
}

function insertChildInfo($mysqli)
{
  // $countChild = $_POST['countChild'];
  $rank = 1;
  for ($i = 1; $i <= 21; $i++) {

    if (!isset($_POST['childName' . $i])) continue;
    if (!isset($_POST['birthdate' . $i])) continue;

    $child = $_POST['childName' . $i];
    $bday = $_POST['birthdate' . $i];

    $sql = "INSERT INTO children(`userID`,`childName`,`birthdate`,`rank`)
                  VALUES (:userID,:childName,:birthdate,:rank)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':childName' => $child,
        ':birthdate' => $bday,
        ':rank' => $rank,
      )
    );

    $rank++;
  }
} // END INSERT CHILDREN INFO

function getParentsInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM parents WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET PARENTS INFO

function updateParentsInfo($mysqli)
{
  $sql = "UPDATE parents SET
                  FatherLastName=:FatherLastName,
                  FatherFirstName=:FatherFirstName,
                  FatherMiddleName=:FatherMiddleName,
                  FatherExtensionName=:FatherExtensionName,
                  MotherMaidenLastName=:MotherMaidenLastName,
                  MotherFirstName=:MotherFirstName,
                  MotherMiddleName=:MotherMiddleName
                  WHERE userID=:userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':FatherLastName' => $_POST['FatherLastName'],
      ':FatherFirstName' => $_POST['FatherFirstName'],
      ':FatherMiddleName' => $_POST['FatherMiddleName'],
      ':FatherExtensionName' => $_POST['FatherExtensionName'],

      ':MotherMaidenLastName' => $_POST['MotherMaidenLastName'],
      ':MotherFirstName' => $_POST['MotherFirstName'],
      ':MotherMiddleName' => $_POST['MotherMiddleName']
    )
  );
} // END UPDATE PARENTS INFO

function insertParentsInfo($mysqli)
{
  $sql = "INSERT INTO parents(`userID`,`FatherLastName`,`FatherFirstName`,`FatherMiddleName`,`FatherExtensionName`,`MotherMaidenLastName`,`MotherFirstName`,`MotherMiddleName`)
                    VALUES(:userID,:FatherLastName,:FatherFirstName,:FatherMiddleName,:FatherExtensionName,:MotherMaidenLastName,:MotherFirstName,:MotherMiddleName)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':FatherLastName' => $_POST['FatherLastName'],
      ':FatherFirstName' => $_POST['FatherFirstName'],
      ':FatherMiddleName' => $_POST['FatherMiddleName'],
      ':FatherExtensionName' => $_POST['FatherExtensionName'],

      ':MotherMaidenLastName' => $_POST['MotherMaidenLastName'],
      ':MotherFirstName' => $_POST['MotherFirstName'],
      ':MotherMiddleName' => $_POST['MotherMiddleName']
    )
  );
} // END UPDATE PARENTS INFO
//  ============== END PAGE 2 ==============

//  ============== START PAGE 3 ==============
function getEducationalInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM education WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET EDUCATIONAL INFO

function updateEducationalInfo($mysqli)
{
  $sql = "UPDATE education SET
                      elemSchoolName=:elemSchoolName,
                      elemDegree=:elemDegree,
                      elemPeriodFrom=:elemPeriodFrom,
                      elemPeriodTo=:elemPeriodTo,
                      elemUnitsEarned=:elemUnitsEarned,
                      elemYearGraduate=:elemYearGraduate,
                      elemScholarship=:elemScholarship,

                      secondarySchoolName=:secondarySchoolName,
                      secondaryDegree=:secondaryDegree,
                      secondaryPeriodFrom=:secondaryPeriodFrom,
                      secondaryPeriodTo=:secondaryPeriodTo,
                      secondaryUnitsEarned=:secondaryUnitsEarned,
                      secondaryYearGraduate=:secondaryYearGraduate,
                      secondaryScholarship=:secondaryScholarship,

                      vocationalSchoolName=:vocationalSchoolName,
                      vocationalDegree=:vocationalDegree,
                      vocationalPeriodFrom=:vocationalPeriodFrom,
                      vocationalPeriodTo=:vocationalPeriodTo,
                      vocationalUnitsEarned=:vocationalUnitsEarned,
                      vocationalYearGraduate=:vocationalYearGraduate,
                      vocationalScholarship=:vocationalScholarship,

                      collegeSchoolName=:collegeSchoolName,
                      collegeDegree=:collegeDegree,
                      collegePeriodFrom=:collegePeriodFrom,
                      collegePeriodTo=:collegePeriodTo,
                      collegeUnitsEarned=:collegeUnitsEarned,
                      collegeYearGraduate=:collegeYearGraduate,
                      collegeScholarship=:collegeScholarship,

                      gradSchoolName=:gradSchoolName,
                      gradDegree=:gradDegree,
                      gradPeriodFrom=:gradPeriodFrom,
                      gradPeriodTo=:gradPeriodTo,
                      gradUnitsEarned=:gradUnitsEarned,
                      gradYearGraduate=:gradYearGraduate,
                      gradScholarship=:gradScholarship
                      WHERE userID=:userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':elemSchoolName' => $_POST['elemSchoolName'],
      ':elemDegree' => $_POST['elemDegree'],
      ':elemPeriodFrom' => $_POST['elemPeriodFrom'],
      ':elemPeriodTo' => $_POST['elemPeriodTo'],
      ':elemUnitsEarned' => $_POST['elemUnitsEarned'],
      ':elemYearGraduate' => $_POST['elemYearGraduate'],
      ':elemScholarship' => $_POST['elemScholarship'],

      ':secondarySchoolName' => $_POST['secondarySchoolName'],
      ':secondaryDegree' => $_POST['secondaryDegree'],
      ':secondaryPeriodFrom' => $_POST['secondaryPeriodFrom'],
      ':secondaryPeriodTo' => $_POST['secondaryPeriodTo'],
      ':secondaryUnitsEarned' => $_POST['secondaryUnitsEarned'],
      ':secondaryYearGraduate' => $_POST['secondaryYearGraduate'],
      ':secondaryScholarship' => $_POST['secondaryScholarship'],

      ':vocationalSchoolName' => $_POST['vocationalSchoolName'],
      ':vocationalDegree' => $_POST['vocationalDegree'],
      ':vocationalPeriodFrom' => $_POST['vocationalPeriodFrom'],
      ':vocationalPeriodTo' => $_POST['vocationalPeriodTo'],
      ':vocationalUnitsEarned' => $_POST['vocationalUnitsEarned'],
      ':vocationalYearGraduate' => $_POST['vocationalYearGraduate'],
      ':vocationalScholarship' => $_POST['vocationalScholarship'],

      ':collegeSchoolName' => $_POST['collegeSchoolName'],
      ':collegeDegree' => $_POST['collegeDegree'],
      ':collegePeriodFrom' => $_POST['collegePeriodFrom'],
      ':collegePeriodTo' => $_POST['collegePeriodTo'],
      ':collegeUnitsEarned' => $_POST['collegeUnitsEarned'],
      ':collegeYearGraduate' => $_POST['collegeYearGraduate'],
      ':collegeScholarship' => $_POST['collegeScholarship'],

      ':gradSchoolName' => $_POST['gradSchoolName'],
      ':gradDegree' => $_POST['gradDegree'],
      ':gradPeriodFrom' => $_POST['gradPeriodFrom'],
      ':gradPeriodTo' => $_POST['gradPeriodTo'],
      ':gradUnitsEarned' => $_POST['gradUnitsEarned'],
      ':gradYearGraduate' => $_POST['gradYearGraduate'],
      ':gradScholarship' => $_POST['gradScholarship']
    )
  );
} // END UPDATE EDUCATIONAL INFO

function insertEducationalInfo($mysqli)
{

  $sql = "INSERT INTO education(`userID`,`elemSchoolName`,`elemDegree`,`elemPeriodFrom`,`elemPeriodTo`,`elemUnitsEarned`,`elemYearGraduate`,`elemScholarship`,`secondarySchoolName`,`secondaryDegree`,`secondaryPeriodFrom`,`secondaryPeriodTo`,`secondaryUnitsEarned`,`secondaryYearGraduate`,`secondaryScholarship`,`vocationalSchoolName`,`vocationalDegree`,`vocationalPeriodFrom`,`vocationalPeriodTo`,`vocationalUnitsEarned`,`vocationalYearGraduate`,`vocationalScholarship`,`collegeSchoolName`,`collegeDegree`,`collegePeriodFrom`,`collegePeriodTo`,`collegeUnitsEarned`,`collegeYearGraduate`,`collegeScholarship`,`gradSchoolName`,`gradDegree`,`gradPeriodFrom`,`gradPeriodTo`,`gradUnitsEarned`,`gradYearGraduate`,`gradScholarship`)
                        VALUES(:userID,:elemSchoolName,:elemDegree,:elemPeriodFrom,:elemPeriodTo,:elemUnitsEarned,:elemYearGraduate,:elemScholarship,:secondarySchoolName,:secondaryDegree,:secondaryPeriodFrom,:secondaryPeriodTo,:secondaryUnitsEarned,:secondaryYearGraduate,:secondaryScholarship,:vocationalSchoolName,:vocationalDegree,:vocationalPeriodFrom,:vocationalPeriodTo,:vocationalUnitsEarned,:vocationalYearGraduate,:vocationalScholarship,:collegeSchoolName,:collegeDegree,:collegePeriodFrom,:collegePeriodTo,:collegeUnitsEarned,:collegeYearGraduate,:collegeScholarship,:gradSchoolName,:gradDegree,:gradPeriodFrom,:gradPeriodTo,:gradUnitsEarned,:gradYearGraduate,:gradScholarship)";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':elemSchoolName' => $_POST['elemSchoolName'],
      ':elemDegree' => $_POST['elemDegree'],
      ':elemPeriodFrom' => $_POST['elemPeriodFrom'],
      ':elemPeriodTo' => $_POST['elemPeriodTo'],
      ':elemUnitsEarned' => $_POST['elemUnitsEarned'],
      ':elemYearGraduate' => $_POST['elemYearGraduate'],
      ':elemScholarship' => $_POST['elemScholarship'],

      ':secondarySchoolName' => $_POST['secondarySchoolName'],
      ':secondaryDegree' => $_POST['secondaryDegree'],
      ':secondaryPeriodFrom' => $_POST['secondaryPeriodFrom'],
      ':secondaryPeriodTo' => $_POST['secondaryPeriodTo'],
      ':secondaryUnitsEarned' => $_POST['secondaryUnitsEarned'],
      ':secondaryYearGraduate' => $_POST['secondaryYearGraduate'],
      ':secondaryScholarship' => $_POST['secondaryScholarship'],

      ':vocationalSchoolName' => $_POST['vocationalSchoolName'],
      ':vocationalDegree' => $_POST['vocationalDegree'],
      ':vocationalPeriodFrom' => $_POST['vocationalPeriodFrom'],
      ':vocationalPeriodTo' => $_POST['vocationalPeriodTo'],
      ':vocationalUnitsEarned' => $_POST['vocationalUnitsEarned'],
      ':vocationalYearGraduate' => $_POST['vocationalYearGraduate'],
      ':vocationalScholarship' => $_POST['vocationalScholarship'],

      ':collegeSchoolName' => $_POST['collegeSchoolName'],
      ':collegeDegree' => $_POST['collegeDegree'],
      ':collegePeriodFrom' => $_POST['collegePeriodFrom'],
      ':collegePeriodTo' => $_POST['collegePeriodTo'],
      ':collegeUnitsEarned' => $_POST['collegeUnitsEarned'],
      ':collegeYearGraduate' => $_POST['collegeYearGraduate'],
      ':collegeScholarship' => $_POST['collegeScholarship'],

      ':gradSchoolName' => $_POST['gradSchoolName'],
      ':gradDegree' => $_POST['gradDegree'],
      ':gradPeriodFrom' => $_POST['gradPeriodFrom'],
      ':gradPeriodTo' => $_POST['gradPeriodTo'],
      ':gradUnitsEarned' => $_POST['gradUnitsEarned'],
      ':gradYearGraduate' => $_POST['gradYearGraduate'],
      ':gradScholarship' => $_POST['gradScholarship']
    )
  );
} // END INSERT EDUCATIONAL INFO
//  ============== END PAGE 3 ==============

//  ============== START PAGE 4 ==============

function getCSEInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM cse WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET CIVIL SERVICE ELIGIBILITY INFO

function deleteCSEInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM cse WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE CSE INFO

function insertCSEInfo($mysqli)
{
  $rank = 1;
  for ($i = 1; $i <= 11; $i++) {
    if (!isset($_POST['cse' . $i])) continue;
    if (!isset($_POST['rate' . $i])) continue;
    if (!isset($_POST['examDate' . $i])) continue;
    if (!isset($_POST['examPlace' . $i])) continue;
    $cse = $_POST['cse' . $i];
    $rate = $_POST['rate' . $i];
    $examDate = $_POST['examDate' . $i];
    $examPlace = $_POST['examPlace' . $i];

    $sql = "INSERT INTO cse(`userID`,`rank`,`cse`,`rate`,`examDate`,`examPlace`)
                            VALUES (:userID,:rank,:cse,:rate,:examDate,:examPlace)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':rank' => $rank,
        ':cse' => $cse,
        ':rate' => $rate,
        ':examDate' => $examDate,
        ':examPlace' => $examPlace
      )
    );
    $rank++;
  }
} // END INSERT CSE INFO
//  ============== END PAGE 4 ==============

//  ============== START PAGE 5 ==============

function getWorkInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM work WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET WORK INFO

function deleteWorkInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM work WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE WORK INFO

function insertWorkInfo($mysqli)
{

  $rank = 1;
  for ($i = 1; $i <= 51; $i++) {
    if (!isset($_POST['startDate' . $i])) continue;
    if (!isset($_POST['endDate' . $i])) continue;
    if (!isset($_POST['workPosition' . $i])) continue;
    if (!isset($_POST['company' . $i])) continue;
    if (!isset($_POST['salary' . $i])) continue;
    if (!isset($_POST['salaryGrade' . $i])) continue;
    if (!isset($_POST['statusAppointment' . $i])) continue;
    if (!isset($_POST['governmentService' . $i])) continue;

    $startDate = $_POST['startDate' . $i];
    $endDate = $_POST['endDate' . $i];
    $workPosition = $_POST['workPosition' . $i];
    $company = $_POST['company' . $i];
    $salary = $_POST['salary' . $i];
    $salaryGrade = $_POST['salaryGrade' . $i];
    $statusAppointment = $_POST['statusAppointment' . $i];
    $governmentService = $_POST['governmentService' . $i];


    $sql = "INSERT INTO work(`userID`,`rank`,`startDate`,`endDate`,`workPosition`,`company`,`salary`,`salaryGrade`,`statusAppointment`,`governmentService`)
                              VALUES (:userID,:rank,:startDate,:endDate,:workPosition,:company,:salary,:salaryGrade,:statusAppointment,:governmentService)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':rank' => $rank,
        ':startDate' => $startDate,
        ':endDate' => $endDate,
        ':workPosition' => $workPosition,
        ':company' => $company,
        ':salary' => $salary,
        ':salaryGrade' => $salaryGrade,
        ':statusAppointment' => $statusAppointment,
        ':governmentService' => $governmentService
      )
    );

    $rank++;
  }
} // END INSERT WORK INFO
//  ============== END PAGE 5 ==============

//  ============== START SERVICE RECORD ==============

function checkGovServicesInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM work WHERE userID = :user AND governmentService = :governmentService");
  $stmt->execute(array(":governmentService" => "Yes", ":user" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END CHECK GOVERNMENT SERVICES

function getServiceRecord($mysqli, $workID)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM service_record WHERE workID = :workID");
  $stmt->execute(array(":workID" => $workID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  return $temp;
}

function deleteServiceRecordInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM service_record WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE SERVICE RECORD INFO

function insertServiceRecordInfo($mysqli)
{

  // $rank = 1;
  for ($i = 1; $i <= 20; $i++) {
    if (!isset($_POST['workID' . $i])) continue;
    if (!isset($_POST['serviceStation' . $i])) continue;
    if (!isset($_POST['serviceBranch' . $i])) continue;
    if (!isset($_POST['leaveFrom' . $i])) continue;
    if (!isset($_POST['leaveTo' . $i])) continue;
    if (!isset($_POST['separationDate' . $i])) continue;
    if (!isset($_POST['separationCause' . $i])) continue;

    $workID = $_POST['workID' . $i];
    $serviceStation = $_POST['serviceStation' . $i];
    $serviceBranch = $_POST['serviceBranch' . $i];
    $leaveFrom = $_POST['leaveFrom' . $i];
    $leaveTo = $_POST['leaveTo' . $i];
    $separationDate = $_POST['separationDate' . $i];
    $separationCause = $_POST['separationCause' . $i];


    $sql = "INSERT INTO service_record(`dateFiled`,`workID`,`station`,`branch`,`leaveStartDate`,`leaveEndDate`,`separationDate`,`separationCause`) VALUES (NOW(),:workID,:station,:serviceBranch,:leaveFrom,:leaveTo,:separationDate,:separationCause)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':workID' => $workID,
        ':station' => $serviceStation,
        ':serviceBranch' => $serviceBranch,
        ':leaveFrom' => $leaveFrom,
        ':leaveTo' => $leaveTo,
        ':separationDate' => $separationDate,
        ':separationCause' => $separationCause
      )
    );

    // $rank++;
  }
} // END INSERT SERVICE RECORD INFO

// function updateServiceRecordInfo($mysqli){
//    $sql = "UPDATE service_record SET
//                   userID=:userID,
//                   startDate=:startDate,
//                   endDate=:endDate,
//                   designationPosition=:designationPosition,
//                   status=:status,
//                   salary=:salary,
//                   station=:station,
//                   branch=:branch,
//                   leaveAbsence=:leaveAbsence,
//                   separationDate=:separationDate,
//                   separationCause=:separationCause
//                   WHERE userID=:userID";
//   $stmt = $mysqli->prepare($sql);
//   $stmt->execute(
//     array(
//       ':userID' => $_SESSION['userID'],
//       ':startDate' => $_POST['startDate'],
//       ':endDate' => $_POST['endDate'],
//       ':FatherMiddleName' => $_POST['FatherMiddleName'],
//       ':designationPosition' => $_POST['designationPosition'],

//       ':status' => $_POST['status'],
//       ':salary' => $_POST['salary'],
//       ':station' => $_POST['station'],
//       ':leaveAbsence' => $_POST['leaveAbsence'],
//       ':separationDate' => $_POST['separationDate'],
//       ':separationCause' => $_POST['separationCause']
//     )
//   );
// }


//  ============== END SERVICE RECORD ==============

//  ============== START PAGE 6 ==============

function getVoluntaryWorkInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM tbl_voluntary WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET VOLUNTARY INFO

function deleteVoluntaryWorkInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM tbl_voluntary WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE VOLUNTARY INFO

function insertVoluntaryWorkInfo($mysqli)
{
  $rank = 1;
  for ($i = 1; $i <= 51; $i++) {
    if (!isset($_POST['orgNameAddress' . $i])) continue;
    if (!isset($_POST['startDate' . $i])) continue;
    if (!isset($_POST['endDate' . $i])) continue;
    if (!isset($_POST['numberOfHours' . $i])) continue;
    if (!isset($_POST['natureOfWork' . $i])) continue;

    $orgNameAddress = $_POST['orgNameAddress' . $i];
    $startDate = $_POST['startDate' . $i];
    $endDate = $_POST['endDate' . $i];
    $numberOfHours = $_POST['numberOfHours' . $i];
    $natureOfWork = $_POST['natureOfWork' . $i];

    $sql = "INSERT INTO tbl_voluntary(`userID`,`rank`,`orgNameAddress`,`startDate`,`endDate`,`numberOfHours`,`natureOfWork`)
                                VALUES (:userID,:rank,:orgNameAddress,:startDate,:endDate,:numberOfHours,:natureOfWork)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':rank' => $rank,
        ':orgNameAddress' => $orgNameAddress,
        ':startDate' => $startDate,
        ':endDate' => $endDate,
        ':numberOfHours' => $numberOfHours,
        ':natureOfWork' => $natureOfWork
      )
    );
    $rank++;
  }
}
// END INSERT VOLUNTARY WORK INFO
//  ============== END PAGE 6 ==============

//  ============== START PAGE 7 ==============

function getTrainingInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM training WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET TRAINING INFO

function deleteTrainingInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM training WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE TRAINING INFO

function insertTrainingInfo($mysqli)
{
  $rank = 1;
  for ($i = 1; $i <= 51; $i++) {
    if (!isset($_POST['title' . $i])) continue;
    if (!isset($_POST['startDate' . $i])) continue;
    if (!isset($_POST['endDate' . $i])) continue;
    if (!isset($_POST['numberOfHours' . $i])) continue;
    if (!isset($_POST['IDType' . $i])) continue;
    if (!isset($_POST['sponsoredBy' . $i])) continue;

    $title = $_POST['title' . $i];
    $startDate = $_POST['startDate' . $i];
    $endDate = $_POST['endDate' . $i];
    $numberOfHours = $_POST['numberOfHours' . $i];
    $IDType = $_POST['IDType' . $i];
    $sponsoredBy = $_POST['sponsoredBy' . $i];

    $sql = "INSERT INTO training(`userID`,`rank`,`title`,`startDate`,`endDate`,`numberOfHours`,`IDType`,`sponsoredBy`)
                                  VALUES (:userID,:rank,:title,:startDate,:endDate,:numberOfHours,:IDType,:sponsoredBy)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':rank' => $rank,
        ':title' => $title,
        ':startDate' => $startDate,
        ':endDate' => $endDate,
        ':numberOfHours' => $numberOfHours,
        ':IDType' => $IDType,
        ':sponsoredBy' => $sponsoredBy
      )
    );
    $rank++;
  }
}  // END INSERT TRAINING INFO
//  ============== END PAGE 7 ==============

//  ============== START PAGE 8 ==============

function getSkillInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM skills WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET SKILL INFO

function deleteSkillInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM skills WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE SKILL INFO

function insertSkillInfo($mysqli)
{
  $rank = 1;
  for ($i = 1; $i <= 51; $i++) {
    if (!isset($_POST['skill' . $i])) continue;

    $skill = $_POST['skill' . $i];

    $sql = "INSERT INTO skills(`userID`,`rank`,`skill`)
                                    VALUES (:userID,:rank,:skill)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':rank' => $rank,
        ':skill' => $skill
      )
    );
    $rank++;
  }
}  // END INSERT SKILL INFO

function getRecognitionInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM recognition WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET RECOGNITION INFO

function deleteRecognitionInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM recognition WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE RECOGNITION INFO

function insertRecognitionInfo($mysqli)
{
  $rank = 1;
  for ($i = 1; $i <= 51; $i++) {
    if (!isset($_POST['recognition' . $i])) continue;

    $recognition = $_POST['recognition' . $i];

    $sql = "INSERT INTO recognition(`userID`,`rank`,`recognition`)
                                      VALUES (:userID,:rank,:recognition)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':rank' => $rank,
        ':recognition' => $recognition
      )
    );
    $rank++;
  }
}  // END INSERT RECOGNITION INFO

function getOrganizationInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM organization WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET RECOGNITION INFO

function deleteOrganizationInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM organization WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE RECOGNITION INFO

function insertOrganizationInfo($mysqli)
{
  $rank = 1;
  for ($i = 1; $i <= 51; $i++) {
    if (!isset($_POST['organization' . $i])) continue;

    $organization = $_POST['organization' . $i];

    $sql = "INSERT INTO organization(`userID`,`rank`,`organization`)
                                        VALUES (:userID,:rank,:organization)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':rank' => $rank,
        ':organization' => $organization
      )
    );
    $rank++;
  }
}  // END INSERT RECOGNITION INFO
//  ============== END PAGE 8 ==============

//  ============== START PAGE 9 ==============
function getOtherInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM other WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET OTHER INFO

function updateOtherInfo($mysqli)
{
  $sql = "UPDATE other SET
                                        num34_a =:num34_a,
                                        num34_b =:num34_b,
                                        num34_b_details =:num34_b_details,
                                        num35_a =:num35_a,
                                        num35_a_details =:num35_a_details,
                                        num35_b =:num35_b,
                                        num35_b_dateFiled =:num35_b_dateFiled,
                                        num35_b_status =:num35_b_status,
                                        num36 = :num36,
                                        num36_details = :num36_details,
                                        num37 = :num37,
                                        num37_details = :num37_details,
                                        num38_a = :num38_a,
                                        num38_a_details = :num38_a_details,
                                        num38_b = :num38_b,
                                        num38_b_details = :num38_b_details,
                                        num39 = :num39,
                                        num39_details = :num39_details,
                                        num40_a = :num40_a,
                                        num40_a_details = :num40_a_details,
                                        num40_b = :num40_b,
                                        num40_b_details =:num40_b_details,
                                        num40_c =:num40_c,
                                        num40_c_details = :num40_c_details
                                        WHERE userID=:userID;";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':num34_a' => $_POST['num34_a'],
      ':num34_b' => $_POST['num34_b'],
      ':num34_b_details' => $_POST['num34_b_details'],
      ':num35_a' => $_POST['num35_a'],
      ':num35_a_details' => $_POST['num35_a_details'],
      ':num35_b' => $_POST['num35_b'],

      ':num35_b_dateFiled' => $_POST['num35_b_dateFiled'],
      ':num35_b_status' => $_POST['num35_b_status'],
      ':num36' => $_POST['num36'],
      ':num36_details' => $_POST['num36_details'],
      ':num37' => $_POST['num37'],
      ':num37_details' => $_POST['num37_details'],

      ':num38_a' => $_POST['num38_a'],
      ':num38_a_details' => $_POST['num38_a_details'],
      ':num38_b' => $_POST['num38_b'],
      ':num38_b_details' => $_POST['num38_b_details'],
      ':num39' => $_POST['num39'],
      ':num39_details' => $_POST['num39_details'],

      ':num40_a' => $_POST['num40_a'],
      ':num40_a_details' => $_POST['num40_a_details'],
      ':num40_b' => $_POST['num40_b'],
      ':num40_b_details' => $_POST['num40_b_details'],
      ':num40_c' => $_POST['num40_c'],
      ':num40_c_details' => $_POST['num40_c_details']

    )
  );
} // END UPDATE OTHER INFO

function insertOtherInfo($mysqli)
{

  $sql = "INSERT INTO other(`userID`,`num34_a`,`num34_b`,`num34_b_details`,`num35_a`,`num35_a_details`,`num35_b`,`num35_b_dateFiled`,`num35_b_status`,`num36`,`num36_details`,`num37`,`num37_details`,`num38_a`,`num38_a_details`,`num38_b`,`num38_b_details`,`num39`,`num39_details`,`num40_a`,`num40_a_details`,`num40_b`,`num40_b_details`,`num40_c`,`num40_c_details`)
                                          VALUES (:userID,:num34_a,:num34_b,:num34_b_details,:num35_a,:num35_a_details,:num35_b,:num35_b_dateFiled,:num35_b_status,:num36,:num36_details,:num37,:num37_details,:num38_a,:num38_a_details,:num38_b,:num38_b_details,:num39,:num39_details,:num40_a,:num40_a_details,:num40_b,:num40_b_details,:num40_c,:num40_c_details)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':num34_a' => $_POST['num34_a'],
      ':num34_b' => $_POST['num34_b'],

      ':num34_b_details' => $_POST['num34_b_details'],
      ':num35_a' => $_POST['num35_a'],
      ':num35_a_details' => $_POST['num35_a_details'],
      ':num35_b' => $_POST['num35_b'],
      ':num35_b_dateFiled' => $_POST['num35_b_dateFiled'],
      ':num35_b_status' => $_POST['num35_b_status'],
      ':num36' => $_POST['num36'],
      ':num36_details' => $_POST['num36_details'],
      ':num37' => $_POST['num37'],
      ':num37_details' => $_POST['num37_details'],
      ':num38_a' => $_POST['num38_a'],
      ':num38_a_details' => $_POST['num38_a_details'],
      ':num38_b' => $_POST['num38_b'],
      ':num38_b_details' => $_POST['num38_b_details'],
      ':num39' => $_POST['num39'],
      ':num39_details' => $_POST['num39_details'],

      ':num40_a' => $_POST['num40_a'],
      ':num40_a_details' => $_POST['num40_a_details'],
      ':num40_b' => $_POST['num40_b'],
      ':num40_b_details' => $_POST['num40_b_details'],
      ':num40_c' => $_POST['num40_c'],
      ':num40_c_details' => $_POST['num40_c_details']
    )
  );
}

function getReferenceInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM reference WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET RECOGNITION INFO

function deleteReferenceInfo($mysqli)
{
  // clear out the old position entries
  $stmt = $mysqli->prepare("DELETE FROM reference WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
} // END DELETE RECOGNITION INFO

//This function structure is tightly coupled, which means that a change in UI also requires a change here.
//This should be avoided.
function insertReferenceInfo($mysqli)
{
  $rank = 1;
  for ($i = 1; $i <= 10; $i++) {
    if (!isset($_POST['referenceName' . $i])) continue;
    if (!isset($_POST['address' . $i])) continue;
    if (!isset($_POST['referenceName' . $i])) continue;

    $referenceName = $_POST['referenceName' . $i];
    $address = $_POST['address' . $i];
    $phoneNumber = $_POST['phoneNumber' . $i];

    $sql = "INSERT INTO reference(`userID`,`rank`,`referenceName`,`address`,`phoneNumber`)
                                              VALUES (:userID,:rank,:referenceName,:address,:phoneNumber)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $_SESSION['userID'],
        ':rank' => $rank,
        ':referenceName' => $referenceName,
        ':address' => $address,
        ':phoneNumber' => $phoneNumber
      )
    );
    $rank++;
  }
}  // END INSERT RECOGNITION INFO

function getIssuedIDInfo($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM issuedid WHERE userID = :w ");
  $stmt->execute(array(":w" => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET ISSUED INFO

function updateIssuedIDInfo($mysqli)
{
  $sql = "UPDATE issuedid SET
                                              IDName =:IDName,
                                              IDNo  =:IDNo,
                                              datePlaceIssuance  =:datePlaceIssuance
                                              WHERE userID=:userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':IDName' => $_POST['IDName'],
      ':IDNo' => $_POST['IDNo'],
      ':datePlaceIssuance' => $_POST['datePlaceIssuance']
    )
  );
} // END UPDATE ISSUED ID INFO

function insertIssuedIDInfo($mysqli)
{
  // echo "<script> alert('$userID'); </script>";
  $sql = "INSERT INTO issuedid(`userID`,`IDName`,`IDNo`,`datePlaceIssuance`)
                                                VALUES (:userID,:IDName,:IDNo,:datePlaceIssuance)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $_SESSION['userID'],
      ':IDName' => $_POST['IDName'],
      ':IDNo' => $_POST['IDNo'],
      ':datePlaceIssuance' => $_POST['datePlaceIssuance']
    )
  );
} // END UPDATE ISSUED ID INFO

//  ============== END PAGE 9 ==============

//  ============== START PREVIEW ==============
function get_employeeInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM personal_info WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET SPOUSE
function get_spouseInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM spouse WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET PARENTS
function get_parentsInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM parents WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET CHILDREN
function get_childInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM children WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET VOLUNTARY
function get_voluntaryInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM tbl_voluntary WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET EDUCATION
function get_educationInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM education WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}
// GET CSE
function get_cseInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM cse WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET WORK
function get_workInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM work WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET TRAINING
function get_trainingInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM training WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET SKILL
function get_skillInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM skills WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET RECOGNITION
function get_recognitionInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM recognition WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET ORGANIZATION
function get_organizationInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM organization WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET OTHER
function get_otherInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM other WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET REFERENCE
function get_referenceInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM reference WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET ISSUED ID
function get_issuedIDInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE employeeID = :w ");
  $stmt1->execute(array(":w" => $_POST['employeeID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM issuedid WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}
//  ============== END PREVIEW ==============

//  ============== START MANAGE USER PASS ==============
function get_user_credentials($mysqli)
{
  $sql = "SELECT * FROM credentials";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function get_user_pass($mysqli)
{
  $sql = "SELECT password FROM credentials WHERE userID = :user";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':user' => $_SESSION['editUserId']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function updatePass($mysqli)
{
  $sql = "UPDATE credentials SET
    password=:password
    WHERE userID=:userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":password" => $_POST['editPassId'],
      ":userID" => $_POST['editPass']
    )
  );
}
//  ============== END MANAGE USER PASS ==============


//  ============== START Anniversary registration ==============
function registerUserToAnniversary($mysqli, $userID, $pwd)
{
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //add validation that checks if the user has already registered for this year's anniv. 
    $sqlGetExistingRegistration = "SELECT id FROM baranniversaryregistration WHERE userID = :userid AND YEAR(createdDateTime) = YEAR(CURRENT_DATE) ORDER BY createdDateTime ASC LIMIT 1";
    $sqlGetExistingRegistrationStmt = $mysqli->prepare($sqlGetExistingRegistration);
    $sqlGetExistingRegistrationStmt->execute([
      ':userid' => $userID
    ]);
    $registrationtExists = $sqlGetExistingRegistrationStmt->fetchColumn();

    if ($registrationtExists) {
      throw new Exception("You have already registered for the anniversary this year!");
      return false;
    }


    //update the pwd status
    $sql = "UPDATE personal_info SET PWD = :pwd WHERE userID = :userid";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute([
      ':userid' => $userID,
      ':pwd' => $pwd
    ]);

    //save ID to baranniversaryregistration
    $sqlAnniversary = "INSERT INTO baranniversaryregistration(`userID`,`createdDateTime`)  VALUES(:userid, NOW())";
    $sqlAnniversaryStmt = $mysqli->prepare($sqlAnniversary);
    $sqlAnniversaryStmt->execute([
      ':userid' => $userID
    ]);
    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function checkAnniversaryRegistrationByUserID($mysqli, $userID)
{
  $sqlGetRegistration = "SELECT * FROM baranniversaryregistration WHERE userID = :userid AND YEAR(createdDateTime) = YEAR(CURRENT_DATE) ORDER BY createdDateTime ASC LIMIT 1";
  $sqlGetRegistrationStmt = $mysqli->prepare($sqlGetRegistration);
  $sqlGetRegistrationStmt->execute([
    ':userid' => $userID,
  ]);

  //fetch returns a single row
  return $sqlGetRegistrationStmt->fetch();
}

function getAnniversaryRegisteredEmployeesByYear($mysqli, $date)
{

  $sqlGetRegisteredEmployees = "SELECT * FROM baranniversaryregistration WHERE YEAR(createdDateTime) = YEAR(CURRENT_DATE) ORDER BY createdDateTime";
  $sqlGetRegisteredEmployeesStmt = $mysqli->prepare($sqlGetRegisteredEmployees);
  $sqlGetRegisteredEmployeesStmt->execute();
  return $sqlGetRegisteredEmployeesStmt->fetchAll(PDO::FETCH_ASSOC);
}
//  ============== END Anniversary registration ==============


//  ============== S T A R T  V A C C I N E  ===============

function addEmployeeVaccination($mysqli, $userID, $brand, $dates, $attachment)
{

  //save attachment information
  //save vaccinated employee information
  //save dosage dates
  //upload attachment to server.
  $attachmentID = null;
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //If there's no error during the uploading of file save the file information to database
    if ($attachment['error'] == 0) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $attachment, $userID);
      $sqlInsertAttachmentStmt->execute();
      $attachmentID = $mysqli->lastInsertId();
    }

    //save vaccination details of employee

    $sqlInsert = "INSERT INTO vaccine_vaccinated_employees(`userID`, `brand`, `attachment`, `createdDateTime`)  
                VALUES(:userid, :brandid, :attachmentid, NOW())";
    $stmt = $mysqli->prepare($sqlInsert);
    $stmt->execute([
      ':userid' => $userID,
      ':brandid' => $brand,
      ':attachmentid' => $attachmentID
    ]);

    $addedVaccinatedEmployeeID = $mysqli->lastInsertId();


    //construct the multi-dimensional array for constructStatement function.
    $dosageDates = array();

    foreach ($dates as $date) {
      $dosageDates[] = array(
        'vaccinationID' => $addedVaccinatedEmployeeID,
        'dosageDate' => $date,
      );
    }

    //insert the dates of the request
    $insertDatesStmt = PDOMultiInsert::constructStatement($mysqli, 'vaccine_dosages', $dosageDates);
    $insertDatesStmt->execute();

    //copy the file to server
    if ($attachmentID != null) {
      Attachment::Upload($attachment, '../../uploads/vaccination/', $userID, $attachmentID);
    }

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function getVaccines($mysqli)
{
  $sql = "SELECT * FROM vaccine_brand";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function userIsVaccinated($mysqli, $userID)
{
  //CREDENTIALS, PERSONAL INFO, DIVISION, SECTION
  $sql = "SELECT id FROM vaccine_vaccinated_employees WHERE userID = :userid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getVaccinationsByUserID($mysqli, $userID)
{
  $sql = "SELECT * FROM view_vaccinatedemployees WHERE userID = :userid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDosagesByVaccinationID($mysqli, $vaccinationID)
{
  $sql = "SELECT * FROM vaccine_dosages WHERE vaccinationID  = :vaccinationid ORDER BY dosageDate";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':vaccinationid' => $vaccinationID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addDosage($mysqli, $vaccinationID, $date)
{
  $sqlInsert = "INSERT INTO vaccine_dosages(`vaccinationID`, `dosageDate`)  
                VALUES(:vaccinationid, :dosagedate, :attachmentid, NOW())";
  $stmt = $mysqli->prepare($sqlInsert);

  //execute returns true on success and false on failure
  return $stmt->execute([
    ':vaccinationid' => $vaccinationID,
    ':dosagedate' => $date
  ]);
}

function updateVaccinationDetails($PDO, $userID, $vaccinationID, $dates, $attachment)
{
  $attachmentID = null;
  try {
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $PDO->beginTransaction();

    if ($attachment != null) {
      if ($attachment['error'] == 0) {
        $sqlInsertAttachmentStmt = Attachment::constructStatement($PDO, 'attachments', $attachment, $userID);
        $sqlInsertAttachmentStmt->execute();
        $attachmentID = $PDO->lastInsertId();

        //update attachment id in vaccine_vaccinated_employees table
        $sqlUpdate = "UPDATE vaccine_vaccinated_employees SET attachment = :attachmentid WHERE id = :vaccinationid";

        $sqlUpdateStmt = $PDO->prepare($sqlUpdate);
        $sqlUpdateStmt->execute([
          ':vaccinationid' => $vaccinationID,
          ':attachmentid' => $attachmentID
        ]);
      }
    }

    if ($dates != null) {
      //construct the multi-dimensional array for constructStatement function.
      $dosageDates = array();

      foreach ($dates as $date) {
        $dosageDates[] = array(
          'vaccinationID' => $vaccinationID,
          'dosageDate' => $date,
        );
      }

      //insert the dates of the request
      $insertDatesStmt = PDOMultiInsert::constructStatement($PDO, 'vaccine_dosages', $dosageDates);
      $insertDatesStmt->execute();
    }

    //copy the file to server
    if ($attachmentID != null) {
      Attachment::Upload($attachment, '../../uploads/vaccination/', $userID, $attachmentID);
    }

    $PDO->commit();
    return true;
  } catch (Exception $e) {
    $PDO->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}


//  ============== E N D   V A C C I N E  ===============


//  ============== START WFH ==============




function checkAccomplishedPDS($mysqli, $userID)
{
  //this will be used for now. but a proper solution should be placed here later.
  $sql = "SELECT COUNT(userID) FROM personal_info WHERE userID = '$userID' AND (lastName != '' AND firstName != '' AND birthdate != '' AND gender != '' AND civilStatus != '' AND bloodType != '')";
  // $sql = "SELECT COUNT(userID) FROM personal_info WHERE userID = '$userID' AND (lastName != '' AND firstName != '' AND birthdate != '' AND gender != '' AND civilStatus != '' AND height != '' AND Res_city != '' )";
  $result = $mysqli->query($sql)->fetchColumn();
  return $result;
}





/**
 * Creates new wfh request with multiple dates. basically adds a row on wfh_request 
 * table then inserts multiple rows to wfh_requestdates table based on the ID of the added row on wfh_request.
 * 
 * @param PDO $mysqli pdo object.
 * @param int $userID ID of the user which the request is for
 * @param array $dates list of dates where we want to request for wfh
 * @param int $createdBy ID of the user who created this request, it could be the user itself or the division/section head.
 * @param string $status this is used for tracking the status of the request. 
 * @return bool returns true upon sucess and false on failure
 */
function addWorkFromHomeRequest($mysqli, $userID, $dates, $createdBy, $remarks, $attachment, $status = "new")
{
  //insert row in wfh_request
  //if allow null is not set in sql this will produce an error when approvedBy field is not provided.
  //then use LAST_INSERT_ID() to retrieve the id of that row as mentioned here: https://bit.ly/3ttSkc5
  //use that ID to insert to requestID field for each date in the date array(list of dates) to wfh_requestDates table
  //optional parameter notes: https://bit.ly/2QpBdsV

  //multiple insert PDO: 
  // link 1: https://thisinterestsme.com/pdo-prepared-multi-inserts/
  // link 2: https://stackoverflow.com/questions/1176352/pdo-prepared-inserts-multiple-rows-in-single-query
  $attachmentID = null;
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //If there's no error during the uploading of file save the file information to database
    if ($attachment['error'] == 0) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $attachment, $userID);
      $sqlInsertAttachmentStmt->execute();
      $attachmentID = $mysqli->lastInsertId();
    }

    //try to retrieve an existing wfh request
    $sql = "SELECT id FROM wfh_requests WHERE userID = :userid AND requestStatus = 'new'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute([
      ':userid' => $userID,
    ]);
    $requestExists = $stmt->fetchColumn();

    //if a pending request exists exit function
    if ($requestExists) {
      echo "<script>alert('you have existing request!');</script>";
      return false;
    }

    //try to retrieve an existing wfh 
    $sqlGetWFH = "SELECT wfhID FROM view_workfromhomeinfo WHERE userID = :userid AND wfhDate >= CURRENT_DATE ORDER BY wfhDate ASC LIMIT 1";
    $sqlGetWFHStmt = $mysqli->prepare($sqlGetWFH);
    $sqlGetWFHStmt->execute([
      ':userid' => $userID,
    ]);
    $wfhExists = $sqlGetWFHStmt->fetchColumn();
    if ($wfhExists) {
      echo "<script>alert('you have an active work from home!');</script>";
      return false;
    }

    //TODO: check if there's an existing work from home date for the user on wfh_workfromhomedates table.
    //if yes. throw exception and exit function.

    //create a remarks, save it to remarks table
    $sqlRemarks = "INSERT INTO remarks(`createdBy`,`remarks`,`createdDateTime`)  VALUES(:userid, :remarks, NOW())";
    $sqlRemarkstStmt = $mysqli->prepare($sqlRemarks);
    $sqlRemarkstStmt->execute([
      ':userid' => $userID,
      ':remarks' => $remarks
    ]);
    $addedRemarksID = $mysqli->lastInsertId();

    //Insert the request
    $sqlWFHRequest = "INSERT INTO wfh_requests(`userID`,`createdDateTime`,`createdBy`,`requestStatus`,`remarksID`,`attachmentID` )  VALUES(:userid, NOW(), :createdby, :mystatus, :remarksid, :attachmentid)";
    $sqlWFHRequestStmt = $mysqli->prepare($sqlWFHRequest);
    $sqlWFHRequestStmt->execute([
      ':userid' => $userID,
      ':createdby' => $createdBy,
      ':mystatus' => $status,
      ':remarksid' => $addedRemarksID,
      ':attachmentid' => $attachmentID
    ]);
    $addedRequestID = $mysqli->lastInsertId();

    //construct the multi dimensional array for constructStatement function.
    $wfhDates = array();

    foreach ($dates as $date) {
      $wfhDates[] = array(
        'requestID' => $addedRequestID,
        'requestDate' => $date,
      );
    }

    //insert the dates of the request
    $insertDatesStmt = PDOMultiInsert::constructStatement($mysqli, 'wfh_requestdates', $wfhDates);
    $insertDatesStmt->execute();

    //copy the file to server
    if ($attachmentID != null) {
      Attachment::Upload($attachment, '../uploads/wfh_request/', $userID, $attachmentID);
    }

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function getAllWFHByUserID($mysqli, $userID)
{
  $sql = "SELECT * FROM wfh_workfromhome WHERE userID = :userid ORDER BY createdDateTime DESC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllWFHDatesByWFHID($mysqli, $WFHID)
{
  $sql = "SELECT * FROM wfh_workfromhomedates WHERE wfhID = :wfhid ORDER BY wfhDate ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':wfhid' => $WFHID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCurrentWFHRequest($mysqli, $userID)
{
  //checks if there's an exising request that is new or accepted
  $sql = "SELECT * FROM wfh_requests WHERE userID = :userid AND approved = false AND (requestStatus = 'new' OR requestStatus = 'accepted') LIMIT 1";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addWFHRequest($mysqli, $userID, $createdBy)
{
  $sqlInsert = "INSERT INTO wfh_requests(`userID`,`createdDateTime`,`createdBy`,`status`)  
                VALUES(:userid, NOW(), :createdby, :mystatus)";

  $stmt = $mysqli->prepare($sqlInsert);
  $stmt->execute(
    array(
      ':userid' => $userID,
      ':createdby' => $userID,
      ':mystatus' => "new"
    )
  );
}

function addWFHRequestDate($mysqli, $requestID)
{
  $sqlInsert = "INSERT INTO wfh_requestdates(`requestID`,`requestDate`)  
                VALUES(:requestid, NOW())";

  $stmt = $mysqli->prepare($sqlInsert);
  $stmt->execute(
    array(
      ':requestid' => $requestID
    )
  );
}

function getAcceptedWFHRequests($mysqli)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE requestStatus = 'accepted' AND accepted = true ORDER BY requestDate ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getAcceptedByHRWFHRequests($mysqli)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE requestStatus = 'accepted' AND acceptedByHR = true ORDER BY requestDate ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAcceptedWFHRequests($mysqli, $acceptedByHR)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE requestApproved = false AND requestStatus = 'accepted' AND acceptedByHR = :acceptedbyhr ORDER BY requestDate ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':acceptedbyhr' => $acceptedByHR
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getWFHRequestsBySection($mysqli, $sectionID)
{
  //HAVING MAX: https://bit.ly/32xZRuc AND https://bit.ly/2Q9v0Sw
  // $sql = "SELECT * FROM view_requestsinfo WHERE section = :section GROUP BY userID HAVING MAX(requestDate) ORDER BY requestDate ASC";
  $sql = "SELECT * FROM view_requestsinfo WHERE sectionID = :section AND requestApproved = false AND requestStatus = 'new' ORDER BY requestDate ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':section' => $sectionID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllNewWFHRequestsByDivision($mysqli, $divisionID)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE divisionID = :division AND requestApproved = false AND requestStatus = 'new' ORDER BY requestDate ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':division' => $divisionID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllWFHRequests($mysqli)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE requestStatus != 'cancelled' ORDER BY requestDate DESC LIMIT 10";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllWFHRequestsByDivision($mysqli, $divisionID)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE divisionID = :division AND requestStatus != 'cancelled' AND requestStatus != 'new' ORDER BY requestDate DESC LIMIT 10";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':division' => $divisionID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAcceptedWFHRequestsByHR($mysqli)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE acceptedByHR = TRUE ORDER BY requestDate DESC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAcceptedWFHRequestsByHRByUser($mysqli, $userID)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE acceptedByHR = TRUE AND acceptedByHRID = :userid ORDER BY requestDate DESC LIMIT 10";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllApprovedWFHRequestsByUser($mysqli, $userID)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE requestApproved = TRUE AND requestApprovedBy = :userid ORDER BY requestDate DESC LIMIT 10";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllDeclinedWFHRequestsByUser($mysqli, $userID)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE declined = TRUE AND declinedBy = :userid ORDER BY requestDate DESC LIMIT 10";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAcceptedWFHRequestsByDivision($mysqli, $divisionID)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE divisionID = :division AND accepted = TRUE ORDER BY requestDate DESC LIMIT 10";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':division' => $divisionID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllDeclinedWFHRequestsByDivision($mysqli, $divisionID)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE divisionID = :division AND declined = TRUE ORDER BY requestDate ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':division' => $divisionID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllDeclinedWFHRequestsByDivisionByUser($mysqli, $divisionID, $userID)
{
  $sql = "SELECT * FROM view_requestsinfo WHERE divisionID = :division AND declined = TRUE AND declinedBy = :userid ORDER BY requestDate DESC LIMIT 10";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':division' => $divisionID,
    ':userid' => $userID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getWFHRequest($mysqli, $requestID)
{
  $sql = "SELECT * FROM wfh_requests WHERE id  = :requestid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':requestid' => $requestID,
  ]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getEmployeesBySection($mysqli, $userID, $sectionID)
{
  //query the credentials list.
  $sql = "SELECT * FROM credentials WHERE userID != :userid  AND section = :section ORDER BY username";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID,
    ':section' => $sectionID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEmployeesByDivision($mysqli, $userID, $divisionID)
{
  //query the credentials list.
  $sql = "SELECT * FROM credentials WHERE userID != :userid  AND division = :division";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID,
    ':division' => $divisionID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEmployeeIDsByDivision($mysqli, $userID, $divisionID)
{
  //query the credentials list.
  $sql = "SELECT userID FROM credentials WHERE userID != :userid  AND division = :division";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID,
    ':division' => $divisionID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getAllEmployees($mysqli)
{
  //query the credentials list.
  $sql = "SELECT * FROM credentials WHERE disabled != TRUE";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllEmployeeIDs($mysqli)
{
  //query the credentials list.
  $sql = "SELECT userID FROM credentials WHERE disabled != TRUE";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getWFHRequestDates($mysqli, $requestID)
{
  $sql = "SELECT * FROM wfh_requestdates WHERE requestID = :requestid ORDER BY requestDate";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':requestid' => $requestID,
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function cancelWFHRequest($mysqli, $userID, $requestID, $remarks)
{
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //save the remarks
    $sqlRemarks = "INSERT INTO remarks(`createdBy`,`remarks`,`createdDateTime`)  VALUES(:userid, :remarks, NOW())";
    $sqlRemarkstStmt = $mysqli->prepare($sqlRemarks);
    $sqlRemarkstStmt->execute([
      ':userid' => $userID,
      ':remarks' => $remarks
    ]);
    //get the ID of the remarks that you just saved
    $addedRemarksID = $mysqli->lastInsertId();

    $sqlRequest = "UPDATE wfh_requests SET cancelled = true, cancelledBy = :userid, cancelledDateTime = NOW(), cancelledRemarks =:remarksid, requestStatus = 'cancelled' WHERE id = :requestid";
    $sqlRequestStmt = $mysqli->prepare($sqlRequest);
    $sqlRequestStmt->execute([
      ':userid' => $userID,
      ':requestid' => $requestID,
      ':remarksid' => $addedRemarksID
    ]);

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function acceptWFHomeRequest($mysqli, $userID, $requestID, $remarks)
{
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //save the remarks
    $sqlRemarks = "INSERT INTO remarks(`createdBy`,`remarks`,`createdDateTime`)  VALUES(:userid, :remarks, NOW())";
    $sqlRemarkstStmt = $mysqli->prepare($sqlRemarks);
    $sqlRemarkstStmt->execute([
      ':userid' => $userID,
      ':remarks' => $remarks
    ]);
    //get the ID of the remarks that you just saved
    $addedRemarksID = $mysqli->lastInsertId();

    //update the request 
    $sqlUpdateWFHRequest = "UPDATE wfh_requests SET accepted = true, acceptedBy =:userid, acceptedDateTime = NOW(), acceptedRemarks =:remarksid, requestStatus = 'accepted' WHERE id = :requestid";
    $sqlUpdateWFHRequestStmt = $mysqli->prepare($sqlUpdateWFHRequest);
    $sqlUpdateWFHRequestStmt->execute([
      ':userid' => $userID,
      ':requestid' => $requestID,
      ':remarksid' => $addedRemarksID
    ]);

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function acceptByHRWFHomeRequest($mysqli, $userID, $requestID, $remarks)
{
  //UPDATED THIS CODE
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    $sqlRemarks = "INSERT INTO remarks(`createdBy`,`remarks`,`createdDateTime`)  VALUES(:userid, :remarks, NOW())";
    $sqlRemarkstStmt = $mysqli->prepare($sqlRemarks);
    $sqlRemarkstStmt->execute([
      ':userid' => $userID,
      ':remarks' => $remarks
    ]);

    $addedRemarksID = $mysqli->lastInsertId();

    //update the request
    $sqlUpdateWFHRequest = "UPDATE wfh_requests SET acceptedByHR = true, acceptedByHRID =:userid, acceptedByHRDateTime = NOW(), acceptedByHRRemarks =:remarksid WHERE id = :requestid";
    $sqlUpdateWFHRequestStmt = $mysqli->prepare($sqlUpdateWFHRequest);
    $sqlUpdateWFHRequestStmt->execute([
      ':userid' => $userID,
      ':requestid' => $requestID,
      ':remarksid' => $addedRemarksID
    ]);

    //save the remarks on wfh_requestremarks, a request can have unlimited number of remarks beneath it
    // $sqlWFHRequestRemarks = "INSERT INTO wfh_requestremarks(`requestID`,`remarksID`) VALUES(:requestid, :remarksid)";
    // $sqlsqlWFHRequestRemarksStmt = $mysqli->prepare($sqlWFHRequestRemarks);
    // $sqlsqlWFHRequestRemarksStmt->execute([
    //   ':requestid' => $requestID,
    //   ':remarksid' => $addedRemarksID
    // ]);

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function approveWFHomeRequest($mysqli, $userID, $requestorID, $requestID, $remarks)
{
  // echo "<script>alert('asdfasf');</script>";
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //create remarks
    $sqlRemarks = "INSERT INTO remarks(`createdBy`,`remarks`,`createdDateTime`)  VALUES(:userid, :remarks, NOW())";
    $sqlRemarkstStmt = $mysqli->prepare($sqlRemarks);
    $sqlRemarkstStmt->execute([
      ':userid' => $userID,
      ':remarks' => $remarks
    ]);
    $addedRemarksID = $mysqli->lastInsertId();


    //change the status of the request to approved
    $sqlRequest = "UPDATE wfh_requests SET requestStatus = 'approved', approved = true, approvedBy = :userid, approvedDateTime = NOW(), approvedRemarks = :remarksid WHERE id = :requestid";
    $sqlRequestStmt = $mysqli->prepare($sqlRequest);
    $sqlRequestStmt->execute([
      ':userid' => $userID,
      ':requestid' => $requestID,
      ':remarksid' => $addedRemarksID
    ]);


    //create wfh
    $sqlWFH = "INSERT INTO wfh_workfromhome(`request`,`wfhStatus`,`userID`,createdDateTime)  VALUES(:requestid,'active',:userid, NOW())";
    $sqlWFHStmt = $mysqli->prepare($sqlWFH);
    $sqlWFHStmt->execute([
      ':requestid' => $requestID,
      ':userid' => $requestorID
    ]);


    $addedWFHID = $mysqli->lastInsertId();

    //insert dates to wfh_workfromhomedates based on select statement on wfh_requestdates, which includes a constant value for wfhID
    //notes: https://bit.ly/2RX1v6F
    $sqlInsertWFHDates = "INSERT INTO wfh_workfromhomedates(`wfhID`,`wfhDate`) SELECT :addedwfhid, requestDate FROM wfh_requestdates WHERE requestID =:requestid";
    $sqlInsertWFHDatestStmt = $mysqli->prepare($sqlInsertWFHDates);
    $sqlInsertWFHDatestStmt->execute([
      ':addedwfhid' => $addedWFHID,
      ':requestid' => $requestID
    ]);

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('Error ocurred while trying to approve request');</script>";
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function declineWFHRequest($mysqli, $userID, $requestID, $remarks)
{
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    $sqlRemarks = "INSERT INTO remarks(`createdBy`,`remarks`,`createdDateTime`)  VALUES(:userid, :remarks, NOW())";
    $sqlRemarkstStmt = $mysqli->prepare($sqlRemarks);
    $sqlRemarkstStmt->execute([
      ':userid' => $userID,
      ':remarks' => $remarks
    ]);
    $addedRemarksID = $mysqli->lastInsertId();

    $sqlUpdateWFHRequest = "UPDATE wfh_requests SET declined = true, declinedBy =:userid, declinedDateTime = NOW(), declinedRemarks=:remarksid, requestStatus = 'declined' WHERE id = :requestid";
    $sqlUpdateWFHRequestStmt = $mysqli->prepare($sqlUpdateWFHRequest);
    $sqlUpdateWFHRequestStmt->execute([
      ':userid' => $userID,
      ':requestid' => $requestID,
      ':remarksid' => $addedRemarksID
    ]);
    //I should use $count = $sqlUpdateWFHRequestStmt->rowCount() right after execute in order to really determine if the update operation did succeed or changed some data.
    //link: https://www.php.net/manual/en/pdostatement.execute.php


    //save the remarks on wfh_requestremarks, a request can have unlimited number of remarks beneath it
    // $sqlWFHRequestRemarks = "INSERT INTO wfh_requestremarks(`requestID`,`remarksID`) VALUES(:requestid, :remarksid)";
    // $sqlsqlWFHRequestRemarksStmt = $mysqli->prepare($sqlWFHRequestRemarks);
    // $sqlsqlWFHRequestRemarksStmt->execute([
    //   ':requestid' => $requestID,
    //   ':remarksid' => $addedRemarksID
    // ]);

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function hasValidWorkFromHomeToday($mysqli, $userID)
{
  $sql = "SELECT wfhDateID FROM view_workfromhomeinfo WHERE userID = :userid AND wfhDate = CURRENT_DATE";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchColumn() ? true : false;
}

function getWorkFromHomeToday($mysqli, $userID)
{
  //make sure that there's no duplicate in the date (e.g. userID 99 - Date 1/1/21 and userID 99 - Date 1/1/21)
  $sql = "SELECT * FROM view_workfromhomeinfo WHERE userID = :userid AND wfhDate = CURRENT_DATE LIMIT 1";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function hasValidWorkFromHome($mysqli, $userID)
{
  $sql = "SELECT wfhDate FROM view_workfromhomeinfo WHERE wfhStatus= 'active' AND userID = :userid AND wfhDate >= CURRENT_DATE ORDER BY wfhDate ASC LIMIT 1";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchColumn();
}


function getNextWFHDate($mysqli, $userID)
{
  $sql = "SELECT wfhDate FROM view_workfromhomeinfo WHERE userID = :userid AND wfhDate > CURRENT_DATE ORDER BY wfhDate ASC LIMIT 1";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAccomplishmentByID($mysqli, $accomplishmentID)
{
  $sql = "SELECT * FROM accomplishment WHERE id = :accomplishmentid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':accomplishmentid' => $accomplishmentID
  ]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserIDByAccomplishmentID($mysqli, $accomplishmentID)
{
  $sql = "SELECT userID FROM accomplishment WHERE id = :accomplishmentid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':accomplishmentid' => $accomplishmentID
  ]);
  return $stmt->fetch(PDO::FETCH_COLUMN);
}

function hasEmptyAccomplishmentToday($mysqli, $userID)
{
  $sql = "SELECT wfhDateID FROM view_workfromhomeinfo WHERE userID = :userid AND wfhDate = CURRENT_DATE AND accomplishmentID IS NULL";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchColumn();
}

function addAccomplishmentActivity($mysqli, $userID, $accomplishmentID, $description, $links, $members, $attachment = null)
{
  try {
    $attachmentID = NULL;
    $activityID = NULL;

    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();


    if ($attachment != null) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $attachment, $userID);
      $sqlInsertAttachmentStmt->execute();
      $attachmentID = $mysqli->lastInsertId();
    }

    //save the activity
    $sqlAccomplishmentActivity = "INSERT INTO accomplishmentactivities(`accomplishmentID`,`description`,`DateTimeCreated`, `attachmentID`)  VALUES(:accomplishmentid, :activitydescription, NOW(), :attachmentid)";

    $sqlAccomplishmentActivityStmt = $mysqli->prepare($sqlAccomplishmentActivity);
    $sqlAccomplishmentActivityStmt->execute([
      ':accomplishmentid' => $accomplishmentID,
      ':activitydescription' => $description,
      ':attachmentid' => $attachmentID
    ]);

    //get the ID of the saved activity
    $activityID = $mysqli->lastInsertId();

    //upload the attachment to server
    if ($attachment != null) {
      Attachment::Upload($attachment, '../uploads/accomplishment/', $userID, $attachmentID);
    }

    //checks if links are set in the UI
    if ($links) {
      //construct the array parameter for our PDOMultiInsert 
      $linksObj = array();

      foreach ($links as $link) {
        $linksObj[] = array(
          'activityID' => $activityID,
          'caption' => empty($link['caption']) ? 'my link' : $link['caption'],
          'url' => $link['url']
        );
      }

      //save all of the links using our custom multi-insert function
      $insertDatesStmt = PDOMultiInsert::constructStatement($mysqli, 'activity_links', $linksObj);
      $insertDatesStmt->execute();
    }

    //checks if members are set in the UI
    if ($members) {
      $membersObj = array();

      foreach ($members as $member) {
        $membersObj[] = array(
          'activityID' => $activityID,
          'userID' => $member
        );
      }

      $insertDatesStmt = PDOMultiInsert::constructStatement($mysqli, 'activity_taggedusers', $membersObj);
      $insertDatesStmt->execute();
    }


    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function addAccomplishment($mysqli, $userID)
{
  $sqlInsert = "INSERT INTO accomplishment(`userID`,`timeIn`,`createdDateTime`)  VALUES(:userid, NOW(), NOW())";

  $stmt = $mysqli->prepare($sqlInsert);
  $stmt->execute([
    ':userid' => $userID
  ]);
}

function getActivityLinks($mysqli, $activityID)
{
  $sql = "SELECT * FROM activity_links WHERE activityID = :activityid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':activityid' => $activityID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getActivityTaggedUsers($mysqli, $activityID)
{
  $sql = "SELECT * FROM activity_taggedusers WHERE activityID = :activityid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':activityid' => $activityID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addAccomplishmentTimeIn($mysqli, $userID, $workFromHomeDateID)
{
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    $sqlAccomplishment = "INSERT INTO accomplishment(`userID`,`timeIn`,`createdDateTime`)  VALUES(:userid, NOW(), NOW())";

    $sqlAccomplishmentStmt = $mysqli->prepare($sqlAccomplishment);
    $sqlAccomplishmentStmt->execute([
      ':userid' => $userID
    ]);
    $addedAccomplishmentID = $mysqli->lastInsertId();

    $sqlUpdateWFHDate = "UPDATE wfh_workfromhomedates SET accomplishmentID = :acccomplishmentid WHERE id=:workfromhomedateid";

    $sqlUpdateWFHDateStmt = $mysqli->prepare($sqlUpdateWFHDate);
    $sqlUpdateWFHDateStmt->execute([
      ':acccomplishmentid' => $addedAccomplishmentID,
      ':workfromhomedateid' => $workFromHomeDateID
    ]);

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function timeOutAccomplishment($mysqli, $accomplishmentID)
{
  $query = "UPDATE accomplishment SET timeOut = NOW() WHERE id=:accomplishmentid";
  $stmt = $mysqli->prepare($query);
  return $stmt->execute([
    ':accomplishmentid' => $accomplishmentID
  ]);
}

function getUsernameByID($mysqli, $userID)
{
  $sql = "SELECT username FROM credentials WHERE userID = :userid LIMIT 1";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchColumn();
}

function getRoleByuserID($mysqli, $userID)
{

  $sql = "SELECT `role`.`role` FROM `role` INNER JOIN `credentials` ON (`credentials`.`role` = `role`.`roleID`) WHERE `credentials`.`userid` = :userid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchColumn();
}

function getUserInformationByID($mysqli, $userID)
{
  $sql = "SELECT * FROM view_employeeinfo WHERE userID = :userid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}



function getAllUsers($mysqli)
{
  $sql = "SELECT * FROM view_employeeinfo WHERE disabled != TRUE ORDER BY lastName Asc";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllUsersExcludingCurrentUser($mysqli, $userID)
{
  $sql = "SELECT * FROM view_employeeinfo WHERE disabled != TRUE AND userID != :userid ORDER BY userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getActivitiesByAccomplishmentID($mysqli, $accomplishmentID)
{
  $sql = "SELECT * FROM accomplishmentactivities WHERE accomplishmentID = :accomplishmentid ORDER BY DateTimeCreated";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':accomplishmentid' => $accomplishmentID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getActivityCountByAccomplishmentID($mysqli, $accomplishmentID)
{
  $sql = "SELECT COUNT(id) AS ActivityCount FROM `accomplishmentactivities` WHERE accomplishmentID = :accomplishmentid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':accomplishmentid' => $accomplishmentID
  ]);

  return $stmt->fetchColumn();
}

function getTaggedActivitiesByUserIDBydate($mysqli, $userID, $date)
{
  $sql = "SELECT * FROM view_taggedactivities WHERE taggedUserID = :userid AND DATE(DateTimeCreated) = :datenow ORDER BY DateTimeCreated";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID,
    ':datenow' => $date
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRemarksByID($mysqli, $remarksID)
{
  $sql = "SELECT * FROM remarks  WHERE id = :remarksid LIMIT 1";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':remarksid' => $remarksID
  ]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getMyRequests($mysqli, $userID)
{
  $sql = "SELECT * FROM wfh_requests WHERE userID = :userid ORDER BY id DESC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAttachmentByID($mysqli, $attachmentID)
{
  $sql = "SELECT * FROM attachments WHERE id = :attachmentid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':attachmentid' => $attachmentID
  ]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAccomplishmentsByDivision($mysqli, $date, $userIDs, $division)
{
  $params = [
    ':datenow' => $date,
    ':division' => $division
  ];

  $in = "";
  $i = 0; //we'll be using external counter even though we're using foreach because the actual array key could be dangerous.

  foreach ($userIDs as $id) {
    $key = ":id" . $i++;
    $in .= "$key,";
    $in_params[$key] = $id; //were constructing values into key-value pair
  }

  $in = rtrim($in, ","); //sample output :id0, :id1, :id2

  $sql = "SELECT * FROM view_employeeaccomplishments WHERE userID IN($in) AND divisionID = :division AND DATE(createdDateTime) = :datenow ORDER BY timeIn";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array_merge($params, $in_params));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getAllAccomplishmentsByDivision($mysqli, $userIDs, $division)
{
  $params = [
    ':division' => $division
  ];

  $in = "";
  $i = 0; //we'll be using external counter even though we're using foreach because the actual array key could be dangerous.

  foreach ($userIDs as $id) {
    $key = ":id" . $i++;
    $in .= "$key,"; //TODO: check if the old server will accept this code
    $in_params[$key] = $id; //were constructing values into key-value pair
  }

  $in = rtrim($in, ","); //sample output :id0, :id1, :id2

  $sql = "SELECT * FROM view_employeeaccomplishments WHERE userID IN($in) AND divisionID = :division ORDER BY timeIn DESC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array_merge($params, $in_params));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAccomplishments($mysqli, $userIDs)
{

  $in = "";
  $i = 0; //we'll be using external counter even though we're using foreach because the actual array key could be dangerous.

  foreach ($userIDs as $id) {
    $key = ":id" . $i++;
    $in .= "$key,"; //TODO: check if the old server will accept this code
    $in_params[$key] = $id; //were constructing values into key-value pair
  }

  $in = rtrim($in, ","); //sample output :id0, :id1, :id2

  $sql = "SELECT * FROM view_employeeaccomplishments WHERE userID IN($in) ORDER BY timeIn DESC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute($in_params);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAccomplishmentsByUserID($mysqli, $userID)
{
  $sql = "SELECT * FROM view_employeeaccomplishments WHERE userID = :userid ORDER BY timeIn DESC";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllAccomplishmentsByDate($mysqli, $date, $userIDs)
{
  $params = [
    ':datenow' => $date
  ];

  $in = "";
  $i = 0; //we'll be using external counter even though we're using foreach because the actual array key could be dangerous.

  foreach ($userIDs as $id) {
    $key = ":id" . $i++;
    $in .= "$key,"; //TODO: check if the old server will accept this code
    $in_params[$key] = $id; //were constructing values into key-value pair
  }

  $in = rtrim($in, ","); //sample output :id0, :id1, :id2

  $sql = "SELECT * FROM view_employeeaccomplishments WHERE userID IN($in) AND DATE(createdDateTime) = :datenow ORDER BY timeIn DESC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array_merge($params, $in_params));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAccomplishmentsByDateByUserID($mysqli, $date, $userID)
{
  $sql = "SELECT * FROM view_employeeaccomplishments WHERE userID = :userid AND DATE(createdDateTime) = :datenow ORDER BY timeIn DESC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':datenow' => $date,
    ':userid' => $userID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function testFunction($mysqli, $userIDs, $division)
{
  $params = [
    ':division' => $division
  ];

  $in = "";
  $i = 0; //we'll be using external counter even though we're using foreach because the actual array key could be dangerous.

  foreach ($userIDs as $id) {
    $key = ":id" . $i++;
    $in .= "$key,";
    $in_params[$key] = $id; //were constructing values into key-value pair
  }

  $in = rtrim($in, ","); //sample output :id0, :id1, :id2

  // $sql = "SELECT * FROM view_employeeaccomplishments WHERE userID IN(" . implode(",", $users) . ") AND divisionID = :division";
  $sql = "SELECT * FROM view_employeeaccomplishments WHERE userID IN($in) AND divisionID = :division";
  // $sql = "SELECT * FROM Accomplishment WHERE userID = '99'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array_merge($params, $in_params));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function testQueryThatUsesSelectInsideIN($mysqli, $userID, $division)
{
  $sql = "SELECT * FROM Accomplishment WHERE userID IN(SELECT userID FROM credentials WHERE userID != :userid  AND division = :division ) ORDER BY timeIn";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':userid' => $userID,
    ':division' => $division
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addRequestRemarks($mysqli, $userID, $requestID, $remarks)
{
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    $sqlRemarks = "INSERT INTO remarks(`createdBy`,`remarks`,`createdDateTime`)  VALUES(:userid, :remarks, NOW())";
    $sqlRemarkstStmt = $mysqli->prepare($sqlRemarks);
    $sqlRemarkstStmt->execute([
      ':userid' => $userID,
      ':remarks' => $remarks
    ]);
    $addedRemarksID = $mysqli->lastInsertId();

    // save the remarks on wfh_requestremarks, a request can have unlimited number of remarks beneath it
    $sqlWFHRequestRemarks = "INSERT INTO wfh_requestremarks(`requestID`,`remarksID`) VALUES(:requestid, :remarksid)";
    $sqlsqlWFHRequestRemarksStmt = $mysqli->prepare($sqlWFHRequestRemarks);
    $sqlsqlWFHRequestRemarksStmt->execute([
      ':requestid' => $requestID,
      ':remarksid' => $addedRemarksID
    ]);

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}


function getAllRequestRemarksByRequestID($mysqli, $requestID)
{
  $sql = "SELECT * FROM wfh_requestremarks  WHERE requestID  = :requestid";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':requestid' => $requestID
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


//  ============== END WFH ==============


//  ============== S T A R T  T R A V E L   A N D  L E A V E ==============
function checkPDS($mysqli, $user)
{
  $temp = array();
  $sql = "SELECT * FROM personal_info WHERE userID=:userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":userID" => $user));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END CHECK PDS OF USER

function getDivision($mysqli, $divisionID)
{
  $temp = array();
  $sql = "SELECT * FROM division WHERE divisionID = :divisionID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':divisionID' => $divisionID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} //END GET DIVISION OF USER

function getSection($mysqli, $sectionID)
{
  $temp = array();
  $sql = "SELECT * FROM section WHERE sectionID = :sectionID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':sectionID' => $sectionID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET SECTION OF USER

function getUsername($mysqli, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM credentials WHERE userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

//  ============== E N D  T R A V E L   A N D  L E A V E ==============


//  ============== S T A R T  T R A V E L ==============

function checkApplicationTravel($mysqli, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT * FROM travel_companions WHERE companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':userID' => $userID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} //END CHECK IF THERE IS A PENDING UNLIQUIDATED TRAVEL

function getEmployees($mysqli)
{
  $sql = "SELECT * FROM view_employeeinfo WHERE firstName != '' AND lastName != '' AND gender != '' AND userID != :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $_SESSION['userID']));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEmployeesAll($mysqli)
{
  $sql = "SELECT * FROM view_employeeinfo WHERE firstName != '' AND lastName != '' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveTravelApplication($mysqli, $rank, $userID, $employeeID, $division, $section, $LNA, $MNA, $FNA, $position, $station, $salary, $perMonth, $departureDate, $returnDate, $choiceDestination, $flightStatus, $requirements, $destination, $purpose, $objective, $sectionHead, $divisionHead, $oad, $od, $personnel)
{

  $temp = array();

  $sql = "INSERT INTO travel_application(`rank`,`userID`,`employeeID`,`division`,`section`,`dateFiled`,`lastName`,`middleName`,`firstName`,`position`,`station`,`departureDate`,`returnDate`,`choiceDestination`,`flightStatus`,`requirements`,`destination`,`specificPurpose`,`objective`,`sectionHead`,`divisionHead`,`od`,`oad`,`personnel`) VALUES(:rank,:userID,:employeeID,:division,:section,NOW(),:lastName,
        :middleName,:firstName,:position,:station,:departureDate,:returnDate,:choiceDestination,:flightStatus,:requirements,:destination,:purpose,:objective,:sectionHead,:divisionHead,:od,:oad,:personnel)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':rank' => $rank,
      ':userID' => $_SESSION['userID'],
      ':employeeID' => $employeeID,
      ':division' => $division,
      ':section' => $section,
      ':lastName' => $LNA,
      ':middleName' => $MNA,
      ':firstName' => $FNA,

      ':position' => $position,
      ':station' => $station,
      // ':salary' => $salary,
      // ':perMonth' => $perMonth,

      ':departureDate' => $departureDate,
      ':returnDate' => $returnDate,
      ':choiceDestination' => $choiceDestination,
      ':flightStatus' => $flightStatus,
      ':requirements' => $requirements,
      ':destination' => $destination,

      ':purpose' => $purpose,
      ':objective' => $objective,

      ':sectionHead' => $sectionHead,
      ':divisionHead' => $divisionHead,
      ':oad' => $oad,
      ':od' => $od,
      ':personnel' => $personnel
    )
  );

  $sql = "SELECT * FROM travel_application WHERE dateFiled = NOW()";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END SAVE TRAVEL APPLICATION

function getLeaderTravelID($mysqli)
{
  $temp = array();
  $sql = "SELECT a.*, b.* FROM travel_application a, travel_companions b WHERE a.dateFiled = b.dateFiled";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function saveCompanion($mysqli, $leaderTID, $leaderUserID, $station, $departureDate, $returnDate, $choiceDestination, $flightStatus, $requirements, $destination, $purpose, $objective)
{
  $rank = 1;
  for ($i = 1; $i <= 50; $i++) {

    if (!isset($_POST['companion' . $i])) continue;
    if (!isset($_POST['positionCompanion' . $i])) continue;

    $companionID = $_POST['companion' . $i];
    $positionCompanion = $_POST['positionCompanion' . $i];

    $details = getCompanionDetails($mysqli, $companionID);
    foreach ($details as $key) {
      $firstName = $key['firstName'];
      $middleName = $key['middleName'];
      $lastName = $key['lastName'];
      $companionDivision = $key['division'];
      $companionSection = $key['section'];
      $companionUserID = $key['userID'];
      $companionEmployeeID = $key['employeeID'];

      $username = $key['username'];
      $gRole = getRole($mysqli, $username);
      foreach ($gRole as $key2) {
        $role = $key2['role'];
      }

      if ($key['division'] == 'OFFICE OF THE ASSISTANT DIRECTOR - PROGRAMS') {
        // F O R  A S S I S T A N T  D I R E C T O R
        if ($role == 7) {
          $sectionHead = 'initial';
          $divisionHead = 'initial';
          $oad = 'approved';
          $od = 'pending';
          $personnel = 'pending';
        }
        if ($role != 7 && $_SESSION['role'] != 7 && $_SESSION['role'] != 4) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'pending';
          $od = 'pending';
          $personnel = 'pending';
        }
        if ($role != 7 && $_SESSION['role'] == 7) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'approved';
          $od = 'pending';
          $personnel = 'pending';
        }
        if ($role != 7 && $_SESSION['role'] == 4) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'pending';
          $od = 'approved';
          $personnel = 'pending';
        }
      }

      if ($key['division'] == 'OFFICE OF THE DIRECTOR') {
        // F O R  D I R E C T O R
        if ($role == 4) {
          $sectionHead = 'initial';
          $divisionHead = 'initial';
          $oad = 'approved';
          $od = 'approved';
          $personnel = 'approved';
        }
        if ($role != 4 && $_SESSION['role'] != 4 && $_SESSION['role'] != 7) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'pending';
          $od = 'pending';
          $personnel = 'pending';
        }
        if ($role != 4 && $_SESSION['role'] == 7) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'pending';
          $od = 'approved';
          $personnel = 'pending';
        }
        if ($role != 4 && $_SESSION['role'] == 4) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'approved';
          $od = 'approved';
          $personnel = 'pending';
        }
      }

      if ($key['division'] != 'OFFICE OF THE DIRECTOR' && $key['division'] != 'OFFICE OF THE ASSISTANT DIRECTOR - PROGRAMS') {
        if ($role == 2) {
          $sectionHead = 'initial';
          $divisionHead = 'initial';
          $oad = 'pending';
          $od = 'pending';
          $personnel = 'pending';
        }
        if ($role != 2 && $_SESSION['role'] != 7 && $_SESSION['role'] != 4) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'pending';
          $od = 'pending';
          $personnel = 'pending';
        }
        if ($role != 2 && $_SESSION['role'] == 7) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'approved';
          $od = 'pending';
          $personnel = 'pending';
        }
        if ($role != 2 && $_SESSION['role'] == 4) {
          $sectionHead = 'initial';
          $divisionHead = 'pending';
          $oad = 'approved';
          $od = 'approved';
          $personnel = 'pending';
        }
      }
    } //  END FOREACH

    $sql = "INSERT INTO travel_companions(`leaderTravelID`,`leaderUserID`,`dateFiled`,`division`,`section`,`rank`,`companionUserID`,`companionEmployeeID`,`firstName`,`middleName`,`lastName`,`position`,`station`,`departureDate`,`returnDate`,`choiceDestination`,`flightStatus`,`requirements`,`destination`,`specificPurpose`,`objective`,`sectionHead`,`divisionHead`,`od`,`oad`,`personnel`)
      VALUES (:leaderTravelID,:leaderUserID,NOW(),:division,:section,:rank,:companionUserID,:companionEmployeeID,:firstName,:middleName,:lastName,:positionCompanion,:station,:departureDate,:returnDate,:choiceDestination,:flightStatus,:requirements,:destination,:purpose,:objective,:sectionHead,:divisionHead,:od,:oad,:personnel)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':leaderTravelID' => $leaderTID,
        ':leaderUserID' => $leaderUserID,
        ':division' => $companionDivision,
        ':section' => $companionSection,
        ':rank' => $rank,
        ':companionUserID' => $companionUserID,
        ':companionEmployeeID' => $companionEmployeeID,
        ':firstName' => $firstName,
        ':middleName' => $middleName,
        ':lastName' => $lastName,
        ':positionCompanion' => $positionCompanion,
        ':station' => $station,
        // ':salary' => $salary,
        // ':perMonth' => $perMonth,

        ':departureDate' => $departureDate,
        ':returnDate' => $returnDate,
        ':choiceDestination' => $choiceDestination,
        ':flightStatus' => $flightStatus,
        ':requirements' => $requirements,
        ':destination' => $destination,

        ':purpose' => $purpose,
        ':objective' => $objective,

        ':sectionHead' => $sectionHead,
        ':divisionHead' => $divisionHead,
        ':oad' => $oad,
        ':od' => $od,
        ':personnel' => $personnel

      )
    );

    $rank++;
  }
}

function checkCompanion($mysqli, $travelID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_companions WHERE leaderTravelID LIKE '$travelID' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function updateLeader($mysqli, $status, $id)
{
  $sql = "UPDATE travel_application SET
      companion = :companion 
      WHERE id =:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":companion" => $status,
      ":id" => $id
    )
  );
}

function getCompanionDetails($mysqli, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM view_employeeinfo WHERE userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // GET NAME FROM VIEW_EMPLOYEEINFO USING USERID

function getRole($mysqli, $username)
{
  $temp = array();
  $sql = "SELECT * FROM credentials WHERE username = :username";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':username' => $username));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // GET NAME FROM VIEW_EMPLOYEEINFO USING USERID

function companion($mysqli, $travelID, $companionUserID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_companions WHERE companionUserID = :userID AND leaderTravelID = :travelID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':travelID' => $travelID, ':userID' => $companionUserID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllCompanions($mysqli, $travelID, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE id = :travelID AND userID != :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':travelID' => $travelID, ':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT * FROM travel_companions WHERE leaderTravelID = :travelID AND companionUserID != :userID AND od = 'approved'";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':travelID' => $travelID, ':userID' => $userID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);

  return $temp;
}

function getCompanionPerTravelID($mysqli, $leaderTravelID, $companionUserID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_companions WHERE leaderTravelID = :leaderTID AND companionUserID != :companionUserID";

  // $sql = "SELECT * FROM view_travelwcompanions WHERE id = :leaderTID AND userID != :companionUserID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":leaderTID" => $leaderTravelID, ":companionUserID" => $companionUserID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getApprovedCompanionPerTravelID($mysqli, $leaderTravelID, $companionUserID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_companions WHERE leaderTravelID = :leaderTID AND companionUserID != :companionUserID AND divisionHead = 'initial' AND od = 'approved'";

  // $sql = "SELECT * FROM view_travelwcompanions WHERE id = :leaderTID AND userID != :companionUserID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":leaderTID" => $leaderTravelID, ":companionUserID" => $companionUserID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getCompanionPerTravelIDApprovedDH($mysqli, $leaderTravelID, $companionUserID)
{
  $temp = array();
  // $sql = "SELECT * FROM travel_companions WHERE leaderTravelID = :leaderTID AND companionUserID != :companionUserID AND (divisionHead = :status OR oad = :status OR od = :status)";

  $sql = "SELECT * FROM travel_companions WHERE leaderTravelID = :leaderTID AND companionUserID != :companionUserID ";

  // $sql = "SELECT * FROM view_travelwcompanions WHERE id = :leaderTID AND userID != :companionUserID";
  $stmt = $mysqli->prepare($sql);
  // $stmt->execute(array(":leaderTID" => $leaderTravelID,":companionUserID" => $companionUserID,":status" => 'initial'));
  $stmt->execute(array(":leaderTID" => $leaderTravelID, ":companionUserID" => $companionUserID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


function getLeader($mysqli, $leaderTravelID, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE id = :leaderTID AND userID != :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":leaderTID" => $leaderTravelID, ":userID" => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getMyTravel($mysqli, $userID)
{
  $temp = array();
  $sql = "SELECT a.*,b.role as role FROM travel_application a,credentials b WHERE a.userID = b.userID AND a.userID = :userID GROUP BY a.id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT a.travelOrderNo as travelOrderNo,a.firstName as firstName, a.middleName as middleName, a.lastName as lastName, a.dateFiled as dateFiled,a.division as division,a.sectionHead as sectionHead,a.divisionHead as divisionHead,a.oad as oad,a.od as od,a.personnel as personnel,a.destination as destination,a.choiceDestination as choiceDestination,a.requirements as requirements,a.flightStatus as flightStatus,a.leaderTravelID as id,a.departureDate as departureDate,a.returnDate as returnDate,a.companionUserID as userID, b.role as role FROM travel_companions a, credentials b WHERE 
      a.companionUserID = :userID  GROUP BY leaderTravelID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':userID' => $userID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // END GET MY OWN TRAVEL DETAILS


function getMyDetails($mysqli, $ID, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE id = :ID AND userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':ID' => $ID, ':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT a.travelOrderNO as travelOrderNo,a.firstName as firstName, a.middleName as middleName, a.lastName as lastName, a.dateFiled as dateFiled,a.division as division,a.sectionHead as sectionHead,a.divisionHead as divisionHead,a.oad as oad,a.od as od,a.personnel as personnel,a.destination as destination,a.leaderTravelID as id,a.departureDate as departureDate,a.returnDate as returnDate,a.position as position, a.station as station, a.choiceDestination as choiceDestination, a.flightStatus as flightStatus,a.comment as comment, a.specificPurpose as specificPurpose,a.objective as objective, a.remarks as remarks,a.companionUserID as userID, a.requirements as requirements,companionEmployeeID as employeeID, b.role as role FROM travel_companions a, credentials b WHERE 
      a.leaderTravelID = :ID AND companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':ID' => $ID, ':userID' => $userID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);

  return $temp;
} // END GET OWN TRAVEL DETAILS PER TRAVEL ID

function getMyDetailsAsCompanion($mysqli, $ID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_companions WHERE companionID = :ID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':ID' => $ID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET OWN TRAVEL DETAILS PER TRAVEL ID AS COMPANION

function getDHNew($mysqli, $userID, $status, $division, $id)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE userID = :userID AND division = :division AND (divisionHead = :divisionHead OR flightStatus = 'booked') AND id = :id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID, ':divisionHead' => $status, ':division' => $division, ':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT * FROM travel_companions WHERE companionUserID = :userID AND division = :division AND (divisionHead = :divisionHead OR flightStatus = 'booked') AND leaderTravelID = :id";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':userID' => $userID, ':divisionHead' => $status, ':division' => $division, ':id' => $id));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);

  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR DH

function getOADNew($mysqli, $userID, $status, $division, $id)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE userID = :userID AND (oad = :oad OR flightStatus = 'booked') AND id =  :id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID, ':oad' => $status, ':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  // $sql2 = "SELECT * FROM view_travelwcompanions WHERE employeeID = :employeeID AND oad = :oad";
  $sql2 = "SELECT *,companionUserID as userID FROM travel_companions WHERE companionUserID = :companionUserID AND divisionHead = :oad AND division = :division AND leaderTravelID = :id";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':companionUserID' => $userID, ':oad' => $status, ':division' => $division, ':id' => $id));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR OAD

// function getODNew($mysqli,$employeeID,$id,$status,$division){
//   $temp = array();
//   $sql = "SELECT * FROM travel_application WHERE employeeID = :employeeID AND id = :id AND od = :od ";
//   $stmt = $mysqli->prepare($sql);
//   $stmt->execute(array(':employeeID' => $employeeID,':id' => $id,':od' => $status));
//   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     $temp[] = $row;
//   }

//   $temp2 = array();
//   // $sql2 = "SELECT * FROM view_travelwcompanions WHERE employeeID = :employeeID AND od = :od";
//   $sql2 = "SELECT * FROM travel_companions WHERE companionEmployeeID = :employeeID AND leaderTravelID = :id AND divisionHead = :od AND division = :division ";
//   $stmt2 = $mysqli->prepare($sql2);
//   $stmt2->execute(array(':employeeID' => $employeeID,':id' => $id,':od' => $status,':division' => $division));
//   while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
//     $temp2[] = $row2;
//   }
//   $temp = array_merge($temp,$temp2);
//   return $temp;
// } // GET ALL THE PENDING TRAVEL APPLICATION FOR OD

function getODNew($mysqli, $employeeID, $id, $division)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE employeeID = :employeeID AND id = :id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  // $sql2 = "SELECT * FROM view_travelwcompanions WHERE employeeID = :employeeID AND od = :od";
  $sql2 = "SELECT * FROM travel_companions WHERE companionEmployeeID = :employeeID AND leaderTravelID = :id AND division = :division ";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':employeeID' => $employeeID, ':id' => $id, ':division' => $division));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR OD

function getHRNew($mysqli, $employeeID, $id, $status)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE employeeID = :employeeID AND id = :id AND personnel = :personnel";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':id' => $id, ':personnel' => $status));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR PERSONNEL

function getAccountingNew($mysqli, $employeeID, $id, $status)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE employeeID = :employeeID AND id = :id AND requirements = :requirements";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':id' => $id, ':requirements' => $status));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  // $sql2 = "SELECT * FROM view_travelwcompanions WHERE employeeID = :employeeID AND od = :od";
  $sql2 = "SELECT * FROM travel_companions WHERE companionEmployeeID = :employeeID AND leaderTravelID = :id AND requirements = :requirements";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':employeeID' => $employeeID, ':id' => $id, ':requirements' => $status));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR OD

// I N I T I A L  T R A V E L  O R D E R . P H P
function getSectionHeadTravelApplication($mysqli, $section, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.section = :section AND b.role != :role AND b.role != 8 GROUP BY userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':role' => $role));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET EMPLOYEES UNDER SECTION HEAD

function getDivisionHeadTravelApplication($mysqli, $division, $role)
{
  $temp = array();
  // $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.division = :division AND b.role != :role AND sectionHead = :status GROUP BY userID";

  // $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.division = :division AND b.role != :role AND sectionHead = :status GROUP BY id";

  $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.division = :division AND b.role != :role AND sectionHead = :status";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':division' => $division, ':role' => $role, ':status' => "initial"));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  // $temp2 = array();
  // $sql2 = "SELECT a.*,a.companionEmployeeID as employeeID, a.companionID as id, b.role FROM travel_companions a, credentials b WHERE a.companionUserID = b.userID AND sectionHead = :sectionHead AND 
  //   b.role != :role AND a.division = :division GROUP BY userID";

  // $stmt2 = $mysqli->prepare($sql2);
  // $stmt2->execute(array(':sectionHead' =>'initial',':role' => $role,':division' => $division));
  // while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
  //   $temp2[] = $row2;
  // }
  // $temp = array_merge($temp,$temp2);

  return $temp;
} // END GET EMPLOYEES UNDER DIVISION HEAD


// S E C T I O N . P H P
function companionCheck($mysqli, $leaderTravelID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_companions WHERE leaderTravelID = :leaderTID AND (divisionHead = 'initial' OR oad ='initial' OR od = 'initial') ";

  // $sql = "SELECT * FROM view_travelwcompanions WHERE id = :leaderTID AND userID != :companionUserID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":leaderTID" => $leaderTravelID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END CHECK IF ALL COMPANIONS ARE RECOMMENDED BY EACH DH

function getOADTravelApplication($mysqli, $division, $role)
{
  $temp = array();
  // $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND role != :role GROUP BY userID";

  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND role != :role";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':sectionHead' => 'initial', ':divisionHead' => 'initial', ':role' => $role));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  // $sql2 = "SELECT a.*,a.companionEmployeeID as employeeID,a.leaderTravelID as id,a.companionUserID as userID, b.role FROM travel_companions a, credentials b WHERE a.companionUserID = b.userID AND sectionHead = :sectionHead AND b.role != :role AND a.division = :division GROUP BY a.companionUserID";

  $sql2 = "SELECT a.*,a.companionEmployeeID as employeeID,a.leaderTravelID as id,a.companionUserID as userID, b.role FROM travel_companions a, credentials b WHERE a.companionUserID = b.userID AND sectionHead = :sectionHead AND b.role != :role AND a.division = :division";

  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':sectionHead' => 'initial', ':role' => $role, ':division' => $division));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // END GET APPLICATION PER SECTION WITH APPROVED OAD FOR OD 


function getODTravelApplication($mysqli, $section, $division)
{
  $temp = array();
  // $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.section = :section AND oad = :oad AND divisionHead = :divisionHead GROUP BY userID";
  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND oad = :oad AND divisionHead = :divisionHead AND a.userID != :user ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':oad' => 'approved', ':divisionHead' => 'initial', ':user' => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT a.*,a.companionEmployeeID as employeeID,a.leaderTravelID as id, a.companionUserID as userID FROM travel_companions a, credentials b WHERE a.companionUserID = b.userID AND a.section = :section AND a.division = :division ";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':section' => $section, ':division' => $division));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // END GET APPLICATION PER SECTION WITH APPROVED OAD FOR OD 


function getPersonnelTravelApplication($mysqli, $section, $role)
{
  $temp = array();

  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND b.role != :role AND sectionHead = :sectionHead AND divisionHead = :divisionHead  AND oad = :oad AND od = :od GROUP BY id";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':role' => $role, ':sectionHead' => 'initial', ':divisionHead' => 'initial', ':oad' => 'approved', ':od' => 'approved'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION PER SECTION FOR PERSONNEL


// T R A V E L  D E T A I L S . P H P
function getTravelSH($mysqli, $employeeID, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role ";

  // $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION FOR SH 

function getTravelDH($mysqli, $employeeID, $role, $travelID)
{
  $temp = array();
  // $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :status";

  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :status AND a.id = :id";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role, ':status' => "initial", ":id" => $travelID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT a.*, a.leaderTravelID as id, a.companionUserID as userID, a.companionEmployeeID as employeeID, a.travelOrderNo FROM travel_companions a, credentials b WHERE a.companionUserID = b.userID AND a.companionEmployeeID = :employeeID AND b.role != :role AND a.sectionHead = :sectionHead AND a.leaderTravelID = :id";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':employeeID' => $employeeID, ':role' => $role, ':sectionHead' => "initial", ":id" => $travelID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);

  return $temp;
} // END GET APPLICATION WITH APPROVED SH FOR DH 

function getTravelOAD($mysqli, $employeeID, $role, $travelID)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND a.id = :id";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role, ':sectionHead' => "initial", ':divisionHead' => "initial", ":id" => $travelID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT a.*, a.leaderTravelID as id, a.companionUserID as userID, a.companionEmployeeID as employeeID FROM travel_companions a, credentials b WHERE a.companionUserID = b.userID AND a.companionEmployeeID = :employeeID AND a.sectionHead = :sectionHead AND a.division = 'OFFICE OF THE ASSISTANT DIRECTOR' AND a.leaderTravelID = :id";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':employeeID' => $employeeID, ':sectionHead' => "initial", ":id" => $travelID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // END GET APPLICATION PER EMPLOYEE ID WITH APPROVED DH FOR OAD 

function getTravelOD($mysqli, $employeeID, $id)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND oad = :status AND id = :id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':sectionHead' => "initial", ':divisionHead' => "initial", ':status' => "approved", ':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT a.*, a.leaderTravelID as id, a.companionUserID as userID, a.companionEmployeeID as employeeID FROM travel_companions a, credentials b WHERE a.companionUserID = b.userID AND a.companionEmployeeID = :employeeID AND sectionHead = :sectionHead AND a.division = 'OFFICE OF THE DIRECTOR' AND leaderTravelID = :id";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':employeeID' => $employeeID, ':sectionHead' => "initial", ':id' => $id));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // END GET APPLICATION PER EMPLOYEE ID WITH APPROVED OAD FOR OD 

function getTravelPersonnel($mysqli, $employeeID, $role, $id)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND oad = :oad AND od = :status AND id = :id";

  // $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND oad = :oad AND od = :status";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role, ':sectionHead' => "initial", ':divisionHead' => "initial", ':oad' => "approved", ':status' => "approved", ':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET EMPLOYEES WITH APPROVED OD FOR PERSONNEL

function getTravelOutsideMM($mysqli, $employeeID)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM travel_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID";

  // $sql = "SELECT a.*, b.role FROM view_travelwcompanions a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


//  I N I T I A L  O R  A P P R O V A L
function saveInitialSH($mysqli, $idNum, $status)
{
  $sql = "UPDATE travel_application SET
      sectionHead=:SHStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":SHStatus" => $status
    )
  );
} // END SAVE INITIAL OF SECTION HEAD

function saveInitialDH($mysqli, $idNum, $userID, $status)
{
  $sql = "UPDATE travel_application SET
      divisionHead=:DHStatus
      WHERE id=:id AND userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":userID" => $userID,
      ":DHStatus" => $status
    )
  );
  $sql2 = "UPDATE travel_companions SET
      divisionHead=:DHStatus
      WHERE leaderTravelID=:id AND companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(
    array(
      ":id" => $idNum,
      ":userID" => $userID,
      ":DHStatus" => $status
    )
  );
} // END SAVE INITIAL OF DIVISION HEAD

function saveInitialCOD($mysqli, $idNum, $userID, $status)
{
  $sql2 = "UPDATE travel_companions SET
      divisionHead = :DHStatus
      WHERE leaderTravelID=:id AND companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(
    array(
      ":id" => $idNum,
      ":userID" => $userID,
      ":DHStatus" => $status
    )
  );
} // END SAVE INITIAL OF DIVISION HEAD

function saveInitialCOAD($mysqli, $idNum, $userID, $status)
{
  $sql2 = "UPDATE travel_companions SET
      divisionHead=:DHStatus
      WHERE leaderTravelID=:id AND companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(
    array(
      ":id" => $idNum,
      ":userID" => $userID,
      ":DHStatus" => $status
    )
  );
} // END SAVE INITIAL OF DIVISION HEAD

function saveApproveOAD($mysqli, $idNum, $status)
{
  $sql = "UPDATE travel_application SET
      oad=:OADStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":OADStatus" => $status
    )
  );
  $sql = "UPDATE travel_companions SET
      oad=:OADStatus
      WHERE leaderTravelID=:id AND divisionHead = :divisionHead";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":OADStatus" => $status,
      ":divisionHead" => "initial"
    )
  );
} // END SAVE APPROVAL OF OAD

function saveApproveOD($mysqli, $idNum, $status)
{
  $sql = "UPDATE travel_application SET
      od=:ODStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":ODStatus" => $status
    )
  );

  $sql = "UPDATE travel_companions SET
      od=:ODStatus
      WHERE leaderTravelID=:id AND sectionHead = 'initial' AND divisionHead = 'initial' AND oad = 'approved' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":ODStatus" => $status
    )
  );
} // END SAVE APPROVAL OF OD

function saveApprovePersonnel($mysqli, $idNum, $status, $requirements, $travelNo)
{
  $sql = "UPDATE travel_application SET
      personnel = :personnel, travelOrderNo = :travelNo,requirements = :requirements
      WHERE id=:id ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":travelNo" => $travelNo,
      ":requirements" => $requirements,
      ":personnel" => $status
      // automatic travel order no.
    )
  );

  $sql = "UPDATE travel_companions SET
      personnel = :personnel, travelOrderNo = :travelNo,requirements = :requirements
      WHERE leaderTravelID=:id AND od = 'approved'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":travelNo" => $travelNo,
      ":requirements" => $requirements,
      ":personnel" => $status
      // automatic travel order no.
    )
  );
} // END SAVE APPROVAL OF PERSONNEL

function getMyRank($mysqli, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT *,rank as rank,travelOrderNO as travelOrderNo FROM travel_companions WHERE companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':userID' => $userID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // END GET OWN TRAVEL DETAILS PER TRAVEL ID

// function getRankTravel($mysqli,$userID,$id){
//   $temp = array();
//   $sql = "SELECT * FROM travel_application WHERE userID = :userID AND id = :id";
//   $stmt = $mysqli->prepare($sql);
//   $stmt->execute(array(':userID' => $userID,':id' => $id));
//   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     $temp[] = $row;
//   }

//   return $temp;
// }// END GET OWN TRAVEL DETAILS PER TRAVEL ID

function getTravelOrder($mysqli, $yearMonth)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE travelOrderNo LIKE :yearMonth";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':yearMonth' => $yearMonth . '%'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  return $temp;
}

// D I S A P P R O V A L  A N D  C O M M E N T
function updateRemarksDH($mysqli, $comment, $status, $id, $userID, $dateFiled)
{
  $sql = "UPDATE travel_application SET
      remarks = :remarks, divisionHead = :status 
      WHERE id=:id AND userID = :userID AND dateFiled = :dateFiled";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":userID" => $userID,
      ":id" => $id,
      ":dateFiled" => $dateFiled
    )
  );

  $sql2 = "UPDATE travel_companions SET
      remarks = :remarks, divisionHead = :status 
      WHERE leaderTravelID=:id AND companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":userID" => $userID,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR DIVISION HEADS

function updateRemarksPersonnel($mysqli, $comment, $status, $id)
{
  $sql = "UPDATE travel_application SET
      remarks = :remarks, personnel = :status 
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR DIVISION HEADS


function updateRemarksOAD($mysqli, $comment, $status, $id)
{
  $sql = "UPDATE travel_application SET
      remarks = :remarks, oad = :status 
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );

  $sql = "UPDATE travel_companions SET
      remarks = :remarks, oad = :status 
      WHERE leaderTravelID=:id AND divisionHead = 'initial'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR OAD AS DH

function updateRemarksHeadasDH($mysqli, $comment, $status, $id, $userID)
{
  // $sql = "UPDATE travel_application SET
  //   remarks = :remarks, oad = :status 
  //   WHERE id=:id AND userID = :userID";
  // $stmt = $mysqli->prepare($sql);
  // $stmt->execute(
  //   array(
  //     ":remarks" => $comment,
  //     ":status" => $status,
  //     ":id" => $id,
  //     ":userID" => $userID
  //   )
  // );

  $sql = "UPDATE travel_companions SET
      remarks = :remarks, divisionHead = :status 
      WHERE leaderTravelID=:id AND companionUserID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id,
      ":userID" => $userID
    )
  );
} // END UPDATE REMARKS FOR OAD AS DH

function updateRemarksOD($mysqli, $comment, $status, $id)
{
  $sql = "UPDATE travel_application SET
      remarks = :remarks, od = :status 
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );

  $sql = "UPDATE travel_companions SET
      remarks = :remarks, od = :status 
      WHERE leaderTravelID=:id AND divisionHead = 'initial' AND oad = 'approved'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR OD

function getTravelTicket($mysqli)
{
  $temp = array();

  $sql = "SELECT * FROM travel_application WHERE oad = :oad AND divisionHead = :divisionHead AND od = :od AND personnel = :personnel AND choiceDestination = :choiceDestination AND travelOrderNO != '' GROUP BY userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':oad' => 'approved', ':divisionHead' => 'initial', ':od' => "approved", ':personnel' => "approved", ':choiceDestination' => 'Air Travel'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getNewTravelOutside($mysqli, $id)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE choiceDestination = :choiceDestination AND userID = :id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':choiceDestination' => 'Air Travel', ':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR BUDGET HEAD

function getTravelTicketPerEmployee($mysqli, $employeeID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE employeeID = :employeeID AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND oad = :oad AND od = :od AND personnel = :personnel AND choiceDestination = :choiceDestination";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':sectionHead' => "initial", ':divisionHead' => "initial", ':oad' => "approved", ':od' => "approved", ':personnel' => "approved", ':choiceDestination' => "Air Travel"));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function updateFlightStatus($mysqli, $status, $comment, $id)
{
  $sql = "UPDATE travel_application SET
      flightStatus = :status, comment = :comment
      WHERE id=:id AND od = 'approved'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":status" => $status,
      ":comment" => $comment,
      ":id" => $id
    )
  );

  $sql = "UPDATE travel_companions SET
      flightStatus = :status, comment = :comment
      WHERE leaderTravelID=:id AND od = 'approved'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":status" => $status,
      ":comment" => $comment,
      ":id" => $id
    )
  );
}

function updateAFlightStatusPerUser($mysqli, $status, $comment, $user, $id)
{
  $sql = "UPDATE travel_application SET
      flightStatus = :status, comment = :comment
      WHERE id=:id AND userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":status" => $status,
      ":comment" => $comment,
      ":userID" => $user,
      ":id" => $id
    )
  );
}

function updateCFlightStatusPerUser($mysqli, $status, $comment, $user, $id)
{
  $sql = "UPDATE travel_companions SET
      flightStatus = :status, comment = :comment
      WHERE leaderTravelID=:id AND companionUserID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":status" => $status,
      ":comment" => $comment,
      ":userID" => $user,
      ":id" => $id
    )
  );
}

function getAllNotTravelled($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE flightStatus = 'not travelled' OR flightStatus = 'travelled'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT *,companionEmployeeID as employeeID,travelOrderNo,leaderTravelID as id,companionUserID as userID FROM travel_companions WHERE flightStatus = 'not travelled' OR flightStatus = 'travelled'";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute();
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // END GET ALL NOT TRAVELLED 

function getAllUnliquidated($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE requirements = 'unliquidated' OR requirements = 'override' GROUP BY userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT *,companionEmployeeID as employeeID,travelOrderNo,leaderTravelID as id, companionUserID as userID FROM travel_companions WHERE requirements = 'unliquidated' OR requirements = 'override' GROUP BY companionUserID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute();
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
}

function getUnliquidated($mysqli, $employeeID, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE (requirements = 'unliquidated' OR requirements = 'override') AND employeeID = :employeeID AND userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":employeeID" => $employeeID, ":userID" => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT *,companionEmployeeID as employeeID,travelOrderNo,leaderTravelID as id, companionUserID as userID FROM travel_companions WHERE (requirements = 'unliquidated' OR requirements = 'override') AND companionEmployeeID = :employeeID AND companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(":employeeID" => $employeeID, ":userID" => $userID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
}

function getAllTravelledDetails($mysqli, $employeeID, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE (flightStatus = 'not travelled' OR flightStatus = 'travelled') AND employeeID = :employeeID AND userID = :userID GROUP BY id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":employeeID" => $employeeID, ":userID" => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT *, companionUserID as userID,leaderTravelID as id, travelOrderNO as travelOrderNo FROM travel_companions WHERE (flightStatus = 'not travelled' OR flightStatus = 'travelled') AND companionEmployeeID = :employeeID AND companionUserID = :userID GROUP BY leaderTravelID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(":employeeID" => $employeeID, ":userID" => $userID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
} // END GET ALL NOT TRAVELLED 

function updateRequirements($mysqli, $requirements, $userID)
{
  $sql = "UPDATE travel_application SET requirements = :requirements
      WHERE userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":requirements" => $requirements,
      ":userID" => $userID
    )
  );

  $sql2 = "UPDATE travel_companions SET requirements = :requirements
      WHERE companionUserID = :userID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(
    array(
      ":requirements" => $requirements,
      ":userID" => $userID
    )
  );
} // END GET ALL UPDATE UNLIQUIDATED TO LIQUIDATED

function getOverride($mysqli, $userID)
{
  $temp = array();
  $sql = "SELECT * FROM travel_application WHERE flightStatus = 'not travelled' AND userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":userID" => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  $temp2 = array();
  $sql2 = "SELECT *, companionUserID as userID,leaderTravelID as id, travelOrderNO as travelOrderNo FROM travel_companions WHERE flightStatus = 'not travelled' AND companionUserID = :companionUserID";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(":companionUserID" => $userID));
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
}

// START NOTIFICATION
function countNewNotTravelled($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM `travel_companions` a WHERE a.flightStatus = 'not travelled' AND a.commentApproval = ''";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  $temp2 = array();
  $sql2 = "SELECT * FROM `travel_application` b WHERE b.flightStatus = 'not travelled' AND b.commentApproval = ''";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute();
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
}

function countNewStaffRequest($mysqli, $userID, $role, $division)
{
  $temp = array();
  $temp2 = array();
  if ($role == 4) {
    $sql = "SELECT * FROM `travel_application` a WHERE a.od = 'pending' AND a.oad = 'approved' AND a.userID != :userID AND a.expired = ''";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(array(":userID" => $userID));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row;
    }
    $sql2 = "SELECT * FROM `travel_companions` b WHERE b.divisionHead = 'pending' AND b.od = 'pending' AND b.division = 'OFFICE OF THE DIRECTOR' AND b.companionUserID != :userID AND b.expired = ''";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->execute(array(":userID" => $userID));
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $temp2[] = $row2;
    }
    $temp = array_merge($temp, $temp2);
    return $temp;
  }
  if ($role == 7) {
    $sql = "SELECT * FROM `travel_application` a WHERE a.oad = 'pending' AND a.divisionHead = 'initial' AND a.userID != :userID AND a.expired = ''";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(array(":userID" => $userID));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row;
    }
    $sql2 = "SELECT * FROM `travel_companions` b WHERE b.divisionHead = 'pending' AND b.oad = 'pending' AND b.division = 'OFFICE OF THE ASSISTANT DIRECTOR' AND b.companionUserID != :userID AND b.expired = ''";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->execute(array(":userID" => $userID));
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $temp2[] = $row2;
    }
    $temp = array_merge($temp, $temp2);
    return $temp;
  }
  if ($role == 2) {
    $sql = "SELECT * FROM `travel_application` a WHERE a.division = :division AND a.divisionHead = 'pending' AND a.userID != :userID AND a.expired = ''";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(array(":userID" => $userID, ":division" => $division));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row;
    }
    $sql2 = "SELECT * FROM `travel_companions` b WHERE b.division = :division AND b.divisionHead = 'pending' AND b.companionUserID != :userID AND b.expired = ''";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->execute(array(":userID" => $userID, ":division" => $division));
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $temp2[] = $row2;
    }
    $temp = array_merge($temp, $temp2);
    return $temp;
  }
  if ($role == 6) {
    $sql = "SELECT * FROM `travel_application` a WHERE a.personnel = 'pending' AND a.od = 'approved'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row;
    }
    return $temp;
  }
}

function countNewUnliquidated($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM `travel_application` a WHERE a.requirements = 'unliquidated' AND ((a.flightStatus = 'not travelled' AND a.commentApproval = 'Approved by Director') OR (a.flightStatus = 'travelled')) AND (a.choiceDestination = 'Air Travel' OR a.choiceDestination = 'Outside Metro Manila')";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  $temp2 = array();
  $sql2 = "SELECT * FROM `travel_companions` b WHERE b.requirements = 'unliquidated' AND ((b.flightStatus = 'not travelled' AND b.commentApproval = 'Approved by Director') OR (b.flightStatus = 'travelled')) AND (b.choiceDestination = 'Air Travel' OR b.choiceDestination = 'Outside Metro Manila')";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute();
  while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp2[] = $row2;
  }
  $temp = array_merge($temp, $temp2);
  return $temp;
}

function countNewFlight($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM `travel_application` a WHERE a.choiceDestination = 'air travel' AND a.flightStatus = 'pending'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}
// END NOTIFICATION

// START FILE UPLOADING
function saveFilename($mysqli, $travelID, $travelOrderNo, $userID, $flightStatus, $choiceDestination)
{

  if ($flightStatus == 'travelled' && $choiceDestination == 'Air Travel') {
    $sql = "INSERT INTO travel_upload(`dateFiled`,`travelID`,`travelOrderNo`,`userID`,`PT`,`BP`,`CTC`,`IOT`,`TR`,`COA`)
       VALUES(NOW(),:travelID,:travelOrderNo,:userID,:PT,:BP,:CTC,:IOT,:TR,:COA)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':travelID' => $travelID,
        ':travelOrderNo' => $travelOrderNo,
        ':userID' => $userID,
        ':PT' => $_FILES['upload1']['name'],
        ':BP' => $_FILES['upload2']['name'],
        ':CTC' => $_FILES['upload3']['name'],
        ':IOT' => $_FILES['upload4']['name'],
        ':TR' => $_FILES['upload5']['name'],
        ':COA' => $_FILES['upload6']['name']

      )
    );
  }

  if ($flightStatus == 'travelled' && $choiceDestination == 'Outside Metro Manila') {
    $sql = "INSERT INTO travel_upload(`dateFiled`,`travelID`,`travelOrderNo`,`userID`,`COA`,`TripT`)
       VALUES(NOW(),:travelID,:travelOrderNo,:userID,:COA,:TripT)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':travelID' => $travelID,
        ':travelOrderNo' => $travelOrderNo,
        ':userID' => $userID,
        ':COA' => $_FILES['upload7']['name'],
        ':TripT' => $_FILES['upload8']['name']
      )
    );
  }
}

function getFiles($mysqli, $userID, $travelOrderNo)
{
  $temp = array();
  $sql = "SELECT * FROM travel_upload WHERE userID = :userID AND travelOrderNo = :travelOrderNo";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":userID" => $userID, ":travelOrderNo" => $travelOrderNo));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// END FILE UPLOADING


function sendEmailTravel($mysqli, $FNA, $LNA, $Ema)
{


  $content1 = nl2br("Good day, \n\n" .
    "Please consider the application of " . $FNA . ' ' . $LNA . "\r\n" .
    // "<a href='http://localhost/bard/travel/initial_travel_order.php'>Click Here!</a>\r\n\n".
    "Thank you. Regards.\r\n\n");

  $content2 = nl2br("<h4>Department of Agriculture - Bureau of Agricultural Research\nRDMIC Building, Visayas Ave. cor. Elliptical Road, Diliman, Quezon City</h4>");

  $email = $Ema;

  $subject = "NOTIFICATION FOR TRAVEL REQUEST - " . $FNA . ' ' . $LNA;
  $body = $content1 . ' ' . $content2;


  $mail = new PHPMailer();

  error_reporting(0);
  //smtp settings
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPDebug = 0;
  $mail->CharSet = 'UTF-8';
  $mail->Debugoutput = 'html';
  $mail->SMTPAuth = true;
  $mail->Username = 'bar.r4dads@gmail.com';
  $mail->Password =  'Abc_123456';
  $mail->Port = 25;
  //$mail->Port = 25; - local
  // $mail->Port = 587; - live
  // $mail->Port = 465; - di ko alam search mo nalang php mailer port
  $mail->SMTPSecure = 'ssl';
  $mail->SMTPAutoTLS = false;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;


  //email settings
  $mail->setFrom($mail->Username);
  $mail->addAddress($email);
  $mail->Subject = $subject;
  $mail->Body = html_entity_decode($body);
  $mail->isHTML(true);
  $mail->ClearCustomHeaders();

  if ($mail->send()) {
    $status = "success";
    $response = "Email is sent!";
  } else {
    $status = "failed";
    $response = "Something is wrong: <br>";
  }
} // END SEND EMAIL TRAVEL

//  ============== E N D  T R A V E L ===============


//  ============== S T A R T  L E A V E ==============

function getEmployeeLeaveDetails($mysqli, $firstName, $lastName)
{
  $temp = array();
  //   $sql = "SELECT YEAR(dateFiled) as YEAR, monthname(dateFiled) as MONTH, 
  // (SELECT COUNT(typeOfLeave) FROM leave_application WHERE typeOfLeave = 'Vacation' AND firstName = :firstName AND lastName = :lastName) AS VACATION,
  // (SELECT COUNT(typeOfLeave) FROM leave_application WHERE typeOfLeave = 'Sick' AND firstName = :firstName AND lastName = :lastName) AS SICK,
  // (SELECT COUNT(typeOfLeave) FROM leave_application WHERE typeOfLeave = 'Privilege' AND firstName = :firstName AND lastName = :lastName) AS PRIVILEGE,
  // (SELECT COUNT(typeOfLeave) FROM leave_application WHERE typeOfLeave = 'Maternity' AND firstName = :firstName AND lastName = :lastName) AS MATERNITY,
  // (SELECT COUNT(typeOfLeave) FROM leave_application WHERE typeOfLeave = 'Force' AND firstName = :firstName AND lastName = :lastName) AS FORCED,
  // (SELECT COUNT(typeOfLeave)FROM leave_application WHERE firstName = :firstName AND lastName = :lastName) AS TOTAL_LEAVES
  // FROM leave_application WHERE
  // firstName = :firstName AND lastName = :lastName
  // GROUP BY firstName, MONTH, YEAR ORDER BY month(dateFiled) ASC";


  $sql = "SELECT YEAR(dateFiled) as YEAR, monthname(dateFiled) as MONTH, firstName, lastName FROM leave_application WHERE firstName = :firstName AND lastName = :lastName GROUP BY firstName, MONTH, YEAR ORDER BY month(dateFiled) ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':firstName' => $firstName,
    ':lastName' => $lastName
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getTypeOfLeave($mysqli, $firstName, $lastName, $type, $month, $year)
{
  $temp = array();
  // $sql = "SELECT typeOfLeave FROM leave_application WHERE
  // firstName = :firstName AND lastName = :lastName AND typeOfLeave = :type AND monthname(dateFiled) = :month AND YEAR(dateFiled) = :year" ;
  $sql = "SELECT days FROM leave_application WHERE
    firstName = :firstName AND lastName = :lastName AND typeOfLeave = :type AND monthname(dateFiled) = :month AND YEAR(dateFiled) = :year";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':firstName' => $firstName,
    ':lastName' => $lastName,
    ':type' => $type,
    ':month' => $month,
    ':year' => $year
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET TYPE OF LEAVE PER MONTH

function getTypeLeave($mysqli, $user, $type, $year)
{
  $temp = array();
  $sql = "SELECT sum(days) as subtractor,typeOfLeave FROM leave_application WHERE userID = :userID AND typeOfLeave = :type AND YEAR(inclusiveDate) = :year GROUP BY monthname(inclusiveDate)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':userID' => $user,
    ':type' => $type,
    ':year' => $year,
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET TYPE OF LEAVE PER YEAR


function getEmployeeID($mysqli)
{
  $sql = "SELECT employeeID FROM credentials WHERE employeeID LIKE '%P'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
} // END CHECK IF USER IS A PERMANENT EMPLOYEE

function getPermaBalance($mysqli, $userID, $employeeID, $year)
{
  $temp = array();
  $sql = "SELECT *,monthname(beginningDate) as bMonth FROM leave_balance WHERE userID = :userID AND employeeID = :employeeID AND YEAR(asOf) = :year";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':userID' => $userID,
    ':employeeID' => $employeeID,
    ':year' => $year
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function findRole($mysqli, $roleID)
{
  $sql = "SELECT role FROM role WHERE roleID = :roleID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':roleID' => $roleID));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function checkApplicationLeave($mysqli, $userID, $year)
{
  $temp = array();
  $sql = "SELECT *,YEAR(dateFiled) = :year FROM leave_application WHERE userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID, ':year' => $year));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} //END CHECK IF ALREADY APPLIED FOR TRAVEL

function saveLeaveApplication($mysqli, $userID, $employeeID, $division, $section, $LNA, $FNA, $agency, $position, $typeOfLeave, $privilege, $where, $study, $other, $commutation, $dates, $workingDays, $sectionHead, $divisionHead, $od, $oad, $personnel)
{

  $sql = "INSERT INTO leave_application(`userID`,`employeeID`,`division`,`section`,`dateFiled`,`lastName`,`firstName`,`agency`,`position`,`typeOfLeave`,`privilege`,`location`,`study`,`other`,`commutation`,`inclusiveDate`,`days`,`sectionHead`,`divisionHead`,`od`,`oad`,`personnel`)
       VALUES(:userID,:employeeID,:division,:section,NOW(),:lastName,:firstName,:agency,:position,:typeOfLeave,:privilege,
             :location,:study,:other,:commutation,:dates,:days,:sectionHead,:divisionHead,:od,:oad,:personnel)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':userID' => $userID,
      ':employeeID' => $employeeID,
      ':division' => $division,
      ':section' => $section,
      ':lastName' => $LNA,
      ':firstName' => $FNA,

      ':agency' => $agency,
      ':position' => $position,
      ':typeOfLeave' => $typeOfLeave,
      ':privilege' => $privilege,
      ':location' => $where,
      ':study' => $study,
      ':other' => $other,
      ':commutation' => $commutation,
      ':dates' => $dates,
      ':days' => $workingDays,

      ':sectionHead' => $sectionHead,
      ':divisionHead' => $divisionHead,
      ':od' => $od,
      ':oad' => $oad,
      ':personnel' => $personnel
    )
  );
} // END SAVE LEAVE APPLICATION


function getLeaveDetails($mysqli, $employeeID, $userID)
{
  $temp = array();
  $sql = "SELECT *,YEAR(dateFiled) as YEAR, monthname(dateFiled) as MONTH FROM leave_application WHERE employeeID = :employeeID AND userID = :userID GROUP BY MONTH";

  // $sql = "SELECT *,YEAR(a.dateFiled) as YEAR, monthname(a.dateFiled) as MONTH,monthname(b.beginningDate) as bMONTH FROM leave_application a, leave_balance b WHERE a.employeeID = :employeeID AND a.userID = :userID ";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  // $temp2 = array();
  // $sql2 = "SELECT *,YEAR(beginningDate) as YEAR, monthname(beginningDate) as MONTH FROM leave_balance WHERE employeeID = :employeeID AND userID = :userID ";
  // $stmt2 = $mysqli->prepare($sql2);
  // $stmt2->execute(array(':employeeID' =>$employeeID,':userID' =>$userID));
  // while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
  //   $temp2[] = $row2;
  // }
  // $temp = array_merge($temp,$temp2);
  return $temp;
} // END GET LEAVE DETAILS PER EMPLOYEE

function getMyLeaveDetails($mysqli, $ID)
{
  $temp = array();
  $sql = "SELECT * FROM leave_application WHERE id = :id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $ID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET MY OWN LEAVE DETAILS

function getDHNewL($mysqli, $employeeID, $status, $division)
{
  $temp = array();
  $sql = "SELECT * FROM leave_application WHERE employeeID = :employeeID AND divisionHead = :divisionHead AND division = :division AND personnel = 'pending' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':divisionHead' => $status, ':division' => $division));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR DH

function getOADNewL($mysqli, $employeeID, $status)
{
  $temp = array();
  $sql = "SELECT * FROM leave_application WHERE employeeID = :employeeID AND oad = :oad AND divisionHead = 'initial'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':oad' => $status));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR OAD

function getODNewL($mysqli, $employeeID, $status)
{
  $temp = array();
  $sql = "SELECT * FROM leave_application WHERE employeeID = :employeeID AND od = :od";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':od' => $status));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR OD

function getHRNewL($mysqli, $employeeID, $status)
{
  $temp = array();
  $sql = "SELECT * FROM leave_application WHERE employeeID = :employeeID AND personnel = :personnel";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':personnel' => $status));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // GET ALL THE PENDING TRAVEL APPLICATION FOR PERSONNEL

// R E Q U E S T  L I S T . P H P
function getSectionHeadLeaveApplication($mysqli, $section, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND b.role != :role AND b.role != 3 GROUP BY userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':role' => $role));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET EMPLOYEES UNDER SECTION HEAD

function getDivisionHeadLeaveApplication($mysqli, $division, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.division = :division AND b.role != :role AND sectionHead = :status  GROUP BY userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':division' => $division, ':role' => $role, ':status' => "initial"));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET EMPLOYEES UNDER DIVISION HEAD


// S E C T I O N . P H P
function getPersonnelLeaveApplication($mysqli, $section)
{
  $temp = array();

  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND (oad = 'approved' OR od = 'approved') GROUP BY userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':sectionHead' => 'initial', ':divisionHead' => 'initial'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION PER SECTION FOR PERSONNEL

function getPersonnelLeaveApplication2($mysqli, $section)
{
  $temp = array();

  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND (oad = 'approved' OR oad = 'initial') AND (od = 'approved' OR od = 'pending') GROUP BY userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':sectionHead' => 'initial', ':divisionHead' => 'initial'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION PER SECTION FOR PERSONNEL UNDER OAD

function getOADLeaveApplication($mysqli, $section, $role)
{
  $temp = array();

  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND b.role != :role AND sectionHead = :sectionHead AND
      divisionHead = :divisionHead AND personnel = :personnel AND days < 30 GROUP BY userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':role' => $role, ':sectionHead' => 'initial', ':divisionHead' => 'initial', ':personnel' => 'approved'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION PER SECTION WITH APPROVED DH FOR OAD 

function getOADLeaveApplication2($mysqli, $section, $role)
{
  $temp = array();

  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND b.role != :role AND sectionHead = :sectionHead AND
      divisionHead = :divisionHead AND (oad = 'pending' OR oad = 'approved') AND (personnel = 'pending' OR personnel = 'approved') AND days < 30 GROUP BY userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':role' => $role, ':sectionHead' => 'initial', ':divisionHead' => 'initial'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION UNDER OAD 

function getODLeaveApplication($mysqli, $section, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND b.role != :role AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND personnel = :personnel AND days > 29 AND oad = :oad GROUP BY userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':role' => $role, ':sectionHead' => 'initial', ':divisionHead' => 'initial', ':personnel' => 'approved', ':oad' => 'approved'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION PER SECTION WITH APPROVED OAD FOR OD 

function getODLeaveApplication2($mysqli, $section, $role)
{
  $temp = array();

  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.section = :section AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND personnel = :personnel AND (days > 29 OR oad = :oad) GROUP BY userID";

  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section, ':sectionHead' => 'initial', ':divisionHead' => 'initial', ':personnel' => 'approved', ':oad' => 'initial'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION FOR OAD SECTION ONLY (IF ASST. DIRECTOR APPLIED FOR LEAVE)


// L E A V E  D E T A I L S . P H P
function getLeaveSH($mysqli, $employeeID, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION FOR SH 

function getLeaveDH($mysqli, $employeeID, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :sectionHead";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role, ':sectionHead' => "initial"));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION WITH APPROVED SH FOR DH 

function getLeavePersonnel($mysqli, $employeeID)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND sectionHead = :status AND divisionHead = :status";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':status' => "initial"));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET EMPLOYEES WITH APPROVED SH AND DH  FOR PERSONNEL

function getLeaveOAD($mysqli, $employeeID, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND divisionHead = :divisionHead  AND (personnel = 'approved' OR personnel = 'pending') AND days < 30";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role, ':divisionHead' => "initial"));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION PER EMPLOYEE ID WITH APPROVED DH FOR OAD 

function getLeaveOADEmployees($mysqli, $employeeID, $division, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :sectionHead AND a.division = :division";

  $stmt = $mysqli->prepare($sql);

  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role, ':sectionHead' => "initial", ':division' => $division));

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET EMPLOYEES UNDER OAD

// function getLeaveOADEmployees($mysqli,$employeeID,$role){
//   $temp = array();
//   $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :sectionHead AND divisionHead = :divisionHead GROUP BY userID";

//   $stmt = $mysqli->prepare($sql);

//   $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role,':sectionHead' => "initial", ':divisionHead' => "initial"));

//   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     $temp[] = $row;
//   }
//   return $temp;
// } // END GET EMPLOYEES UNDER OAD

function getLeaveOD($mysqli, $employeeID, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND personnel = :personnel AND days > 30 AND oad = :oad GROUP BY userID";

  $stmt = $mysqli->prepare($sql);

  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role, ':sectionHead' => "initial", ':divisionHead' => "initial", ':personnel' => 'approved', ':oad' => "approved"));

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION PER EMPLOYEE ID WITH APPROVED OAD FOR OD

function getLeaveODForOAD($mysqli, $employeeID, $role)
{
  $temp = array();
  $sql = "SELECT a.*, b.role FROM leave_application a, credentials b WHERE a.userID = b.userID AND a.employeeID = :employeeID AND b.role != :role AND sectionHead = :sectionHead AND divisionHead = :divisionHead AND personnel = :personnel";

  $stmt = $mysqli->prepare($sql);

  $stmt->execute(array(':employeeID' => $employeeID, ':role' => $role, ':sectionHead' => "initial", ':divisionHead' => "initial", ':personnel' => 'approved'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET APPLICATION PER EMPLOYEE ID WITH APPROVED OAD FOR OD  



//  I N I T I A L  O R  A P P R O V A L
function saveInitialSHLeave($mysqli, $idNum, $status)
{
  $sql = "UPDATE leave_application SET
      sectionHead=:SHStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":SHStatus" => $status
    )
  );
} // END SAVE INITIAL OF SECTION HEAD

function saveInitialDHLeave($mysqli, $idNum, $status)
{
  $sql = "UPDATE leave_application SET
      divisionHead=:DHStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":DHStatus" => $status
    )
  );
} // END SAVE INITIAL OF DIVISION HEAD

function saveApproveOADLeave($mysqli, $idNum, $status)
{
  $sql = "UPDATE leave_application SET
      oad=:OADStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":OADStatus" => $status
    )
  );
} // END SAVE APPROVAL OF OAD

function saveApproveOADHLeave($mysqli, $idNum, $status)
{
  $sql = "UPDATE leave_application SET
      divisionHead=:OADStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":OADStatus" => $status
    )
  );
} // END SAVE APPROVAL OF OAD


function saveApproveODLeave($mysqli, $idNum, $status)
{
  $sql = "UPDATE leave_application SET
      od=:ODStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":ODStatus" => $status
    )
  );
} // END SAVE APPROVAL OF OD

function saveApproveODLeaveForOAD($mysqli, $idNum, $status)
{
  $sql = "UPDATE leave_application SET
      od=:ODStatus, oad = :ODStatus
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":ODStatus" => $status
    )
  );
} // END SAVE APPROVAL OF OD FOR OAD

function saveApprovePersonnelLeave($mysqli, $idNum, $status)
{
  $sql = "UPDATE leave_application SET
      personnel=:personnel
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":id" => $idNum,
      ":personnel" => $status
      // automatic travel order no.
    )
  );
} // END SAVE APPROVAL OF PERSONNEL


// D I S A P P R O V A L  A N D  C O M M E N T
function updateRemarksSHLeave($mysqli, $comment, $status, $id)
{
  $sql = "UPDATE leave_application SET
      remarks = :remarks, sectionHead = :status 
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR SECTION HEADS

function updateRemarksDHLeave($mysqli, $comment, $status, $id)
{
  $sql = "UPDATE leave_application SET
      remarks = :remarks, divisionHead = :status 
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR DIVISION HEADS

function updateRemarksHRLeave($mysqli, $comment, $status, $id)
{
  $sql = "UPDATE leave_application SET
      remarks = :remarks, personnel = :status 
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR DIVISION HEADS

function updateRemarksOADLeave($mysqli, $comment, $status, $id)
{
  $sql = "UPDATE leave_application SET
      remarks = :remarks, oad = :status 
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR OAD

function updateRemarksODLeave($mysqli, $comment, $status, $id)
{
  $sql = "UPDATE leave_application SET
      remarks = :remarks, od = :status 
      WHERE id=:id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":remarks" => $comment,
      ":status" => $status,
      ":id" => $id
    )
  );
} // END UPDATE REMARKS FOR OD

function getALLPermaEmployee($mysqli)
{
  $sql = "SELECT * FROM view_employeeinfo WHERE employeeID LIKE '%P' ORDER BY employeeID ASC";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveBalance($mysqli, $employeeID, $userID, $name, $vacationLeave, $sickLeave, $beginningDate)
{
  $sql = "INSERT INTO leave_balance(`asOf`,`employeeID`,`userID`,`name`,`inVL`,`inSL`,`vacationLeave`,`sickLeave`,`beginningDate`) VALUES(NOW(),:employeeID,:userID,:name,:inVL,:inSL,:vacationLeave,:sickLeave,:beginningDate)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':employeeID' => $employeeID,
      ':userID' => $userID,
      ':name' => $name,
      ':inVL' => $vacationLeave,
      ':inSL' => $sickLeave,
      ':vacationLeave' => $vacationLeave,
      ':sickLeave' => $sickLeave,
      ':beginningDate' => $beginningDate
    )
  );
}

function getforCredits($mysqli, $user, $type, $year, $month)
{
  $temp = array();
  // $sql = "SELECT *,sum(days) as DAYS FROM leave_application WHERE userID = :userID AND typeOfLeave = :type AND YEAR(inclusiveDate) = :year AND monthname(inclusiveDate) = :month ";

  $sql = "SELECT * FROM leave_application WHERE userID = :userID AND typeOfLeave = :type AND YEAR(inclusiveDate) = :year AND monthname(inclusiveDate) = :month ORDER BY inclusiveDate";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':userID' => $user,
    ':type' => $type,
    ':year' => $year,
    ':month' => $month
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET TYPE OF LEAVE PER YEAR AND MONTH

//  ============== E N D  L E A V E ===============



//  ============== S T A R T  P R I N T  P D S  ===============
function PemployeeInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM personal_info WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET SPOUSE
function PspouseInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM spouse WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET PARENTS
function PparentsInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM parents WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET CHILDREN
function PchildInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM children WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET VOLUNTARY
function PvoluntaryInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM tbl_voluntary WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET EDUCATION
function PeducationInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM education WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET CSE
function PcseInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM cse WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET WORK
function PworkInfo($mysqli)
{

  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM work WHERE userID = :x AND startDate >= CURRENT_DATE - interval 10 year");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET TRAINING
function PtrainingInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM work WHERE userID = :x AND startDate >= CURRENT_DATE - interval 5 year");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET SKILL
function PskillInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM skills WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET RECOGNITION
function PrecognitionInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM recognition WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET ORGANIZATION
function PorganizationInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM organization WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET OTHER
function PotherInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM other WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET REFERENCE
function PreferenceInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM reference WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// GET ISSUED ID
function PissuedIDInfo($mysqli)
{
  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM issuedid WHERE userID = :x ");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}
//  ============== E N D  P R I N T  P D S  ===============

//  ============== S T A R T  E X A M  ===============

// P M I 
function savePMIName($mysqli, $name, $userID, $division, $section, $evaluator, $_1a, $_1b, $_2a, $_2b, $_3a, $_3b, $_3c, $_4a, $_5a, $_5b, $_6a, $_6b, $_6c, $_6d, $_7a, $_7b, $_8a, $_8b, $_9a, $_9b, $_10a, $_10b, $_11a, $_12a, $_12b, $_13a, $_13b, $_13c, $_14a, $_14b, $_14c, $_15a, $_15b, $_15c, $_16a, $_17a, $_17b, $totalAVG)
{
  $sql = "INSERT INTO pmi(`name`,`userID`,`division`,`section`,`evaluator`,`1a`,`1b`,`2a`,`2b`,`3a`,`3b`,`3c`,`4a`,`5a`,`5b`,`6a`,`6b`,`6c`,`6d`,`7a`,`7b`,`8a`,`8b`,`9a`,`9b`,`10a`,`10b`,`11a`,`12a`,`12b`,`13a`,`13b`,`13c`,`14a`,`14b`,`14c`,`15a`,`15b`,`15c`,`16a`,`17a`,`17b`,`average`) VALUES (:name,:userID,:division,:section,:evaluator,:1a,:1b,:2a,:2b,:3a,:3b,:3c,:4a,:5a,:5b,:6a,:6b,:6c,:6d,:7a,:7b,:8a,:8b,:9a,:9b,:10a,:10b,:11a,:12a,:12b,:13a,:13b,:13c,:14a,:14b,:14c,:15a,:15b,:15c,:16a,:17a,:17b,:average)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':name' => $name,
      ':userID' => $userID,
      ':division' => $division,
      ':section' => $section,
      ':evaluator' => $evaluator,
      ':1a' => $_1a,
      ':1b' => $_1b,
      ':2a' => $_2a,
      ':2b' => $_2b,
      ':3a' => $_3a,
      ':3b' => $_3b,
      ':3c' => $_3c,
      ':4a' => $_4a,
      ':5a' => $_5a,
      ':5b' => $_5b,
      ':6a' => $_6a,
      ':6b' => $_6b,
      ':6c' => $_6c,
      ':6d' => $_6d,
      ':7a' => $_7a,
      ':7b' => $_7b,
      ':8a' => $_8a,
      ':8b' => $_8b,
      ':9a' => $_9a,
      ':9b' => $_9b,
      ':10a' => $_10a,
      ':10b' => $_10b,
      ':11a' => $_11a,
      ':12a' => $_12a,
      ':12b' => $_12b,
      ':13a' => $_13a,
      ':13b' => $_13b,
      ':13c' => $_13c,
      ':14a' => $_14a,
      ':14b' => $_14b,
      ':14c' => $_14c,
      ':15a' => $_15a,
      ':15b' => $_15b,
      ':15c' => $_15c,
      ':16a' => $_16a,
      ':17a' => $_17a,
      ':17b' => $_17b,
      ':average' => $totalAVG
    )
  );
}

function getPMIExam($mysqli, $name, $evaluator)
{
  $temp = array();
  $sql = "SELECT * FROM pmi WHERE name = :name AND evaluator = :evaluator";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':name' => $name,
    ':evaluator' => $evaluator
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// function getPMIApplicants($mysqli){
//   $temp = array();
//   $sql = "SELECT * FROM pmi";
//   $stmt = $mysqli->prepare($sql);
//   $stmt->execute();
//   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     $temp[] = $row;
//   }
//   return $temp;
// }

function getALLPMI($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM pmi";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getPMIApplicantPerDivision($mysqli, $division)
{
  $temp = array();
  $sql = "SELECT * FROM pmi WHERE division = :division";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":division" => $division));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getPMIApplicantPerSection($mysqli, $section)
{
  $temp = array();
  $sql = "SELECT * FROM pmi WHERE section = :section";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":section" => $section));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


function getPMIApplicantDetails($mysqli, $id, $name, $evaluator)
{
  $temp = array();
  $sql = "SELECT * FROM pmi WHERE id = :id AND name = :name AND evaluator = :evaluator";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':id' => $id,
    ':name' => $name,
    ':evaluator' => $evaluator
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// function getPMIAverageApplicants($mysqli, $id,$division){
//   $temp = array();
//   $sql = "SELECT ((1a+1b+2a+2b+3a+3b+3c+4a+5a+5b+6a+6b+6c+6d+7a+7b+8a+8b+9a+9b+10a+10b+11a+12a+12b+13a+13b+13c+14a+14b+14c+15a+15b+15c+16a+17a+17b)/37) AS AVERAGE FROM pmi WHERE id = :id AND division = :division GROUP BY id";
//   $stmt = $mysqli->prepare($sql);
//   $stmt->execute(array(':id' => $id,':division' => $division));
//   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     $temp[] = $row;
//   }
//   return $temp;
// }

function getPMIAverageApplicants($mysqli, $id)
{
  $temp = array();
  $sql = "SELECT ((1a+1b+2a+2b+3a+3b+3c+4a+5a+5b+6a+6b+6c+6d+7a+7b+8a+8b+9a+9b+10a+10b+11a+12a+12b+13a+13b+13c+14a+14b+14c+15a+15b+15c+16a+17a+17b)/37) AS AVERAGE FROM pmi WHERE id = :id GROUP BY id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// E N D  O F  P M I

// C P Q
function getALLCPQ($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM cpq";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getCPQApplicants($mysqli, $division)
{
  $temp = array();
  $temp2 = array();

  $sql = "SELECT * FROM cpq WHERE division = :division";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":division" => $division));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  if ($_SESSION['role'] == 7) {
    $sql2 = "SELECT * FROM cpq WHERE username = :username OR username = :username1";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->execute(array(":username" => 'rpcabrera', ":username1" => 'crdeguia'));
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $temp2[] = $row2;
    }
    $temp = array_merge($temp, $temp2);
  }
  return $temp;
}

function getCPQApplicantPerDiv($mysqli, $division, $username, $username2)
{
  $temp = array();
  $temp2 = array();
  $temp3 = array();

  $sql = "SELECT * FROM cpq WHERE division = :division AND username != :username AND username != :username2";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":division" => $division, ":username" => $username, ":username2" => $username2));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  if ($_SESSION['role'] == 7) {
    $sql2 = "SELECT * FROM cpq WHERE username = :username OR username = :username1 AND username != :username2";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->execute(array(":username" => 'rpcabrera', ":username1" => 'crdeguia', ":username2" => $username2));
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $temp2[] = $row2;
    }
    $temp = array_merge($temp, $temp2);
  }

  if ($_SESSION['username'] == 'sritual') {
    $sql2 = "SELECT * FROM cpq WHERE username = :username AND username != :username2";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->execute(array(":username" => 'jbermas', ":username2" => $username2));
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $temp2[] = $row2;
    }
    $temp = array_merge($temp, $temp2);
  }

  return $temp;
}

// MA'AM EVE
function getCPQApplicantUsername($mysqli, $username)
{
  $temp = array();
  $sql = "SELECT * FROM cpq WHERE  username = :username";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":username" => $username));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// SIR BOB
function getCPQApplicantPerSec_3($mysqli, $section, $username)
{
  $temp = array();
  $sql = "SELECT * FROM cpq WHERE (section = :section AND username != :username) OR username = :username2";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":section" => $section, ":username" => $username, ":username2" => 'jyonzon'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END FOR getCPQApplicantPerSec_2 FOR MR. ROBERTO QUING

function getCPQApplicantPerSec($mysqli, $section, $username)
{
  $temp = array();
  $sql = "SELECT * FROM cpq WHERE section = :section AND username != :username";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":section" => $section, ":username" => $username));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getCPQApplicantPerSec_2($mysqli, $section, $username)
{
  $temp = array();
  $sql = "SELECT * FROM cpq WHERE section = :section OR section =:section2 AND username != :username";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":section" => $section, ":section2" => 2.6, ":username" => $username));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END FOR getCPQApplicantPerSec_2 FOR MS. JUDITH MAGHANOY

function getCPQApplicantDetails($mysqli, $id, $name)
{
  $temp = array();
  $sql = "SELECT * FROM cpq WHERE id = :id AND name = :name";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':id' => $id,
    ':name' => $name
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getTotalAVG($mysqli, $id)
{
  $temp = array();
  $sql = "SELECT AVG(quantitativeA + quantitativeB + quantitativeC + quantitativeD) AS QuantitativeAVERAGE FROM cpq WHERE id = :id GROUP BY id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function saveCPQName($mysqli, $name, $userID, $division, $section, $username, $quantitativeA, $quantitativeB, $quantitativeC, $quantitativeD, $cognitiveA, $cognitiveB, $cognitiveC, $emotionalA, $emotionalB, $emotionalC, $demandsA, $demandsB, $sensorialA, $sensorialB, $sensorialC, $sensorialD, $influenceA, $influenceB, $influenceC, $influenceD, $possibilitiesA, $possibilitiesB, $possibilitiesC, $possibilitiesD, $degreeA, $degreeB, $degreeC, $degreeD, $meaningA, $meaningB, $meaningC, $commitmentA, $commitmentB, $commitmentC, $commitmentD, $predictabilityA, $predictabilityB, $roleClarityA, $roleClarityB, $roleClarityC, $roleClarityD, $roleConflictA, $roleConflictB, $roleConflictC, $roleConflictD, $qualityA, $qualityB, $qualityC, $qualityD, $socialSupportA, $socialSupportB, $socialSupportC, $socialSupportD, $feedbackA, $feedbackB, $socialRelationA, $socialRelationB, $senseComA, $senseComB, $senseComC, $insecurityA, $insecurityB, $insecurityC, $insecurityD, $jobSatisfactionA, $jobSatisfactionB, $jobSatisfactionC, $jobSatisfactionD, $generalHealthA, $generalHealthB, $generalHealthC, $generalHealthD, $mentalHealthA, $mentalHealthB, $mentalHealthC, $mentalHealthD, $mentalHealthE, $vitalityA, $vitalityB, $vitalityC, $vitalityD, $behavioralStressA, $behavioralStressB, $behavioralStressC, $behavioralStressD, $somaticStressA, $somaticStressB, $somaticStressC, $somaticStressD, $totalAVG)
{
  $sql = "INSERT INTO cpq(`name`,`userID`,`division`,`section`,`username`,`quantitativeA`,`quantitativeB`,`quantitativeC`,`quantitativeD`,`cognitiveA`,`cognitiveB`,`cognitiveC`,`emotionalA`,`emotionalB`,`emotionalC`,`demandsA`,`demandsB`,`sensorialA`,`sensorialB`,`sensorialC`,`sensorialD`,`influenceA`,`influenceB`,`influenceC`,`influenceD`,`possibilitiesA`,`possibilitiesB`,`possibilitiesC`,`possibilitiesD`,`degreeA`,`degreeB`,`degreeC`,`degreeD`,`meaningA`,`meaningB`,`meaningC`,`commitmentA`,`commitmentB`,`commitmentC`,`commitmentD`,`predictabilityA`,`predictabilityB`,`roleClarityA`,`roleClarityB`,`roleClarityC`,`roleClarityD`,`roleConflictA`,`roleConflictB`,`roleConflictC`,`roleConflictD`,`qualityA`,`qualityB`,`qualityC`,`qualityD`,`socialSupportA`,`socialSupportB`,`socialSupportC`,`socialSupportD`,`feedbackA`,`feedbackB`,`socialRelationA`,`socialRelationB`,`senseComA`,`senseComB`,`senseComC`,`insecurityA`,`insecurityB`,`insecurityC`,`insecurityD`,`jobSatisfactionA`,`jobSatisfactionB`,`jobSatisfactionC`,`jobSatisfactionD`,`generalHealthA`,`generalHealthB`,`generalHealthC`,`generalHealthD`,`mentalHealthA`,`mentalHealthB`,`mentalHealthC`,`mentalHealthD`,`mentalHealthE`,`vitalityA`,`vitalityB`,`vitalityC`,`vitalityD`,`behavioralStressA`,`behavioralStressB`,`behavioralStressC`,`behavioralStressD`,`somaticStressA`,`somaticStressB`,`somaticStressC`,`somaticStressD`,`average`) VALUES (:name,:userID,:division,:section,:username,:quantitativeA,:quantitativeB,:quantitativeC,:quantitativeD,:cognitiveA,:cognitiveB,:cognitiveC,:emotionalA,:emotionalB,:emotionalC,:demandsA,:demandsB,:sensorialA,:sensorialB,:sensorialC,:sensorialD,:influenceA,:influenceB,:influenceC,:influenceD,:possibilitiesA,:possibilitiesB,:possibilitiesC,:possibilitiesD,:degreeA,:degreeB,:degreeC,:degreeD,:meaningA,:meaningB,:meaningC,:commitmentA,:commitmentB,:commitmentC,:commitmentD,:predictabilityA,:predictabilityB,:roleClarityA,:roleClarityB,:roleClarityC,:roleClarityD,:roleConflictA,:roleConflictB,:roleConflictC,:roleConflictD,:qualityA,:qualityB,:qualityC,:qualityD,:socialSupportA,:socialSupportB,:socialSupportC,:socialSupportD,:feedbackA,:feedbackB,:socialRelationA,:socialRelationB,:senseComA,:senseComB,:senseComC,:insecurityA,:insecurityB,:insecurityC,:insecurityD,:jobSatisfactionA,:jobSatisfactionB,:jobSatisfactionC,:jobSatisfactionD,:generalHealthA,:generalHealthB,:generalHealthC,:generalHealthD,:mentalHealthA,:mentalHealthB,:mentalHealthC,:mentalHealthD,:mentalHealthE,:vitalityA,:vitalityB,:vitalityC,:vitalityD,:behavioralStressA,:behavioralStressB,:behavioralStressC,:behavioralStressD,:somaticStressA,:somaticStressB,:somaticStressC,:somaticStressD,:average)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':name' => $name,
      ':userID' => $userID,
      ':division' => $division,
      ':section' => $section,
      ':username' => $username,
      ':quantitativeA' => $quantitativeA,
      ':quantitativeB' => $quantitativeB,
      ':quantitativeC' => $quantitativeC,
      ':quantitativeD' => $quantitativeD,
      ':cognitiveA' => $cognitiveA,
      ':cognitiveB' => $cognitiveB,
      ':cognitiveC' => $cognitiveC,
      ':emotionalA' => $emotionalA,
      ':emotionalB' => $emotionalB,
      ':emotionalC' => $emotionalC,
      ':demandsA' => $demandsA,
      ':demandsB' => $demandsB,
      ':sensorialA' => $sensorialA,
      ':sensorialB' => $sensorialB,
      ':sensorialC' => $sensorialC,
      ':sensorialD' => $sensorialD,
      ':influenceA' => $influenceA,
      ':influenceB' => $influenceB,
      ':influenceC' => $influenceC,
      ':influenceD' => $influenceD,
      ':possibilitiesA' => $possibilitiesA,
      ':possibilitiesB' => $possibilitiesB,
      ':possibilitiesC' => $possibilitiesC,
      ':possibilitiesD' => $possibilitiesD,
      ':degreeA' => $degreeA,
      ':degreeB' => $degreeB,
      ':degreeC' => $degreeC,
      ':degreeD' => $degreeD,
      ':meaningA' => $meaningA,
      ':meaningB' => $meaningB,
      ':meaningC' => $meaningC,
      ':commitmentA' => $commitmentA,
      ':commitmentB' => $commitmentB,
      ':commitmentC' => $commitmentC,
      ':commitmentD' => $commitmentD,
      ':predictabilityA' => $predictabilityA,
      ':predictabilityB' => $predictabilityB,
      ':roleClarityA' => $roleClarityA,
      ':roleClarityB' => $roleClarityB,
      ':roleClarityC' => $roleClarityC,
      ':roleClarityD' => $roleClarityD,
      ':roleConflictA' => $roleConflictA,
      ':roleConflictB' => $roleConflictB,
      ':roleConflictC' => $roleConflictC,
      ':roleConflictD' => $roleConflictD,
      ':qualityA' => $qualityA,
      ':qualityB' => $qualityB,
      ':qualityC' => $qualityC,
      ':qualityD' => $qualityD,
      ':socialSupportA' => $socialSupportA,
      ':socialSupportB' => $socialSupportB,
      ':socialSupportC' => $socialSupportC,
      ':socialSupportD' => $socialSupportD,
      ':feedbackA' => $feedbackA,
      ':feedbackB' => $feedbackB,
      ':socialRelationA' => $socialRelationA,
      ':socialRelationB' => $socialRelationB,
      ':senseComA' => $senseComA,
      ':senseComB' => $senseComB,
      ':senseComC' => $senseComC,
      ':insecurityA' => $insecurityA,
      ':insecurityB' => $insecurityB,
      ':insecurityC' => $insecurityC,
      ':insecurityD' => $insecurityD,
      ':jobSatisfactionA' => $jobSatisfactionA,
      ':jobSatisfactionB' => $jobSatisfactionB,
      ':jobSatisfactionC' => $jobSatisfactionC,
      ':jobSatisfactionD' => $jobSatisfactionD,
      ':generalHealthA' => $generalHealthA,
      ':generalHealthB' => $generalHealthB,
      ':generalHealthC' => $generalHealthC,
      ':generalHealthD' => $generalHealthD,
      ':mentalHealthA' => $mentalHealthA,
      ':mentalHealthB' => $mentalHealthB,
      ':mentalHealthC' => $mentalHealthC,
      ':mentalHealthD' => $mentalHealthD,
      ':mentalHealthE' => $mentalHealthE,
      ':vitalityA' => $vitalityA,
      ':vitalityB' => $vitalityB,
      ':vitalityC' => $vitalityC,
      ':vitalityD' => $vitalityD,
      ':behavioralStressA' => $behavioralStressA,
      ':behavioralStressB' => $behavioralStressB,
      ':behavioralStressC' => $behavioralStressC,
      ':behavioralStressD' => $behavioralStressD,
      ':somaticStressA' => $somaticStressA,
      ':somaticStressB' => $somaticStressB,
      ':somaticStressC' => $somaticStressC,
      ':somaticStressD' => $somaticStressD,
      ':average' => $totalAVG
    )
  );
}
// E N D  O F  C P Q

// G E T  A L L  A P P L I C A N T S
function getAllApplicants($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM pmi";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// C H E C K  P M I
function checkIfPMI($mysqli, $name, $yearMonth)
{
  $temp = array();
  $sql = "SELECT * FROM pmi WHERE name = :name AND date LIKE '%$yearMonth%' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':name' => $name
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function checkIfCPQ($mysqli, $userID, $yearMonth)
{
  $temp = array();
  $sql = "SELECT * FROM cpq WHERE userID = :userID AND dateFiled LIKE '%$yearMonth%'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':userID' => $userID
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// C H E C K  C P Q 
function checkCPQ($mysqli, $name)
{
  $temp = array();
  $sql = "SELECT * FROM cpq WHERE name = :name";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':name' => $name
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


// C H E C K  P M I
function checkPMI($mysqli, $name)
{
  $temp = array();
  $sql = "SELECT * FROM pmi WHERE name = :name";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':name' => $name
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}
// P X P
function getAllPXPApplicants($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM pxp";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllPXPProvider($mysqli, $provider)
{
  $temp = array();
  $sql = "SELECT * FROM pxp WHERE id = :provider";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':provider' => $provider));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getPXPApplicants($mysqli, $evaluatedBy)
{
  $temp = array();
  $sql = "SELECT * FROM pxp WHERE evaluatedBy = :evaluatedBy";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":evaluatedBy" => $evaluatedBy));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getPXPApplicantPerDiv($mysqli, $division, $username)
{
  $temp = array();
  $sql = "SELECT * FROM pxp WHERE division = :division AND username != :username";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":division" => $division, ":username" => $username));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getPXPApplicantDetails($mysqli, $id, $name)
{
  $temp = array();
  $sql = "SELECT * FROM pxp WHERE id = :id AND name = :name";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(
    ':id' => $id,
    ':name' => $name
  ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getPXPTotalAVG($mysqli, $id)
{
  $temp = array();
  $sql = "SELECT AVG(quantitativeA + quantitativeB + quantitativeC + quantitativeD) AS QuantitativeAVERAGE FROM pxp WHERE id = :id GROUP BY id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getEvaluator($mysqli, $userID)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM personal_info WHERE userID = :userID ");
  $stmt->execute(array(":userID" => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
} // END GET EVALUATOR

function savePXPName($mysqli, $externalProvider, $natureOfBusiness, $approvalDate, $periodCoverage, $_1, $_1_remarks, $_2, $_2_remarks, $_3, $_3_remarks, $_4, $_4_remarks, $_5, $_5_remarks, $_6, $_6_remarks, $_7, $_7_remarks, $_8, $_8_remarks, $_9, $_9_remarks, $_10, $_10_remarks, $evaluatedBy, $recommendation, $aveScore)
{
  $sql = "INSERT INTO pxp(`externalProvider`,`natureOfBusiness`,`approvalDate`,`periodCoverage`,`1`,`1_remarks`,`2`,`2_remarks`,`3`,`3_remarks`,`4`,`4_remarks`,`5`,`5_remarks`,`6`,`6_remarks`,`7`,`7_remarks`,`8`,`8_remarks`,`9`,`9_remarks`,`10`,`10_remarks`,`evaluatedBy`,`recommendation`,`aveScore`) VALUES (:externalProvider,:natureOfBusiness,:approvalDate,:periodCoverage,:_1,:_1_remarks,:_2,:_2_remarks,:_3,:_3_remarks,:_4,:_4_remarks,:_5,:_5_remarks,:_6,:_6_remarks,:_7,:_7_remarks,:_8,:_8_remarks,:_9,:_9_remarks,:_10,:_10_remarks,:evaluatedBy,:recommendation,:aveScore)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':externalProvider' => $externalProvider,
      ':natureOfBusiness' => $natureOfBusiness,
      ':approvalDate' => $approvalDate,
      ':periodCoverage' => $periodCoverage,
      ':_1' => $_1,
      ':_1_remarks' => $_1_remarks,
      ':_2' => $_2,
      ':_2_remarks' => $_2_remarks,
      ':_3' => $_3,
      ':_3_remarks' => $_3_remarks,
      ':_4' => $_4,
      ':_4_remarks' => $_4_remarks,
      ':_5' => $_5,
      ':_5_remarks' => $_5_remarks,
      ':_6' => $_6,
      ':_6_remarks' => $_6_remarks,
      ':_7' => $_7,
      ':_7_remarks' => $_7_remarks,
      ':_8' => $_8,
      ':_8_remarks' => $_8_remarks,
      ':_9' => $_9,
      ':_9_remarks' => $_9_remarks,
      ':_10' => $_10,
      ':_10_remarks' => $_10_remarks,
      ':evaluatedBy' => $evaluatedBy,
      ':recommendation' => $recommendation,
      ':aveScore' => $aveScore
    )
  );
}
// E N D  O F  P X P

//  ============== E N D  E X A M  ===============

//  ============== S T A R T  I N T E R V I E W  ===============

function saveInterview($mysqli, $name, $position, $evaluator, $question1, $question2, $question3, $question4, $question5, $question6, $question7, $question8, $question9, $question10)
{

  $sql = "INSERT INTO interview(`dateFiled`,`name`,`appliedPosition`,`evaluatedBy`,`question1`,`question2`,`question3`,`question4`,`question5`,`question6`,`question7`,`question8`,`question9`,`question10`) VALUES (NOW(),:name,:position,:evaluator,:question1,:question2,:question3,:question4,:question5,:question6,:question7,:question8,:question9,:question10)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':name' => $name,
      ':position' => $position,
      ':evaluator' => $evaluator,
      ':question1' => $question1,
      ':question2' => $question2,
      ':question3' => $question3,
      ':question4' => $question4,
      ':question5' => $question5,
      ':question6' => $question6,
      ':question7' => $question7,
      ':question8' => $question8,
      ':question9' => $question9,
      ':question10' => $question10
    )
  );
}

function getAllInterview($mysqli)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM interview");
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function viewInterview($mysqli, $id, $name)
{
  $temp = array();
  $stmt = $mysqli->prepare("SELECT * FROM interview WHERE id = :id AND name = :name");
  $stmt->execute(array(':id' => $id, ':name' => $name));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

//  ============== E N D   I N T E R V I E W  ===============

//  ============== S T A R T  C H A R A C T E R  R E F E R E N C E  ===============
function saveCReference($mysqli, $name, $evaluator, $workEthicA, $workEthicB, $workEthicC, $workEthicD, $dependabilityA, $dependabilityB, $dependabilityC, $dependabilityD, $positiveA, $positiveB, $emotionalC, $adaptabilityA, $adaptabilityB, $adaptabilityC, $adaptabilityD, $adaptabilityE, $honestyA, $honestyB, $selfMotivatedA, $selfMotivatedB, $selfMotivatedC, $growLearnA, $growLearnB, $selfConfidenceA, $selfConfidenceB, $selfConfidenceC, $selfConfidenceD, $selfConfidenceE, $selfConfidenceF, $selfConfidenceG, $professionalismA, $professionalismB, $professionalismC, $professionalismD, $professionalismE)
{
  $sql = "INSERT INTO characterreference(`dateFiled`,`name`,`evaluator`,`workEthicA`,`workEthicB`,`workEthicC`,`workEthicD`,`dependabilityA`,`dependabilityB`,`dependabilityC`,`dependabilityD`,`positiveA`,`positiveB`,`emotionalC`,`adaptabilityA`,`adaptabilityB`,`adaptabilityC`,`adaptabilityD`,`adaptabilityE`,`honestyA`,`honestyB`,`selfMotivatedA`,`selfMotivatedB`,`selfMotivatedC`,`growLearnA`,`growLearnB`,`selfConfidenceA`,`selfConfidenceB`,`selfConfidenceC`,`selfConfidenceD`,`selfConfidenceE`,`selfConfidenceF`,`selfConfidenceG`,`professionalismA`,`professionalismB`,`professionalismC`,`professionalismD`,`professionalismE`) 


   VALUES (NOW(),:name,:evaluator,:workEthicA,:workEthicB,:workEthicC,:workEthicD,:dependabilityA,:dependabilityB,:dependabilityC,:dependabilityD,:positiveA,:positiveB,:emotionalC,:adaptabilityA,:adaptabilityB,:adaptabilityC,:adaptabilityD,:adaptabilityE,:honestyA,:honestyB,:selfMotivatedA,:selfMotivatedB,:selfMotivatedC,:growLearnA,:growLearnB,:selfConfidenceA,:selfConfidenceB,:selfConfidenceC,:selfConfidenceD,:selfConfidenceE,:selfConfidenceF,:selfConfidenceG,:professionalismA,:professionalismB,:professionalismC,:professionalismD,:professionalismE)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':name' => $name,
      ':evaluator' => $evaluator,
      ':workEthicA' => $workEthicA,
      ':workEthicB' => $workEthicB,
      ':workEthicC' => $workEthicC,
      ':workEthicD' => $workEthicD,
      ':dependabilityA' => $dependabilityA,
      ':dependabilityB' => $dependabilityB,
      ':dependabilityC' => $dependabilityC,
      ':dependabilityD' => $dependabilityD,
      ':positiveA' => $positiveA,
      ':positiveB' => $positiveB,
      ':emotionalC' => $emotionalC,
      ':adaptabilityA' => $adaptabilityA,
      ':adaptabilityB' => $adaptabilityB,
      ':adaptabilityC' => $adaptabilityC,
      ':adaptabilityD' => $adaptabilityD,
      ':adaptabilityE' => $adaptabilityE,
      ':honestyA' => $honestyA,
      ':honestyB' => $honestyB,
      ':selfMotivatedA' => $selfMotivatedA,
      ':selfMotivatedB' => $selfMotivatedB,
      ':selfMotivatedC' => $selfMotivatedC,
      ':growLearnA' => $growLearnA,
      ':growLearnB' => $growLearnB,
      ':selfConfidenceA' => $selfConfidenceA,
      ':selfConfidenceB' => $selfConfidenceB,
      ':selfConfidenceC' => $selfConfidenceC,
      ':selfConfidenceD' => $selfConfidenceD,
      ':selfConfidenceE' => $selfConfidenceE,
      ':selfConfidenceF' => $selfConfidenceF,
      ':selfConfidenceG' => $selfConfidenceG,
      ':professionalismA' => $professionalismA,
      ':professionalismB' => $professionalismB,
      ':professionalismC' => $professionalismC,
      ':professionalismD' => $professionalismD,
      ':professionalismE' => $professionalismE
    )
  );
}

function getAllCReference($pdo)
{
  $sql = "SELECT * FROM characterreference";
  $temp = array();
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getReferenceDetails($mysqli, $id, $name)
{
  $sql = "SELECT * FROM characterreference WHERE id = :id AND name = :name";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $id, ':name' => $name));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}
//  ============== E N D  C H A R A C T E R  R E F E R E N C E  ===============

//  ============== S T A R T  V E H I C L E  ===============
function getManufacturer($mysqli)
{
  $sql = "SELECT * FROM vehicle_manufacturer";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function saveVehicle($mysqli, $plateNo, $manufacturer, $model, $yearModel, $color, $permitNo, $issuedDate, $expiryDate)
{
  $sql = "INSERT INTO vehicle(`dateAdded`,`plateNo`,`manufacturer`,`model`,`yearModel`,`color`,`permitNo`,`issuedDate`,`expiryDate`) 
   VALUES (NOW(),:plateNo,:manufacturer,:model,:yearModel,:color,:permitNo,:issuedDate,:expiryDate)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':plateNo' => $plateNo,
      ':manufacturer' => $manufacturer,
      ':model' => $model,
      ':yearModel' => $yearModel,
      ':color' => $color,
      ':permitNo' => $permitNo,
      ':issuedDate' => $issuedDate,
      ':expiryDate' => $expiryDate
    )
  );
}

function getAllVehicles($mysqli)
{
  $sql = "SELECT * FROM vehicle";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}
//  ============== E N D   V E H I C L E  ===============

//  ============== S T A R T   V E H I C L E   R E S E R V A T I O N ===============

function saveVehicleRequest($mysqli, $division_section, $passengers, $places, $purpose, $contactPerson, $phoneNum, $departureTime, $departureDate, $arrivalTime, $arrivalDate, $requestedBy)
{
  $sql = "INSERT INTO vehicle_request(`userID`,`dateFiled`,`division_section`,`passengers`,`places`,`purpose`,`contactPerson`,`phone`,`departureTime`,`departureDate`,`arrivalTime`,`arrivalDate`,`requestedBy`) 
       VALUES (:user,NOW(),:division_section,:passengers,:places,:purpose,:contactPerson,:phoneNum,:departureTime,:departureDate,:arrivalTime,:arrivalDate,:requestedBy)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':user' => $_SESSION['userID'],
      ':division_section' => $division_section,
      ':passengers' => $passengers,
      ':places' => $places,
      ':purpose' => $purpose,
      ':contactPerson' => $contactPerson,
      ':phoneNum' => $phoneNum,
      ':departureTime' => $departureTime,
      ':departureDate' => $departureDate,
      ':arrivalTime' => $arrivalTime,
      ':arrivalDate' => $arrivalDate,
      ':requestedBy' => $requestedBy
    )
  );
}

function getMyReservations($mysqli)
{
  $sql = "SELECT * FROM vehicle_request WHERE userid = :user";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':user' => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllReservations($mysqli)
{
  $sql = "SELECT * FROM vehicle_request";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getMyReservationDetails($mysqli, $requestID, $userID)
{
  $sql = "SELECT * FROM vehicle_request WHERE userID = :userID AND id = :requestID";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID, ':requestID' => $requestID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}
//  ============== E N D   V E H I C L E   R E S E R V A T I O N ===============

//  ============== S T A R T  S T O R A G E ===============

function uploadFileToStorage($mysqli, $userID, $file, $fileType, $fileSize, $name)
{
  $sql = "INSERT INTO storage_upload (`userID`,`name`,`dateUpload`,`fileType`,`fileSize`,`fileName`) 
     VALUES (:user,:name,NOW(),:fileType,:fileSize,:fileName)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':user' => $userID,
      ':name' => $name,
      ':fileType' => $fileType,
      ':fileSize' => $fileSize,
      ':fileName' => $file
    )
  );
}

function saveDownloadHistory($mysqli, $name, $file, $fileType, $fileSize)
{
  $sql = "INSERT INTO storage_download (`userID`,`name`,`dateDownload`,`fileName`,`fileType`,`fileSize`) 
     VALUES (:user,:name,NOW(),:fileName,:fileType,:fileSize)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':user' => $_SESSION['userID'],
      ':name' => $name,
      ':fileName' => $file,
      ':fileType' => $fileType,
      ':fileSize' => $fileSize
    )
  );
  return true;
}

function getAllStorage($mysqli)
{
  $sql = "SELECT * FROM storage_upload";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllDownload($mysqli)
{
  $sql = "SELECT * FROM storage_download";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function checkExisting($mysqli, $file)
{
  $sql = "SELECT * FROM storage_upload WHERE fileName = :filename";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':filename' => $file));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function deleteDocuStorage($mysqli, $filename)
{
  $sql = "DELETE FROM storage_upload WHERE fileName = :filename";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':filename' => $filename));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

//  ============== E N D  S T O R A G E ===============

//  ============== S T A R T  D O C U M E N T  T R A C K I N G ===============

function dtsGetSenderList($mysqli)
{
  $sql = "SELECT * FROM dts_personconcerned GROUP BY firstName, lastName, division, email";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsCheckSender($mysqli, $firstName, $lastName, $email, $divAgency)
{
  $sql = "SELECT * FROM dts_personconcerned WHERE firstName = :firstName AND lastName = :lastName AND division = :divAgency AND email = :email";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":firstName" => $firstName, ":lastName" => $lastName, ":divAgency" => $divAgency, ":email" => $email));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsSaveRemarks($mysqli, $remarks, $id)
{
  $sql ="UPDATE dts_incomingfield SET remarks = :remarks WHERE id = :id";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(':remarks' => $remarks
          ,':id' => $id
    )
  );
  return;
}

function dtsSaveSender($mysqli, $firstName, $lastName, $email, $divAgency)
{
  $sql = "INSERT INTO dts_personconcerned(`createdDateTime`,`firstName`,`lastName`,`division`,`email`) 
  VALUES (NOW(),:firstName,:lastName,:divAgency,:email)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':firstName' => $firstName,
      ':lastName' => $lastName,
      ':email' => $email,
      ':divAgency' => $divAgency
    )
  );
  return;
}

function dtsGetDocuTypeList($mysqli)
{
  $sql = "SELECT * FROM dts_docuType GROUP BY documentType";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsCheckDocuType($mysqli, $docuType)
{
  $sql = "SELECT * FROM dts_docuType WHERE documentType = :docuType";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":docuType" => $docuType));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsSaveDocType($mysqli, $docuType)
{
  $sql = "INSERT INTO dts_docuType(`createdDateTime`,`documentType`) 
   VALUES (NOW(),:docuType)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':docuType' => $docuType
    )
  );
  // dtsGenerateQRCode($mysqli);
  return;
}

//function dtsSaveDocIncoming($mysqli, $personConcerned, $actionNeeded, $remarks, $actionTaken, $dateDone)
//{
  //$sql = "INSERT INTO dts_incoming('personConcerned','actionNeeded','remarks','actionTaken','dateDone','referenceNo') 
 //  VALUES (NOW(),:createdDateTime, :personConcerned, :actionNeeded, :remarks, :actionTaken, :dateDone";
  //$stmt = $mysqli->prepare($sql);
  //$stmt->execute(
   //array(
     //':personConcerned' => $personConcerned,
     // ':actionNeeded' => $actionNeeded,
     // ':remarks' => $remarks,
     // ':actionTaken' => $actionTaken,
      //':dateDone' => $dateDone
    //)
  //);
  
  //return "true";
//}

function dtsGetPersonConList($mysqli)
{
  $sql = "SELECT * FROM dts_personconcerned";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsCheckPerson($mysqli, $PCemail)
{
  $sql = "SELECT * FROM dts_personconcerned WHERE email = :email";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":email" => $PCemail));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsSavePersonCon($mysqli, $firstName, $lastName, $email, $divAgency)
{
  $sql = "INSERT INTO dts_personconcerned(`createdDateTime`,`firstName`,`lastName`,`email`,`division/agency`) 
   VALUES (NOW(),:firstName,:lastName,:email,:divAgency)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':firstName' => $firstName,
      ':lastName' => $lastName,
      ':email' => $email,
      ':divAgency' => $divAgency
    )
  );
  return "true";
}

function dtsGetReferenceNo($mysqli,  $year)
{
  $temp = array();
  $sql = "SELECT * FROM dts_incoming WHERE referenceNo LIKE :this";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':this' => "I-" . $year . '-' . '%'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllDocType($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM dts_docutype";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllSender($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM dts_personconcerned";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsCheckCategory($mysqli, $monthYear, $table)
{
  $temp = array();
  if ($table == "dts_incoming") {
    $sql = "SELECT * FROM dts_incoming WHERE referenceNo LIKE :this";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(array(':this' => "%-" . $monthYear . '-' . '%'));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row;
    }
  }
  if ($table == "dts_outgoing") {
    $sql = "SELECT * FROM dts_outgoing WHERE referenceNo LIKE :this";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(array(':this' => "%-" . $monthYear . '-' . '%'));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row;
    }
  }
  if ($table == "dts_specialorder") {
    $sql = "SELECT * FROM dts_specialorder WHERE referenceNo LIKE :this";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(array(':this' => "%-" . $monthYear . '-' . '%'));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row;
    }
  }
  if ($table == "dts_memorandum") {
    $sql = "SELECT * FROM dts_memorandum WHERE referenceNo LIKE :this";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(array(':this' => "%-" . $monthYear . '-' . '%'));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row;
    }
  }
  return $temp;
}


/// == INCOMING == ////
function dtsCheckIncoming($mysqli, $dtsPersonConcerned, $dtsActionTaken, $dtsDateDone, $dtsDocuActionNeeded, $dtsDocuRemarks)
{
  $sql = "SELECT * FROM dts_incoming 
  WHERE personConcerned = :personConcerned 
  AND actionNeeded = :actionNeeded 
  AND remarks = :remarks 
  AND actionTaken = :actionTaken
  AND dateDone -:dateDone";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":personConcerned " => $dtsPersonConcerned, ":actionNeeded " => $dtsDocuActionNeeded, ":remarks" => $dtsDocuRemarks, ":actionTaken" => $dtsActionTaken, ":dateDone" => $dtsDateDone));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsCheckParticulars($mysqli,$dtsParticulars){
  $sql = "SELECT * FROM dts_incoming WHERE particulars = :dtsParticulars";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":dtsParticulars" => $dtsParticulars));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $temp[] = $row;
  }
  return $temp;
}

function dtsSaveIncoming($mysqli, $category, $IreferenceNo, $Isender, $IdocumentType, $IdateReceived, $Isubject, $Iattachment)
{
  //save attachment information to attachments table
  //save incoming documents details to incoming table
  //save multiple details in dts_incomingfield table
  //upload attachment to server.
  $dtsAttachmentID = null;
 
    //If there's no error during the uploading of file save the file information to database
    if ($Iattachment['error'] == 0) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $Iattachment, $_SESSION['userID']);
      $sqlInsertAttachmentStmt->execute();
      $dtsAttachmentID =$mysqli->lastInsertId();
    }

    // FOR INCOMING
    if ($category == "Incoming") { // Start Incoming
      $sql = "INSERT INTO dts_incoming(`createdDateTime`,`category`,`referenceNo`,`sender`,`documentType`,`dateReceived`,`particulars`,`attachment`) 
        VALUES (NOW(),:category,:referenceNo,:sender,:documentType,:dateReceived,:particulars,:attachment)";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ':category' => $category,
          ':referenceNo' => $IreferenceNo,
          ':sender' => $Isender,
          ':documentType' => $IdocumentType,
          ':dateReceived' => $IdateReceived,
          ':particulars' => $Isubject,
          ':attachment' => $dtsAttachmentID
        )
      );
      }
      if ($dtsAttachmentID != null) {
        Attachment::Upload($Iattachment, '../../uploads/records/incoming', "", $dtsAttachmentID);
      }
      return "saved";
    } // End Incoming

    // FOR INCOMING OD
   function dtsSaveIncomingOD($mysqli,$id,$dtsDocuActionNeeded,$dtsDocuRemarks,$dtsActionTaken,$dtsDateDone, $dtsDocuDivision, $dtsDocuSection){
     // Start Incoming
      $sql = "INSERT INTO dts_incomingfield(`actionNeeded`,`remarks`,`actionTaken`,`dateDone`, `concernedDivision`,`concernedSection`,`referenceNo`) 
        VALUES (:actionNeeded,:remarks,:actionTaken,:dateDone, :concernedDivision, :concernedSection, :id)";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
         array(
          
          ':actionNeeded' => $dtsDocuActionNeeded,
          ':remarks' => $dtsDocuRemarks,
          ':actionTaken' => $dtsActionTaken,
          ':dateDone' => $dtsDateDone,
          ':concernedDivision' =>  $dtsDocuDivision,
          ':concernedSection' => $dtsDocuSection,
           ':id' => $id
        )
      );
       return "saved";
    } 

    function UpdateStatusIncoming($mysqli,$id,$statusType){
      $sql = "UPDATE dts_incomingfield SET 
              statusType=:statusType
              WHERE id=:id";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ':statusType' => $statusType,
          ':id'=> $id
        )
        );
        return "saved";
    }

    //FOR OUTGOING SENDER//

    function dtsSaveOutgoingSender($mysqli, $id, $Sender, $dtsActionTaken){
      $sql = "INSERT INTO dts_outgoingfield('addressedTo','actionTaken','referenceNo')
      VALUES (:addressedTo, :actionTaken, :id)";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(array(
        ':addressedTo' => $Sender,
        ':actionTaken' => $dtsActionTaken,
        ':id' => $id
      ));
      return "saved";
    }
   

   function getStatus($mysqli)
   {
     $sql = "SELECT * FROM dts_status WHERE disabled != TRUE";
     $stmt = $mysqli->prepare($sql);
     $stmt->execute();
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }


// Get Data 
function dtsGetIncomingDoc($mysqli, $id)
{
  $sql = "SELECT a.id as docuID, a.referenceNo, a.category, a.createdDateTime, a.particulars, a.dateReceived, CONCAT(b.firstName,' ', b.lastName) AS sender, c.documentType,  a.attachment
  FROM dts_incoming a, dts_personconcerned b, dts_docutype c
  WHERE b.id = a.sender
  AND c.id = a.documentType
  AND a.referenceNo = :id limit 1";
  //ORDER BY dts_incoming.referenceNo limit 1";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getDataIncomingTableOD($mysqli,$docuID)
{
  $sql = "SELECT  a.concernedDivision as id, a.id AS docID, a.concernedDivision AS person, a.concernedSection AS section, a.dateDone, a.actionNeeded, a.remarks, a.actionTaken, a.StatusType, c.referenceNo
  FROM dts_incomingfield a, dts_incoming c
  WHERE a.referenceNo = :docuID 
  AND a.referenceNo=c.id ";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':docuID' => $docuID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsGetIncomingDocus($mysqli)
{
  $sql = "SELECT dts_incoming.id,dts_incoming.referenceNo as referenceNo,dts_incoming.createdDateTime as createdDateTime,CONCAT(dts_personconcerned.firstName,' ',dts_personconcerned.lastName) as sender,dts_docutype.documentType as documentType,dts_incoming.particulars as subject, dts_incoming.dateReceived as dateReceived 
  FROM dts_incoming
  INNER JOIN dts_personconcerned ON dts_incoming.sender=dts_personconcerned.id 
  INNER JOIN dts_docutype ON dts_incoming.documentType=dts_docutype.id
  ORDER BY dts_incoming.createdDateTime DESC limit 10";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsGetAllIncomingDocus($mysqli)
{
  $sql = "SELECT dts_incoming.id,dts_incoming.referenceNo as referenceNo,dts_incoming.createdDateTime as createdDateTime,CONCAT(dts_personconcerned.firstName,' ',dts_personconcerned.lastName) as sender,dts_docutype.documentType as documentType, dts_incoming.dateReceived as dateReceived 
  FROM dts_incoming
  INNER JOIN dts_personconcerned ON dts_incoming.sender=dts_personconcerned.id 
  INNER JOIN dts_docutype ON dts_incoming.documentType=dts_docutype.id
  ORDER BY dts_incoming.createdDateTime DESC limit 10";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getIncoming($mysqli, $id)
{
  $sql = "SELECT id, createdDateTime, category, referenceNo, sender, documentType, particulars FROM dts_incoming WHERE id = :id";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}



function getIncomingDetails($mysqli, $id)
{
  /*
  1. Get Incoming Details
  2. Person c*/
  // $sql = "SELECT dts_incoming.referenceNo as referenceNo,dts_incoming.category as category, dts_incoming.createdDateTime as createdDateTime,CONCAT(dts_personconcerned.firstName,' ',dts_personconcerned.lastName) as sender,dts_docutype.documentType as documentType, dts_incoming.dateReceived as dateReceived, dts_incoming.particulars as particulars FROM dts_incoming INNER JOIN dts_personconcerned ON dts_incoming.sender=dts_personconcerned.id INNER JOIN dts_docutype ON dts_incoming.documentType=dts_docutype.id WHERE dts_incoming.referenceNo = :id";
  $sql = "SELECT b.id as documentID, b.referenceNo, a.personconcerned as person,
  a.actionNeeded, a.remarks, b.category, b.createdDateTime, b.attachment,
  CONCAT(c.firstName, c.lastName) as sender, d.documentType, 
  b.dateReceived, b.particulars, a.actionTaken, a.dateDone
  FROM dts_incomingfield a, 
  dts_incoming b, dts_personconcerned c, 
  dts_docutype d 
  WHERE c.id = b.sender 
  AND d.id = b.documentType
  AND a.referenceNo =b.id 
  AND b.referenceNo = :id limit 1";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}



function dtsGetPerson($mysqli, $id)
{
  $sql = "SELECT CONCAT(b.firstName, b.lastName) AS personConcerned FROM dts_personconcerned b WHERE b.id = :id";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":id" => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsGetIncomingPerson($mysqli, $id)
{
  $sql = "SELECT * FROM view_employeeinfo a, dts_incomingfield b 
  WHERE a.userID = b.personConcerned
  AND b.referenceNo = :id";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":id" => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getSOdetails($mysqli){
  $sql = "SELECT a.referenceNo, a.particulars
  FROM dts_specialorder a";
   $temp = array();
   $stmt = $mysqli->prepare($sql);
   $stmt->execute();
   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
   }
   return $temp;
}


function getSpecialOrderDetails($mysqli, $id){

  $sql = "SELECT a.id AS documentID, a.referenceNo,a.createdDateTime, a.particulars, a.AddressedTo AS person, a.StatusType, a.attachment
  FROM dts_specialorder a
  WHERE a.referenceNo = :id limit 1";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":id" => $id));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}
//  ============== S T A R T  P R I N T  INCOMING  ===============
//function printincoming($mysqli)
//{
//  $stmt1 = $mysqli->prepare("SELECT * FROM credentials WHERE userID = :w ");
 // $stmt1->execute(array(":w" => $_SESSION['userID']));
 // $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

 // if ($row1) {
  //  $temp = array();
  //  $addValiditystmt = $mysqli->prepare("SELECT * FROM personal_info WHERE userID = :x ");
  //  $addValiditystmt->execute(array(":x" => $row1['userID']));
  //  while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
   //   $temp[] = $row2;
   // }
   // return $temp;
 // }
//}
/// == OUTGOING == ///

function  getoutgoingView($mysqli){
  $sql = "SELECT CONCAT (b.firstName,' ', b.lastName) as sender
  FROM dts_personconcerned b 
  WHERE b.id = :id limit 1";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
}
return $temp;
}
 
function getOutgoingdetails($mysqli,$id){
  // $sql = "SELECT dts_incoming.referenceNo as referenceNo,dts_incoming.category as category, dts_incoming.createdDateTime as createdDateTime,CONCAT(dts_personconcerned.firstName,' ',dts_personconcerned.lastName) as sender,dts_docutype.documentType as documentType, dts_incoming.dateReceived as dateReceived, dts_incoming.particulars as particulars FROM dts_incoming INNER JOIN dts_personconcerned ON dts_incoming.sender=dts_personconcerned.id INNER JOIN dts_docutype ON dts_incoming.documentType=dts_docutype.id WHERE dts_incoming.referenceNo = :id";
$sql = "SELECT b.referenceNo, b.category, b.createdDateTime,
        CONCAT(c.firstName,' ', c.lastName) as sender, d.documentType, b.particulars, b.actionTaken, b.sender AS person, b.StatusType, b.attachment 
        FROM dts_outgoing b, dts_personconcerned c, dts_docutype d 
        WHERE c.id = b.addressedTo
        AND d.id = b.documentType
        AND b.referenceNo = :id limit 1";
$temp = array();
$stmt = $mysqli->prepare($sql);
$stmt->execute(array(':id' => $id));
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $temp[] = $row;
}
return $temp;

}
function dtsSaveOutgoing($mysqli, $category, $referenceNo, $sender, $documentType, $particulars, $dtsActionTaken, $dtsPersonConcerned, $attachment)
{
  //save attachment information to attachments table
  //save incoming documents details to incoming table
  //save multiple details in dts_incomingfield table
  //upload attachment to server.
  $dtsAttachmentID = null;
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //If there's no error during the uploading of file save the file information to database
    if ($attachment['error'] == 0) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $attachment, $_SESSION['userID']);
      $sqlInsertAttachmentStmt->execute();
      $dtsAttachmentID = $mysqli->lastInsertId();
    }

    // FOR OUTGOING
    // FOR OUTGOING
    if ($category == "Outgoing") { // Start Outgoing
      $sql = "INSERT INTO dts_outgoing(`createdDateTime`,`category`,`referenceNo`,`addressedTo`,`documentType`,`particulars`,`actionTaken`,`sender`,`attachment`) 
        VALUES (NOW(),:category,:referenceNo,:addressedTo,:documentType,:particulars,:actionTaken,:sender,:attachment)";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ':category' => $category,
          ':referenceNo' => $referenceNo,
          ':addressedTo' => $sender,
          ':documentType' => $documentType,
          ':particulars' => $particulars,
          ':actionTaken' => $dtsActionTaken,
          ':sender' => $dtsPersonConcerned,
          ':attachment' => $dtsAttachmentID
        )
      );

      $docuID = $mysqli->lastInsertId();
      foreach ($concernedPeople as $v) {
        $concernedPeople2[] = array(
          'createdDateTime' => $v['createdDateTime'],
          'addressedTo' => $v['addressedTo'],
          'referenceNo' => $docuID
        );
      }

      //insert multiple data at once
     //$insertConcernedPeople = PDOMultiInsert::constructStatement($mysqli, 'dts_outgoingfield', $concernedPeople2);
    // $insertConcernedPeople->execute();

      // copy the file to server
      if ($dtsAttachmentID != null) {
        Attachment::Upload($attachment, '../../uploads/records/outgoing', "", $dtsAttachmentID);
      }
      
      $mysqli->commit();
      return "saved";
    } // End Outgoing

  } catch (Exception $e) {
    $mysqli->rollBack();
    echo "Failed: " . $e->getMessage();
    return $e;
  }
}

function dtsGetOutgoingDocus($mysqli)
{
  $sql = "SELECT dts_outgoing.referenceNo as referenceNo,dts_outgoing.category as category, dts_outgoing.createdDateTime as createdDateTime,CONCAT(dts_personconcerned.firstName,' ',dts_personconcerned.lastName) as sender,dts_docutype.documentType as documentType, dts_outgoing.particulars as subject
  FROM dts_outgoing 
  INNER JOIN dts_personconcerned ON dts_outgoing.addressedTo=dts_personconcerned.id INNER JOIN dts_docutype ON dts_outgoing.documentType=dts_docutype.id
  ORDER BY dts_outgoing.createdDateTime DESC limit 10";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


//== END OF OUTGOING DTS ==//
// == SPECIAL ORDER == //
function dtsSaveSpecialOrder($mysqli, $category, $referenceNo, $particulars, $addressedTo, $attachment)
{
  //save attachment information to attachments table
  //save incoming documents details to incoming table
  //save multiple details in dts_incomingfield table
  //upload attachment to server.
  $dtsAttachmentID = null;
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //If there's no error during the uploading of file save the file information to database
    if ($attachment['error'] == 0) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $attachment, $_SESSION['userID']);
      $sqlInsertAttachmentStmt->execute();
      $dtsAttachmentID = $mysqli->lastInsertId();
    }

    $sql = "INSERT INTO dts_specialorder(`createdDateTime`,`category`,`referenceNo`,`particulars`,`AddressedTo`,`attachment`) 
        VALUES (NOW(),:category,:referenceNo,:particulars,:addressedTo,:attachment)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':category' => $category,
        ':referenceNo' => $referenceNo,
        ':particulars' => $particulars,
        ':addressedTo' => $addressedTo,
        ':attachment' => $dtsAttachmentID
      )
    );

    $docuID = $mysqli->lastInsertId();
    foreach ($concernedPeople as $v) {
      $concernedPeople2[] = array(
        'createdDateTime' => $v['createdDateTime'],
        'addressedTo' => $v['addressedTo'],
        'referenceNo' => $docuID
      );
    }

    //insert multiple data at once
    //$insertConcernedPeople = PDOMultiInsert::constructStatement($mysqli, 'dts_specialorderfield', $concernedPeople2);
    //$insertConcernedPeople->execute();
    // copy the file to server
    if ($dtsAttachmentID != null) {
      Attachment::Upload($attachment, '../../uploads/records/specialorder', "", $dtsAttachmentID);
    }

    $mysqli->commit();
    return "saved";
  } catch (Exception $e) {
    $mysqli->rollBack();
    echo "Failed: " . $e->getMessage();
    return $e;
  }
}

function dtsGetSpecialOrderDocus($mysqli)
{
  $sql = "SELECT dts_specialorder.referenceNo as referenceNo, dts_specialorder.category as category, dts_specialorder.createdDateTime as createdDateTime, dts_specialorder.particulars as subject FROM dts_specialorder
  ORDER BY dts_specialorder.createdDateTime DESC limit 10";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dtsSaveMemorandum($mysqli, $category, $referenceNo, $particulars, $addressedTo, $attachment)
{
  //save attachment information to attachments table
  //save incoming documents details to incoming table
  //save multiple details in dts_incomingfield table
  //upload attachment to server.
  $dtsAttachmentID = null;
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //If there's no error during the uploading of file save the file information to database
    if ($attachment['error'] == 0) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $attachment, $_SESSION['userID']);
      $sqlInsertAttachmentStmt->execute();
      $dtsAttachmentID = $mysqli->lastInsertId();
    }

    $sql = "INSERT INTO dts_memorandum(`createdDateTime`,`category`,`referenceNo`,`particulars`,`AddressedTo`,`attachment`) 
        VALUES (NOW(),:category,:referenceNo,:particulars,:addressedTo,:attachment)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':category' => $category,
        ':referenceNo' => $referenceNo,
        ':particulars' => $particulars,
        ':addressedTo' => $addressedTo,
        ':attachment' => $dtsAttachmentID
      )
    );

    $docuID = $mysqli->lastInsertId();
    foreach ($concernedPeople as $v) {
      $concernedPeople2[] = array(
        'createdDateTime' => $v['createdDateTime'],
        'addressedTo' => $v['addressedTo'],
        'referenceNo' => $docuID
      );
    }

    //insert multiple data at once
    //$insertConcernedPeople = PDOMultiInsert::constructStatement($mysqli, 'dts_memorandumfield', $concernedPeople2);
   // $insertConcernedPeople->execute();

    // copy the file to server
    if ($dtsAttachmentID != null) {
      Attachment::Upload($attachment, '../../uploads/records/memorandum', "", $dtsAttachmentID);
    }

    $mysqli->commit();
    return "saved";
  } catch (Exception $e) {
    $mysqli->rollBack();
    echo "Failed: " . $e->getMessage();
    return $e;
  }
}

// download attachment -TESTING-

function dtsGetMemorandumDocus($mysqli)
{
  $sql = "SELECT dts_memorandum.referenceNo as referenceNo, dts_memorandum.category as category, dts_memorandum.createdDateTime as createdDateTime, dts_memorandum.particulars as subject FROM dts_memorandum
  ORDER BY dts_memorandum.createdDateTime DESC limit 10";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getMemorandumDetails($mysqli, $id){
  $sql = "SELECT a.id AS documenID, a.referenceNo, a.createdDateTime, a.particulars, a.AddressedTo AS person, a.StatusType, a.attachment
  FROM dts_memorandum a
  WHERE a.referenceNo = :id limit 1";
   $temp = array();
   $stmt = $mysqli->prepare($sql);
   $stmt->execute(array(":id" => $id));
   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
     $temp[] = $row;
   }
   return $temp;
 }
 // Attachment // --TESTING
 

//function dtsGenerateQRCode($mysqli)
//{
  // S T A R T  Q R  C O D E
 // $last_id = $mysqli->lastInsertId();
 /// $encrypted_travel_id = md5(md5(base64_encode($last_id)));

  //include('vendor/qrcode/libs/phpqrcode/qrlib.php');
 // $tempDir = 'vendor/qrcode/temp/';
 /// $approved = md5(md5('wahahaha'));
 // $filename = $encrypted_travel_id;
//  $codeContents = $encrypted_travel_id . '&id=' . $approved;
  //QRcode::png($codeContents, $tempDir . '' . $filename . '.png', QR_ECLEVEL_L, 5);
  // E N D  Q R  C O D E
//}
//  ============== E N D  D O C U M E N T  T R A C K I N G ===============

//  ============== S T A R T  NEWSLETTER ===============
function checkNewsletter($mysqli, $title)
{
  $sql = "SELECT * FROM newsletter WHERE title = :title";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":title" => $title));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function checkVolume($mysqli, $volume)
{
  $sql = "SELECT * FROM newsletter_volume WHERE vol_number = :volume";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":volume" => $volume));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getNewsLetterVolume($mysqli)
{
  $sql = "SELECT * FROM newsletter_volume";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}
function viewletter($pdo, $id_issue)
{
  $sql = "SELECT newsletter.title as title,
  newsletter.description as description,
  newsletter.issue_month as issue_idz,
  newsletter.id as newsletterid,
  newsletter.author as newsletterauthor,
  newsletter.dateUploaded as news_dateUploaded,
  newsletter_issue.id as isyu_id,
  newsletter_issue.vol_number as num_vol,
  newsletter_issue.month as buwan,
  newsletter_issue.date_from as from_date,
  newsletter_issue.date_from as to_date
  FROM newsletter
  INNER JOIN newsletter_issue ON newsletter_issue.id=newsletter.issue_month WHERE newsletter_issue.id = :isyu_aydi";
  $temp = array();
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(":isyu_aydi" => $id_issue));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getNewsLetters($mysqli, $vol_number)
{
  $temp = array();
  $sql = "SELECT newsletter.title as title,
  newsletter.description as description,
  attachments.fileName as fileName, attachments.size as fileSize,attachments.id as fileID,
  attachments.fileExtension as fileExtension FROM newsletter INNER JOIN attachments ON attachments.id=newsletter.attachment ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  return $temp;
} // END GET NEWSLETTER

function getVolume($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM newsletter_volume";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  return $temp;
} // END GET VOLUME

function getIssue($mysqli)
{
  $temp = array();
  $sql = "SELECT * FROM newsletter_issue";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  return $temp;
} // END GET ISSUE

function saveVolume($mysqli, $VolumeNumber)
{
  $sql = "INSERT INTO newsletter_volume(`vol_number`) 
      VALUES (:volume_)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ':volume_' => $VolumeNumber
    )
  );
  return true;
}
function saveIssue($mysqli, $issueVolume_no, $issuefrom_date, $issueto_date, $issue_month_no, $issue_attachment, $issue_id)
{

  //save attachment information to attachments table
  //save details to newsletter table
  //upload attachment to server.
  $IssueAttachmentID = null;
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //If there's no error during the uploading of file save the file information to database
    if ($issue_attachment['error'] == 0) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $issue_attachment, $_SESSION['userID']);
      $sqlInsertAttachmentStmt->execute();
      $IssueAttachmentID = $mysqli->lastInsertId();
    }


    //save incoming documents details to incoming table
    $sql = "INSERT INTO newsletter_issue(`vol_number`, `date_from`, `date_to`, `month`, `issue_header`, `issue_id`) 
        VALUES (:vol_number_, :date_from_, :date_to_, :month_, :issue_header_, :issue_id_)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':vol_number_' => $issueVolume_no,
        ':date_from_' => $issuefrom_date,
        ':date_to_' => $issueto_date,
        ':month_' => $issue_month_no,
        ':issue_header_' => $IssueAttachmentID,
        ':issue_id_' => $issue_id
      )
    );
    //copy the file to server
    if ($IssueAttachmentID != null) {
      Attachment::Upload($issue_attachment, '../../uploads/newsletter/', "", $IssueAttachmentID);
    }

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollBack();
    echo "Failed: " . $e->getMessage();
  }
  // return "saved";
}


function saveNewsletter($mysqli, $newsLetterTitle, $newsLetterDescription, $newsLetterAttachment, $newsLetterauthor, $newsLetterIssueno, $newsletterlead, $newsletterdate)
{

  //save attachment information to attachments table
  //save details to newsletter table
  //upload attachment to server.
  $newsLetterAttachmentID = null;
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    //If there's no error during the uploading of file save the file information to database
    if ($newsLetterAttachment['error'] == 0) {
      $sqlInsertAttachmentStmt = Attachment::constructStatement($mysqli, 'attachments', $newsLetterAttachment, $_SESSION['userID']);
      $sqlInsertAttachmentStmt->execute();
      $newsLetterAttachmentID = $mysqli->lastInsertId();
    }


    //save incoming documents details to incoming table
    $sql = "INSERT INTO newsletter(`dateUploaded`,`title`,`description`,`attachment`, `issue_month`, `lead`, `date_letter`) 
        VALUES (NOW(), :title_, :description_, :attachment_,  :issue_month_, :lead_, :date_letter_)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':title_' => $newsLetterTitle,
        ':description_' => $newsLetterDescription,
        ':attachment_' => $newsLetterAttachmentID,
        ':issue_month_' => $newsLetterIssueno,
        ':lead_' => $newsletterlead,
        ':date_letter_' => $newsletterdate
      )
    );
    $newsletter_id_id = $mysqli->lastInsertId();
    for ($i = 0; $i < count($newsLetterauthor); $i++) {
      $author = $newsLetterauthor[$i];
      $sql = "INSERT INTO newsletter_authors(`author_id`,`newsletter_id`) 
          VALUES (:author_, :newsletter_id)";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ':author_' => $author,
          ':newsletter_id' => $newsletter_id_id
        )
      );
    }
    //copy the file to server
    if ($newsLetterAttachmentID != null) {
      Attachment::Upload($newsLetterAttachment, '../../uploads/newsletter/', "", $newsLetterAttachmentID);
    }

    $mysqli->commit();
    return true;
  } catch (Exception $e) {
    $mysqli->rollBack();
    echo "Failed: " . $e->getMessage();
  }
  // return "saved";
}

function viewthisletter($mysqli, $newsletter_id2)
{
  $sql = "SELECT * FROM newsletter WHERE id = :news_letter_id";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":news_letter_id" => $newsletter_id2));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

//  ============== END  NEWSLETTER ===============


function getR4DISInfo($mysqli, $userID)
{
  $sqlGetR4DISInfo = "SELECT * FROM view_r4dis_users WHERE userID = :userid LIMIT 1";
  $sqlGetR4DISInfoStmt = $mysqli->prepare($sqlGetR4DISInfo);
  $sqlGetR4DISInfoStmt->execute([
    ':userid' => $userID,
  ]);

  //fetch returns a single row
  return $sqlGetR4DISInfoStmt->fetch();
}

function getUserPassword($mysqli, $userID)
{
  $sqlGetPassword = "SELECT password FROM credentials WHERE userID = :userid";
  $sqlGetPasswordStmt = $mysqli->prepare($sqlGetPassword);
  $sqlGetPasswordStmt->execute([
    ':userid' => $userID,
  ]);

  //fetch returns a single column
  return $sqlGetPasswordStmt->fetchColumn();
}

function getUserPasswordByUsername($mysqli, $username)
{
  $sqlGetPassword = "SELECT password FROM credentials WHERE username  = :username";
  $sqlGetPasswordStmt = $mysqli->prepare($sqlGetPassword);
  $sqlGetPasswordStmt->execute([
    ':username' => $username,
  ]);

  //fetch returns a single column
  return $sqlGetPasswordStmt->fetchColumn();
}

function updateUserPasswordByUserID($mysqli, $userID, $newPassword)
{
  $sql = "UPDATE credentials SET password = :newpassword, updatePassword = FALSE WHERE userID = :userID";
  $stmt = $mysqli->prepare($sql);
  return $stmt->execute([
    ':userID' => $userID,
    ':newpassword' => $newPassword

  ]);
}

function updateUserPasswordByUsername($mysqli, $username, $newPassword)
{
  $sql = "UPDATE credentials SET password = :newpassword, updatePassword = FALSE WHERE username = :username";
  $stmt = $mysqli->prepare($sql);
  return $stmt->execute([
    ':username' => $username,
    ':newpassword' => $newPassword

  ]);
}

function setResetHashToUsed($mysqli, $resetHash)
{
  $sql = "UPDATE account_resetlinks SET used = TRUE WHERE resetHash = :resethash";
  $stmt = $mysqli->prepare($sql);
  return $stmt->execute([
    ':resethash' => $resetHash
  ]);
}

function getUserCredentials($mysqli, $username)
{
  $stmt = $mysqli->prepare("SELECT * FROM credentials WHERE username = :username AND disabled != true");
  $stmt->execute(array(
    ":username" => $username
  ));
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPasswordUpdate($mysqli, $username)
{
  $sql = "SELECT updatePassword FROM credentials WHERE username = :username";
  $Stmt = $mysqli->prepare($sql);
  $Stmt->execute([
    ':username' => $username,
  ]);

  //fetch returns a single column
  return $Stmt->fetchColumn();
}

function validateForgotPassword($mysqli, $username)
{
  //check if username exists in database.
  $stmt = $mysqli->prepare("SELECT EXISTS (SELECT 1 FROM `credentials` WHERE username = :username LIMIT 1)");
  $stmt->execute(array(
    ":username" => $username
  ));
  return $stmt->fetchColumn();
}

function hasExistingForgotPasswordLink($mysqli, $username)
{
  //try to retrieve a valid request created from the past 24 hours.
  $stmt = $mysqli->prepare("SELECT EXISTS (SELECT 1 FROM `account_resetlinks` WHERE username = :username AND used = FALSE AND createdDateTime > NOW() - INTERVAL 1 DAY LIMIT 1)");
  $stmt->execute(array(
    ":username" => $username
  ));
  return $stmt->fetchColumn();
}

function validateResetPasswordHash($mysqli, $resetHash)
{
  $stmt = $mysqli->prepare("SELECT EXISTS (SELECT 1 FROM `account_resetlinks` WHERE resetHash = :resethash AND used = FALSE  AND createdDateTime > NOW() - INTERVAL 1 DAY LIMIT 1)");
  $stmt->execute(array(
    ":resethash" => $resetHash
  ));
  return $stmt->fetchColumn();
}

function createResetPasswordLink($mysqli, $username, $resetHash, $requestorIPAddress)
{
  $sqlInsert = "INSERT INTO account_resetlinks(`userName`, `resetHash`, `requestorIPAddress`, `createdDateTime`)  
                VALUES(:username, :resethash, :requestorip, NOW())";

  $stmt = $mysqli->prepare($sqlInsert);
  return $stmt->execute([
    ':username' => $username,
    ':resethash' => $resetHash,
    ':requestorip' => $requestorIPAddress
  ]);
}

function getUserNameByResetHash($mysqli, $resetHash)
{

  $sql = "SELECT username FROM account_resetlinks WHERE resetHash = :resethash";
  $Stmt = $mysqli->prepare($sql);
  $Stmt->execute([
    ':resethash' => $resetHash,
  ]);

  //fetch returns a single column
  return $Stmt->fetchColumn();
}

function emailForgotPasswordLink($email, $resetHash)
{

  include("../PHPMailer/PHPMailer.php");
  include("../PHPMailer/SMTP.php");
  include("../PHPMailer/Exception.php");
  include("../PHPMailer/PHPMailerAutoload.php");
  include("../PHPMailer/class.phpmailer.php");
  $content = '
  <!DOCTYPE html>
  <body style="margin:auto; background-color: #e9e9e9; border-radius: 7px; ">

    <div style="display: flex; align-items: center; justify-content: center; padding: 20px; " class="container">

        <div style="display: flex; padding: 20px; align-items: center; justify-content: center;border-radius: 10px; border: dashed 2px black; background-color: #191919;" class="content">

            <div class="information">
                <center>
                    <div style="color: white; font-family:sans-serif;" class="header">
                        <h2>Reset Password Request</h2>
                        <small> BARPortal.Support@gmail.com</small>
                    </div>
                    <br>

                    <div style="color: white; font-family:sans-serif;" class="context">
                        <p style="background-color: #242424; padding: 25px; border:1px dashed black; border-radius: 5px;">
                            You recently have requested to reset your password for your BAR Portal Account. Click the button below to reset it.
                        </p>
                        <br>
                        <a href=" http://compendium.bar.gov.ph/BARPortal_Test/View/Account/ResetPassword.php?rpw=' . $resetHash . '">
                            <button type="button" style=" box-shadow: 2px 2px black; background-color:red; color:white; border:none; border-radius: 20px; padding: 10px 15px;  cursor:pointer; font-size: medium; font-weight:600;" class="button h2">Reset Password</button>
                        </a>
                    </div>
                    <br>
                    <div style="align-items: center; justify-content: center; color: white; font-family:sans-serif; border-top: 2px dashed #3D3D3D; " class="footer mt-3">

                        <br>
                        <small style="margin-bottom: 100px; background-color: #fbbc04; color: #222222;  border-left:5px solid red;  border-radius: 5px; padding: 5px">
              <b>This password reset is valid for 24 hours.</b> </small>
                        <br>
                        <br>
                        <small> <b>If this isn\'t you, you may ignore this email.</b> </small>
                    </div>
                </center>
            </div>
        </div>
    </div>
  </body>
  </html>
  ';


  $subject = "Reset password request: " . $email;
  $body = $content;


  $mail = new PHPMailer();


  //smtp settings
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPDebug = SMTP::DEBUG_OFF;

  $mail->CharSet = 'UTF-8';
  $mail->Debugoutput = 'html';
  $mail->SMTPAuth = true;
  $mail->Username = 'BARPortal.support@bar.gov.ph';
  // $mail->Username = 'barportal.notifications@bar.gov.ph';

  // $mail->Password =  'bar@admin2021';
  $mail->Password =  'ikldnaicudklderv';
  // $mail->Password =  'xgiukfqhimjkxndo';

  $mail->Port = 25;
  //$mail->Port = 25; - local
  // $mail->Port = 587; - live
  // $mail->Port = 465; - di ko alam search mo nalang php mailer port
  $mail->SMTPSecure = 'ssl';
  $mail->SMTPAutoTLS = false;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

  //email settings
  $mail->setFrom($mail->Username);
  $mail->addAddress($email);
  $mail->Subject = $subject;
  $mail->Body = html_entity_decode($body);
  $mail->isHTML(true);
  $mail->ClearCustomHeaders();
  if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  } else {
    echo 'Email sent!';
  }
}

function generateCode()
{
  //update this to generate temporary password
  $keyLength = 4;
  $str = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()!$#@*";
  $randStr = substr(str_shuffle($str), 0, $keyLength);
  $_SESSION['code'] = $randStr;
}

function getAllSpecialOrders($mysqli)
{
  $sql = "SELECT * FROM special_order";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//  ============== S T A R T  D O R M  R E S E R V A T I O N ===============

function dormGetThisGuest($mysqli, $controlNo)
{
  // $sql = "SELECT dormreservation.*,credentials.username as recommendedBy,credentials.username as approvedBy FROM dormreservation INNER JOIN credentials ON dormreservation.recommendedBy = credentials.userID WHERE dormreservation.controlNo = :controlNo";
  $sql = "SELECT dormreservation.*,a.username as recommendBy,b.username as approveBy,c.username as disapproveBy,d.username as paymentOrderedBy,e.username as receiptBy FROM dormreservation 
    LEFT JOIN credentials a ON dormreservation.recommendedBy = a.userID 
    LEFT JOIN credentials b ON dormreservation.approvedBy = b.userID
    LEFT JOIN credentials c ON dormreservation.disapprovedBy = c.userID
    LEFT JOIN credentials d ON dormreservation.paymentOrderBy = d.userID
    LEFT JOIN credentials e ON dormreservation.receiptBy = e.userID WHERE dormreservation.controlNo = :controlNo";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':controlNo' => $controlNo));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// for extended guests
function dormGetThisExtendedGuest($mysqli, $controlNo)
{
  // $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.amountWords as extendedAmountWords,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPayment,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dormreservation.controlNo = :controlNo";
  $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.amountWords as extendedAmountWords,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPayment,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,a.username as extensionApprovedBy,b.username as extensionPaymentOrderBy,c.username as extensionReceiptBy,d.username as extensionDisapprovedBy,dorm_extension.approvedDateTime as extensionApprovedDateTime,dorm_extension.disapprovedDateTime as extensionDisapprovedDateTime,dorm_extension.paymentOrderDateTime as extensionPaymentOrderDateTime,dorm_extension.receiptDateTime as extensionReceiptDateTime,dorm_extension.status as extendedStatus
    FROM dormreservation
    INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id 
    LEFT JOIN credentials a ON dorm_extension.approvedBy = a.userID 
    LEFT JOIN credentials b ON dorm_extension.paymentOrderBy = b.userID 
    LEFT JOIN credentials c ON dorm_extension.receiptBy = c.userID 
    LEFT JOIN credentials d ON dorm_extension.disapprovedBy = d.userID 
    WHERE dormreservation.controlNo = :controlNo";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':controlNo' => $controlNo));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormUpdateControlNo($mysqli, $controlNo, $status, $color, $email, $dateFiled, $dateCheckIn, $timeCheckIn, $payment, $numberOfNights, $amount, $amountWords, $dateCheckOut, $emailRecipient)
{

  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();


    if ($status == "recommended") {

      $sql = "UPDATE dormreservation SET status = :status, color = :color, payment = :payment, amount = :amount, numberOfNights = :numberOfNights, amountWords = :amountWords,recommendedBy = :recommendedBy,recommendedDateTime = NOW() WHERE controlNo = :controlNo";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ":status" => $status,
          ":color" => $color,
          ":controlNo" => $controlNo,
          ":payment" => $payment,
          ":amount" => $amount,
          ":numberOfNights" => $numberOfNights,
          ":amountWords" => $amountWords,
          ":recommendedBy" => $_SESSION['userID']
        )
      );
      $subject = "[BAR DORM RESERVATION] Pending Approval Request";
      $content = nl2br("Good day, \r\n\n" .
        "You have new pending approval. Control Number: <strong>" . $controlNo . ".</strong> \n <html><body><span>Click <a href='https://compendium.bar.gov.ph/BARPortal/'>here</a>  to login.</span></body></html>\nThank you.\n\n");

      $res = dormSendNotification($emailRecipient, $controlNo, $content, $subject);
    }

    if ($status == "approved" && $payment == "NOT PAYING") {
      $sql = "UPDATE dormreservation SET status = :status, color = :color,approvedBy = :approvedBy,approvedDateTime = NOW() WHERE controlNo = :controlNo";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ":status" => $status,
          ":color" => $color,
          ":controlNo" => $controlNo,
          ":approvedBy" => $_SESSION['userID']
        )
      );
      $subject = "[BAR DORM RESERVATION] Approved Request";
      $content = nl2br("Good day, \r\n\n" . "This is to confirm that the reservation you made on " . $dateFiled . " has been approved with control number <b>" . $controlNo . "</b>. See you on <b>" . $dateCheckIn . "</b> at <b>" . $timeCheckIn . "</b>. \n\n" .  "<i>Important Reminders:</i> \n\t 1. Bring the print copy of the accomplished declaration form for each guest during check-in. \n\t 2. Vaccination Card for each guest is required. In the absence of proof of vaccination, a negative RT-PCR result is required taken within three days before the scheduled check-in date.  \n\t 3. Those guests who wish to extend their stay can request through this link <u> https://compendium.bar.gov.ph/DormReservation/extension.php</u>.\n\t\t Requests should be made before the day of check-out.\n\t\t Requests can be accommodated from 8 am - 12 nn (weekdays) only. \n\n" . "<i>Thank you and enjoy your stay.</i>\n\n\n");
      $attachment = 'BAR-ADD-OP-21F1_Health_Declaration_Form.docx';
      $res = dormSendConfirmation($controlNo, $email, $status, $content, $attachment, $subject);
    }

    if ($status == "approved" && $payment == "PAYING") {
      $sql = "UPDATE dormreservation SET status = :status, color = :color,approvedBy = :approvedBy,approvedDateTime = NOW() WHERE controlNo = :controlNo";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ":status" => $status,
          ":color" => $color,
          ":controlNo" => $controlNo,
          ":approvedBy" => $_SESSION['userID']
        )
      );
      $subject = "[BAR DORM RESERVATION] Approved Request";
      $content = nl2br("Good day, \r\n\n" . "This is to confirm that the reservation you made on " . $dateFiled . " has been approved with control  number <b>" . $controlNo . "</b>. See you on <b>" . $dateCheckIn . "</b> at <b>" . $timeCheckIn . "</b>. \n\n" . "<i>Important Reminders:</i> \n\t 1. Bring the print copy of the accomplished health declaration form for each guest during check-in. \n\t 2. Vaccination Card for each guest is required. In the absence of proof of vaccination, a negative RT-PCR result is required taken within three days before the scheduled check-in date.  \n\t 3. Prepare <b>PhP " . $amount . "</b> and proceed to the cashier for payment transaction. \n\t 4. Payment is non-refundable. \n\t 5. Those guests who wish to extend their stay can request through this link <u> https://compendium.bar.gov.ph/DormReservation/extension.php</u>.\n\t\t Requests should be made before the day of check-out.\n\t\t Requests can be accommodated from 8 am - 12 nn (weekdays) only.\n\n" . "<i>Thank you and enjoy your stay.</i>\n\n\n");
      $attachment = 'BAR-ADD-OP-21F1_Health_Declaration_Form.docx';

      // checking number of nights
      if ($numberOfNights == 1)
        $stay = "night";
      if ($numberOfNights > 1)
        $stay = "nights";

      $subject2 = "[BAR DORM RESERVATION] Pending Request for Payment Order";
      $content2 = nl2br("Good day, \r\n\n" .
        "You have new pending payment order for " . $numberOfNights . " " . $stay . ". Control Number: <strong>" . $controlNo . ".</strong> \n <html><body><span>Click <a href='https://compendium.bar.gov.ph/BARPortal/'>here</a>  to login.</span></body></html>\nThank you.\n\n");

      // send email confirmation to guest then send email notif to accounting
      $res = dormSendNotifConfirmation($controlNo, $email, $emailRecipient, $content, $content2, $attachment, $subject, $subject2);
    }

    if ($status == "disapproved") {
      $sql = "UPDATE dormreservation SET status = :status, color = :color,disapprovedBy = :disapprovedBy,disapprovedDateTime = NOW() WHERE controlNo = :controlNo";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ":status" => $status,
          ":color" => $color,
          ":controlNo" => $controlNo,
          ":disapprovedBy" => $_SESSION['userID']
        )
      );
      $subject = "[BAR DORM RESERVATION] Regret Notification";
      $content = nl2br("Good day, \r\n\n" . "We regret to inform you that the reservation you made for <b>" . $dateCheckIn . "</b> to <b>" . $dateCheckOut . "</b> with control number <b>" . $controlNo . "</b> has been declined due to date conflict. \n You may wish to book on another day. Thank you. \n\n\n");
      $attachment = "";
      $res = dormSendConfirmation($controlNo, $email, $status, $content, $attachment, $subject);
    }

    if ($res == "success") {
      $mysqli->commit();
    }

    return $res;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

// update payment order status
function dormUpdatePaymentOrderStatus($mysqli, $paymentOrderStatus, $controlNo, $cashier)
{
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    $sql = "UPDATE dormreservation SET
            paymentOrderStatus = :paymentOrderStatus,paymentOrderBy = :paymentOrderBy,paymentOrderDateTime = NOW()
            WHERE controlNo = :controlNo";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ":paymentOrderStatus" => $paymentOrderStatus,
        ":controlNo" => $controlNo,
        ":paymentOrderBy" => $_SESSION['userID']
      )
    );

    $subject = "[BAR DORM RESERVATION] Notice for Receipt ";
    $content = nl2br("Good day, \r\n\n" .
      "This is to notify you that payment order with control number <strong>" . $controlNo . "</strong> is ready for signature. \nPlease wait for the hardcopy that will be handed to your office.\n<html><body><span>Click <a href='https://compendium.bar.gov.ph/BARPortal/'>here</a>  to see details.</span></body></html>\n Thank you.\n\n");

    $res = dormSendNotification($cashier, $controlNo, $content, $subject);

    if ($res == "success")
      $mysqli->commit();

    return $res;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

// update payment order status -EXTENSION
function dormUpdateExtendedPaymentOrderStatus($mysqli, $paymentOrderStatus, $controlNo, $cashier)
{
  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    $sql = "UPDATE dorm_extension SET
          paymentOrderStatus = :paymentOrderStatus,paymentOrderBy = :userID,paymentOrderDateTime = NOW()
          WHERE controlNo = :controlNo";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ":paymentOrderStatus" => $paymentOrderStatus,
        ":controlNo" => $controlNo,
        ":userID" => $_SESSION['userID']
      )
    );

    $subject = "[BAR DORM RESERVATION] Notice for Receipt of Extended Reservation";
    $content = nl2br("Good day, \r\n\n" .
      "This is to notify you that payment order for <strong>EXTENSION</strong> with a control number <strong>" . $controlNo . "</strong> is ready for signature. \nPlease wait for the hard copy that will be handed to your office. \n <html><body><span>Click <a href='https://compendium.bar.gov.ph/BARPortal/'>here</a>  to login.</span></body></html>\nThank you.\n\n");

    $res = dormSendNotification($cashier, $controlNo, $content, $subject);
    if ($res == "success")
      $mysqli->commit();

    return $res;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

// update payment status
function dormUpdatePaymentStatus($mysqli, $paymentStatus, $controlNo)
{
  $sql = "UPDATE dormreservation SET
          payment = :paymentStatus,receiptBy = :receiptBy,receiptDateTime = NOW()
          WHERE controlNo = :controlNo";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":paymentStatus" => $paymentStatus,
      ":controlNo" => $controlNo,
      ":receiptBy" => $_SESSION['userID']
    )
  );

  return "updated";
}

// update payment status -EXTENSION
function dormUpdateExtendedPaymentStatus($mysqli, $paymentStatus, $controlNo)
{
  $sql = "UPDATE dorm_extension SET
          payment = :paymentStatus,receiptBy = :userID,receiptDateTime = NOW()
          WHERE controlNo = :controlNo";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(
    array(
      ":paymentStatus" => $paymentStatus,
      ":controlNo" => $controlNo,
      ":userID" => $_SESSION['userID']
    )
  );

  return "updated";
}

// save extension details
function dormSaveExtension($mysqli, $controlNo, $newDateCheckOut, $newTimeCheckOut, $extendReason, $numberOfNights, $amount, $amountWords, $paymentStatus, $email, $accounting)
{

  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    $sql = "INSERT INTO dorm_extension(`dateFiled`,`controlNo`,`dateCheckOut`,`timeCheckOut`,`extendedNights`,`amount`,`amountWords`,`paymentOrderStatus`,`payment`,`reason`,`status`,`approvedBy`,`approvedDateTime`)
       VALUES (NOW(),:controlNo,:dateCheckOut,:timeCheckOut,:extendedNights,:amount,:amountWords,:paymentOrderStatus,:payment,:reason,:status,:approvedBy,NOW())";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':controlNo' => $controlNo,
        ':dateCheckOut' => $newDateCheckOut,
        ':timeCheckOut' => $newTimeCheckOut,
        ':extendedNights' => $numberOfNights,
        ':amount' => $amount,
        ':amountWords' => $amountWords,
        ':paymentOrderStatus' => "",
        ':payment' => $paymentStatus,
        ':reason' => $extendReason,
        ':status' => "approved",
        ':approvedBy' => $_SESSION['userID']
      )
    );

    $extensionId = $mysqli->lastInsertId();

    $sql = "UPDATE dormreservation SET
          extension = :extension
          WHERE controlNo = :controlNo";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ":extension" => $extensionId,
        ":controlNo" => $controlNo
      )
    );


    if ($paymentStatus == "PAYING") {
      $subject = "[BAR DORM RESERVATION] Dorm Extension";
      $content = nl2br("Good day, \r\n\n" . "This is to confirm that the dorm extension you requested with control number <b>" . $controlNo . "</b> has been approved. \n\n" . "<i>Important Reminders:</i> \n\t 1. Prepare <b>PhP " . $amount . "</b> and proceed to the cashier for payment transaction before 12 NN. \n\t 2. Payment is non-refundable. \n\n" . "<i>Thank you and enjoy your stay.</i>\n\n\n");

      // checking number of nights
      if ($numberOfNights == 1)
        $stay = "1 night";
      if ($numberOfNights > 1)
        $stay = "nights";

      $subject2 = "[BAR DORM RESERVATION] Pending Request for Payment Order of Extended Reservation ";
      $content2 = nl2br("Good day, \r\n\n" .
        "You have new pending payment order extended for " . $stay . ". Control Number: <strong>" . $controlNo . ".</strong> \n <html><body><span>Click <a href='https://compendium.bar.gov.ph/BARPortal/'>here</a>  to login.</span></body></html>\nThank you.\n\n");
      // send email confirmation to guest then send email notif to accounting
      $res = dormSendNotifConfirmation($controlNo, $email, $accounting, $content, $content2, "", $subject, $subject2);
    } else {
      $subject = "[BAR DORM RESERVATION] Dorm Extension";
      $content = nl2br("Good day, \r\n\n" . "This is to confirm that the dorm extension you requested with control number <b>" . $controlNo . "</b> has been approved.\nThank you and enjoy your stay.\n\n\n");
      $res = dormSendConfirmation($controlNo, $email, "", $content, "", $subject);
    }

    if ($res == "success")
      $mysqli->commit();

    return $res;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

// approve/disapprove extension request
function dormApproveDisapproveExtension($mysqli, $controlNo, $status, $paymentStatus, $numberOfNights, $email, $amount, $newDateCheckOut, $accounting)
{

  try {
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    $sql = "UPDATE dorm_extension SET
            payment = :paymentStatus,status = :status,approvedBy = :userID,approvedDateTime = NOW()
            WHERE controlNo = :controlNo";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ":paymentStatus" => $paymentStatus,
        ":status" => $status,
        ":userID" => $_SESSION['userID'],
        ":controlNo" => $controlNo
      )
    );


    if ($paymentStatus == "PAYING" && $status == "approved") {
      $subject = "[BAR DORM RESERVATION] Dorm Extension";
      $content = nl2br("Good day, \r\n\n" . "This is to confirm that the dorm extension you requested with control number <b>" . $controlNo . "</b> has been approved. \n\n" . "<i>Important Reminders:</i> \n\t 1. Prepare <b>PhP " . $amount . "</b> and proceed to the cashier for payment transaction before 12 NN. \n\t 2. Payment is non-refundable. \n\n" . "<i>Thank you and enjoy your stay.</i>\n\n\n");

      // checking number of nights
      if ($numberOfNights == 1)
        $stay = "1 night";
      if ($numberOfNights > 1)
        $stay = $numberOfNights . " nights";

      $subject2 = "[BAR DORM RESERVATION] Pending Request for Payment Order of Extended Reservation ";
      $content2 = nl2br("Good day, \r\n\n" .
        "You have new pending payment order extended for " . $stay . ". Control Number: <strong>" . $controlNo . ".</strong> \n <html><body><span>Click <a href='https://compendium.bar.gov.ph/BARPortal/'>here</a>  to login.</span></body></html>\nThank you.\n\n");
      // send email confirmation to guest then send email notif to accounting
      $res = dormSendNotifConfirmation($controlNo, $email, $accounting, $content, $content2, "", $subject, $subject2);
    }

    if ($status == "disapproved") {
      $subject = "[BAR DORM RESERVATION] Dorm Extension";
      $content = nl2br("Good day, \r\n\n" . "We regret to inform you that the reservation you made for " . $newDateCheckOut . " with control number <b>" . $controlNo . "</b> has been declined due to date conflict. \n\n\n");
      $res = dormSendConfirmation($controlNo, $email, $status, $content, "", $subject);
    }

    if ($paymentStatus == "NOT PAYING" && $status == "approved") {
      $subject = "[BAR DORM RESERVATION] Dorm Extension";
      $content = nl2br("Good day, \r\n\n" . "This is to confirm that the dorm extension you requested with control number <b>" . $controlNo . "</b> has been approved.\nThank you and enjoy your stay.\n\n\n");
      $res = dormSendConfirmation($controlNo, $email, "", $content, "", $subject);
    }

    if ($res == "success")
      $mysqli->commit();

    return $res;
  } catch (Exception $e) {
    // $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

// send to admin accounting and guest
function dormSendNotifConfirmation($controlNo, $recipient, $accounting, $content, $content2, $attachment, $subject, $subject2)
{

  $signature = nl2br("<html><body><span styles='font-family: sans-serif;'
  <strong>DA-Bureau of Agricultural Research</strong> 
    +632 8461 2900 loc. 1101 |  +632 8920 0227  |  
    www.bar.gov.ph 
    RDMIC Bldg., Elliptical Rd. cor. Visayas Avenue, Diliman, Quezon City, Philippines 
  </span></body></html>");

  $body = $content . $signature;

  include("../../PHPMailer/PHPMailer.php");
  include("../../PHPMailer/SMTP.php");
  include("../../PHPMailer/Exception.php");
  include("../../PHPMailer/PHPMailerAutoload.php");
  include("../../PHPMailer/class.phpmailer.php");

  $mail = new PHPMailer();

  //smtp settings
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPDebug = SMTP::DEBUG_OFF;
  // $mail->SMTPDebug = 0;

  $mail->CharSet = 'UTF-8';
  $mail->Debugoutput = 'html';
  $mail->Username = 'barportal.notifications@bar.gov.ph';
  // $mail->Password =  'ims@admin2022';
  $mail->Password =  'xgiukfqhimjkxndo';
  $mail->Port = 587;
  $mail->SMTPSecure = 'ssl';
  $mail->SMTPAuth = true;
  $mail->SMTPAutoTLS = false;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  // $mail->SMTPDebug = SMTP::DEBUG_SERVER;


  //email settings
  $mail->setFrom($mail->Username);
  $mail->addAddress($recipient);
  $mail->Subject = $subject;
  $mail->Body = html_entity_decode($body);
  if ($attachment != "") {
    $mail->addAttachment('../../DOCS/dorm-reservation/' . $attachment);
  }
  // $mail->addAttachment('../../uploads/dorm/'.$reservationForm); 

  $mail->isHTML(true);
  $mail->ClearCustomHeaders();
  $sendEmail1 = $mail->send();

  // FOR ACCOUNTING NOTIFICATION
  // Remove previous recipients
  $mail->clearAllRecipients();

  // alternative in this case (only addresses, no cc, bcc): 
  // $mail->ClearAddresses();

  // clear attachments
  $mail->clearAttachments();

  $body2 = $content2 . $signature;
  $mail->Subject = $subject2;
  $mail->Body = html_entity_decode($body2);;
  //$adminemail = $generalsettings[0]["admin_email"]; 

  // Add the admin address
  $mail->addAddress($accounting);
  $mail->ClearCustomHeaders();
  $sendEmail2 = $mail->Send();

  if ($sendEmail1 && $sendEmail2) {
    $status = "success";
    $response = "Email is sent!";
  } else {
    $status = "failed";
    $response = "Something is wrong: <br>";
  }
  return $status;
}

// send to guest
function dormSendConfirmation($controlNo, $recipient, $status, $content, $attachment, $subject)
{

  $signature = nl2br("<html><body><span styles='font-family: sans-serif;'
  <strong>DA-Bureau of Agricultural Research</strong> 
    +632 8461 2900 loc. 1101 |  +632 8920 0227  |  
    www.bar.gov.ph 
    RDMIC Bldg., Elliptical Rd. cor. Visayas Avenue, Diliman, Quezon City, Philippines 
  </span></body></html>");

  $body = $content . $signature;

  include("../../PHPMailer/PHPMailer.php");
  include("../../PHPMailer/SMTP.php");
  include("../../PHPMailer/Exception.php");
  include("../../PHPMailer/PHPMailerAutoload.php");
  include("../../PHPMailer/class.phpmailer.php");

  $mail = new PHPMailer();

  //smtp settings
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPDebug = SMTP::DEBUG_OFF;
  // $mail->SMTPDebug = 0;

  $mail->CharSet = 'UTF-8';
  $mail->Debugoutput = 'html';
  $mail->Username = 'barportal.notifications@bar.gov.ph';
  // $mail->Password =  'ims@admin2022';
  $mail->Password =  'xgiukfqhimjkxndo';

  $mail->Port = 587;
  //$mail->Port = 25; - local
  // $mail->Port = 587; - live
  // $mail->Port = 465; - di ko alam search mo nalang php mailer port
  $mail->SMTPSecure = 'ssl';
  $mail->SMTPAuth = true;
  $mail->SMTPAutoTLS = false;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  // $mail->SMTPDebug = SMTP::DEBUG_SERVER;


  //email settings
  $mail->setFrom($mail->Username);
  $mail->addAddress($recipient);
  $mail->Subject = $subject;
  $mail->Body = html_entity_decode($body);
  if ($attachment != "") {
    $mail->addAttachment('../../DOCS/dorm-reservation/' . $attachment);
    // $mail->addAttachment('../../uploads/dorm/'.$reservationForm); 
  }

  $mail->isHTML(true);
  $mail->ClearCustomHeaders();

  if ($mail->send()) {
    $status = "success";
    $response = "Email is sent!";
  } else {
    $status = "failed";
    $response = "Something is wrong: <br>";
  }
  return $status;
}

// send to admin
function dormSendNotification($recipient, $controlNo, $content, $subject)
{


  $signature = nl2br("<html><body><span styles='font-family: sans-serif;'
  <strong>DA-Bureau of Agricultural Research</strong> 
    +632 8461 2900 loc. 1101 |  +632 8920 0227  |  
    www.bar.gov.ph 
    RDMIC Bldg., Elliptical Rd. cor. Visayas Avenue, Diliman, Quezon City, Philippines 
  </span></body></html>");

  $body = $content . $signature;

  include("../../PHPMailer/PHPMailer.php");
  include("../../PHPMailer/SMTP.php");
  include("../../PHPMailer/Exception.php");
  include("../../PHPMailer/PHPMailerAutoload.php");
  include("../../PHPMailer/class.phpmailer.php");

  $mail = new PHPMailer();

  //smtp settings
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPDebug = SMTP::DEBUG_OFF;
  // $mail->SMTPDebug = 0;

  $mail->CharSet = 'UTF-8';
  $mail->Debugoutput = 'html';
  $mail->Username = 'barportal.notifications@bar.gov.ph';
  // $mail->Password =  'ims@admin2022';
  $mail->Password =  'xgiukfqhimjkxndo';

  $mail->Port = 587;
  //$mail->Port = 25; - local
  // $mail->Port = 587; - live
  // $mail->Port = 465; - di ko alam search mo nalang php mailer port
  $mail->SMTPSecure = 'ssl';
  $mail->SMTPAuth = true;
  $mail->SMTPAutoTLS = false;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  // $mail->SMTPDebug = SMTP::DEBUG_SERVER;


  //email settings
  $mail->setFrom($mail->Username);
  $mail->addAddress($recipient);
  $mail->Subject = $subject;
  $mail->Body = html_entity_decode($body);
  // $mail->addAttachment($attachment);
  $mail->isHTML(true);
  $mail->ClearCustomHeaders();

  if ($mail->send()) {
    $status = "success";
    $response = $controlNo;
  } else {
    $status = "failed";
    $response = "Something is wrong";
  }
  return $status;
}

function dormSendEmailReq($controlNo_, $recipient)
{
  $subject = "BAR DORM RESERVATION SYSTEM";
  $content = nl2br("Good day, \r\n\n" .
    "You have one (1) new pending recommendation <strong>" . $controlNo_ . ".</strong> \n <html><body><span>Click <a href='https://compendium.bar.gov.ph/BARPortal/'>here</a>  to login.</span></body></html>");

  $signature = nl2br("<html><body><span styles='font-family: sans-serif;'>
      <strong>DA-Bureau of Agricultural Research
      +632 8461 2900 loc. 1101 |  +632 8920 0227  |  
      www.bar.gov.ph 
      RDMIC Bldg., Elliptical Rd. cor. Visayas Avenue, Diliman, Quezon City, Philippines 
  </strong> </span></body></html>");

  $body = $content . $signature;
  // $attachment = $path;
  include("../../PHPMailer/PHPMailer.php");
  include("../../PHPMailer/SMTP.php");
  include("../../PHPMailer/Exception.php");
  include("../../PHPMailer/PHPMailerAutoload.php");
  include("../../PHPMailer/class.phpmailer.php");

  $mail = new PHPMailer();

  //smtp settings
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPDebug = SMTP::DEBUG_OFF;
  // $mail->SMTPDebug = 0;

  $mail->CharSet = 'UTF-8';
  $mail->Debugoutput = 'html';
  $mail->Username = 'barportal.notifications@bar.gov.ph';
  // $mail->Password =  'ims@admin2022';
  $mail->Password =  'xgiukfqhimjkxndo';

  $mail->Port = 587;
  //$mail->Port = 25; - local
  // $mail->Port = 587; - live
  // $mail->Port = 465; - di ko alam search mo nalang php mailer port
  $mail->SMTPSecure = 'ssl';
  $mail->SMTPAuth = true;
  $mail->SMTPAutoTLS = false;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  // $mail->SMTPDebug = SMTP::DEBUG_SERVER;


  //email settings
  $mail->setFrom($mail->Username);
  $mail->addAddress($recipient);
  $mail->Subject = $subject;
  $mail->Body = html_entity_decode($body);
  // $mail->addAttachment($attachment);
  $mail->isHTML(true);
  $mail->ClearCustomHeaders();

  if ($mail->send()) {
    $status = "success";
    $response = "Email is sent!";
  } else {
    $status = "failed";
    $response = "Something is wrong: <br>";
  }
  return $controlNo_;
}

function dormGetAll($mysqli)
{
  // for general service 
  if ($_SESSION['role'] != 7 && $_SESSION['section'] != 2.6 && $_SESSION['section'] != 2.7) {
    $sql = "SELECT * FROM dormreservation WHERE status = 'requested' GROUP BY controlNo ORDER BY controlNo";
  }
  // for asst. director
  if ($_SESSION['role'] == 7) {
    $sql = "SELECT * FROM dormreservation WHERE status = 'recommended' GROUP BY controlNo ORDER BY controlNo";
  }
  // for accounting/finance
  if ($_SESSION['section'] == 2.6) {
    $sql = "SELECT * FROM dormreservation WHERE status = 'approved' AND payment = 'PAYING' AND paymentOrderStatus = '' GROUP BY controlNo ORDER BY controlNo";
  }
  // for cashier
  if ($_SESSION['section'] == 2.7) {
    $sql = "SELECT * FROM dormreservation WHERE status = 'approved' AND payment = 'PAYING' AND paymentOrderStatus = 'ready' GROUP BY controlNo ORDER BY controlNo";
  }
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormGetAllNewExtension($mysqli)
{
  $temp = array();
  //for cashier
  if ($_SESSION['section'] == 3.7) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.status = 'approved' AND dorm_extension.payment = 'PAYING' AND dorm_extension.paymentOrderStatus = 'ready' GROUP BY controlNo";
  }
  //for accounting
  if ($_SESSION['section'] == 3.6) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.status = 'approved' AND dorm_extension.payment = 'PAYING' AND dorm_extension.paymentOrderStatus = '' GROUP BY controlNo";
  }
  //for general service
  if ($_SESSION['role'] == 10) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.status = 'requested' GROUP BY controlNo";
  }
  // //for accounting
  // if ($_SESSION['section'] == 2.6) {
  //   $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.status = 'approved' AND dorm_extension.payment = 'PAYING' AND dorm_extension.paymentOrderStatus = '' GROUP BY controlNo";
  // }
  // //for cashier
  // if ($_SESSION['section'] == 2.7) {
  //   $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.paymentOrderStatus != ''GROUP BY controlNo";
  // }


  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// for notification extended
function dormCountAllExtended($mysqli)
{
  $temp = array();
  // for accounting/finance
  if ($_SESSION['section'] == 3.6) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.paymentOrderStatus = '' GROUP BY controlNo";
  }
  // for cashier
  if ($_SESSION['section'] == 3.7) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.paymentOrderStatus != '' AND dorm_extension.payment = 'PAYING' GROUP BY controlNo";
  }


  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// for dorm history
function dormGetAllHistory($mysqli)
{
  // for general service 
  if ($_SESSION['role'] != 7 && $_SESSION['section'] != 3.6 && $_SESSION['section'] != 3.7) {
    $sql = "SELECT * FROM dormreservation WHERE status = 'recommended' OR status = 'approved' OR status = 'disapproved' GROUP BY controlNo ORDER BY controlNo";
  }
  // for asst. director
  if ($_SESSION['role'] == 7) {
    $sql = "SELECT * FROM dormreservation WHERE status = 'approved' OR status = 'disapproved' GROUP BY controlNo ORDER BY controlNo";
  }
  // for accounting/finance
  if ($_SESSION['section'] == 3.6) {
    $sql = "SELECT * FROM dormreservation WHERE status = 'approved' AND paymentOrderStatus = 'ready' GROUP BY controlNo ORDER BY controlNo";
  }
  // for cashier
  if ($_SESSION['section'] == 3.7) {
    $sql = "SELECT * FROM dormreservation WHERE status = 'approved' AND payment = 'PAID' AND paymentOrderStatus = 'ready' GROUP BY controlNo ORDER BY controlNo";
  }
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// for extension history
function dormGetAllExtensionHistory($mysqli)
{
  $temp = array();

  //for general service
  if ($_SESSION['role'] == 10) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.status = 'approved' OR dorm_extension.status = 'disapproved' GROUP BY controlNo";
  }
  //for accounting
  if ($_SESSION['section'] == 3.6) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.paymentOrderStatus = 'ready' GROUP BY controlNo";
  }
  //for cashier
  if ($_SESSION['section'] == 3.7) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.status = 'approved' AND dorm_extension.payment = 'PAID' AND dorm_extension.paymentOrderStatus = 'ready' GROUP BY controlNo";
  }
  //for asst. director
  if ($_SESSION['role'] == 7) {
    $sql = "SELECT dormreservation.*,dorm_extension.dateFiled as extendedDate ,dorm_extension.dateCheckOut as newDateCheckOut,dorm_extension.timeCheckOut as newTimeCheckOut,dorm_extension.extendedNights as extendedNights, dorm_extension.amount as amountExtended,dorm_extension.reason as extendedReason,dorm_extension.payment as extendedPaymentStatus,dorm_extension.paymentOrderStatus as extendedPaymentOrderStatus,dorm_extension.status as extendedStatus FROM dormreservation INNER JOIN dorm_extension ON dormreservation.extension =  dorm_extension.id WHERE dorm_extension.status = 'approved' GROUP BY controlNo";
  }
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormSearchThis($mysqli, $dormSearch)
{
  $temp = array();
  $sql = "SELECT * FROM dormreservation WHERE MATCH(controlNo, dateFiled,status,dateCheckIn,dateCheckOut) AGAINST(:dormSearch IN NATURAL LANGUAGE MODE)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':dormSearch' => $dormSearch));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormCheckThisDate($mysqli, $checkThisDate)
{
  $temp = array();
  // $sql = "SELECT * FROM dormreservation WHERE status = 'approved' AND :checkThisDate BETWEEN dateCheckIn AND dateCheckOut";
  // $stmt = $mysqli->prepare($sql);
  // $stmt->execute(array(':checkThisDate' => $checkThisDate));
  // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  //   $temp[] = $row;
  // }

  $sql2 = "SELECT * FROM dormreservation WHERE status = 'approved' AND gender = 'Male' AND :checkThisDate BETWEEN dateCheckIn AND dateCheckOut";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->execute(array(':checkThisDate' => $checkThisDate));
  while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }

  // array_push($temp,$row2);
  return $temp;
}

function dormGetAllReservations($mysqli, $yearMonth)
{
  $temp = array();
  $sql = "SELECT * FROM dormreservation WHERE controlNo LIKE '%$yearMonth%' GROUP BY controlNo";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormGetMyName($mysqli)
{
  $temp = array();
  $sql = "SELECT firstName,lastName,gender,mobile FROM personal_info WHERE userID = :userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $_SESSION['userID']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getThisUsername($mysqli, $userID)
{
  $sql = "SELECT username FROM credentials WHERE userID=:userID";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(":userID" => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormSaveReservation($mysqli, $controlNo_, $contactLeader_, $officeAgency_, $officeAddress_, $reasonOfStay_, $dateCheckIn_, $dateCheckOut_, $timeCheckIn_, $timeCheckOut_, $endorsedBy_, $emergencyPerson_, $contactPerson_, $email_)
{


  // $fileName = $controlNo_.".".$fileType;

  // $path = '../../uploads/dorm/attachment/'.$fileName;  
  if ($_SESSION['role'] == 10) {
    $status = "recommended";
    $color = "#f0ad4e";
  } else {
    $status = "requested";
    $color = "#0771de";
  }


  try {
    // save reservation details
    $mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysqli->beginTransaction();

    for ($i = 1; $i <= 20; $i++) {

      if (!isset($_POST['dormGender' . $i])) continue;
      if (!isset($_POST['dormName' . $i])) continue;

      $dormGender = $_POST['dormGender' . $i];
      $dormName = $_POST['dormName' . $i];

      $sql = "INSERT INTO dormreservation(`dateFiled`,`controlNo`,`contactNo`,`officeAgency`,`officeAddress`,`reasonOfStay`,`dateCheckIn`,`timeCheckIn`,`dateCheckOut`,`timeCheckOut`,`endorsedBy`,`emergencyPerson`,`contactPerson`,`email`,`status`,`color`,`payment`,`name`,`gender`)
       VALUES (NOW(),:controlNo,:contactNo,:officeAgency,:officeAddress,:reasonOfStay,:dateCheckIn,:timeCheckIn,:dateCheckOut,:timeCheckOut,:endorsedBy,:emergencyPerson,:contactPerson,:email,:status,:color,:payment,:name,:gender)";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute(
        array(
          ':controlNo' => $controlNo_,
          ':contactNo' => $contactLeader_,
          ':officeAgency' => $officeAgency_,
          ':officeAddress' => $officeAddress_,
          ':reasonOfStay' => $reasonOfStay_,
          ':dateCheckIn' => $dateCheckIn_,
          ':timeCheckIn' => $timeCheckIn_,
          ':dateCheckOut' => $dateCheckOut_,
          ':timeCheckOut' => $timeCheckOut_,
          ':endorsedBy' => $endorsedBy_,
          ':emergencyPerson' => $emergencyPerson_,
          ':contactPerson' => $contactPerson_,
          ':email' => $email_,
          ':status' => $status,
          ':color' => $color,
          ':payment' => "",
          ':name' =>  $dormName,
          ':gender' =>  $dormGender
        )
      );
    }  // end for loop

    $mysqli->commit();

    // //upload file
    // move_uploaded_file($tempPath_,$path);

    $adminApprove = 'jserrano@bar.gov.ph';
    if ($_SESSION['role'] == 10) {
      $payment = "";
      dormSendNotification($adminApprove, "recommended", $payment, $controlNo_);
    } else {
      dormSendEmailReq($controlNo_, $adminApprove);
    }
    return $controlNo_;
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function dormGetMyReservations($mysqli, $myName)
{
  $temp = array();
  $sql = "SELECT *,CONCAT(dateCheckIn,' ',timeCheckIn) as dateTimeCheckin FROM dormreservation WHERE name = :name GROUP BY controlNo";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':name' => $_SESSION['myName']));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormGetAllFeedback($mysqli)
{
  $sql = "SELECT * FROM dorm_feedback ORDER BY controlNo";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormGetThisFeedback($mysqli, $feedbackID)
{
  $sql = "SELECT * FROM dorm_feedback WHERE id = :id";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':id' => $feedbackID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function dormGetEmail($mysqli, $role)
{
  $sql = "SELECT email FROM dorm_email WHERE role = :role";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':role' => $role));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row;
}
//  ============== E N D  D O R M  R E S E R V A T I O N ===============

// --------------- S T A R T  W O R K  S C H E D U L E -----------------
function getAllDivision($mysqli)
{
  $sql = "SELECT division FROM division WHERE divisionID != 0 AND divisionID != 2";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}
 
function getAllSection($mysqli, $division)
{
  $sql = "SELECT section FROM section WHERE division = :division";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':division' => $division));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getSpecificSection($mysqli, $section)
{
  $sql = "SELECT * FROM credentials WHERE section = :section AND disabled = 0";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAccountBudget($mysqli, $section)
{
  $sql = "SELECT * FROM credentials WHERE (section = :section OR section = 3.4)AND disabled = 0";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':section' => $section));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllAdminSection($mysqli)
{
  $sql = "SELECT section FROM section WHERE division = 3 AND section != 'OAD (RSS)'";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllDateSchedule($mysqli)
{
  $sql = "SELECT DISTINCT monthSched FROM work_schedule";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllStaffByDivisionID($mysqli, $division)
{
  $sql = "SELECT * FROM credentials WHERE division = :division AND disabled = 0";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':division' => $division));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllStaffByUsername($mysqli, $username)
{
  $sql = "SELECT * FROM view_employeeinfo WHERE username = :username ORDER BY employeeID";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':username' => $username));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function checkSubmitted($mysqli,$userID,$weekNumber,$monthSched){
  $sql = "SELECT * FROM work_schedule WHERE userID = :userID AND weekNumber = :weekNumber AND monthSched = :monthSched";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID,':weekNumber' => $weekNumber,':monthSched' => $monthSched));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function submitWorkSched($mysqli, $userID, $txtUserDivision, $txtWeekNumber, $checkboxMon, $checkboxTue, $checkboxWed, $checkboxThu, $checkboxFri, $monthSched, $workSched)
{
  try{
    $sql = "INSERT INTO work_schedule(`userID`,`division`,`weekNumber`,`weekMon`,`weekTue`,`weekWed`,`weekThu`,`weekFri`,`monthSched`, `workSched`) 
    VALUES (:userID,:division,:weekNum,:weekMon,:weekTue,:weekWed,:weekThu,:weekFri,:monthSched,:workSched)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $userID,
        ':division' => $txtUserDivision,
        ':weekNum' => $txtWeekNumber,
        ':weekMon' => $checkboxMon,
        ':weekTue' => $checkboxTue,
        ':weekWed' => $checkboxWed,
        ':weekThu' => $checkboxThu,
        ':weekFri' => $checkboxFri,
        ':monthSched'=> $monthSched,
        ':workSched'=> $workSched
      )
    );
    return "success";
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function updateWorkSched($mysqli, $userID, $checkboxMon, $checkboxTue, $checkboxWed, $checkboxThu, $checkboxFri, $monthSched, $workSched)
{
  try{
    $sql = "UPDATE work_schedule SET 
              `userID` = :userID,
              `weekMon` = :weekMon,
              `weekTue` = :weekTue,
              `weekWed` = :weekWed,
              `weekThu` = :weekThu,
              `weekFri` = :weekFri,
              `monthSched` = :monthSched,
              `workSched` = :workSched,
              `updateBy` = :updateBy,
              `updateDT` = NOW()
            WHERE userID=:userID";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $userID,
        ':weekMon' => $checkboxMon,
        ':weekTue' => $checkboxTue,
        ':weekWed' => $checkboxWed,
        ':weekThu' => $checkboxThu,
        ':weekFri' => $checkboxFri,
        ':monthSched'=> $monthSched,
        ':workSched'=> $workSched,
        ':updateBy' => $_SESSION['username']
      )
    );
    return "success";
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function workScheduleRemarks($mysqli, $userID, $monthSched){
  $sql = "SELECT * FROM work_schedule WHERE (workSched = :ws OR workSched = 'Flexitime-Flexiplace') AND userID = :userID AND monthSched = :monthSched";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':ws' => 'Flexiplace', ':userID' => $userID,':monthSched' => $monthSched));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// GET WORK
function pFWA_Schedule($mysqli)
{

  $stmt1 = $mysqli->prepare("SELECT * FROM work_schedule WHERE division = :w");
  $stmt1->execute(array(":w" => $_SESSION['userID']));
  $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  if ($row1) {
    $temp = array();
    $addValiditystmt = $mysqli->prepare("SELECT * FROM work WHERE userID = :x AND startDate >= CURRENT_DATE - interval 10 year");
    $addValiditystmt->execute(array(":x" => $row1['userID']));
    while ($row2 = $addValiditystmt->fetch(PDO::FETCH_ASSOC)) {
      $temp[] = $row2;
    }
    return $temp;
  }
}

// WORK ACCOMPLISHMENTS
function submitWorkAccomplishments($mysqli, $userID, $workMonth, $workDay, $workYear, $workTime, $workAccomplish, $workStatus, $workRemarks)
{
  try{
    $sql = "INSERT INTO work_accomplishment(`userID`,`workMonth`,`workDay`,`workYear`,`workTime`,`workAccomplish`,`workStatus`,`workRemarks`) 
    VALUES (:userID,:workMonth,:workDay,:workYear,:workTime,:workAccomplish,:workStatus,:workRemarks)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $userID,
        ':workMonth' => $workMonth,
        ':workDay' => $workDay,
        ':workYear' => $workYear,
        ':workTime' => $workTime,
        ':workAccomplish' => $workAccomplish,
        ':workStatus' => $workStatus,
        ':workRemarks'=> $workRemarks
      )
    );
    return "success";
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function checkAccomplishments($mysqli,$userID,$workMonth,$workDay,$workYear){
  $sql = "SELECT * FROM work_accomplishment WHERE userID = :userID AND workMonth = :workMonth AND workDay = :workDay AND workYear = :workYear";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID,':workMonth' => $workMonth,':workDay' => $workDay,':workYear' => $workYear));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


function workTimeIn($mysqli, $userID, $wfhMonth, $wfhDay, $wfhYear){
  try{
    $sql = "INSERT INTO work_flexiplace(`userID`,`timeIn`,`wfhInStatus`,`wfhOutStatus`,`wfhMonth`,`wfhDay`,`wfhYear`) 
    VALUES (:userID,NOW(),'active','inactive',:wfhMonth,:wfhDay,:wfhYear)";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $userID,
        ':wfhMonth' => $wfhMonth,
        ':wfhDay' => $wfhDay,
        ':wfhYear' => $wfhYear
      )
    );
    return "success";
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function workTimeOut($mysqli, $userID){
  try{
    $sql = "UPDATE work_flexiplace SET 
              `userID` = :userID,
              `wfhInStatus` = 'inactive',
              `wfhOutStatus` = 'inactive',
              `timeOut` = NOW()
            WHERE userID=:userID";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $userID
      )
    );
    return "success";
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function checkFlexiActive($mysqli, $userID, $wfhInStatus){
  $sql = "SELECT * FROM work_flexiplace WHERE userID = :userID AND wfhOutStatus = 'inactive' AND DATE(timeIn) = CURRENT_DATE";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function checkFlexiInactive($mysqli, $userID, $wfhInStatus){
  $sql = "SELECT * FROM work_flexiplace WHERE userID = :userID AND wfhInStatus = 'active' AND DATE(timeIn) = CURRENT_DATE";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getTimeInLogs($mysqli, $userID, $wfhMonth, $wfhDay, $wfhYear){
  $sql = "SELECT * FROM work_flexiplace WHERE userID = :userID and wfhMonth = :wfhMonth AND wfhDay = :wfhDay AND wfhYear = :wfhYear";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID, ':wfhMonth' => $wfhMonth, ':wfhDay' => $wfhDay, ':wfhYear' => $wfhYear));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $temp[] = $row;
  }
  return $temp;
}

function getTimeOutLogs($mysqli, $userID, $wfhMonth, $wfhDay, $wfhYear){
  $sql = "SELECT * FROM work_flexiplace WHERE userID = :userID and wfhMonth = :wfhMonth AND wfhDay = :wfhDay AND wfhYear = :wfhYear";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID, ':wfhMonth' => $wfhMonth, ':wfhDay' => $wfhDay, ':wfhYear' => $wfhYear));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $temp[] = $row;
  }
  return $temp;
}

function saveFWALogs($mysqli, $userID){
  try{
    $sql = "INSERT INTO fwa_log(`userID`,`remarks`) 
    VALUES (:userID,'Active')";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':userID' => $userID
      )
    );
    return "success";
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function getFWALogs($mysqli, $userID, $remarks){
  $sql = "SELECT * FROM fwa_log WHERE userID = :userID AND remarks = :remarks";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID,':remarks' => $remarks));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

// --------------- E N D  W O R K  S C H E D U L E -----------------

// --------------- S T A R T  U S E R  I N F O R M A T I O N ---------------
function getAllEmpList($mysqli){
  $sql = "SELECT * FROM view_employeeinfo WHERE disabled = 0 ORDER BY userID";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getAllEmpInfor($mysqli, $userID){
  $sql = "SELECT * FROM view_employeeinfo WHERE userID = :userID AND disabled = 0 ORDER BY userID";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':userID' => $userID));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getFullName($mysqli, $username){
  $sql = "SELECT * FROM view_employeeinfo WHERE username = :username";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':username' => $username));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getEmployeeInformation($mysqli, $username){
  $sql = "SELECT * FROM view_employeeinfo WHERE username = :username";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute([
    ':username' => $username
  ]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllBday($mysqli){
  $sql = "SELECT * FROM  view_employeeinfo WHERE MONTH(`birthdate`) = MONTH(CURRENT_DATE)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCOSDetails($mysqli){
  $sql = "SELECT employeeID FROM view_employeeinfo WHERE employeeID LIKE '%COS' GROUP BY employeeID ORDER BY userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $temp[] = $row;
    }
  return $temp;
}

function getPERMDetails($mysqli){
  $sql = "SELECT employeeID FROM view_employeeinfo WHERE employeeID LIKE '%P' GROUP BY employeeID ORDER BY userID";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $temp[] = $row;
    }
  return $temp;
}



// --------------- E N D  U S E R  I N F O R M A T I O N ---------------

//  ============== Add Announcement ===========
function saveAnnouncement($mysqli, $AnnouncementTitle, $AnnouncementContent)
{
  try {
    $sql = " INSERT INTO `announcements`(`AnnouncementTitle`, `AnnouncementContent`, `userID`) 
    VALUES (:x,:y,:z)";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute(
      array(
        ':x' => $AnnouncementTitle,
        ':y' => $AnnouncementContent,
        ':z' => $_SESSION['userID']

      )
    );
    return "success";
  } catch (Exception $e) {
    $mysqli->rollback();
    echo "<script>alert('" . $e->getMessage() . "');</script>";
    return false;
  }
}

function getAnnouncement($mysqli)
{
  $sql = "SELECT * FROM announcements";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


//////GET Incoming Routing Slip //////

function getIncomingRoutingSlip($mysqli, $referenceNo)
{
  $sql = "SELECT * FROM view_dts_incoming WHERE referenceNo in (:referenceNo)";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':referenceNo' => $referenceNo ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

function getIncomingRoutingSlip2($mysqli, $referenceNo)
{
  $new_ref = trim($referenceNo,"[]");
  $sql = "SELECT * FROM view_dts_incoming WHERE referenceNo in ($new_ref)";
    // echo $sql;
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  //  echo $temp;
  return $temp;
}

function getReferenceNo($mysqli)
{
  $sql = "SELECT * FROM view_dts_incoming";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}

//////GET Outgoing Routing Slip //////

function getOutgoingRoutingSlip($mysqli, $referenceNo)
{
  $sql = "SELECT * FROM view_dts_outgoing WHERE referenceNo in (:referenceNo)";
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute(array(':referenceNo' => $referenceNo ));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  return $temp;
}


function getOutgoingRoutingSlip2($mysqli, $referenceNo)
{
  $new_ref = trim($referenceNo,"[]");
  $sql = "SELECT * FROM view_dts_outgoing_2 WHERE referenceNo in ($new_ref)";
    // echo $sql;
  $temp = array();
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temp[] = $row;
  }
  //  echo $temp;
  return $temp;
}