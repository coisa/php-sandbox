<?php $this->layout('layout/default', ['title' => 'Hello World!']) ?>

<div class="jumbotron">
    <div class="container">
        <h1 class="display-3">Hello, world!</h1>
        <hr class="my-4">
        <p><?php echo microtime(true) ?></p>
    </div>
</div>
