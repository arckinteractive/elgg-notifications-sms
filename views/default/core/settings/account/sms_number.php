<?php

$user = elgg_get_page_owner_entity();

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('user:set:sms_number');
$content = elgg_view_input('text', array(
	'name' => 'sms_number',
	'value' => $user->getPrivateSetting('sms_number') ? : '+1',
	'label' => elgg_echo('user:set:sms_number:label'),
	'help' => elgg_echo('user:set:sms_number:help'),
		));

echo elgg_view_module('info', $title, $content);