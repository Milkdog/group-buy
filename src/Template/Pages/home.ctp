<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = 'default';

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

?>

<div class="jumbotron" role="main">
    <div class="container">
        <h1>Buy group gifts on Amazon</h1>
        <p>
        Group Buy allows for multiple people to come together to buy a gift for a friend.
        </p>
    </div>
</div>
<div class="container">
    <div class="row">
        <h1>Product URL</h1>
        <?= $this->Form->create(null, ['type' => 'get', 'url' => ['controller' => 'Search', 'action' => 'url']]) ?>
        <?= $this->Form->input('url', ['label' => '', 'required' => true]) ?>
        <?= $this->Form->button('Search') ?>
        <?= $this->Form->end() ?>
    </div>
</div>