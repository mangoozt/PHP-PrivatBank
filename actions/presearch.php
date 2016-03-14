<?php

	$units = $data['Transfer']['Data']['Unit'];
	$funit = reset($units);

	$billPayers = array();

	if (isset($funit['name']) &&
		$funit['name'] == 'ls')
	{
		$pn	= $funit['value'];
		if (!$pn)
		{
			$pbXml = pbXml::error(99, '�������� "�������� �������" 
				� ����\'�������');
		}
		else
		{
			$billPayers = $pbAdapter->selectPayersByNum($pn);
		}				
	}
	if (isset($funit['name']) &&
		$funit['name'] == 'login')
	{
		$resource = $funit['value'];
		if (!$resource)
		{
			$pbXml = pbXml::error(99, '�������� "����" 
				� ����\'�������');
		}
		else
		{
			$billPayers = $pbAdapter->selectPayersByResource($resource);
		}				
	}
	else if ($funit['attr']['name'] == 'street')
	{
		$street = $units[0]['attr']['value'];
		$house  = $units[1]['attr']['value'];
		$flat   = $units[2]['attr']['value'];

		if (!$street || !$house)
		{
			$pbXml = pbXml::error(99, '��������� "������" �� 
				"�������" � ����\'��������');
		}
		else
		{
			$address = array();
			$address['street'] = $street;
			$address['house']  = $house;
			$address['flat']   = $flat;

			$billPayers = $pbAdapter->selectPayersByAddr($address);
		}
	}

	if ($billPayers &&
		sizeof($billPayers) > 5)
	{
		$pbXml = pbXml::error(99, '�������� ����� 5 ������!
			������� ��������� ������');
	}
	else if ($billPayers)
	{
		$pbXml .= '<Headers>';
		$pbXml .= '<Header name="fio"/>';
		$pbXml .= '<Header name="ls"/>';
		$pbXml .= '</Headers>';
		$pbXml .= '<Columns>';

		$pbXml .= '<Column>';
		$txml = '';
		foreach ($billPayers as $payer)
		{
			$pbXml .= '<Element>' . $payer['name'] . '</Element>';
			$txml  .= '<Element>' . $payer['num'] . '</Element>';
		}

		$pbXml .= '</Column>';

		$pbXml .= '<Column>';
		$pbXml .= $txml;
		$pbXml .= '</Column>';
		$pbXml .= '</Columns>';

		$pbXml = pbXml::data($pbXml, $schema, 'PayersTable');
	}
	else if (!$pbXml)
	{
		$pbXml = pbXml::error(2, '�������� ��������� ������');
	}

?>