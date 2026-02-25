<?php
// --- 設定：保存先ファイル ---
$csv_file = 'data.csv';

// --- A. 送信ボタン（最終確定）が押された場合 ---
if (isset($_POST['confirm_submit'])) {
    $id = $_POST['id'] ?? '';
    $date = $_POST['date'] ?? '';
    $pref = $_POST['pref'] ?? '';
    $content = $_POST['content'] ?? '';
    $receive_time = date("Y/m/d H:i");

    $data = [$receive_time, $id, $date, $pref, $content];
    $file = fopen($csv_file, 'a');
    fputcsv($file, $data);
    fclose($file);

    header("Location: ../woman.html?status=success");
    exit;
}

// --- B. フォームから「確認画面へ」で届いた場合 ---
$is_confirm = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['confirm_submit'])) {
    $is_confirm = true;
    $tmp_id = $_POST['id'] ?? '';
    $tmp_date = $_POST['date'] ?? '';
    $tmp_pref = $_POST['pref'] ?? '';
    $tmp_content = isset($_POST['play_content']) ? implode(", ", $_POST['play_content']) : '未選択';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理パネル / 内容確認</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --mauve: #8e7a8a; --mauve-light: #f4f1f3; --bg: #fdfcfc; }
        body { font-family: 'Noto Sans JP', sans-serif; margin: 0; background: var(--bg); color: #4a4548; line-height: 1.6; }
        header { background: var(--mauve); color: white; padding: 1rem; text-align: center; font-weight: bold; }
        .container { max-width: 600px; margin: 20px auto; padding: 0 15px; }
        
        /* 確認画面用スタイル */
        .confirm-box { background: white; border: 2px solid var(--mauve); padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .confirm-item { margin-bottom: 20px; border-bottom: 1px dashed #eee; padding-bottom: 10px; }
        .label { font-size: 0.8rem; color: var(--mauve); font-weight: bold; display: block; }
        .val { font-size: 1.1rem; margin-top: 5px; display: block; }
        
        .btn-group { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 30px; }
        .btn { padding: 15px; border-radius: 12px; border: none; font-weight: bold; cursor: pointer; text-align: center; text-decoration: none; font-size: 1rem; }
        .btn-submit { background: var(--mauve); color: white; }
        .btn-back { background: #eee; color: #666; }

        /* 管理画面用テーブルスタイル */
        .section-title { border-left: 4px solid var(--mauve); padding-left: 10px; margin: 40px 0 15px; font-weight: bold; }
        .table-wrapper { background: white; border-radius: 12px; border: 1px solid #e8cfcf; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 500px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.85rem; }
        th { background: var(--mauve-light); color: var(--mauve); }
    </style>
</head>
<body>

<header><?php echo $is_confirm ? '入力内容の確認' : '管理用ダッシュボード'; ?></header>

<div class="container">

    <?php if ($is_confirm): // --- 確認画面の表示 --- ?>
        <div class="confirm-box">
            <p style="text-align:center; font-weight:bold; margin-top:0;">以下の内容で送信しますか？</p>
            <div class="confirm-item"><span class="label">ID/ニックネーム</span><span class="val"><?php echo htmlspecialchars($tmp_id); ?></span></div>
            <div class="confirm-item"><span class="label">プレイ予定日時</span><span class="val"><?php echo htmlspecialchars($tmp_date); ?></span></div>
            <div class="confirm-item"><span class="label">開催場所</span><span class="val"><?php echo htmlspecialchars($tmp_pref); ?></span></div>
            <div class="confirm-item"><span class="label">希望内容</span><span class="val"><?php echo htmlspecialchars($tmp_content); ?></span></div>

            <form action="index.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($tmp_id); ?>">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($tmp_date); ?>">
                <input type="hidden" name="pref" value="<?php echo htmlspecialchars($tmp_pref); ?>">
                <input type="hidden" name="content" value="<?php echo htmlspecialchars($tmp_content); ?>">
                <div class="btn-group">
                    <button type="button" class="btn btn-back" onclick="history.back()">修正する</button>
                    <button type="submit" name="confirm_submit" class="btn btn-submit">この内容で送信</button>
                </div>
            </form>
        </div>

    <?php else: // --- 管理画面（一覧）の表示 --- ?>
        <div class="section-title">届出リスト</div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>日時</th><th>ID</th><th>プレイ日</th><th>場所</th></tr>
                </thead>
                <tbody>
                    <?php
                    if (file_exists($csv_file)) {
                        $rows = array_reverse(array_map('str_getcsv', file($csv_file)));
                        foreach ($rows as $row) {
                            echo "<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td><td>{$row[3]}</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align:center; padding:30px;'>データがありません</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <p style="text-align:center; margin-top:20px;"><a href="../woman.html" style="color:var(--mauve); text-decoration:none; font-size:0.9rem;">← フォームへ戻る</a></p>
    <?php endif; ?>

</div>
</body>
</html>