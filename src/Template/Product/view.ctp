<div class="view-group">

	<?php
	echo $this->element('itemview-large', [
		'asin' => $asin,
		'item' => $item
	]);
	?>

	<h2>Contributors</h2>
	<ul class="user-list">
	<?php
	foreach($users as $user) {
		echo '<li><a href="' . $user['profile_url'] . '" target="_blank"><img src="' . $user['photo_url'] . '" height="20" /> ' . $user['name'] . '</a></li>';
	}
	?>
	</ul>

</div>