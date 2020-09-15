<?php
session_start();

$loggedin = isset($_SESSION['name']);

$USER = "mysqluser";
$PW = "mysqlPassword0!";
$DBINFO = "mysql:host=172.17.0.3;dbname=testdb";

// connnect to mysql
$flag = false;
try {
    $pdo = new PDO($DBINFO, $USER, $PW);
    $mes1 = "接続しました<br>";
    $flag = true;
} catch (PDOException $e) {
    $mes1 = $e->getMessage();
}

if ($loggedin) {
    // logout
    if (isset($_POST['logout'])) {
        session_destroy(); // unset($_SESSION['name']); でもいいかも
        $mes2 = "ログアウトしました";
        $loggedin = false;
    }

} else {

    // check empty
    $ele = array('name' => 'Name', 'pw' => 'Password');
    $err = array();

    $inp_flag = false;
    if (isset($_POST['login'])) {
        foreach ($ele as $key => $value) {
            if (empty($_POST[$key])) {
                $inp_flag = false;
                $err[$key]= "$value が入力されていません";
            } else {
                $ele[$key] = $_POST[$key];
                $inp_flag = true;
            }
        }
    }

    // select database
    $sel_flag = false;
    if ($inp_flag) {
        try {
            $sql = "select pw from accounts where name = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $ele['name']);
            $res = $stmt->execute();

            // check result
            if ($res == 1) {
                $sel_flag = true;
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $mes2 = "ccurred database error";
            } 

        } catch (PDOException $e) {
            $mes2 = $e->getMessage();
        }
    }

    // check password and login
    if ($sel_flag) {
        if (password_verify($ele['pw'], $row['pw'])) {
            $_SESSION['name'] = $ele['name'];
            $loggedin = true;
        } else {
            $mes2 = "ログインに失敗しました";
        }
    }
}

if ($loggedin)  {
    $status = "ログイン済み";
    $user = $_SESSION['name'];
} else {
    $status = "未ログイン";
    $user = "ゲスト";
}

?>

<!DOCTYPE html>
<html><head>
    <meta charset="utf-8">
    <title>Step3</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head><body>
    <h1>Step3</h1>
    <h2><?php echo $status ?></h2>
    <h3><?php echo "ようこそ $user さん" ?></h3>
    <p><?php echo $mes1 ?></p>
    <p><?php echo $mes2 ?></p>

    <form action="" method="post">
<?php if ($loggedin) : ?>
    <p><input type="submit" name="logout" value="ログアウト"></p>
<?php else : ?>
    <table>
        <tr>
            <td>Name</td>
            <td><input type="text" name="name"></td>
            <td class="err"><?php echo $err['name'] ?></td>
        </tr><tr>
            <td>Password</td>
            <td><input type="password" name="pw"></td>
            <td class="err"><?php echo $err['pw'] ?></td>
        </tr><tr>
            <td colspan="2"><center><input type="submit" name="login" value="ログイン"></center></td>
        </tr>
    </table>
<?php endif; ?>
    </form>
</body></html>