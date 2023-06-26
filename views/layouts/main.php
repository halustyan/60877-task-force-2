<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\MainAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;

MainAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=e666f398-c983-4bde-8f14-e3fec900592a&lang=ru_RU"
            type="text/javascript"></script>
</head>
<body class="d-flex flex-column h-100">

<?php $this->beginBody() ?>

<?php if (\Yii::$app->controller->id !== "register"): ?>
    <header class="page-header">
        <nav class="main-nav">
            <a href='<?php echo Url::to(['/tasks']); ?>' class="header-logo">
                <?php echo Html::img('/img/logotype.png'); ?>
            </a>

            <div class="nav-wrapper">
                <ul class="nav-list">
                    <li class="list-item">
                        <a href="<?php echo Url::to(['/tasks']); ?>" class="link link--nav">Новое</a>
                    </li>
                    <li class="list-item">
                        <a href="<?php echo Url::to(['/my-tasks?status=new']); ?>" class="link link--nav">Мои
                            задания</a>
                    </li>
                    <li class="list-item">
                        <a href="<?php echo Url::to(['/tasks/add']); ?>" class="link link--nav">Создать задание</a>
                    </li>
                    <li class="list-item">
                        <a href="<?php echo Url::to(['/edit-profile']); ?>" class="link link--nav">Настройки</a>
                    </li>
                </ul>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const listItems = document.querySelectorAll('.nav-list .list-item');

                    function setActiveItem(index) {
                        const activeItem = document.querySelector('.nav-list .list-item--active');
                        if (activeItem) {
                            activeItem.classList.remove('list-item--active');
                        }

                        listItems[index].classList.add('list-item--active');
                        localStorage.setItem('activeListItemIndex', index);
                    }

                    const activeListItemIndex = localStorage.getItem('activeListItemIndex');

                    if (activeListItemIndex !== null) {
                        setActiveItem(activeListItemIndex);
                    }

                    listItems.forEach((listItem, index) => {
                        listItem.addEventListener('click', (e) => {
                            // e.preventDefault(); // Отмена перехода по ссылке
                            setActiveItem(index);
                        });
                    });

                    // Добавление обработчика события клика для логотипа
                    const logo = document.querySelector('.header-logo');
                    logo.addEventListener('click', (e) => {
                        // e.preventDefault(); // Отмена перехода по ссылке
                        setActiveItem(0); // Установка активного класса для первого элемента списка
                    });
                });
            </script>
        </nav>
        <div class="user-block">
            <?php
            $isGuest = Yii::$app->user->isGuest;
            if (!$isGuest): ?>
                <a href="#">
                    <?php
                    $user_img = \Yii::$app->user->identity->user_img;

                    if ($user_img !== null) {
                        if (!str_contains($user_img, 'http')) {
                            echo Html::img('/uploads/' . Html::encode($user_img), ['width' => '60', 'height' => '60']);
                        } else {
                            echo Html::img($user_img, ['width' => '60', 'height' => '60']);
                        }
                    } else {
                        echo Html::img('/img/man-glasses.png', ['width' => '60', 'height' => '60']);
                    }
                    ?>
                </a>

                <div class="user-menu">
                    <p class="user-name"><?php echo Html::encode(Yii::$app->user->identity->name); ?></p>
                    <div class="popup-head">
                        <ul class="popup-menu">
                            <li class="menu-item">
                                <a href="<?php echo Url::to(['edit-profile/index']); ?>" class="link">Настройки</a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="link">Связаться с нами</a>
                            </li>
                            <li class="menu-item">
                                <a href="<?php echo Url::to(['user/logout']); ?>" class="link">Выход из системы</a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>
<?php endif; ?>

<?= $content ?>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container"></div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
