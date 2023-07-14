<?php include('../functions/function.php'); 

//call the autoload
require '../vendor/autoload.php';
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Iwriter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;
// if(!checkAccomplishedPDS($mysqli, $_SESSION['userID'])){
$checkReferenceNo = getReferenceNo($mysqli);

// }
// else {
    $rs_to = "";
    $GenCell = "";
    $rs_act_taken="";
    $rs_particulars ="";
    $rs_remarks ="";
    $counter = 1;
   
    $res = getIncomingRoutingSlip($mysqli,$_GET['referenceNo']);
    $rs_counter= count($res);
    $reader = IOFactory::createReader('Xlsx');
    $spreadsheet = $reader->load('../assets/Documents/Templates/ROUTING_SLIP.xlsx');
    if ($res ){
        foreach($res as $key){
          
            if($rs_counter > $counter){
                $rs_to .=  strtoupper(htmlentities($key['dts_fullname']));
                $counter++;
                $rs_to .= ",";
           }
           else{
           $rs_to .=  strtoupper(htmlentities($key['dts_fullname']));
           }

            $rs_reffNo =  strtoupper(htmlentities($key['referenceNo']));
            $rs_act_taken =  strtoupper(htmlentities($key['actionTaken']));
            $rs_remarks =  strtoupper(htmlentities($key['remarks']));
            $rs_particulars = strtoupper(htmlentities($key['particulars']));
        
             if($rs_act_taken == "FOR COMPLIANCE"){
               $GenCell = 'C19'; $act_taken = 'P';
               }
              if($rs_act_taken == "FOR APPROPRIATE ACTION"){
                $GenCell = 'C21'; $act_taken = 'P';
              } 
              if($rs_act_taken == "FOR YOUR COMMENTS"){
                  $GenCell = 'C23'; $act_taken = 'P';
              }
              if($rs_act_taken == "APPROVED/DISSAPROVED"){
                  $GenCell = 'C25'; $act_taken = 'P';
              }
              if($rs_act_taken == "PLS. SEE ME"){
                  $GenCell = 'C27'; $act_taken = 'P';
              }
              if($rs_act_taken == "FOR YOUR FILES"){
                  $GenCell = 'C29'; $act_taken = 'P';
              }
              if($rs_act_taken == "FOR YOUR INFORMATION"){
                  $GenCell = 'C31'; $act_taken = 'P';
              }

            }
        }
        $spreadsheet->getActiveSheet()->getStyle('C19:C31')
        ->getFont()
        ->setName('Wingdings 2');

        $spreadsheet->getSheetByName('Routing_Slip')
        ->setCellValue("E10",$rs_to)
        ->setCellValue("E12",$rs_particulars)
        ->setCellValue($GenCell,$act_taken)
        ->setCellValue("C35",$rs_remarks);
            
        //load the template file
        //set the header first, so the result will be treated as an xlsx file.
        ob_end_clean();
        //make it an attachment so we can define filename
        //header('Content-Disposition: attachment;filename="'.$_SESSION['username'].'.xlsx"');
        //create IOFactory object
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: inline; filename="'.$key['dts_fullname'].'-'.$key['referenceNo'].'.xlsx";');
        // header('Content-Disposition: inline; filename="asdf.xlsx";');
        header('Content-Type:  application/octet-stream');
        //save into php output
        $writer->save('php://output');
        exit;
   

    // }

?>