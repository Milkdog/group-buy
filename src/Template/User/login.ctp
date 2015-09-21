<?php

echo $this->Html->link(
    'Login with Facebook',
    ['controller' => 'User', 'action' => 'login', '?' => ['provider' => 'Facebook']]
);