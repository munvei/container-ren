<?php
$get_mes = $_GET['message'];
$post_mes = $_POST['message'];
$type = "データが送信されていません";
$mes = "メッセージが設定されていません";

if (isset($get_mes)) {
  $type = "GET";
  $mes = $get_mes;
} elseif (isset($post_mes)) {
  $type = "POST";
  $mes = $post_mes;
}
?>

<!DOCTYPE HTML>
<html><head>
  <meta charset="utf-8">
  <title>リザルトページ</title>
</head><body>
  <h1>リザルトページ</h1>

  <p>データ送信タイプ: <?php echo $type ?></p>
  <p>入力された内容: <?php echo $mes ?></p>

</body></html>