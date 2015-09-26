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

?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Group Buy - Amazon for group purchases :: <?= $this->fetch('title') ?></title>

        <!-- Bootstrap CSS -->
        <?= $this->Html->css('../bootstrap/css/bootstrap.min.css') ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>

    </head>
    <body>
         <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">Amazon Group Buy</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="/">Home</a></li>
                        <li><a href="/product/view/4">Group View</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        <div class="row">
            <div class="main container">
                <div class="facebook-login">
                    <?php
                        echo $this->Html->link(
                            'Login with Facebook',
                            ['controller' => 'User', 'action' => 'login', '?' => ['provider' => 'Facebook']]
                        );
                    ?>
                </div>

                <?= $this->fetch('content') ?>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <?= $this->Html->script(['//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', '../bootstrap/js/bootstrap.min.js']) ?>
        <?= $this->fetch('script') ?>
    </body>
</html>
