<?php

return [
	'user:set:sms_number' => 'SMS Delivery',
	'user:set:sms_number:label' => 'Phone Number',
	'user:set:sms_number:help' => 'This phone number will be used to deliver SMS messages should you opt in for SMS notifications. '
	. 'Phone number must include the country code (i.e. +1xxxxxxxxxx)',
	'user:set:sms_number:success' => 'SMS delivery number has been updated. We have sent a confirmation to the new number',
	'user:set:sms_number:fail' => 'SMS delivery number could not be updated',
	'user:set:sms_number:wrong_format' => 'SMS delivery number must start with a country code, i.e. +1',
	'user:set:sms_number:notify' => 'Your phone number has been updated',
	'notification:method:sms' => 'SMS',

	'notifications:settings:add_url' => 'Add notification URLs to outgoing SMS messages',
];