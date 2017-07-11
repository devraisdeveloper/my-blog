<?php

if(!empty($communities)){
    foreach($communities as $model){
        echo $this->render('view',['model'=>$model]);
    }    
}else{
        echo $this->render('no-posts');
    }
