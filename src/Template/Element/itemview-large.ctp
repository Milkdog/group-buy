<div class="item-view large">
	<h2><?= $item['title'] ?></h2>
	<img src="<?= $item['image'] ?>">
	<div class="price"><?= $item['price'] ?></div>
	<?php
		if (isset($groupId) && is_numeric($groupId)) {
			echo $this->Html->link(
				'Join Group Buy',
				'/product/join/' . $groupId,
				['class' => 'btn btn-default']
			);
			echo $this->element('stripe-pay', [
				'user' => $user,
				'groupId' => $groupId
			]);
		} else {
			echo $this->Html->link(
				'Start Group Buy',
				'/product/add/' . $asin . '/' . urlencode($item['title']),
				['class' => 'btn btn-default']
			);
		}
	?>

	<div class="features">
		<ul>
			<?php
				foreach($item['features'] as $feature) {
					echo '<li>' . $feature . '</li>';
				}
			?>
		</ul>
	</div>
</div>