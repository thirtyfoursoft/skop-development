<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ((isset($_GET)) && $_GET['action'] == 'downloadDoc') {

		$doc = new downloaddoc( $_GET['fileName'], $_GET['tempName'], $_GET['user_id'], $_GET['type'] );
		$docUrl = $doc->getDownloadedUrl();

		wp_redirect($docUrl);
		exit;
}

class downloaddoc {

	public function __construct( $fileName, $tempName, $userID, $type ) {
		$this->fileName              = $fileName;
		$this->tempName 			= $tempName;
		$this->userID					= $userID;
		$this->type 					= $type;
		$this->pathurl					= get_site_url().'/wp-content/plugins/skopes/frontend/wordtemplate/files/codefiles/';
		$this->parentURL			= get_site_url().'/wp-content/plugins/skopes/frontend/wordtemplate/files';
		$this->parentpath			= ABSPATH.'wp-content/plugins/skopes/frontend/wordtemplate/files';
		$this->dirpathurl				= dirname($this->pathurl	);
		$this->dirpath				= $this->parentpath.'/codefiles/';
		$this->templatepath		= $this->parentpath.'/templates';
	}

	public function getDownloadedUrl() {

		require_once $this->parentpath.'/codefiles/function.php';
		require_once dirname($this->parentpath).'/classes/CreateDocx.inc';

		$docx = new CreateDocx();
		$newdocx = new getdocx();
		
		$result_filename = $this->tempName.'_result';

		$docxfile = $result_filename.$this->userID.'_html.docx';
		$existing_file = $this->parentpath.'/docx/'.$docxfile;



		if( file_exists($existing_file) ) {
			unlink($existing_file);
		}

		if ( $this->type == 'doc' ) {

			$filename = $newdocx->createuserdocx( $this->tempName, $result_filename, $this->userID );
			$docx->createDocx($filename);

			$downloadFileName = base64_encode($this->fileName);
			$finalFile = base64_encode($filename);

			$linktoReport = get_site_url().'/fileTest.php?file='.$finalFile.'&downloadFileName='.$downloadFileName;

		}

		if ( $this->type == 'pdf' ) {

			$filename = $newdocx->createuserdocx( $this->tempName, $result_filename, $this->userID);
			$docx->createDocx($filename);

			exec("java -jar ".ABSPATH."/wp-content/plugins/skopes/frontend/wordtemplate/lib/openoffice/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar $existing_file $existing_file.pdf");

			$fileNameWithoutExtension = strstr( $this->fileName, '.', true);
			$downloadFileName = base64_encode($fileNameWithoutExtension.'.pdf');

			$finalFile = base64_encode($this->parentURL.'/docx/'.$docxfile.'.pdf');

			$linktoReport = get_site_url().'/fileTestPdf.php?file='.$finalFile.'&downloadFileName='.$downloadFileName;
		}

		return $linktoReport;
	}

}
?>
