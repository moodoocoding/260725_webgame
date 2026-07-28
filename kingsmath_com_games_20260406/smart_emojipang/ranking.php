<?php
header('Content-Type: application/json; charset=utf-8');

// --- 파일 설정 ---
// 랭킹 데이터를 저장할 파일 이름 (자동으로 생성됩니다)
$dataFile = 'ranking_data.json';
// 24시간 = 86400초
$expirationTime = 86400; 

// --- 헬퍼 함수: 데이터 읽기 및 24시간 지난 데이터 삭제 ---
function getRankingData($file, $expirationTime) {
    if (!file_exists($file)) {
        return [];
    }
    
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    
    if (!is_array($data)) return [];

    $currentTime = time();
    $filteredData = [];

    // 24시간 이내의 데이터만 필터링
    foreach ($data as $item) {
        if (isset($item['timestamp']) && ($currentTime - $item['timestamp']) <= $expirationTime) {
            $filteredData[] = $item;
        }
    }

    return $filteredData;
}

// --- 헬퍼 함수: 데이터 저장 ---
function saveRankingData($file, $data) {
    // LOCK_EX를 사용하여 동시에 여러 요청이 올 때 파일 덮어쓰기 충돌 방지
    file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX);
}


// GET 요청: 랭킹 데이터 가져오기
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
    
    // 데이터 읽기 (24시간 지난 데이터는 이 과정에서 걸러짐)
    $data = getRankingData($dataFile, $expirationTime);

    // 정렬: 점수 내림차순(DESC), 같은 점수일 경우 먼저 등록된 순(ASC)
    usort($data, function($a, $b) {
        if ($a['score'] == $b['score']) {
            return $a['timestamp'] <=> $b['timestamp'];
        }
        return $b['score'] <=> $a['score'];
    });

    // 요청한 limit 개수만큼 자르기
    $results = array_slice($data, 0, $limit);

    // 클라이언트에는 필요한 정보(nickname, score)만 전달하기 위해 매핑
    $output = array_map(function($item) {
        return [
            'nickname' => $item['nickname'],
            'score' => $item['score']
        ];
    }, $results);

    echo json_encode($output);
    
    // 읽을 때마다 필터링된 최신 상태를 파일에 한 번 더 저장해 주면 
    // 파일 용량이 계속 늘어나는 것을 방지할 수 있습니다.
    saveRankingData($dataFile, $data);
    exit;
}

// POST 요청: 새로운 점수 기록하기
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save') {
        $nickname = strip_tags(trim($_POST['nickname'] ?? 'Anonymous'));
        $score = (int)($_POST['score'] ?? 0);

        // --- 욕설 필터링 로직 시작 ---
 $badWords = [
    // 1. 시발 및 변형/초성
    '시발', '씨발', '씨벌', '쓰발', 'ㅆㅂ', 'ㅅㅂ', '씨바', '시바', '씨부랄', '씨빨', 
    '슈발', '쓔발', '쒸발', '쉬발', '싀발', '슈비', '십알', '씹알',

    // 2. 병신 및 변형/초성
    '병신', '븅신', '빙신', 'ㅂㅅ', '뵹신', '병싄', '등신', '멍청이', '띨띨이',

    // 3. 새끼, 개 및 조합형
    '새끼', '새기', '새키', '쉐끼', '쉑', '개새', '개새끼', '개쉑', '개씨발', '개씹', 
    '씹새', '씹새끼', '십새끼', '십새', '씹빨', '개돼지',

    // 4. 지랄 및 변형
    '지랄', 'ㅈㄹ', '옘병', '염병', '지뢍', '쥐랄',

    // 5. 좆 및 강조형 (존나 등)
    '좆', '좃', '죶', 'ㅈ까', '좆까', '좃까', '좆밥', '좃밥', '죶밥', 'ㅈㅂ',
    '존나', '좃나', '죶나', '존니', '졸라', '줫나', 'ㅈㄴ', '줜나', '조온나',

    // 6. 미친 및 쌍욕
    '미친', '미친년', '미친놈', '미친새끼', 'ㅁㅊ',
    '썅', '쌍년', '썅년', '썅놈', '썅새끼', '썅뇬',

    // 7. 가족 비하 (패드립)
    '니애미', '니기미', '느금마', '애미', '애비', '엄창', '엠창', '니엄마', '느개비',

    // 8. 성적 비하 및 음란어
    '창녀', '창년', '걸레', '갈보', '챙녀', '창남',
    '섹스', 'ㅅㅅ', '보지', '자지', 'ㅂㅈ', 'ㅈㅈ', '잠지', '야동', '딸딸이', '오나홀', 
    '유두', '찌찌', '강간', '성폭행',

    // 9. 혐오/비하 단어 및 커뮤니티성 단어 (필요시 조절)
    '맘충', '틀딱', '급식충', '한남', '메갈', '이기야', '일베', '운지', '노무현', '홍어', '통구이',
    '찐따', '호구', '뒈져', '나가죽어', '닥쳐', '아가리', '주둥이', '대가리', '빡대가리', '돌대가리'
];

        foreach ($badWords as $word) {
            $hearts = str_repeat('♥', mb_strlen($word, 'UTF-8'));
            $nickname = str_ireplace($word, $hearts, $nickname);
        }
        // --- 욕설 필터링 로직 끝 ---

        if (empty(trim($nickname))) {
            $nickname = 'Anonymous';
        }

        // 기존 데이터 불러오기 (24시간 지난 데이터 필터링 포함)
        $data = getRankingData($dataFile, $expirationTime);

        // 새 데이터 추가
        $data[] = [
            'nickname' => $nickname,
            'score' => $score,
            'timestamp' => time() // 현재 시간을 초 단위로 저장
        ];

        // 파일에 저장
        saveRankingData($dataFile, $data);
        
        echo json_encode(['status' => 'success']);
    }
    exit;
}
?>