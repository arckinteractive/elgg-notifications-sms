<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('notifications:settings:add_url'),
	'name' => 'params[add_url]',
	'checked' => isset($entity->add_url) ? $entity->add_url : 1,
	'value' => 1,
	'default' => 0,
]);