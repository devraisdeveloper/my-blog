<?php

use app\helpers\Calculator;

$calc = new Calculator();
?>
<div class="row">
    <div class="col-lg-1">
        <span class="glyphicon glyphicon-user"></span>
    </div>  
    <div class="col-lg-11">
        <p><?=
            'Comment by : ' . (($comment->user == '') ? 'Anonymous' : $comment->commentCreator) . '  created ';
            echo $calc->time_elapsed_string($comment->created_at);
            ?></p>
        <p class="text-responsive"><?= $comment->text ?></p>
    </div>
</div>