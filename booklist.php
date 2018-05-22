<?php
    // MySQLサーバ接続に必要な値を変数に代入
    $username = 'root';
    $password = '';

    // PDO のインスタンスを生成して、MySQLサーバに接続
    $database = new PDO('mysql:host=localhost;dbname=booklist;charset=UTF8;', $username, $password);

    // フォームから書籍タイトルが送信されていればデータベースに保存する
    if ($_POST['book_title']) {
        // 実行するSQLを作成
        $sql = 'INSERT INTO books (book_title) VALUES(:book_title)';
        // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
        $statement = $database->prepare($sql);
        // ユーザ入力データ($_POST['book_title'])をVALUES(?)の?の部分に代入する
        $statement->bindParam(':book_title', $_POST['book_title']);
        // SQL文を実行する
        $statement->execute();
        // ステートメントを破棄する
        $statement = null;
    }

    // 実行するSQLを作成
    $sql = 'SELECT * FROM books ORDER BY created_at DESC';
    // SQL を実行する直前のステートメントを取得する
    $statement = $database->query($sql);
    // ステートメントから SQL を実行し、レコードを取得する
    $records = $statement->fetchAll();

    // ステートメントを破棄する
    $statement = null;
    // MySQLを使った処理が終わると、接続は不要なので切断する
    $database = null;
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Booklist</title>
    </head>
    <body>
<?php
    // フォームデータ送受信確認用コード
    print '<div style="background-color: skyblue;">';
    print '<p>動作確認用:</p>';
    print_r($_POST);
    print '</div>';
?>
        <h1><a href="booklist.php">Booklist</a></h1>
        <h2>書籍の登録フォーム</h2>
        <form action="booklist.php" method="post">
            <input type="text" name="book_title" placeholder="書籍タイトルを入力" required>
            <input type="submit" name="submit_add_book" value="登録">
        </form>
        <h2>登録された書籍一覧</h2>
        <ul>
<?php
            if ($records) {
                foreach ($records as $record) {
                    $book_title = $record['book_title'];
?>
                    <li><?php print htmlspecialchars($book_title, ENT_QUOTES, 'UTF-8'); ?></li>
<?php
                }
            }
?>
        </ul>
    </body>
</html>