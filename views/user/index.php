<?php

use app\models\TagsAttribute;

/**
 * @var app\models\User $singleUser ;
 */

use yii\helpers\Html;
?>


<main class="main-content container">
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php endif; ?>
    <div class="left-column">
        <h3 class="head-main"><?php echo $singleUser->name; ?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <?php echo \yii\helpers\Html::img("/img/{$singleUser->user_img}", ['alt' => '', 'id' => '', 'width' => 191, 'height' => 190]); ?>
                <?php if ($numberReplies > 0): ?>
                    <div class="card-rate">

                        <div class="stars-rating big">
                            <?php for ($i = 0; $i < $singleUser->getUserAvgRating(); $i++): ?>
                                <span class="fill-star"></span>
                            <?php endfor; ?>

                        </div>
                        <span class="current-rate">
                        <?php if (!is_null($singleUser->getUserAvgRating())): ?>
                            <?php echo $singleUser->getUserAvgRating(); ?>
                        <?php endif; ?>
                    </span>

                    </div>
                <?php endif; ?>
            </div>
            <p class="user-description">
                <?php echo $singleUser->quote; ?>
            </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации </p>
                <ul class="special-list">
                    <?php foreach ($singleUser->tags as $tag): ?>
                        <li class="special-item">
                            <a href="#" class="link link--regular"><?php echo $tag->attributes; ?></a>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bio">
                <p class="head-info"> Био</p>
                <p class="bio-info">
                    <span class="country-info"><?php echo Html::encode($singleUser->country); ?></span>,
                    <span class="town-info"><?php
                        if ($singleUser->city) {
                            echo $singleUser->city->name;
                        } else {
                            echo 'Город не указан';
                        }
                        ?></span>,
                    <span class="age-info"><?php echo $singleUser->age; ?></span> лет
                </p>
            </div>
        </div>
        <h4 class="head-regular">Отзывы заказчиков</h4>

        <?php foreach ($singleUser->executorReplies as $reply): ?>

            <div class="response-card">
                <?php echo \yii\helpers\Html::img("/img/{$reply->userWriter->user_img}", ['alt' => '', 'id' => '', 'width' => 120, 'height' => 127]); ?>
                <div class="feedback-wrapper">
                    <p class="feedback">
                        <?php echo $reply->description; ?>
                    </p>
                    <p class="task">Задание «<a href="<?php echo $reply->getLastTask()->id; ?>"
                                                class="link link--small"><?php echo $reply->getLastTask()->name ?></a>»
                        выполнено
                    </p>
                </div>
                <div class="feedback-wrapper">

                    <div class="stars-rating small">
                        <?php for ($i = 0; $i < $reply->rate; $i++): ?>
                            <span class="fill-star">&nbsp;</span>
                        <?php endfor; ?>
                    </div>
                    <p class="info-text"><span class="current-time"><?php echo $reply->getWasOnSite(); ?> </span>назад
                    </p>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                <dt>Всего заказов</dt>
                <dd><?php echo count($singleUser->doneTasks); ?>
                    выполнено, <?php echo count($singleUser->failedTasks); ?> провалено
                </dd>
                <?php
                $rating = $singleUser->getRating();

                if (!is_null($rating)):?>
                    <dt>Место в рейтинге</dt>

                    <dd>
                        <?php
                        if ($rating !== false) {
                            echo $rating['rate'];
                        }

                        ?>

                    </dd>
                <?php endif; ?>
                <dt>Дата регистрации</dt>
                <dd><?php echo $singleUser->dt_add; ?></dd>
                <dt>Статус</dt>
                <?php if ($singleUser->status === 1): ?>
                    <dd>Открыт для новых заказов</dd>
                <?php else: ?>
                    <dd>Закрыт для новых заказов</dd>
                <?php endif; ?>
            </dl>
        </div>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--phone"><?php echo $singleUser->phone; ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--email"><?php echo $singleUser->email; ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--tg">@<?php echo $singleUser->telegram; ?></a>
                </li>
            </ul>
        </div>
    </div>

</main>