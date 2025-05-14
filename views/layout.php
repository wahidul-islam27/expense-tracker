<!-- views/layout.php -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Expense Tracker' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
        }

        nav {
            background-color: #333;
            padding: 15px;
            text-align: right;
        }

        nav a {
            color: white;
            margin-right: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$token = $_COOKIE['token'] ?? null;
if (empty($token)) {
    $role = '';
} else {
    $decode = JWT::decode($token, new Key(SECRET_KEY, 'HS256'));

    $role = $decode->data->role;
}

?>

<body>
    <?php $baseUrl = '/expense-tracker/public'; ?>
    <nav>
        <?php if ($role === 'user'): ?>
            <a href="<?= $baseUrl ?>/">Home</a>
            <a href="<?= $baseUrl ?>/add-expense">Add Expense</a>
            <a href="<?= $baseUrl ?>/report">Report</a>
            <a href="<?= $baseUrl ?>/profile">Profile</a>
        <?php elseif ($role === 'admin'): ?>
            <a href="<?= $baseUrl ?>/category">Category</a>
            <a href="<?= $baseUrl ?>/user-manage">Users</a>
        <?php endif ?>
        <a href="<?= $baseUrl ?>/logout">Logout</a>
    </nav>

    <div class="container">
        <?= $content ?>
    </div>

</body>

</html>