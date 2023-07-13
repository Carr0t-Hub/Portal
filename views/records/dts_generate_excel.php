composer require phpoffice/phpspreadsheet<?php include('functions/function.php'); 


//call the autoload
require 'vendor/autoload.php';
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Iwriter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;


if(!checkAccomplishedPDS($mysqli, $_SESSION['userID'])){
    echo "<script> alert('Incomplete PDS.'); window.location.href='personal_data_sheet.php'</script> ";
}
else {
    $res = dtsGetIncomingDocus($mysqli);
    if ($res){
        foreach ($res as $key){
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load('../assets/Documents/Templates/ROUTING_SLIP.xlsx');

            $rs_to = "";
            $rs_cc = "";
            $rs_subj ="";
            $rs_compliance = "";
            $rs_appro_act = "";
            $rs_comments= "";
            $rs_appr_disappr = "";
            $rs_pls_see = "";
            $rs_your_file = "";
            $rs_your_info= "";
            $rs_remarks = "";
            $rs_act_taken = ""; 

            $rs_to =  strtoupper(htmlentities($key['referenceNo']));
            $Fna =  strtoupper(htmlentities($key['firstName']));
            $Mna =  strtoupper(htmlentities($key['middleName']));
            $Ena =  strtoupper(htmlentities($key['extensionName']));
            $Bda =  htmlentities(date_format(date_create($key['birthdate']),"m/d/Y"));
        }

        switch ($act_taken) {
            case "FOR COMPLIANCE":
                $GenCell = 'C19'; $act_taken = 'P';
            break;
            case "FOR APPROPRIATE ACTION":
                $GenCell = 'C21'; $act_taken = 'P';
            break;
            case "FOR YOUR COMMENTS":
                $GenCell = 'C23'; $act_taken = 'P';
            break;
            case "APPROVED/DISSAPROVED":
                $GenCell = 'C25'; $act_taken = 'P';
            break;
            case "PLS. SEE ME":
                $GenCell = 'C27'; $act_taken = 'P';
            break;
            case "FOR YOUR FILES":
                $GenCell = 'C29'; $act_taken = 'P';
            break;
            case "FOR YOUR INFORMATION":
                $GenCell = 'C31'; $act_taken = 'P';
            break;
            case NULL:
                $GenCell = 'D16'; $Gen = 'P';
            break;
        } 
        
        //END PAGE FOUR


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
        header('Content-Disposition: inline; filename="'.$key['dateReceived'].'.xlsx";');
        header('Content-Type:  application/octet-stream');
        //save into php output
        $writer->save('php://output');
        exit;
        

    }
  

 
  


 
}

?>