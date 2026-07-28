<?php
// ranking.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); 

$file = 'ranking_data.json';
$uploadDir = 'uploads/';

if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
if (!file_exists($file)) file_put_contents($file, json_encode([]));

$action = $_REQUEST['action'] ?? '';

function cleanAndSortData($data, $now) {
    $filtered = [];
    foreach ($data as $item) {
        if (($now - $item['timestamp']) <= (24 * 60 * 60)) {
            $filtered[] = $item;
        } else {
            if (!empty($item['image_path']) && file_exists($item['image_path'])) unlink($item['image_path']);
        }
    }
    usort($filtered, function($a, $b) { return $b['score'] - $a['score']; });
    foreach ($filtered as $index => &$item) {
        if ($index >= 3) { // 3등까지만 이미지 유지
            if (!empty($item['image_path']) && file_exists($item['image_path'])) unlink($item['image_path']);
            $item['image_path'] = null;
        }
    }
    return $filtered;
}

if ($action === 'list') {
    $data = json_decode(file_get_contents($file), true) ?: [];
    $filtered = cleanAndSortData($data, time());
    file_put_contents($file, json_encode($filtered, JSON_UNESCAPED_UNICODE));
    echo json_encode(array_values(array_slice($filtered, 0, 50)));
    exit;
}

if ($action === 'submit') {
    $nickname = mb_substr(preg_replace("/[^a-zA-Z0-9가-힣]/u", "", $_POST['nickname'] ?? '익명'), 0, 8);
    $score = (int)($_POST['score'] ?? 0);
    $subject = $_POST['subject'] ?? '';
    $comment = $_POST['comment'] ?? '';
    $imageBase64 = $_POST['image'] ?? '';

    $fp = fopen($file, 'c+');
    if (flock($fp, LOCK_EX)) {
        $content = (filesize($file) > 0) ? fread($fp, filesize($file)) : "[]";
        $data = json_decode($content, true) ?: [];

        $imagePath = null;
        if (!empty($imageBase64)) {
            $imageParts = explode(";base64,", $imageBase64);
            $imageBase64Decoded = base64_decode($imageParts[1]);
            $fileName = uniqid() . '.png';
            $imagePath = $uploadDir . $fileName;
            file_put_contents($imagePath, $imageBase64Decoded);
        }

        $newData = [
            'nickname' => $nickname,
            'score' => $score,
            'subject' => $subject,
            'comment' => $comment,
            'timestamp' => time(),
            'image_path' => $imagePath
        ];
        $data[] = $newData;
        $filtered = array_slice(cleanAndSortData($data, time()), 0, 100);

        ftruncate($fp, 0); rewind($fp);
        fwrite($fp, json_encode(array_values($filtered), JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);

        $myRank = 1;
        foreach($filtered as $d) {
            if($d['timestamp'] === $newData['timestamp']) break;
            $myRank++;
        }
        echo json_encode(['status' => 'success', 'rank' => $myRank]);
    }
    fclose($fp);
    exit;
}
?>