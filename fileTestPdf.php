<?php
      $finalFile = base64_decode($_REQUEST['file']);
      $downloadFileName = base64_decode($_REQUEST['downloadFileName']);
	  
      header('Content-type: application/pdf'); 

      header('Content-Disposition: attachment; filename="'.$downloadFileName.'"'); 
	  
	  readfile($finalFile); 

?>
<?php die; ?>