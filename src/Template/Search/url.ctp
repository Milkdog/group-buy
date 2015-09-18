<?php

$this->layout = 'default';

?>
<p>ASIN: <?= h($asin) ?></p>

<?php
echo $this->element('itemview-large', [
	'item' => $item
]);
?>

