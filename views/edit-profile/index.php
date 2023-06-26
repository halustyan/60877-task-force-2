<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item side-menu-item--active">
                <a class="link link--nav">Мой профиль</a>
            </li>
            <li class="side-menu-item">
                <a href="#" class="link link--nav">Безопасность</a>
            </li>
        </ul>
    </div>
    <div class="my-profile-form">

        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'method' => 'post', 'enableAjaxValidation' => true]]); ?>
        <h3 class="head-main head-regular">Мой профиль</h3>
        <div class="photo-editing">
            <?php
            $user = Yii::$app->user->identity;

            if ($user->user_img) {
                echo Html::img("/uploads/" . Html::encode($user->user_img), ["class" => "avatar-preview", "width" => "83", "height" => "83"]);
            } else {
                echo Html::img("/img/man-glasses.png", ["class" => "avatar-preview", "width" => "83", "height" => "83"]);
            }
            ?>

            <?= $form->field($model, 'user_img')->fileInput(['id' => 'button-input', 'style' => 'display:none;']) ?>

            <?= Html::label('Сменить аватар', 'button-input', ['class' => 'button button--black']) ?>

        </div>
        <div class="form-group">
            <?= $form->field($model, 'name', ['errorOptions' => ['id' => 'help-block'],
                'options' => ['id' => 'profile-name1']])
                ->textInput(['value' => Html::encode($user->name)])->label('Ваше имя'); ?>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <?= $form->field($model, 'email', ['errorOptions' => ['id' => 'help-block'],
                    'options' => ['id' => 'profile-name2']])
                    ->textInput(['value' => Html::encode($user->email)])->label('Email'); ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'date_of_birth')->widget(\kartik\date\DatePicker::class, [
                    'options' => ['placeholder' => 'Выберите дату рождения ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]); ?>
            </div>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <?= $form->field($model, 'phone', ['errorOptions' => ['id' => 'help-block'],
                    'options' => ['id' => 'profile-name3']])
                    ->textInput(['value' => Html::encode($user->phone)])->label('Телефон'); ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'telegram', ['errorOptions' => ['id' => 'help-block'],
                    'options' => ['id' => 'profile-name4']])
                    ->textInput(['value' => Html::encode($user->telegram)])->label('Телеграм'); ?>
            </div>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'quote', ['errorOptions' => ['id' => 'help-block'],
                'options' => ['id' => 'profile-name5']])
                ->textarea(['value' => Html::encode($user->quote)])->label('Информация о себе'); ?>
        </div>
        <div class="form-group">
            <p class="form-label">Выбор специализаций</p>
            <div class="checkbox-profile">

                <?php foreach ($categories as $category): ?>
                    <label class="control-label" for="category-<?= Html::encode($category->id) ?>">
                        <input type="checkbox" id="category-<?= Html::encode($category->id) ?>" name="EditProfile[categories][]" value="<?= Html::encode($category->id) ?>" <?= in_array($category->id, $model->categories) ? 'checked' : '' ?>>
                        <?= Html::encode($category->name); ?>
                    </label>
                <?php endforeach; ?>

            </div>
        </div>
        <input type="submit" class="button button--blue" value="Сохранить">
        <?php ActiveForm::end(); ?>


    </div>
</main>