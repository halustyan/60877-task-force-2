<?php

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\User;

$this->title = 'Страница добавления задания';
?>
<?php echo Yii::$app->session->getFlash('success_task_add'); ?>
<br>
<?php echo Yii::$app->session->getFlash('success_files_add'); ?>
?>
<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">

        <?php
        $form = ActiveForm::begin(['options' => ['method' => 'post', 'enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]); ?>
        <h3 class="head-main head-main">Публикация нового задания</h3>
        <?= $form->field($model, 'about_job', ['template' => '{label}{input}{error}{hint}',
            'options' => ['class' => 'form-group']])
            ->textInput(['value' => ''])->label('Опишите суть работы', ['class' => 'control-label']); ?>
        <?= $form->field($model, 'describe_task', ['errorOptions' => ['class' => 'help-block'],
            'options' => ['class' => 'form-group']])
            ->textarea(['value' => '', 'class' => 'username'])->label('Подробности задания', ['class' => 'control-label']); ?>
        <?php echo $form->field($model, 'categories')->dropdownList(
            ArrayHelper::map($categories, 'id', 'name')
        ); ?>
        <?= $form->field($model, 'location', ['errorOptions' => ['class' => 'help-block'],
            'options' => ['id' => 'location']])
            ->textInput(['class' => 'location-icon'])->label('Локация', ['class' => 'control-label']); ?>
        <div class="half-wrapper">
            <?= $form->field($model, 'budget', ['errorOptions' => ['class' => 'help-block3'],
                'options' => []])
                ->textInput(['class' => 'budget-icon'])->label('Бюджет', ['class' => 'control-label']); ?>
            <?= $form->field($model, 'expire_date', ['errorOptions' => ['class' => 'help-block'],
                'options' => []])
                ->input('date')->label('Срок исполнения', ['class' => 'control-label']); ?>
        </div>
        <p class="form-label">Файлы</p>

        <?= $form->field($model, 'files[]', ['errorOptions' => ['class' => 'help-block'],
            'options' => ['class' => 'add-file']])
            ->fileInput(['multiple' => 'multiple', 'accept' => ['jpg', 'gif']])->label('', ['class' => 'control-label']); ?>

        <?= Html::submitButton('Опубликовать', ['class' => 'button button--blue']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</main>