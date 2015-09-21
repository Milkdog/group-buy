<?php

$this->layout = 'default';

?>
<p>ASIN: <?= h($asin) ?></p>

<?php
echo $this->element('itemview-large', [
	'asin' => $asin,
	'item' => $item
]);
?>

