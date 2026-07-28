<?php
// ranking.php
// 게임 코드는 수정할 필요 없는 '안전 강화' 버전
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); 

$file = 'ranking_data.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
    chmod($file, 0777); 
}

$action = $_REQUEST['action'] ?? '';

// 비속어 목록
$forbiddenWords = [
    "시발", "씨발", "개새끼", "병신", "지랄", "닥쳐", "엿먹어", "꺼져", "젠장", "빌어먹을",
    "옘병", "염병", "쓰레기", "개놈", "새끼", "죽어", "미친", "미친놈", "미친년", "개소리",
    "씹", "좆", "존나", "졸라", "개같은", "호구", "찐따", "바보", "멍청이", "등신",
    "섹스", "성관계", "자위", "보지", "자지", "야동", "강간", "변태", "콘돔", "오르가즘",
    "성폭행", "따먹", "발기", "사정", "유두", "클리", "딜도", "망가", "성노예", "창녀",
    "걸레", "룸살롱", "조건만남", "원조교제", "몸캠", "벗방", "야한", "19금",
    "느금마", "니애미", "느개비", "니애비", "앰창", "엠창", "패드립", "애미", "애비",
    "고아", "후레자식", "가정교육",
    "짱깨", "쪽발이", "맘충", "한남", "김치녀", "된장녀", "틀딱", "급식충", "장애인", "애자",
    "전라디언", "홍어", "운지", "일베", "메갈", "페미", "게이", "레즈", "트젠",
    "ㅅㅂ", "ㅆㅂ", "ㅂㅅ", "ㅈㄹ", "ㄲㅈ", "ㄷㅊ", "ㅁㅊ", "존맛", "존예", "존잘",
    "씨1발", "시1발", "개1새끼", "병1신", "좃", "젖", "뵹신", "쉬발", "슈발", "시바",
    "10새끼", "10새기", "십새끼", "시불", "썅",
    "운영자", "관리자", "어드민", "admin"
];

// 1. 리스트 출력 (기존과 동일)
if ($action === 'list') {
    $jsonString = file_get_contents($file);
    $data = json_decode($jsonString, true);
    if (!is_array($data)) $data = [];

    $now = time();
    $filtered = array_filter($data, function($item) use ($now) {
        return ($now - $item['timestamp']) <= (24 * 60 * 60);
    });

    usort($filtered, function($a, $b) {
        return $b['score'] - $a['score'];
    });

    echo json_encode(array_values(array_slice($filtered, 0, 100)));
    exit;
}

// 2. 점수 등록 (파일 잠금 기능만 추가됨)
if ($action === 'submit') {
    $nickname = $_POST['nickname'] ?? '익명';
    $score = (int)($_POST['score'] ?? 0);

    $cleanNickname = preg_replace("/[^a-zA-Z0-9가-힣]/u", "", $nickname);
    $cleanNickname = mb_substr($cleanNickname, 0, 8, 'UTF-8');

    foreach ($forbiddenWords as $bad) {
        if (mb_strpos($cleanNickname, $bad, 0, 'UTF-8') !== false) {
            echo json_encode(['status' => 'error', 'message' => 'bad_word']);
            exit;
        }
    }

    // 파일 잠금 시작 (데이터 충돌 방지)
    $fp = fopen($file, 'c+');
    
    if (flock($fp, LOCK_EX)) {
        $fileSize = filesize($file);
        $content = ($fileSize > 0) ? fread($fp, $fileSize) : "[]";
        $data = json_decode($content, true);
        if (!is_array($data)) $data = [];

        $newData = [
            'nickname' => $cleanNickname,
            'score' => $score,
            'timestamp' => time()
        ];
        $data[] = $newData;

        $now = time();
        $data = array_filter($data, function($item) use ($now) {
            return ($now - $item['timestamp']) <= (24 * 60 * 60);
        });

        usort($data, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        // 300개까지만 저장 (안전빵)
        $data = array_slice($data, 0, 300);

        ftruncate($fp, 0); 
        rewind($fp);
        fwrite($fp, json_encode(array_values($data), JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN); // 잠금 해제

        // 내 단순 등수 계산 (1등, 2등, 3등...)
        $myRank = 1;
        foreach($data as $d) {
            if($d['nickname'] === $cleanNickname && $d['score'] === $score && $d['timestamp'] === $newData['timestamp']) {
                break;
            }
            $myRank++;
        }

        echo json_encode(['status' => 'success', 'rank' => $myRank]);
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'busy']);
    }

    fclose($fp);
    exit;
}
?>