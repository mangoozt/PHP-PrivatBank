<?php
/**
 * @var pb $pbAdapter
 */
$ref = pbLib::getCheckRef($data);
$res = $pbAdapter->cancelPayment($ref);
if ($res) {
	$pbXml = pbXml::data('', SCHEMA, 'Gateway', $ref);
} else {
	$pbXml = pbXml::error(99, 'Помилка скасування платежу');
}
