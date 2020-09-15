<?php
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

$inp_flag = $flag ? isset($_POST['insert']) : false;
if ($inp_flag) {
    // check empty
    $ele = array('name' => 'Name', 'pw' => 'Password', 'repw' => 'Re:Password');
    $err = array();

    foreach ($ele as $key => $value) {
        if (empty($_POST[$key])) {
            $inp_flag = false;
            $err[$key]= "$value が入力されていません";
        } else {
            $ele[$key] = $_POST[$key];
        }
    }

    // check password
    if ($inp_flag && strcmp($ele['pw'], $ele['repw']) != 0) {
        $inp_flag = false;
        $mes2 = "パスワードが一致しません";
    }

    // add account
    if ($inp_flag) {
        // get date
        $date = date('YmdHis');

        // hash password
        $pw_hash = password_hash($ele['pw'], PASSWORD_BCRYPT);        

        // insert
        try {
            $ins_ele = array(':id' => $date, ':name' => $ele['name'], ':pw' => $pw_hash);
            $sql = "insert into accounts values (:id, :name, :pw)";
            $stmt = $pdo->prepare($sql);
            $res = $stmt->execute($ins_ele);
        } catch (PDOException $e) {
            $mes2 = $e->getMessage();
        }
    }

}

// select database
if ($flag) {
    try {
        $sql = "select id, name, pw from accounts;";
        $stmt = $pdo->query($sql);
    } catch (PDOException $e) {
        $mes2 = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html><head>
    <meta charset="utf-8">
    <title>Step2</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head><body>
    <h1>Step2</h1>
    <h2>新規ユーザの登録</h2>
    <p><?php echo $mes1 ?></p>
    <p><?php echo $mes2 ?></p>

    <form method="post" action="">
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
            <td>Re:Password</td>
            <td><input type="password" name="repw"></td>
            <td class="err"><?php echo $err['repw'] ?></td>
        </tr><tr>
            <td colspan="2"><center><input type="submit" name="insert" value="登録"></center></td>
        </tr>
    </table>
    </form>

    <br>

    <table border="1">
        <tr>
            <th>ID</th><th>Name</th><th>Password</th>
        </tr>
<?php
foreach ($stmt as $row) {
    echo "<tr>";
    $row = array_values(array_unique($row));
    foreach ($row as $ele) echo "<td>${ele}</td>";
    echo "</tr>";
}
?>
    </table>
</body></html>