<div class="item-view large">
	<h2><?= $item['title'] ?></h2>
	<img src="<?= $item['image'] ?>">
	<div class="price"><?= $item['price'] ?></div>
	<a class="btn btn-default" href="/product/add/<?= $asin ?>">Start Group Buy</a>
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