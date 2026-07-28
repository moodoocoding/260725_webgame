<?php
header('Content-Type: application/json; charset=utf-8');

// 데이터를 저장할 파일 이름 (권한 문제가 없어야 합니다)
$dataFile = '100_data.json';

// 파일이 없으면 초기화
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

$action = $_GET['action'] ?? '';

// 1. 명단 불러오기
if ($action === 'list') {
    $data = json_decode(file_get_contents($dataFile), true);
    // 데이터 반환 (가장 최근 등록자가 맨 위에 오도록 설계됨)
    echo json_encode($data);
    exit;
}

// 2. 10.00초 성공자 등록하기
if ($action === 'submit') {
    $nickname = $_POST['nickname'] ?? 'Anonymous';
    
    // 현재 날짜 및 시간 기록
    $date = date('Y-m-d H:i:s');

    // 기존 데이터 불러오기
    $data = json_decode(file_get_contents($dataFile), true);

    // 새 데이터를 배열의 '맨 앞(최상단)'에 추가
    array_unshift($data, [
        'nickname' => mb_substr(trim($nickname), 0, 8),
        'date' => $date
    ]);

    // 전체 기록이 100명을 초과하면, 가장 오래된(배열 맨 끝) 기록을 잘라냄
    if (count($data) > 100) {
        $data = array_slice($data, 0, 100);
    }

    // 파일 갱신 저장
    file_put_contents($dataFile, json_encode($data));

    echo json_encode(['status' => 'success']);
    exit;
}

// 잘못된 접근
echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>