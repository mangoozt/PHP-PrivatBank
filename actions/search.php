<?php

	$y = date('Y');
	$m = date('m');

	$isError = false;
	$serviceCode = '';
	if (isset($data['Transfer']['Data']['attr']['presearchId']))
	{
		$presearchId = $data['Transfer']['Data']['attr']['presearchId'];

		$currentPayer = $pbAdapter->getPayerByNum($presearchId);
		if (!$currentPayer)
		{
			$pbXml = pbXml::error(2);
			$isError = true;
		}
		else
		{
			$payerDebts = $pbAdapter->selectDebts($currentPayer['id'], $serviceCode);
		}
	}
	else if (isset($data['Transfer']['Data']['Unit']))
	{
		$billIdentifier = $data['Transfer']['Data']['Unit']['attr']['value'];

		$currentPayer = $pbAdapter->getPayerByNum($billIdentifier);
		if (!$currentPayer)
		{
			$pbXml = pbXml::error(2);
			$isError = true;
		}
		else
		{
			$pbXml .= '<Message>���� ��� ������������� ����� �������� � ���!</Message>';
//			$pbXml .= '<DopData>';
//			$pbXml .= '<Dop name="��� ������" value="��������"/>';
//			$pbXml .= '</DopData>';

			$payerDebts = $pbAdapter->selectDebts($currentPayer['id'], $serviceCode);
		}
	}

	if (!$isError)
	{
		$pbXml .=  pbXml::payerInfo($currentPayer, $currentPayer['num']);
		$pbXml .= '<ServiceGroup>';

		foreach ($payerDebts as $debt)
		{
			$currentCompany = $pbAdapter->getCompanyByService($debt['service_id']);

			$tariff = '';
			if (isset($debt['service_price']) && $debt['service_price'] != '')
			{
				$tariff = ' metersGlobalTarif="' . $debt['service_price'] . '"';
			}

			$pbXml .= '<DebtService' . $tariff . ' serviceCode="' . $debt['service_id'] . '">';
//			$pbXml .= '<DopData>';
//			$pbXml .= '<Dop name="fine" value="2.51"/>';
//			$pbXml .= '</DopData>';
			$pbXml .=  pbXml::companyInfo($currentCompany);
			$pbXml .=  pbXml::debtInfo($debt);
//			$pbXml .= '<MeterData>';
//			$pbXml .= '<Meter previosValue="213" tarif="0.01" delta="2341234" name="��������������� �������"/>';
//			$pbXml .= '</MeterData>';
			$pbXml .= '<ServiceName>' . $debt['service_name'] . '</ServiceName>';
			$pbXml .= '<Destination>������ �� ������� "' . $debt['service_name'] . '"</Destination>';
			$pbXml .=  pbXml::payerInfo($currentPayer, $currentPayer['num'], $currentPayer['num']);
			$pbXml .= '</DebtService>';
		}
		
		$pbXml .= '</ServiceGroup>';

		$pbXml = pbXml::data($pbXml, $schema, 'DebtPack');
	}

?>