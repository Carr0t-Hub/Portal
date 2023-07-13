<?php
include('../functions/function.php'); 
include_once("../test/connection.php");
include_once('../fpdf/fpdf.php');


$rs_to = "";
$GenCell = "";
$rs_act_taken="";
$rs_particulars ="";
$rs_remarks ="";
$col = 0;
$counter = 1;

// $res = getIncomingRoutingSlip($mysqli,$_GET['referenceNo']);
// $rs_counter = count($res);
if (isset($_GET['referenceNo'])){
    $ref_No = $_GET['referenceNo'];
    

 $res = getIncomingRoutingSlip2($mysqli,$ref_No);
 $rs_counter = count($res);

 $pdf = new FPDF('L','mm',array(210,297));


 for($counter = 0; $rs_counter > $counter ;$counter ++){
        $rs_to =  strtoupper(htmlentities($res[$counter]['dts_fullname']));
        $rs_reffNo =  strtoupper(htmlentities($res[$counter]['referenceNo']));
        $rs_act_taken =  strtoupper(htmlentities($res[$counter]['actionNeeded']));
        $rs_remarks =  strtoupper(htmlentities($res[$counter]['remarks']));
        $rs_particulars = strtoupper(htmlentities($res[$counter]['particulars']));
        if ($counter % 2!=0){
                    $pdf->setX(150);
                    $pdf->rect(140,4,130,200,'S');
                    $pdf->Image('../assets/img/logo.png',145,5,30);
                    $pdf->SetFont('Arial','',10);
                    $pdf->setY(10);
                    $pdf->setX(150);
                    $pdf->Cell(35);
                    //Title
                    $pdf->Cell(35,0,'DEPARTMENT OF AGRICULTURE',0,0);
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','B',10);
                    $pdf->setX(150);
                    $pdf->Cell(30);
                    $pdf->Cell(30,2,'BUREAU OF AGRICULTURAL RESEARCH',0,0);
                    
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','',8);
                    $pdf->setX(150);
                    $pdf->Cell(25);
                    $pdf->Cell(25,2,'RDMIC bldg., Elliptical rd. cor. Visayas ave.,Diliman, Q.C 1104',0,0);
        
                    $pdf->Ln(5);
                    $pdf->setX(150);
                    $pdf->Cell(35);
                    $pdf->Cell(35,2,'Trunkline: 8461-2900/8461-2800  Fax: 8927-5691',0,0);
        
                    $pdf->Ln(5);
                    $pdf->setX(150);
                    $pdf->Cell(32);
                    $pdf->Cell(32,2,'Email:od@bar.gov.ph Website:www.bar.gov.ph',0,0);
        
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','B',24);
                    $pdf->setX(150);
                    $pdf->Cell(35);
                    $pdf->Cell(35,15,'Routing Slip',0,0);
        
        
                    $pdf->Ln(15);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    $pdf->Cell(15,10,'TO:',0,0,'L');
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(8);
                    $pdf->Line(178,56,255,56);
                    $pdf->Cell(90,8,$rs_to,0,0,'L');
                    
        
                    $pdf->Ln(8);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    $pdf->Cell(15,10,'CC:',0,0);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(8);
                    $pdf->Line(178,64,255,64);
                    $pdf->Cell(90,8,"",0,0,'L');
        
                    $pdf->Ln(8);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    $pdf->Cell(15,10,'SUBJECT:',0,0);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(8);
                    $pdf->Line(178,72,255,72);
                    $pdf->Cell(90,8,$rs_particulars,0,0,'L');
        
                    $pdf->Ln(25);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    if ($rs_act_taken =="FOR COMPLIANCE"){
                      $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                    }else
                    {
                      $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                    }
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(15);
                    $pdf->Cell(15,-5,'FOR COMPLIANCE',0,0,'L');
        
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    if ($rs_act_taken == "FOR APPROPRIATE ACTION")
                    {
                      $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                    }
                    else{
                      $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                    }
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(15);
                    $pdf->Cell(15,-5,'FOR APPROPRIATE ACTION',0,0,'L');
        
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    if($rs_act_taken =="FOR YOUR COMMENTS"){
                      $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                    }
                    else{
                      $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                    }
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(15);
                    $pdf->Cell(15,-5,'FOR YOUR COMMENTS',0,0,'L');
        
                    $pdf->Ln(5);
                    $pdf->setX(150);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(5);
                    if($rs_act_taken == "APPROVED / DISAPPROVED")
                    {
                    $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                    }
                    else{
                      $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                    }
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(15);
                    $pdf->Cell(15,-5,'APPROVED / DISAPPROVED',0,0,'L');
        
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    if($rs_act_taken == "PLS. SEE ME." ){
                      $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                    }
                    else{
                      $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                    }
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(15);
                    $pdf->Cell(15,-5,'PLS. SEE ME.',0,0,'L');
        
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    if ($rs_act_taken == 'FOR YOUR FILES'){
                      $pdf->Cell(7,5,'',1,1,'C',$fill = true);
                    }
                    else{
                      $pdf->Cell(7,5,'',1,1,'C',$fill = false);
                    }
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(15);
                    $pdf->Cell(15,-5,'FOR YOUR FILES',0,0,'L');
        
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(5);
                    if($rs_act_taken == 'FOR YOUR INFORMATION'){
                      $pdf->Cell(7,5,'',1,1,'C',$fill= true);
                    }
                    else{
                      $pdf->Cell(7,5,'',1,1,'C',$fill= false);
                    }
                    $pdf->SetFont('Arial','',12);
                    $pdf->setX(150);
                    $pdf->Cell(15);
                    $pdf->Cell(15,-5,'FOR YOUR INFORMATION',0,0,'L');
        
                    $pdf->Ln(3);
                    $pdf->setX(150);
                    $pdf->SetFont('Arial','',12);
                    $pdf->Cell(5);
                    $pdf->Cell(105,30,'',1,0,'L');
                    $pdf->Ln(2);
                    $pdf->setX(150);
                    $pdf->Cell(10,20,'',0,0,'L');
                    $pdf->Cell(25,5,'Remarks:',0,0,'L');
                    $pdf->Cell(30,25,$rs_remarks,0,0,'L');
                         }
                         else{
                          $pdf->AddPage();
                          $pdf->rect(5,4,130,200,'S');
                          $pdf->Image('../assets/img/logo.png',10,5,30);
                          $pdf->SetFont('Arial','',10);
                          $pdf->Cell(40);
                          //Title
                          $pdf->Cell(40,0,'DEPARTMENT OF AGRICULTURE',0,0);
                           $pdf->Ln(5);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(35);
                        $pdf->Cell(35,2,'BUREAU OF AGRICULTURAL RESEARCH',0,0);
        
                        $pdf->Ln(5);
                        $pdf->SetFont('Arial','',8);
                        $pdf->Cell(30);
                        $pdf->Cell(30,2,'RDMIC bldg., Elliptical rd. cor. Visayas ave.,Diliman, Q.C 1104',0,0);
        
                        $pdf->Ln(5);
                        $pdf->Cell(40);
                        $pdf->Cell(40,2,'Trunkline: 8461-2900/8461-2800  Fax: 8927-5691',0,0);
        
                        $pdf->Ln(5);
                        $pdf->Cell(37);
                        $pdf->Cell(37,2,'Email:od@bar.gov.ph Website:www.bar.gov.ph',0,0);
        
                        $pdf->Ln(5);
                        $pdf->SetFont('Arial','B',24);
                        $pdf->Cell(35);
                        $pdf->Cell(35,15,'Routing Slip',0,0);
        
        
                        $pdf->Ln(15);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        $pdf->Cell(15,10,'TO:',0,0,'L');
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(8);
                        $pdf->Line(40,56,120,56);
                        $pdf->Cell(90,8,$rs_to,0,0,'L');
                        
        
                        $pdf->Ln(8);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        $pdf->Cell(15,10,'CC:',0,0);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(8);
                        $pdf->Line(40,64,120,64);
                        $pdf->Cell(90,8,"",0,0,'L');
        
                        $pdf->Ln(8);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        $pdf->Cell(15,10,'SUBJECT:',0,0);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(8);
                        $pdf->Line(40,72,120,72);
                        $pdf->Cell(90,8,$rs_particulars,0,0,'L');
        
                        $pdf->Ln(25);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        if ($rs_act_taken =="FOR COMPLIANCE"){
                          $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                        }else
                        {
                          $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                        }
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(15);
                        $pdf->Cell(15,-5,'FOR COMPLIANCE',0,0,'L');
        
                        $pdf->Ln(5);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        if ($rs_act_taken == "FOR APPROPRIATE ACTION")
                        {
                          $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                        }
                        else{
                          $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                        }
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(15);
                        $pdf->Cell(15,-5,'FOR APPROPRIATE ACTION',0,0,'L');
        
                        $pdf->Ln(5);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        if($rs_act_taken =="FOR YOUR COMMENTS"){
                          $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                        }
                        else{
                          $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                        }
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(15);
                        $pdf->Cell(15,-5,'FOR YOUR COMMENTS',0,0,'L');
        
                        $pdf->Ln(5);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        if($rs_act_taken == "APPROVED / DISAPPROVED")
                        {
                        $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                        }
                        else{
                          $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                        }
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(15);
                        $pdf->Cell(15,-5,'APPROVED / DISAPPROVED',0,0,'L');
        
                        $pdf->Ln(5);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        if($rs_act_taken == "PLS. SEE ME." ){
                          $pdf->Cell(7,5,'',1,1,'C',$fill=true);
                        }
                        else{
                          $pdf->Cell(7,5,'',1,1,'C',$fill=false);
                        }
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(15);
                        $pdf->Cell(15,-5,'PLS. SEE ME.',0,0,'L');
        
                        $pdf->Ln(5);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        if ($rs_act_taken == 'FOR YOUR FILES'){
                          $pdf->Cell(7,5,'',1,1,'C',$fill = true);
                        }
                        else{
                          $pdf->Cell(7,5,'',1,1,'C',$fill = false);
                        }
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(15);
                        $pdf->Cell(15,-5,'FOR YOUR FILES',0,0,'L');
        
                        $pdf->Ln(5);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        if($rs_act_taken == 'FOR YOUR INFORMATION'){
                          $pdf->Cell(7,5,'',1,1,'C',$fill= true);
                        }
                        else{
                          $pdf->Cell(7,5,'',1,1,'C',$fill= false);
                        }
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(15);
                        $pdf->Cell(15,-5,'FOR YOUR INFORMATION',0,0,'L');
        
                        $pdf->Ln(3);
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(5);
                        $pdf->Cell(105,30,'',1,0,'L');
                        $pdf->Ln(2);
                        $pdf->Cell(10,20,'',0,0,'L');
                        $pdf->Cell(25,5,'Remarks:',0,0,'L');
                        $pdf->Cell(30,25,$rs_remarks,0,0,'L');
                   }
                  
                 }
 }

else{
  header("Location: http://localhost/WebPortal/records/history.php?code=I");
  exit();
  
}
                
    // footer
    $pdf->AliasNbPages();
    $pdf->Setfont('Arial','B',12);
              // }
          
    $pdf->Output();
  
// }


?>