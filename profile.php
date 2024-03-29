<?php
session_start();

require_once 'db/Helpers.php';

function d($str): void
{
    echo '<pre>';
    var_dump($str);
    echo '</pre>';
}

function dd($str): never
{
    echo '<pre>';
    var_dump($str);
    echo '</pre>';
    exit;
}

include './db/lib/config.php';

foreach ($_SESSION['user'] as $key => $user)
{
    $userId = $key;
    $avatar = './public/pic/def_user.png';
    if ($user['avatar'] !== 0) {
        $avatar = './public/pic/' . $userId . '.png';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SF-33. Профиль</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</head>

<body>
    <h3 class="h3 my-3 text-center">Профиль пользователя</h3>
    <div class="container">
        <?php
        Helpers::get_alert();
        ?>
        <div class="row justify-content-center">
            <div class="col-6">
                <?php
                // изменение профиля
                $checked = ($_SESSION['user'][$userId]['hide_email'] == 1) ? 'checked' : '';
                $hiden = ($_SESSION['user'][$userId]['hide_email'] == 1) ? '(не отображается в чате)' : '(отображается в чате)';
                if (isset($_POST['edit']) and $_POST['token'] == $_COOKIE['PHPSESSID']) {
                    require_once 'db/Users.php';
                    $objUser = new Users;

                    if (isset($_POST['username']) and $_SESSION['user'][$userId]['name'] == $_POST['username']) {
                        $objUser->setName($_POST['username']);
                        $objUser->updateUser($userId);
                        $_SESSION['user'][$userId]['name'] = $_POST['username'];
                        header('location: profile.php');
                    } elseif (isset($_POST['username'])) {
                        if ($objUser->checkUserExists($_POST['username'])) {
                            $_SESSION['errors'][] = 'Такое ИМЯ уже используется';
                            header('location: profile.php');
                        } else {
                            $objUser->setName($_POST['username']);
                            $objUser->updateUser($userId);
                            $_SESSION['user'][$userId]['name'] = $_POST['username'];
                            header('location: profile.php');
                        }
                    }

                    if (isset($_POST['hide_email'])) {
                        $objUser->setHideEmail(1);
                        $objUser->updateHideEmail($userId);
                        $_SESSION['user'][$userId]['hide_email'] = 1;
                        header('location: profile.php');
                    } else {
                        $objUser->setHideEmail(0);
                        $objUser->updateHideEmail($userId);
                        $_SESSION['user'][$userId]['hide_email'] = 0;
                        header('location: profile.php');
                    }
                }
                ?>

                <div class="my-2">
                    <p><a href="chatroom.php" type="button" class="btn btn-warning">Вернуться в чат</a></p>
                </div>

                <div class="card justify-content-center" style="width: 25rem;">
                    <img src="<?= $avatar ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Email: <?= $user['email'] . ' ' . $hiden; ?></h5>

                        <form action="" method="post" class="card p-2">
                            <!-- CSRF -->
                            <input type="hidden" name="token" value="<?= $_COOKIE['PHPSESSID']; ?>">
                            <div class="field">
                                <label for="username" class="form-label">Имя пользователя</label>
                                <input type="text" name="username" class="form-control" value="<?= $user['name']; ?>">
                            </div>
                            <br>
                            <?php if ($user['name'] !== $user['email']) : ?>
                                <div>
                                    <input type="checkbox" id="hide_email" name="hide_email" value="<?= $checked ?>" <?= $checked ?>>
                                    <label for="scales"> Скрыть email от других пользователей?</label>
                                </div>
                            <?php endif; ?>
                            <br>
                            <div class="field">
                                <button type="submit" class="btn btn-warning" id="edit" name="edit">Обновить</button>
                            </div>
                        </form>

                        <p class="card-text">Вы можете добавить или изменить имя пользователя, так же при желании Вы можете скрыть отображение email в чате</p>
                    </div>
                </div>


            </div>

            <div class="col-6">
                <?php
                // загрузка файла
                if (!empty($_FILES))
                {
                    require("db/Users.php");
                    $objUser = new Users;
                    if (!$objUser->imageValidate($_FILES)) {
                        exit;
                    }
                    $file = $objUser->imageAdd($userId);
                    if (!$file) {
                        $_SESSION['errors'][] = 'Ошибка добавления в БД';
                        echo 'Ошибка добавления в БД';
                        exit;
                    }
                    $objUser->imageUpload($_FILES['img']['tmp_name'], $userId);
                    $_SESSION['user'][$userId]['avatar'] = 1;
                    header('location: profile.php');
                }
                ?>
                <div class="content-wrapper p-1 mb-1">
                    <div class="container">
                        <div class="card">
                            <div class="card-header text-center">Изменить аватар</div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-8">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Изображение</label>
                                                <input class="form-control" type="file" name="img">
                                            </div>
                                            <small class="form-text text-muted">
                                                <p class="mt-1"> Максимальный размер файла: <?php echo MAX_FILE_SIZE / 1000000; ?>Мб.</p>
                                                <p> Допустимые форматы: <?php echo implode(', ', ALLOWED_TYPES) ?>.</p>
                                            </small>
                                            <button type="submit" class="btn btn-sm btn-success">Добавить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>