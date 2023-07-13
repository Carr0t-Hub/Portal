<?php include('../functions/function.php'); ?>
<?php
//call the autoload
require '../vendor/autoload.php';
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

//load the template file
$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load('../assets/Documents/Templates/ROUTING SLIP.xlsx');

//START
$res = printincoming($mysqli);
$PersonConcerned= "";
$CC="";
$Subject = "";
$ActionNeeded = "";
$Remarks = "";

if ($res) {
  foreach ($res as $key) {
      $PersonConcerned =  strtoupper(htmlentities($key['']));
      $CC =  strtoupper(htmlentities($key['']));
      $Subject =  strtoupper(htmlentities($key['']));
      $ActionNeeded =  strtoupper(htmlentities($key['']));
      $Remarks =  strtoupper(htmlentities($key['']));

      $spreadsheet->getSheetByName('C1')
    ->setCellValue('E10', $PersonConcerned)
    ->setCellValue('E11', $CC)
    ->setCellValue('E12', $Subject)
    ->setCellValue('', $ActionNeeded)
    ->setCellValue('D35', $Remarks)
    
//set the header first, so the result will be treated as an xlsx file.


//make it an attachment so we can define filename

//header('Content-Disposition: attachment;filename="'.$_SESSION['username'].'.xlsx"');

//create IOFactory object
$_writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false);
header('Content-Transfer-Encoding: binary');
header('Content-Disposition: inline; filename="'.$_SESSION['username'].'.xlsx";');
header('Content-Type:  application/octet-stream');
//save into php output
$writer->save('php://output');
exit;
}
//START 

#$res = printincoming($mysqli);
//if($res = false){
 //   $Receiver ="";
  //  $CC ="";
  //  $Particulars ="";
  //  $ActionNeeded ="";
  //  $Remarks ="";

//}else{
//    foreach ($res as $key) {
 //       $Receiver =  strtoupper(htmlentities($key['sender']));
 //       $CC =  strtoupper(htmlentities($key['']));
  //      $Particulars =  strtoupper(htmlentities($key['particulars']));
  //      $ActionNeeded =  strtoupper(htmlentities($key['ActionNeeded']));

  ///  }

//}

//START PDS PAGE ONE
// $res = PemployeeInfo($mysqli);
// if ($res == false) {
//     $Lna = "";
//     $Fna = "";
//     $Mna = "";
//     $Ena = "";
//     $Bda = "";

//     $Bpl = "";
//     $Gen = "";
//     $Sta = "";
//     $Hei = "";
//     $Wei = "";
//     $Blo = "";

//     $Gsi = "";
//     $Pag = "";
//     $Phi = "";
//     $Sss = "";
//     $Tin = "";
//     $Aen = "";

//     $Cit = "";
//     $Cib = "";
//     $DCo = "";

//     $RBl = "";
//     $RSt = "";
//     $RVi = "";
//     $RBr = "";
//     $RCi = "";
//     $RPr = "";
//     $RZi = "";

//     $PBl = "";
//     $PSt = "";
//     $PVi = "";
//     $PBr = "";
//     $PCi = "";
//     $PPr = "";
//     $PZi = "";

//     $Tel = "";
//     $Mob = "";
//     $Ema = "";

//     $SLna = "";
//     $SFna = "";
//     $SMna = "";
//     $SEna = "";

//     $FLna = "";
//     $FFna = "";
//     $FMna = "";
//     $FEna = "";

//     $MLna = "";
//     $MFna = "";
//     $MMna = "";
//     $MEna = "";

//     $SOcu = "";
//     $SEmp = "";
//     $SBAd = "";
//     $STel = "";
// } else {
//     foreach ($res as $key) {
//         $Lna =  strtoupper(htmlentities($key['lastName']));
//         $Fna =  strtoupper(htmlentities($key['firstName']));
//         $Mna =  strtoupper(htmlentities($key['middleName']));
//         $Ena =  strtoupper(htmlentities($key['extensionName']));
//         $Bda =  strtoupper(htmlentities($key['birthdate']));

//         $Bpl =  strtoupper(htmlentities($key['birthplace']));
//         $Gen = strtoupper(htmlentities($key['gender']));
//         $Sta = strtoupper(htmlentities($key['civilStatus']));
//         $Hei = strtoupper(htmlentities($key['height']));
//         $Wei = strtoupper(htmlentities($key['weight']));
//         $Blo = strtoupper(htmlentities($key['bloodType']));

//         $Gsi = strtoupper(htmlentities($key['gsis']));
//         $Pag = strtoupper(htmlentities($key['pagibig']));
//         $Phi = strtoupper(htmlentities($key['philhealth']));
//         $Sss = strtoupper(htmlentities($key['sss']));
//         $Tin = strtoupper(htmlentities($key['tin']));
//         $Aen = strtoupper(htmlentities($key['agencyEmployeeNum']));

//         $Cit = strtoupper(htmlentities($key['citizenship']));
//         $Cib = strtoupper(htmlentities($key['citizenBy']));
//         $DCo = strtoupper(htmlentities($key['dualCitizenshipCountry']));

//         $RBl = strtoupper(htmlentities($key['Res_block']));
//         $RSt = strtoupper(htmlentities($key['Res_street']));
//         $RVi = strtoupper(htmlentities($key['Res_village']));
//         $RBr = strtoupper(htmlentities($key['Res_brgy']));
//         $RCi = strtoupper(htmlentities($key['Res_city']));
//         $RPr = strtoupper(htmlentities($key['Res_province']));
//         $RZi = strtoupper(htmlentities($key['Res_zip']));

//         $PBl = strtoupper(htmlentities($key['Per_block']));
//         $PSt = strtoupper(htmlentities($key['Per_street']));
//         $PVi = strtoupper(htmlentities($key['Per_village']));
//         $PBr = strtoupper(htmlentities($key['Per_brgy']));
//         $PCi = strtoupper(htmlentities($key['Per_city']));
//         $PPr = strtoupper(htmlentities($key['Per_province']));
//         $PZi = strtoupper(htmlentities($key['Per_zip']));

//         $Tel = strtoupper(htmlentities($key['telephone']));
//         $Mob = strtoupper(htmlentities($key['mobile']));
//         $Ema = strtoupper(htmlentities($key['email']));

//         $SLna =  strtoupper(htmlentities($key['lastName']));
//         $SFna =  strtoupper(htmlentities($key['firstName']));
//         $SMna =  strtoupper(htmlentities($key['middleName']));
//         $SEna =  strtoupper(htmlentities($key['extensionName']));
//     }
// }

//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="routing_slip.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//save into php output
$writer->save('php://output');
