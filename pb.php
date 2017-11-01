<?php

/** 
* @desc дія для проведення(підтвердження) платежу за запитом банку
*/
	ob_start();

	require_once 'classes/pbDemo.class.php';
	require_once 'classes/pbLib.class.php';
	require_once 'classes/pbXml.class.php';

	define('SAFE_MODE', false);

	$allowedIps = array('217.117.64.232', '217.117.68.232');

	define('SCHEMA','http://www.w3.org/2001/XMLSchema-instance');
	$apiUrl = 'http://debt.privatbank.ua/Transfer';

	$stdin 		= file_get_contents('php://input');
	$data 		= pbXml::xml2array($stdin);
	$action 	= $data['Transfer']['attr']['action'];

	$xmlheader  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
	$xmlheader .= '<Transfer xmlns="' . $apiUrl . '" interface="Debt" action="' . $action . '">';

	$xmlbody = '';

	if (!$stdin)
	{
		$xmlbody .= pbXml::error(99, 'Не передано даних для обробки');
	}
	else if (!$action)
	{
		$xmlbody .= pbXml::error(99, 'Не задано дії для обробки');
	} 
	else
	{
		$pbAdapter = new pbDemo();

		$isFailed = false;
		if (SAFE_MODE)
		{
			if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIps))
			{
				$xmlbody .= pbXml::error(5);
				$isFailed = true;
			}
		}

		if (!$isFailed)
		{
			$pbXml = '';

			switch ($action)
			{
				case 'Presearch':
					include 'actions/presearch.php';
					break;
				case 'Search':
					include 'actions/search.php';
					break;
				case 'Check':
					include 'actions/check.php';
					break;
				case 'Pay':
					include 'actions/pay.php';
					break;
				case 'Cancel':
					include 'actions/cancel.php';
					break;
			}

			$xmlbody .= $pbXml;	
		}
	}

	$xmlfooter = '</Transfer>';

	$ob = ob_get_contents();
	ob_end_clean();

	header('Content-Type: application/xml; charset=utf-8');
	echo $xmlheader .
		 $xmlbody .
		 $xmlfooter;
