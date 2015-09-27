$<input type="text" placeholder = "20.00" id="contribute-amount" />
<input type="hidden" name="group-id" id="group-id" value="<?= $groupId ?>" />
<?php
echo $this->Html->link(
	'Contribute',
	'/product/contribute/' . $groupId,
	[
		'id' => 'stripe-contribute',
		'class' => 'btn btn-primary',
		'data-name' => $user['display_name'],
		'data-email' => $user['email']
	]
);
?>