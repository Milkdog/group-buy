<div class="item-list">
	<ul>
		<?php
		foreach($groups as $groupInfo) {
			// Is the user the group owner
			$ownerText = '';
			if ($groupInfo['owner'] == true) {
				$ownerText = ' (Your Group)';
			}
		?>
			<li>
				<?php
				echo $this->Html->link(
					$groupInfo['group']['product_name'] . $ownerText,
					'/product/view/' . $groupInfo['group']['id']
				);
				?>
			</li>
		<?php
		}
		?>
	</ul>
</div>