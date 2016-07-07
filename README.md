SMS Notifications API
=====================
![Elgg 2.1](https://img.shields.io/badge/Elgg-2.1.x-orange.svg?style=flat-square)

## Features

 * API to integrate Elgg notifications with an SMS provider

## Notes


### Sending

The plugin does not send actual SMS messages. The provider plugin must register
for `'send', 'sms'` hook and use provider API to dispatch messages. The hook will
receive `to` phone number and `text` as parameters.

To send an SMS, use `notifications_sms_send()`.

### Instant notifications

To provide a custom SMS text for instant notifications, pass `sms` with parameters:

```php
notify_user($user->guid, elgg_get_site_entity()->guid, 'Really long notification subject', 'Really long notification body', array(
	'sms' => 'Send this sms',
), 'email');
```

### Subscriptions notifications

To provide a custom SMS text for subscription notifications that differs from the `summary`, set `sms` property on the `\Elgg\Notifications\Notification` instance in the `prepare` hook.