<div class="view-group">

	<?php
	echo $this->element('itemview-large', [
		'asin' => $asin,
		'group_id' => $groupId,
		'item' => $item,
		'user' => $user
	]);
	?>

	<h2>Contributors</h2>
	<ul class="user-list">
	<?php
	foreach($users as $user) {
		echo '<li>';
		echo $this->Html->link(
			$this->Html->image($user['photo_url'], ['height' => '30']) . ' ' . $user['name'],
			$user['profile_url'],
			['escapeTitle' => false, 'class' => 'profile_image', 'target' => '_blank']
		);
		echo '</li>';
	}
	?>
	</ul>

</div>