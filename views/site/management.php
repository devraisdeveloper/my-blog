<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Management';
$this->params['breadcrumbs'][] = $this->title;
$model->managementPosition = 'admin';
?>

<div container="management-page">
    <div class="row">
        <div class="col-xs-6 col-xs-offset-3 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
            <div class="accordion">
                <div id="management-accordion" class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a href="#collapse-management-signup" data-parent="#accordion" data-toggle="collapse"> Signup</a>
                            </h4>
                        </div>
                        <div id="collapse-management-signup" class="panel-collapse collapse">
                            <div class="panel-body">

                                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                                <?= $form->field($model, 'name')->textInput() ?>

                                <?= $form->field($model, 'username')->textInput() ?>

                                <?= $form->field($model, 'email') ?>

                                <?= $form->field($model, 'password')->passwordInput() ?>

                                <?= $form->field($model, 'managementPosition')->radioList(['admin' => 'admin', 'moderator' => 'moderator'])->label('Management position'); ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>

                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a href="#collapse-management-login" data-parent="#accordion" data-toggle="collapse"> Login</a>
                            </h4>
                        </div>
                        <div id="collapse-management-login" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p>Please fill out the following fields to login:</p>
                                <?php
                                $form = ActiveForm::begin([
                                            'id' => 'login-form',
                                ]);
                                ?>

                                <?= $form->field($model, 'username')->textInput() ?>

                                <?= $form->field($model, 'password')->passwordInput() ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
