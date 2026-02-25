<?php
// 1. フォームからのデータ受け取り処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? '未入力';
    $date = $_POST['date'] ?? '未入力';
    $pref = $_POST['pref'] ?? '未入力';
    $play_content = isset($_POST['play_content']) ? implode(", ", $_POST['play_content']) : '未選択';
    $msg = $_POST['msg'] ?? '';
    $receive_time = date("Y/m/d H:i");

    // 保存用データ行
    $data = [$receive_time, $id, $date, $pref, $play_content, $msg];
    
    // csvに追記保存
    $file = fopen('data.csv', 'a');
    fputcsv($file, $data);
    fclose($file);

    // 送信後の戻り先（女性用フォームへ）
    header("Location: ../woman.html?status=success");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理パネル | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mauve-admin: #8e7a8a; --mauve-light: #f4f1f3;
            --text-color: #4a4548; --bg-color: #fdfcfc; --white: #ffffff;
        }
        body { font-family: 'Noto Sans JP', sans-serif; margin: 0; background: var(--bg-color); color: var(--text-color); }
        header { background: var(--mauve-admin); color: white; padding: 1rem; text-align: center; font-weight: bold; letter-spacing: 0.1em; }
        .container { max-width: 900px; margin: 20px auto; padding: 0 15px; }
        
        /* 統計バッジ */
        .stats { display: flex; gap: 10px; margin-bottom: 20px; }
        .stat-card { background: var(--white); padding: 15px; border-radius: 12px; flex: 1; text-align: center; border: 1px solid #e8cfcf; }
        .stat-num { display: block; font-size: 1.5rem; font-weight: bold; color: var(--mauve-admin); }

        /* リスト表示 */
        .section-title { border-left: 4px solid var(--mauve-admin); padding-left: 10px; margin: 30px 0 15px; font-weight: bold; }
        .table-wrapper { background: var(--white); border-radius: 12px; border: 1px solid #e8cfcf; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        th { background: var(--mauve-light); color: var(--mauve-admin); font-weight: bold; }
        tr:last-child td { border-bottom: none; }
        .no-data { text-align: center; padding: 50px; color: #999; }
    </style>
</head>
<body>

<header>ADMIN DASHBOARD</header>

<div class="container">
    <div class="stats">
        <?php
        $lines = file_exists('data.csv') ? file('data.csv') : [];
        $count = count($lines);
        ?>
        <div class="stat-card"><span class="stat-num"><?php echo $count; ?></span>届出件数</div>
        <div class="stat-card" onclick="location.reload()" style="cursor:pointer; display:flex; align-items:center; justify-content:center;">🔄 更新</div>
    </div>

    <div class="section-title">最新の届出リスト</div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>受信日時</th>
                    <th>ID/名</th>
                    <th>プレイ予定</th>
                    <th>場所</th>
                    <th>内容</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($count > 0) {
                    $rows = array_reverse(array_map('str_getcsv', $lines)); // 新しい順
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row[0]) . "</td>";
                        echo "<td>" . htmlspecialchars($row[1]) . "</td>";
                        echo "<td>" . htmlspecialchars($row[2]) . "</td>";
                        echo "<td>" . htmlspecialchars($row[3]) . "</td>";
                        echo "<td>" . htmlspecialchars($row[4]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='no-data'>まだデータがありません</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>