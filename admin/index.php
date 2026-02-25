<?php
// エラーを表示させる設定（真っ白を防ぐ）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// フォームからデータが届いているか確認
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? '未入力';
    $date = $_POST['date'] ?? '未入力';
    $pref = $_POST['pref'] ?? '未入力';
    $play_content = $_POST['play_content'] ?? '選択なし';
    $play_msg = $_POST['play_msg'] ?? '';
    $member_count = $_POST['member_count'] ?? '1';
    $toys = $_POST['toys'] ?? '';
    $public_note = $_POST['public_note'] ?? '';
    $ng_list = $_POST['ng_list'] ?? '選択なし';
    $other_ng = $_POST['other_ng'] ?? '';
    $exam_status = $_POST['exam_status'] ?? '未選択';
    $private_note = $_POST['private_note'] ?? '';
} else {
    // 直接アクセスされた場合はエラー表示
    die("フォームから正しく送信されていません。");
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>内容確認 | 管理画面</title>
    <style>
        body { font-family: sans-serif; background: #fdf8f7; color: #5a4d4d; margin: 0; padding: 20px; }
        .confirm-box { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 2px solid #8e7a8a; }
        h2 { color: #8e7a8a; border-bottom: 2px solid #8e7a8a; padding-bottom: 10px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; vertical-align: top; }
        th { width: 35%; color: #8e7a8a; font-weight: bold; }
        .btn-area { display: flex; gap: 10px; margin-top: 30px; }
        .btn { flex: 1; padding: 15px; border: none; border-radius: 10px; cursor: pointer; font-weight: bold; text-align: center; text-decoration: none; font-size: 16px; }
        .back-btn { background: #eee; color: #666; }
        .submit-btn { background: #8e7a8a; color: white; }
        .note { font-size: 0.9em; color: #b57d7d; margin-top: 5px; display: block; }
    </style>
</head>
<body>

<div class="confirm-box">
    <h2>ご依頼内容の確認</h2>
    <table>
        <tr><th>ID/ニックネーム</th><td><?php echo htmlspecialchars($id); ?></td></tr>
        <tr><th>プレイ日時</th><td><?php echo htmlspecialchars($date); ?></td></tr>
        <tr><th>開催都道府県</th><td><?php echo htmlspecialchars($pref); ?></td></tr>
        <tr><th>プレイ内容</th><td><?php echo nl2br(htmlspecialchars($play_content)); ?><br><small><?php echo nl2br(htmlspecialchars($play_msg)); ?></small></td></tr>
        <tr><th>希望人数</th><td><?php echo htmlspecialchars($member_count); ?> 名</td></tr>
        <tr><th>使用玩具</th><td><?php echo nl2br(htmlspecialchars($toys)); ?></td></tr>
        <tr><th>掲載用メモ</th><td><?php echo nl2br(htmlspecialchars($public_note)); ?></td></tr>
        <tr><th>NGリスト</th><td><?php echo nl2br(htmlspecialchars($ng_list)); ?><br><small><?php echo nl2br(htmlspecialchars($other_ng)); ?></small></td></tr>
        <tr><th>受検経験</th><td><?php echo htmlspecialchars($exam_status); ?></td></tr>
        <tr><th style="color:#d4a5a5;">【非掲載メモ】</th><td><?php echo nl2br(htmlspecialchars($private_note)); ?></td></tr>
    </table>

    <div class="btn-area">
        <button type="button" class="btn back-btn" onclick="history.back()">修正する</button>
        <button type="button" class="btn submit-btn" onclick="alert('この内容でLINE転送等の処理を実行します（未実装）')">この内容で確定</button>
    </div>
</div>

</body>
</html>