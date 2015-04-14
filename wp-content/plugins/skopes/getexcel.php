<?php

	set_include_path("../../..");
	require_once 'wp-load.php';
	require_once 'wp-includes/pluggable.php';
	require_once 'wp-includes/registration.php';

	include 'PHPExcel.php';

	$objPHPExcel = new PHPExcel();

	$objPHPExcel->setActiveSheetIndex(0);


	if(!isset($_REQUEST['user_id']))
		return;

	if(!isset($_REQUEST['user_key']))
		return;

	if(!isset($_REQUEST['reportid']))
		return;

	if(md5('hej'.$_REQUEST['user_id']) != $_REQUEST['user_key'])
		return;

	switch ($_REQUEST['reportid'])
	{
		case 'customcat':
			$objPHPExcel->getActiveSheet()->setTitle('List of Custom Categories');
			$data = getCustomCategoryReportData();
			$r=1;
			$cnt=0;
			foreach ($data as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r,$data[$cnt]["user"]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r,$data[$cnt]["name"]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r,$data[$cnt]["text"]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$r,$data[$cnt]["comment"]);
				$cnt++;
				$r++;
			}
			break;

		case 'customli':
			$objPHPExcel->getActiveSheet()->setTitle('List of Custom Line Items');
			$data = getCustomLineitemReportData();
			$r=1;
			$cnt=0;
			foreach ($data as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r,$data[$cnt]["user"]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r,$data[$cnt]["category"]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r,$data[$cnt]["name"]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$r,$data[$cnt]["text"]);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$r,$data[$cnt]["comment"]);
				$cnt++;
				$r++;
			}
			break;

		case 'standardlia':
			$objPHPExcel->getActiveSheet()->setTitle('List Item by Rating');
			$data = getStandardLineItemReportData('averagecmp');
			$r=1;
			$cnt=0;
			foreach ($data as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r,$data[$cnt][0]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r,$data[$cnt][1]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r,$data[$cnt][2]);
				$cnt++;
				$r++;
			}
			break;

		case 'standardlis':
			$objPHPExcel->getActiveSheet()->setTitle('List Item by Popularity');
			$data = getStandardLineItemReportData('numselectioncmp');
			$r=1;
			$cnt=0;
			foreach ($data as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r,$data[$cnt][0]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r,$data[$cnt][1]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r,$data[$cnt][2]);
				$cnt++;
				$r++;
			}
			break;

		case 'all':
			/* first worksheet */
			$objPHPExcel->getActiveSheet()->setTitle('List of Custom Categories');
			$data = getCustomCategoryReportData();
			$r=1;
			$cnt=0;
			foreach ($data as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r,$data[$cnt]["user"]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r,$data[$cnt]["name"]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r,$data[$cnt]["text"]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$r,$data[$cnt]["comment"]);
				$cnt++;
				$r++;
			}

			/* second worksheet */
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex(1);
			$objPHPExcel->getActiveSheet()->setTitle('List of Custom Line Items');
			$data1 = getCustomLineitemReportData();
			$r1=1;
			$cnt1=0;
			foreach ($data1 as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r1,$data1[$cnt1]["user"]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r1,$data1[$cnt1]["category"]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r1,$data1[$cnt1]["name"]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$r1,$data1[$cnt1]["text"]);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$r1,$data1[$cnt1]["comment"]);
				$cnt1++;
				$r1++;
			}

			/* third worksheet */
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setTitle('List Item by Rating');
			$data2 = getStandardLineItemReportData('averagecmp');
			$r2=1;
			$cnt2=0;
			foreach ($data2 as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r2,$data2[$cnt2][0]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r2,$data2[$cnt2][1]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r2,$data2[$cnt2][2]);
				$cnt2++;
				$r2++;
			}

			/* fourth worksheet */
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex(3);
			$objPHPExcel->getActiveSheet()->setTitle('List Item by Popularity');
			$data3 = getStandardLineItemReportData('numselectioncmp');
			$r3=1;
			$cnt3=0;
			foreach ($data3 as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$r3,$data3[$cnt3][0]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$r3,$data3[$cnt3][1]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$r3,$data3[$cnt3][2]);
				$cnt3++;
				$r3++;
			}
			break;

	}

	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('report.xls');
	header('location: '.plugins_url().'/specdoc.me/report.xls');
	exit;