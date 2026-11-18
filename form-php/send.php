<?php
// ============================================================
// send.php  お問い合わせフォームの受け取り係
// 流れ: 受け取る → 検査する → メールを送る(または保存する) → 結果をJSで返す
// ============================================================

// ----- 設定(ここだけ書き換えればOK) -----
const MAIL_TO   = '#';       // 問い合わせを受け取るアドレス
const MAIL_FROM = 'noreply@your-domain.example'; // 差出人。公開するサイトのドメインのアドレスにする
const SITE_NAME = '#';

// 結果を JSON(プログラムが読みやすい形式)で返す準備
header('Content-Type: application/json; charset=UTF-8');

// 結果を返して終了する小さな部品
function respond(int $status, bool $ok, string $message): void
{
    http_response_code($status);
    echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

// ----- 1. POST以外(URL直打ちなど)は断る -----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, false, '不正なアクセスです。');
}

// ----- 2. スパム対策: 人間には見えない欄(website)に入力があったら無視 -----
if (!empty($_POST['website'])) {
    respond(200, true, '送信しました。'); // ボットには成功したふりをする
}

// ----- 3. 入力を受け取る(前後の空白を除く) -----
$name    = trim((string)($_POST['name'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

// ----- 4. 検査する -----
if ($name === '' || $email === '' || $message === '') {
    respond(422, false, '未入力の項目があります。');
}
if (mb_strlen($name) > 50 || mb_strlen($message) > 3000) {
    respond(422, false, '文字数が多すぎます。');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(422, false, 'メールアドレスの形式を確認してください。');
}
// 改行を混ぜて勝手に宛先を増やす攻撃(ヘッダーインジェクション)を防ぐ
if (preg_match('/[\r\n]/', $name . $email)) {
    respond(422, false, '不正な文字が含まれています。');
}

// ----- 5. メールの中身を作る -----
$subject = '【' . SITE_NAME . '】お問い合わせ: ' . $name . ' 様';
$body = "ポートフォリオから問い合わせがありました。\n\n"
      . "お名前: {$name}\n"
      . "メール: {$email}\n"
      . "----------------------------\n"
      . "{$message}\n";

// ----- 6. 送る -----
// 自分のMac(localhost)では本物のメールは送れないので、ファイルに保存して確認する
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'], true);

if ($isLocal) {
    $log = "=== " . date('Y-m-d H:i:s') . " ===\n"
         . "To: " . MAIL_TO . "\nSubject: {$subject}\n\n{$body}\n\n";
    file_put_contents(__DIR__ . '/mail-test.log', $log, FILE_APPEND);
    respond(200, true, '送信しました。(テスト: mail-test.log に保存)');
}

mb_language('Japanese');
mb_internal_encoding('UTF-8');

$headers = 'From: ' . MAIL_FROM . "\r\n" . 'Reply-To: ' . $email;
$sent = mb_send_mail(MAIL_TO, $subject, $body, $headers);

if ($sent) {
    respond(200, true, '送信しました。ありがとうございます。');
}
respond(500, false, '送信に失敗しました。時間をおいてもう一度お試しください。');
