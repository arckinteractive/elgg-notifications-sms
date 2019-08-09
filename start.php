<?php

/**
 * SMS notifications API
 *
 * @author    Ismayil Khayredinov <info@arckinteractive.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', 'notifications_sms_init');

/**
 * Initialize the plugin
 * @return void
 */
function notifications_sms_init() {

	elgg_register_notification_method('sms');
	elgg_register_plugin_hook_handler('send', 'notification:sms', 'notifications_sms_send_hook');

	elgg_extend_view('forms/account/settings', 'core/settings/account/sms_number');
	elgg_register_plugin_hook_handler('usersettings:save', 'user', 'notification_sms_save_user_sms_number');

	elgg_extend_view('elgg.css', 'notifications/sms.css');
}

/**
 * Send an SMS
 *
 * @param \ElggUser $to   Recipient
 * @param string    $text Text of the SMS
 *
 * @return bool
 */
function notifications_sms_send(\ElggUser $to, $text) {
	$to = $to->getPrivateSetting('sms_number');
	if (!$to) {
		return false;
	}
	$params = [
		'to' => $to,
		'text' => $text,
	];

	return elgg_trigger_plugin_hook('send', 'sms', $params, false);
}

/**
 * Prepare and send a notification via SMS
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Has the notification been sent
 * @param array  $params Hook parameters
 *
 * @return bool Was the notification handled successfully
 */
function notifications_sms_send_hook($hook, $type, $result, $params) {

	$notification = elgg_extract('notification', $params);
	/* @var \Elgg\Notifications\Notification $notification */

	$event = elgg_extract('event', $params);
	/* @var \Elgg\Notifications\NotificationEvent $event */

	if (isset($params['sms'])) {
		$body = $params['sms'];
	} else if ($notification->sms) {
		$body = $notification->sms;
	} else if ($notification->summary) {
		$body = $notification->summary;
	} else if ($notification->subject) {
		$body = $notification->subject;
	} else {
		$body = $notification->body;
	}

	$body = strip_tags($body);
	if (!$body) {
		return;
	}

	$url = false;

	if (elgg_get_plugin_setting('add_url', 'notifications_sms', true)) {
		$url = elgg_get_site_url();

		if (isset($notification->params['url'])) {
			$url = $notification->params['url'];
		} else {
			$object = $event->getObject();

			if ($object instanceof ElggEntity) {
				$url = $object->getURL();
			}
		}
	}

	if ($url) {
		$url = elgg_normalize_url($url);
		
		$body .= ' ' . $url;
	}

	$to = $notification->getRecipient();

	return notifications_sms_send($to, $body);
}

/**
 * Save user's SMS delivery phone number
 * @return void
 */
function notification_sms_save_user_sms_number() {

	$sms_number = get_input('sms_number');
	$user_guid = get_input('guid');

	if (!isset($sms_number)) {
		return;
	}

	if ($user_guid) {
		$user = get_user($user_guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}

	if ($user && $sms_number) {
		if (0 == strcmp($sms_number, $user->getPrivateSetting('sms_number'))) {
			// no change
			return;
		}

		if (0 !== strpos($sms_number, '+')) {
			register_error(elgg_echo('user:set:sms_number:wrong_format'));

			return;
		}

		if ($user->setPrivateSetting('sms_number', $sms_number)) {
			notifications_sms_send($user, elgg_echo('user:set:sms_number:notify'));
			system_message(elgg_echo('user:set:sms_number:success'));
		} else {
			register_error(elgg_echo('user:set:sms_number:fail'));
		}
	} else {
		register_error(elgg_echo('user:set:sms_number:fail'));
	}
}
