
<h1 align="center"><?php echo 'Community : '. $groupName ?></h1>
<?php
if (!empty($posts)) {
    foreach ($posts as $post) {
        echo $this->render('post-template', ['post' => $post]);
    }
}else{
        echo $this->render('no-posts');
    }

