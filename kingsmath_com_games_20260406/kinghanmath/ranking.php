<?php
header('Content-Type: application/json');

// 데이터 파일 경로
$file = 'ranking.json';

// 파일이 없으면 초기화
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

// 요청 메소드 확인
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents($file), true);
if (!$data) $data = [];

// 현재 시간
$now = time();

// --- 24시간 지난 데이터 정리 및 정렬 로직 ---
// 1. 점수 내림차순 정렬
usort($data, function($a, $b) {
    return $b['score'] - $a['score'];
});

// 2. 24시간 이내 데이터와 지난 데이터 분리
$fresh = [];
$stale = [];

foreach ($data as $row) {
    if ($now - $row['ts'] <= 86400) { // 24시간 (60*60*24)
        $fresh[] = $row;
    } else {
        $stale[] = $row;
    }
}

// 3. 로직 적용: 24시간 이내 데이터가 10명 미만이면, 10명이 찰 때까지 옛날 데이터 유지
if (count($fresh) >= 10) {
    // 24시간 이내 데이터가 충분하면 그것만 유지 (상위 10개)
    $finalData = array_slice($fresh, 0, 10);
} else {
    // 부족하면 옛날 데이터에서 점수 높은 순으로 채움
    $needed = 10 - count($fresh);
    $staleKept = array_slice($stale, 0, $needed);
    $finalData = array_merge($fresh, $staleKept);
    
    // 합친 후 다시 점수순 정렬
    usort($finalData, function($a, $b) {
        return $b['score'] - $a['score'];
    });
}

// --- POST 요청 (점수 등록) ---
if ($method === 'POST') {
    // 입력 데이터 받기
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['name']) && isset($input['score'])) {
        $newEntry = [
            'name' => mb_substr(strip_tags($input['name']), 0, 10), // 이름 길이 제한 및 태그 제거
            'score' => (int)$input['score'],
            'ts' => $now
        ];
        
        // 기존 데이터에 추가
        $finalData[] = $newEntry;
        
        // 다시 정렬 및 10위 자르기 (방금 들어온게 1등일 수 있으므로)
        usort($finalData, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        $finalData = array_slice($finalData, 0, 10);
        
        // 파일 저장
        file_put_contents($file, json_encode($finalData));
    }
}

// --- GET/POST 모두 최신 랭킹 반환 ---
echo json_encode($finalData);
?>