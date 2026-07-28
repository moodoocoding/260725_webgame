<!DOCTYPE html>
<html lang="ko">
<head>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5YW0T2C109"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-5YW0T2C109');
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Big5 성격검사 킹 / Big5 Personality Test King</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* 공통 스타일 (Dark Mode 기본) */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Pretendard', -apple-system, sans-serif; }
        body { background-color: #121212; color: #E0E0E0; padding: 20px; min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; position: relative; }
        
        /* 상단 헤더 & 언어선택 & 톱니바퀴 */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        h1 { color: #FFFFFF; font-weight: 800; font-size: 1.8em; margin: 0; }
        
        .header-controls { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .lang-select-wrapper { display: flex; flex-direction: column; font-size: 0.8em; color: #A0A0A0; }
        .lang-select { background: #1E1E1E; color: #FFF; border: 1px solid #444; padding: 6px 10px; border-radius: 6px; outline: none; font-size: 1rem; cursor: pointer; }
        .lang-select:focus { border-color: #3B82F6; }

        .teacher-mode-btn { background: none; border: none; font-size: 1.8em; cursor: pointer; color: #888; transition: transform 0.3s, color 0.3s; margin-left: 5px; }
        .teacher-mode-btn:hover { color: #3B82F6; transform: rotate(90deg); }

        /* 다국어 텍스트 스타일 */
        .lang2-text { color: #FCD34D; font-size: 0.8em; display: block; margin-top: 6px; opacity: 0.9; font-weight: 500; }
        .btn-lang2-text { color: #FCD34D; font-size: 0.85em; display: block; margin-top: 2px; font-weight: normal; }

        /* 문항 버튼 네비게이션 */
        #button-container { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 15px; margin-bottom: 10px; scrollbar-width: thin; scrollbar-color: #333 #121212; }
        .question-btn { background-color: #1E1E1E; border: 1px solid #333; color: #A0A0A0; padding: 8px 16px; border-radius: 20px; cursor: pointer; white-space: nowrap; transition: all 0.2s ease; font-weight: 600; }
        .question-btn:hover { background-color: #2C2C2C; color: #FFF; }
        .question-btn.active { background-color: #3B82F6; color: #FFF; border-color: #3B82F6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.4); }
        .question-btn.completed { background-color: #10B981; color: #FFF; border-color: #059669; }
        .question-btn.active.completed { box-shadow: 0 0 10px rgba(16, 185, 129, 0.6); border-color: #FFF; }

        /* 문항 표시부 */
        #question-display { background: linear-gradient(145deg, #1A1A1A, #252525); padding: 40px 30px; border-radius: 12px; margin-bottom: 10px; font-size: 1.8em; font-weight: bold; text-align: center; color: #FFF; border: 1px solid #333; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; word-break: keep-all; line-height: 1.4; }
        #average-display { text-align: center; color: #10B981; font-size: 1.2em; font-weight: bold; margin-bottom: 20px; min-height: 25px; }

        /* 학생 리스트 Grid */
        #student-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(95px, 1fr)); gap: 12px; }
        .student-card { background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 12px; padding: 12px 8px; text-align: center; cursor: pointer; transition: all 0.2s ease; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; }
        .student-card:hover { transform: translateY(-3px); background-color: #252525; border-color: #444; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .student-avatar { width: 50px; height: 50px; border-radius: 50%; background-color: #2C2C2C; margin-bottom: 8px; object-fit: cover; border: 2px solid #333; }
        .student-name { font-weight: bold; font-size: 1.05em; color: #FFF; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; }
        .status-badge { font-size: 0.75em; font-weight: bold; padding: 4px 6px; border-radius: 10px; background-color: #333; color: #888; }
        .status-badge.completed { background-color: #10B981; color: #FFF; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); }

        /* 범용 모달 스타일 */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: all 0.3s ease; z-index: 1000; }
        .modal-overlay.show { opacity: 1; visibility: visible; }
        .modal-content { background-color: #1E1E1E; width: 95%; max-width: 650px; max-height: 90vh; overflow-y: auto; border-radius: 12px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.8); border: 1px solid #333; position: relative; transform: translateY(20px); transition: transform 0.3s ease; }
        .modal-overlay.show .modal-content { transform: translateY(0); }
        .modal-content.large { max-width: 900px; }
        .close-btn { position: absolute; top: 15px; right: 20px; font-size: 1.8em; color: #888; cursor: pointer; border: none; background: none; line-height: 1; }
        .close-btn:hover { color: #FFF; }

        /* 학생 입력 모달 전용 */
        .scale-group { display: flex; justify-content: space-between; margin: 25px 0; max-width: 450px; margin-left: auto; margin-right: auto; }
        .scale-btn { width: 55px; height: 55px; border-radius: 50%; border: 2px solid #444; background-color: #121212; color: #A0A0A0; font-size: 1.3em; font-weight: bold; cursor: pointer; transition: all 0.2s ease; }
        .scale-btn:hover { border-color: #3B82F6; color: #FFF; }
        .scale-btn.selected { background-color: #3B82F6; border-color: #3B82F6; color: #FFF; box-shadow: 0 0 12px rgba(59, 130, 246, 0.6); }
        .btn-block { width: 100%; padding: 15px; border: none; border-radius: 8px; font-size: 1.1em; font-weight: bold; cursor: pointer; transition: 0.2s; margin-top: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .btn-primary { background-color: #3B82F6; color: white; }
        .btn-primary:hover { background-color: #2563EB; }
        .btn-success { background-color: #10B981; color: white; }
        .btn-success:hover { background-color: #059669; }
        .btn-warning { background-color: #F59E0B; color: white; }
        .btn-warning:hover { background-color: #D97706; }
        .btn-danger { background-color: #EF4444; color: white; }
        .btn-danger:hover { background-color: #DC2626; }
        .btn-dark { background-color: #333; color: white; }
        .btn-dark:hover { background-color: #444; }

        /* 교사 모달 탭 시스템 */
        .tab-container { display: flex; border-bottom: 1px solid #333; margin-bottom: 20px; gap: 10px; overflow-x: auto; padding-bottom: 5px; }
        .tab-btn { background: none; border: none; color: #888; padding: 10px 15px; font-size: 1.1em; cursor: pointer; font-weight: bold; border-bottom: 3px solid transparent; transition: 0.2s; white-space: nowrap; }
        .tab-btn.active { color: #FFF; border-bottom-color: #3B82F6; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* 리스트 & 입력 폼 */
        .input-group { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; }
        input[type="text"], input[type="file"], select { flex: 1; padding: 12px; border-radius: 8px; border: 1px solid #444; background: #121212; color: #FFF; font-size: 1em; min-width: 200px; }
        .list-group { list-style: none; max-height: 300px; overflow-y: auto; padding-right: 5px; }
        .list-item { display: flex; justify-content: space-between; align-items: center; background: #252525; padding: 12px; margin-bottom: 8px; border-radius: 8px; border: 1px solid #333; }
        
        /* 백업/복구 영역 */
        .backup-card { background: #252525; border: 1px solid #333; padding: 20px; border-radius: 10px; margin-bottom: 15px; }
        
        /* 결과 & 테이블 영역 */
        .chart-wrapper { max-width: 450px; margin: 0 auto; background: #FFF; padding: 20px; border-radius: 12px; }
        table.result-table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #FFF; color: #000; border-radius: 8px; overflow: hidden; font-size: 0.95em; }
        table.result-table th, table.result-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        table.result-table th { background-color: #f4f7f6; font-weight: bold; }
        table.result-table td.text-left { text-align: left; }
        
        #ind-detail-table-wrapper { margin-top: 25px; max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; }
        #ind-detail-table-wrapper table { margin-top: 0; border-radius: 0; }
        #ind-detail-table-wrapper th { position: sticky; top: 0; z-index: 10; border-top: none; }
        
        /* 유틸 */
        .mt-2 { margin-top: 15px; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }

        /* 하단 안내 영역 추가 */
        .footer-notice { margin-top: 50px; margin-bottom: 80px; text-align: center; }
        .footer-box { background-color: #1E1E1E; border: 1px solid #333; padding: 25px; border-radius: 15px; font-size: 0.9em; color: #A0A0A0; line-height: 1.6; }
        .footer-link-btn { background-color: #333; color: #FFF; padding: 12px 30px; border-radius: 30px; font-weight: bold; transition: background 0.3s; border: 1px solid #444; text-decoration: none; display: inline-block; margin-top: 15px; }
        .footer-link-btn:hover { background-color: #444; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1 id="ui-title">📝 big5 성격검사 킹</h1>
        <div class="header-controls">
            <div class="lang-select-wrapper">
                <label for="lang1" id="ui-label-lang1">1언어 (기본)</label>
                <select id="lang1" class="lang-select" onchange="changeLanguage()"></select>
            </div>
            <div class="lang-select-wrapper">
                <label for="lang2" id="ui-label-lang2">2언어 (다문화용)</label>
                <select id="lang2" class="lang-select" onchange="changeLanguage()">
                    <option value="none" id="ui-opt-none">선택 안함</option>
                </select>
            </div>
            <button class="teacher-mode-btn" onclick="openTeacherModal()" title="Settings">⚙️</button>
        </div>
    </div>

    <div id="button-container"></div>

    <div id="question-display">
        <div id="qd-lang1"></div>
        <div id="qd-lang2" class="lang2-text" style="font-size: 0.6em; margin-top:15px; text-align:center;"></div>
    </div>
    <div id="average-display"></div>

    <div id="student-list"></div>

    <div class="footer-notice">
        <div class="footer-box">
            <h4 id="ui-warn-title" style="color: #FCD34D; margin-bottom: 12px; font-size: 1.1em;"></h4>
            <p id="ui-warn-desc" style="margin-bottom: 12px; word-break: keep-all;"></p>
            <p id="ui-backup-path" style="color: #3B82F6; font-weight: bold;"></p>
            <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">
            <a href="/" target="_blank" class="footer-link-btn">
                🏠 <span id="ui-go-home"></span>
            </a>
        </div>
    </div>
</div>

<div id="student-input-modal" class="modal-overlay">
    <div class="modal-content">
        <button class="close-btn" onclick="closeModal('student-input-modal')">&times;</button>
        <h2 id="modal-student-name" style="color: #3B82F6; text-align: center; margin-bottom: 20px; font-size: 1.5em;"></h2>
        
        <div id="single-input-view">
            <div id="modal-question" style="font-size: 1.4em; color: #FFF; text-align: center; line-height: 1.4; margin-bottom: 20px; word-break: keep-all; font-weight: bold;">
                <div id="mq-lang1"></div>
                <div id="mq-lang2" class="lang2-text" style="font-size: 0.7em;"></div>
            </div>
            <div class="scale-group">
                <button class="scale-btn" onclick="selectScore(1)">1</button>
                <button class="scale-btn" onclick="selectScore(2)">2</button>
                <button class="scale-btn" onclick="selectScore(3)">3</button>
                <button class="scale-btn" onclick="selectScore(4)">4</button>
                <button class="scale-btn" onclick="selectScore(5)">5</button>
            </div>
            <button class="btn-block btn-success" onclick="saveSingleData()">
                <span id="ui-btn-save">저장 후 닫기</span>
                <span id="ui-btn-save-l2" class="btn-lang2-text"></span>
            </button>
            <hr style="border-color: #333; margin: 20px 0;">
            <button class="btn-block btn-dark" onclick="toggleBulkInputView()">
                <span id="ui-btn-batch">이 학생 20문항 일괄 입력하기 ➡️</span>
                <span id="ui-btn-batch-l2" class="btn-lang2-text"></span>
            </button>
        </div>

        <div id="bulk-input-view" style="display: none;">
            <p style="text-align: center; color: #A0A0A0; margin-bottom: 15px;">
                <span id="ui-msg-score">각 문항을 읽고 점수(1~5점)를 선택하세요.</span><br>
                <span id="ui-msg-score-l2" class="lang2-text"></span>
            </p>
            <div id="bulk-questions-container" style="max-height: 50vh; overflow-y: auto; border: 1px solid #333; padding: 10px; border-radius: 8px; margin-bottom: 15px; background: #121212;">
                </div>
            <div style="display: flex; gap: 10px;">
                <button class="btn-block" style="background: #444; color: #FFF; width: 30%; flex-direction: column;" onclick="toggleBulkInputView()">
                    <span id="ui-btn-cancel">⬅️ 취소</span>
                    <span id="ui-btn-cancel-l2" class="btn-lang2-text"></span>
                </button>
                <button class="btn-block btn-primary" style="width: 70%; flex-direction: column;" onclick="saveBulkData()">
                    <span id="ui-btn-batch-save">20문항 일괄 저장</span>
                    <span id="ui-btn-batch-save-l2" class="btn-lang2-text"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="teacher-modal" class="modal-overlay">
    <div class="modal-content large">
        <button class="close-btn" onclick="closeModal('teacher-modal')">&times;</button>
        <h2 style="color: #FFF; margin-bottom: 20px; font-size: 1.6em;">⚙️ <span id="ui-dash-title">교사 대시보드</span><a href="https://indischool.com/boards/libCurriculum/37559750" target="_blank" 
   style="display: inline-block; margin-left: 10px; padding: 6px 12px; border: 1.5px solid #4A90E2; color: #4A90E2; text-decoration: none; border-radius: 4px; font-weight: 500; font-size: 13px;">
  성격 검사 활용 방법(indischool.com 링크)
</a></h2>
        
        <div class="tab-container">
            <button id="ui-tab-guide" class="tab-btn active" onclick="switchTab('tab-guide')">📖 이용 안내</button>
            <button id="ui-tab-manage" class="tab-btn" onclick="switchTab('tab-manage')">👥 학생 관리</button>
            <button id="ui-tab-ind" class="tab-btn" onclick="switchTab('tab-result-ind')">📊 개별 결과</button>
            <button id="ui-tab-all" class="tab-btn" onclick="switchTab('tab-result-all')">📈 전체 결과</button>
            <button id="ui-tab-backup" class="tab-btn" onclick="switchTab('tab-backup')">💾 백업/복구</button>
        </div>

        <div id="tab-guide" class="tab-content active">
            <div class="backup-card" style="margin-bottom: 15px;" id="guide-content-area">
                </div>
        </div>

        <div id="tab-manage" class="tab-content">
            <div class="input-group">
                <input type="text" id="new-student-input" placeholder="새 학생 이름 입력 (예: 25홍길동)" onkeypress="if(event.key==='Enter') addStudent()">
                <button id="btn-add-st" class="btn-block btn-primary" style="width: auto; margin: 0; padding: 12px 20px;" onclick="addStudent()">추가</button>
            </div>
            <p id="ui-msg-avatar" style="color: #888; font-size: 0.9em; margin-bottom: 10px;">※ 학생을 추가하면 아바타 캐릭터가 자동으로 생성됩니다.</p>
            <ul id="manage-student-list" class="list-group"></ul>
        </div>

        <div id="tab-result-ind" class="tab-content">
            <div class="input-group" style="flex-wrap: nowrap; overflow-x: auto; padding-bottom: 5px;">
                <select id="student-select" onchange="renderIndividualResult()"></select>
                <button id="btn-copy" class="btn-block btn-warning" style="width: auto; margin: 0; white-space: nowrap;" onclick="copyIndividualResult()">📋 복사하기</button>
                <button id="btn-save-txt-ind" class="btn-block btn-success" style="width: auto; margin: 0; white-space: nowrap;" onclick="exportIndividualTXT()">📄 TXT 저장</button>
            </div>
            <div id="ind-result-area" style="display: none; background: #FFF; padding: 20px; border-radius: 12px; margin-top: 15px;">
                <div id="ind-scores-display" style="text-align: center; margin-bottom: 15px; color: #000;"></div>
                <div class="chart-wrapper">
                    <canvas id="radarChart"></canvas>
                </div>
                <div id="ind-detail-table-wrapper"></div>
            </div>
        </div>

        <div id="tab-result-all" class="tab-content">
            <div class="flex-between">
                <p id="ui-msg-allsum" style="color: #A0A0A0;">전체 학생의 요인별 점수 요약입니다.</p>
                <button id="btn-save-txt-all" class="btn-block btn-success" style="width: auto; margin: 0;" onclick="exportAllTXT()">📑 TXT 저장</button>
            </div>
            <div style="overflow-x: auto;">
                <div id="all-result-table-container"></div>
            </div>
        </div>

        <div id="tab-backup" class="tab-content">
            <div class="backup-card">
                <h3 style="color: #3B82F6; margin-bottom: 10px;">💾 <span id="ui-bk-down-title">데이터 백업 (다운로드)</span></h3>
                <button id="btn-bk-down" class="btn-block btn-primary" onclick="exportBackupData()">JSON 다운로드</button>
            </div>
            
            <div class="backup-card">
                <h3 style="color: #10B981; margin-bottom: 10px;">📂 <span id="ui-bk-up-title">백업 복구 (불러오기)</span></h3>
                <input type="file" id="backup-file-input" accept=".json" style="margin-bottom: 10px;">
                <button id="btn-bk-up" class="btn-block btn-success" onclick="importBackupData()">파일 선택 후 데이터 복구</button>
            </div>

            <div class="backup-card" style="border-color: #7f1d1d;">
                <h3 style="color: #EF4444; margin-bottom: 10px;">🗑️ <span id="ui-bk-del-title">데이터 초기화</span></h3>
                <button id="btn-bk-del" class="btn-block btn-danger" onclick="clearAllData()">모든 데이터 삭제</button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- Massive Multi-language Dictionary ---
    const dict = {
        'ko': {
            name: '🇰🇷 한국어', 
            ui: { 
                title: "big5 성격검사 킹", lang1: "1언어 (기본)", lang2: "2언어 (다문화용)", none: "선택 안함", dashTitle: "교사 대시보드",
                wait: "대기중", entered: "입력됨", completed: "전체완료", avgPrefix: "현재 문항 평균:", avgSuffix: "명 참여", noData: "아직 입력된 데이터가 없습니다.",
                guide: "이용 안내", manage: "학생 관리", indRes: "개별 결과", allRes: "전체 결과", backup: "백업/복구",
                add: "추가", del: "삭제", phName: "새 학생 이름 입력", msgAvatar: "※ 학생을 추가하면 아바타 캐릭터가 자동으로 생성됩니다.",
                selTarget: "결과를 볼 학생을 선택하세요", copy: "복사하기", saveTxt: "TXT 저장", msgAllSum: "전체 학생의 요인별 점수 요약입니다.",
                bkDownTitle: "데이터 백업 (다운로드)", bkUpTitle: "백업 복구 (불러오기)", bkDelTitle: "데이터 초기화", btnBkDown: "JSON 다운로드", btnBkUp: "파일 선택 후 데이터 복구", btnBkDel: "모든 데이터 삭제",
                saveClose: "저장 후 닫기", batchInput: "이 학생 20문항 일괄 입력하기 ➡️", batchSave: "20문항 일괄 저장", cancel: "⬅️ 취소", selectScore: "각 문항을 읽고 점수(1~5점)를 선택하세요.", qNum: "번 문항",
                ext: "외향성", agr: "우호성", con: "성실성", neu: "정서성", ope: "개방성", noResp: "미응답", sumTitle: "요인별 점수 요약", detailTitle: "문항별 응답 상세",
                nameCol: "이름", noCol: "번호", traitCol: "요인", qCol: "문항", scoreCol: "점수", scorePt: "점",
                alertNoSt: "학생을 선택해주세요.", alertNoData: "데이터가 없습니다.", alertDel: "학생을 삭제할까요?", alertInit: "정말로 모든 데이터를 삭제하시겠습니까?", alertCopyOk: "결과가 복사되었습니다.", alertBkOk: "데이터 복구가 완료되었습니다!",
                gTitle: "📌 프로그램 기본 사용 순서", g1: "학생 등록: [학생 관리] 탭에서 이름을 추가합니다.", g2: "언어 설정: 우측 상단 메뉴에서 기본 언어와 보조 언어를 설정합니다.", g3: "검사 진행: 메인 화면에서 학생 카드를 눌러 점수를 입력하거나 일괄 입력합니다.", g4: "결과 확인: [개별 결과] 탭에서 결과를 확인하고 TXT로 저장할 수 있습니다.",
                warnTitle: "⚠️ 데이터 저장 안내 및 주의사항",
                warnDesc: "입력되는 모든 데이터는 외부 서버로 전송되지 않고 현재 사용 중인 브라우저(로컬 스토리지)에만 안전하게 저장됩니다. 단, 학교 컴퓨터 등에서 재부팅(복구 프로그램)을 하거나 브라우저 기록을 삭제하면 저장 데이터가 초기화될 수 있습니다. 데이터를 잃지 않으려면 브라우저 창을 닫거나 컴퓨터를 끄기 전 반드시 백업을 해두세요.",
                backupPath: "(💾 저장 방법 : 우측 상단 ⚙️톱니바퀴 클릭 -> 백업/복구 -> JSON 다운로드)",
                goHome: "다른 게임 고르기 kingsmath.com"
            },
            q: { 1:"쉬는 시간에 친구들과 어울려 노는 것이 가장 즐겁다.", 2:"친구가 지우개나 연필을 빌려달라고 하면 기꺼이 빌려준다.", 3:"학교 숙제나 알림장 쓰기를 미루지 않고 제시간에 끝낸다.", 4:"발표를 하거나 시험을 볼 때 심하게 가슴이 두근거리고 떨린다.", 5:"미술이나 글쓰기 시간에 나만의 새롭고 기발한 생각을 표현하는 게 즐겁다.", 6:"처음 보는 친구에게도 먼저 반갑게 인사할 수 있다.", 7:"친구가 속상해서 울면 나도 마음이 아프고 위로해주고 싶다.", 8:"내 책상 위나 사물함 안을 항상 깨끗하게 정리정돈한다.", 9:"친구가 장난으로 한 말에도 크게 상처받고 슬퍼질 때가 있다.", 10:"한 번도 해보지 않은 새로운 보드게임이나 규칙을 배우는 것이 신난다.", 11:"모둠 활동을 할 때 내가 먼저 나서서 친구들을 이끄는 편이다.", 12:"모둠 활동을 할 때 내 생각만 고집하지 않고 친구들의 의견을 잘 듣는다.", 13:"한 번 시작한 일이나 게임은 끝까지 해내려고 노력한다.", 14:"내가 계획한 대로 일이 풀리지 않으면 쉽게 짜증이 나고 화가 난다.", 15:"과학 실험을 하거나 동식물을 관찰하면서 호기심을 많이 느낀다.", 16:"체육 시간이나 야외 활동을 할 때 에너지가 넘친다.", 17:"친구가 실수하더라도 화내지 않고 괜찮다고 말해준다.", 18:"선생님이나 부모님과 한 약속은 꼭 지키려고 노력한다.", 19:"무서운 영화를 보거나 깜깜한 곳에 가면 남들보다 더 많이 무서워한다.", 20:"만화책이나 동화책을 읽으면서 내가 주인공이 된 것처럼 상상을 자주 한다." }
        },
        'en': {
            name: '🇺🇸 English', 
            ui: { 
                title: "Big5 Personality Test King", lang1: "Primary Lang", lang2: "Secondary Lang", none: "None", dashTitle: "Teacher Dashboard",
                wait: "Waiting", entered: "Entered", completed: "Completed", avgPrefix: "Question Avg:", avgSuffix: "participated", noData: "No data entered yet.",
                guide: "Guide", manage: "Manage Students", indRes: "Individual Results", allRes: "All Results", backup: "Backup/Restore",
                add: "Add", del: "Delete", phName: "Enter new student name", msgAvatar: "※ Avatars are generated automatically when a student is added.",
                selTarget: "Select a student to view", copy: "Copy", saveTxt: "Save TXT", msgAllSum: "Summary of scores by trait for all students.",
                bkDownTitle: "Data Backup (Download)", bkUpTitle: "Data Restore (Upload)", bkDelTitle: "Initialize Data", btnBkDown: "Download JSON", btnBkUp: "Restore Data", btnBkDel: "Delete All Data",
                saveClose: "Save & Close", batchInput: "Batch Input 20 Questions ➡️", batchSave: "Save 20 Questions", cancel: "⬅️ Cancel", selectScore: "Read and select a score (1-5).", qNum: " Question",
                ext: "Extraversion", agr: "Agreeableness", con: "Conscientiousness", neu: "Neuroticism", ope: "Openness", noResp: "No Response", sumTitle: "Score Summary by Trait", detailTitle: "Response Details by Question",
                nameCol: "Name", noCol: "No.", traitCol: "Trait", qCol: "Question", scoreCol: "Score", scorePt: "pt",
                alertNoSt: "Please select a student.", alertNoData: "No data available.", alertDel: "Delete this student?", alertInit: "Are you sure you want to delete all data?", alertCopyOk: "Results copied to clipboard.", alertBkOk: "Data restored successfully!",
                gTitle: "📌 Basic Usage Guide", g1: "Registration: Add student names in the [Manage Students] tab.", g2: "Language: Set primary and secondary languages in the top right menu.", g3: "Testing: Click on a student card to input scores or use batch input.", g4: "Results: Check results in the [Individual Results] tab and export as TXT.",
                warnTitle: "⚠️ Data Storage Notice & Precautions",
                warnDesc: "All data entered is stored safely only in your current browser (Local Storage) and is not sent to any external server. However, data may be initialized if you reboot (recovery program) on school computers or clear browser history. To avoid data loss, please back up before closing the browser or turning off the computer.",
                backupPath: "(💾 How to save: Click ⚙️ in the top right -> Backup/Restore -> JSON Download)",
                goHome: "Pick another game kingsmath.com"
            },
            q: { 1:"I enjoy playing with friends the most during break time.", 2:"I gladly lend an eraser or pencil if a friend asks.", 3:"I finish my school homework or planner on time without delaying.", 4:"My heart beats fast and I feel very nervous when presenting or taking a test.", 5:"I enjoy expressing my own creative ideas during art or writing class.", 6:"I can be the first to gladly greet a friend I've never met before.", 7:"When a friend cries because they are upset, my heart hurts too and I want to comfort them.", 8:"I always keep my desk and the inside of my locker clean and organized.", 9:"I sometimes get deeply hurt and sad even when a friend makes a joke.", 10:"It's exciting to learn a new board game or rules I've never tried before.", 11:"I tend to take the lead and guide my friends during group activities.", 12:"During group activities, I don't just insist on my own ideas but listen well to my friends' opinions.", 13:"I try my best to finish a task or game once I start it.", 14:"I easily get annoyed and angry when things don't go as I planned.", 15:"I feel a lot of curiosity when doing science experiments or observing plants and animals.", 16:"I am full of energy during PE class or outdoor activities.", 17:"Even if a friend makes a mistake, I don't get angry and tell them it's okay.", 18:"I always try to keep promises made with my teachers or parents.", 19:"I get more scared than others when watching a scary movie or going to a dark place.", 20:"I often imagine myself as the main character while reading comic books or storybooks." }
        },
        'zh': {
            name: '🇨🇳 中文 (简体)', 
            ui: { 
                title: "Big5 性格测试之王", lang1: "第一语言", lang2: "第二语言", none: "无", dashTitle: "教师仪表板",
                wait: "等待中", entered: "已输入", completed: "已完成", avgPrefix: "当前问题平均分:", avgSuffix: "人参与", noData: "暂无输入数据。",
                guide: "使用指南", manage: "学生管理", indRes: "个人结果", allRes: "全部结果", backup: "备份/恢复",
                add: "添加", del: "删除", phName: "输入新学生姓名", msgAvatar: "※ 添加学生后将自动生成头像。",
                selTarget: "选择要查看结果的学生", copy: "复制", saveTxt: "保存 TXT", msgAllSum: "所有学生按特征划分的分数摘要。",
                bkDownTitle: "数据备份 (下载)", bkUpTitle: "数据恢复 (上传)", bkDelTitle: "初始化数据", btnBkDown: "下载 JSON", btnBkUp: "选择文件后恢复数据", btnBkDel: "删除所有数据",
                saveClose: "保存并关闭", batchInput: "批量输入20个问题 ➡️", batchSave: "批量保存20个问题", cancel: "⬅️ 取消", selectScore: "请阅读并选择分数（1-5分）。", qNum: "题",
                ext: "外向性", agr: "宜人性", con: "尽责性", neu: "神经质", ope: "开放性", noResp: "未回答", sumTitle: "特征分数摘要", detailTitle: "问题回复详情",
                nameCol: "姓名", noCol: "编号", traitCol: "特征", qCol: "问题", scoreCol: "分数", scorePt: "分",
                alertNoSt: "请选择一名学生。", alertNoData: "没有数据。", alertDel: "要删除该学生吗？", alertInit: "确定要删除所有数据吗？", alertCopyOk: "结果已复制。", alertBkOk: "数据恢复成功！",
                gTitle: "📌 基本使用指南", g1: "注册：在 [学生管理] 选项卡中添加学生姓名。", g2: "语言：在右上角菜单中设置主要和次要语言。", g3: "测试：点击学生卡片输入分数或使用批量输入。", g4: "结果：在 [个人结果] 选项卡中查看结果并可导出为 TXT。",
                warnTitle: "⚠️ 数据保存说明及注意事项",
                warnDesc: "所有输入数据仅存储在当前浏览器（本地存储）中，不会发送到外部服务器。但在学校电脑上重启（还原程序）或清除浏览器历史记录可能会导致数据丢失。为防止数据丢失，请在关闭浏览器前进行备份。",
                backupPath: "(💾 保存方法：点击右上角 ⚙️ 图标 -> 备份/恢复 -> 下载 JSON)",
                goHome: "选择其他游戏 kingsmath.com"
            },
            q: { 1:"休息时间我最喜欢和朋友们一起玩。", 2:"如果朋友向我借橡皮或铅笔，我会很乐意借给他们。", 3:"我能按时完成学校作业或记事本，不拖延。", 4:"在发表或考试时，我会心跳加快，感到非常紧张。", 5:"在美术或写作课上，我喜欢表达自己新颖奇特的想法。", 6:"即使是第一次见面的朋友，我也能主动打招呼。", 7:"当朋友因为伤心而哭泣时，我也会感到心痛，并想安慰他们。", 8:"我总是把我的书桌和储物柜里面整理得干干净净。", 9:"即使朋友开玩笑，我有时也会感到很受伤和难过。", 10:"学习我从未尝试过的新桌游或规则让我感到兴奋。", 11:"在小组活动中，我倾向于主动带头引导朋友们。", 12:"在小组活动中，我不会固执己见，而是认真听取朋友们的意见。", 13:"一旦开始了一件事或游戏，我就会努力坚持到底。", 14:"如果事情没有按照我的计划进行，我很容易感到烦躁和生气。", 15:"在做科学实验或观察动植物时，我充满好奇心。", 16:"在体育课或户外活动中，我精力充沛。", 17:"即使朋友犯了错，我也不会生气，并告诉他们没关系。", 18:"我总是努力遵守对老师或父母的承诺。", 19:"看恐怖电影或去黑暗的地方时，我比别人更害怕。", 20:"看漫画书或童话书时，我经常想象自己是主人公。" }
        },
        'ja': {
            name: '🇯🇵 日本語', 
            ui: { 
                title: "Big5 性격診断キング", lang1: "第1言語", lang2: "第2言語", none: "選択なし", dashTitle: "教師ダッシュボード",
                wait: "待機中", entered: "入力済", completed: "完了", avgPrefix: "현재の質問平均:", avgSuffix: "人参加", noData: "入力されたデータがありません。",
                guide: "利用案内", manage: "学生管理", indRes: "個別結果", allRes: "全体結果", backup: "バックアップ/復元",
                add: "追加", del: "削除", phName: "新しい学生名を入力", msgAvatar: "※ 学生を追加するとアバターが自動生成されます。",
                selTarget: "結果を見る学生を選択してください", copy: "コピー", saveTxt: "TXT 保存", msgAllSum: "全学生の特性別スコア要約です。",
                bkDownTitle: "データバックアップ (ダウンロード)", bkUpTitle: "バックアップ復元", bkDelTitle: "データ初期化", btnBkDown: "JSON ダウンロード", btnBkUp: "データ復元", btnBkDel: "全データ削除",
                saveClose: "保存して閉じる", batchInput: "20問一括入力 ➡️", batchSave: "20問一括保存", cancel: "⬅️ キャンセル", selectScore: "スコア(1〜5)を選択してください。", qNum: "番目の質問",
                ext: "外向性", agr: "協調性", con: "誠実性", neu: "神経症的傾向", ope: "開放性", noResp: "未回答", sumTitle: "特性別スコア要約", detailTitle: "質問別回答詳細",
                nameCol: "名前", noCol: "番号", traitCol: "特性", qCol: "質問", scoreCol: "スコア", scorePt: "点",
                alertNoSt: "学生を選択してください。", alertNoData: "データがありません。", alertDel: "学生を削除しますか？", alertInit: "本当に全てのデータを削除しますか？", alertCopyOk: "結果がコピーされました。", alertBkOk: "データ復元が完了しました！",
                gTitle: "📌 基本的な使用手順", g1: "登録: [学生管理] タブで名前を追加します。", g2: "言語設定: 右上のメニューで第1・第2言語を設定します。", g3: "テスト: 学生カードをクリックしてスコアを入力します。", g4: "結果確認: [個別結果] タブで結果を確認し、TXTとして保存できます。",
                warnTitle: "⚠️ データ保存に関する案内と注意事項",
                warnDesc: "入力されたデータは外部サーバーに送信されず、現在のブラウザにのみ保存されます。学校のPCでの再起動や履歴削除により消去される可能性があるため、終了前に必ずバックアップを取ってください。",
                backupPath: "(💾 保存方法：右上の ⚙️ アイコンをクリック -> バックアップ/復元 -> JSONダウンロード)",
                goHome: "他のゲームを選ぶ kingsmath.com"
            },
            q: { 1:"休み時間に友達と一緒に遊ぶのが一番楽しい。", 2:"友達に消しゴムや鉛筆を貸してと言われたら、喜んで貸す。", 3:"学校の宿題や連絡帳を後回しにせず、時間通りに終わらせる。", 4:"発表したりテストを受けたりするとき、心臓がドキドキしてとても緊張する。", 5:"図工や作文の授業で、自分だけの新しい斬新なアイデアを表現するのが楽しい。", 6:"初めて会う友達にも、自分から先に嬉しそうに挨拶できる。", 7:"友達が悲しくて泣いていると、私も心が痛くなり、慰めてあげたいと思う。", 8:"机の上やロッカーの中をいつもきれいに整理整頓している。", 9:"友達が冗談で言ったことでも、深く傷ついて悲しくなることがある。", 10:"やったことのない新しいボードゲームやルールを学ぶのがワクワクする。", 11:"グループ活動をするとき、私が先に立って友達を引っ張っていく方だ。", 12:"グループ活動をするとき、自分の考えだけを押し通さず、友達の意見をよく聞く。", 13:"一度始めたことやゲームは、最後までやり遂げようと努力する。", 14:"自分が計画した通りに物事が進まないと、すぐにイライラして怒ってしまう。", 15:"理科の実験をしたり、動植物を観察したりするとき、好奇心をたくさん感じる。", 16:"体育の時間や屋外での活動をするとき、エネルギーに満ちあふれている。", 17:"友達が失敗しても怒らず、大丈夫だと言ってあげる。", 18:"先生や親との約束は必ず守るように努力している。", 19:"怖い映画を見たり、暗い場所に行ったりすると、人よりもずっと怖がる。", 20:"漫画や童話を読みながら、自分が主人公になったように想像することがよくある。" }
        },
        'es': {
            name: '🇪🇸 Español', 
            ui: { 
                title: "Rey del Test de Personalidad Big5", lang1: "Idioma 1", lang2: "Idioma 2", none: "Ninguno", dashTitle: "Panel de control del profesor",
                wait: "Esperando", entered: "Ingresado", completed: "Completado", avgPrefix: "Promedio:", avgSuffix: "participantes", noData: "Sin datos.",
                guide: "Guía", manage: "Estudiantes", indRes: "Resultados Ind.", allRes: "Resultados Gen.", backup: "Copia de Seg.",
                add: "Añadir", del: "Borrar", phName: "Ingrese nombre", msgAvatar: "※ El avatar se genera automáticamente.",
                selTarget: "Seleccione un estudiante", copy: "Copiar", saveTxt: "Guardar TXT", msgAllSum: "Resumen de puntuaciones de todos.",
                bkDownTitle: "Descargar Datos", bkUpTitle: "Restaurar Datos", bkDelTitle: "Borrar Datos", btnBkDown: "Descargar JSON", btnBkUp: "Restaurar", btnBkDel: "Borrar Todo",
                saveClose: "Guardar y Cerrar", batchInput: "Entrada por Lotes ➡️", batchSave: "Guardar por Lotes", cancel: "⬅️ Cancelar", selectScore: "Selecciona una puntuación (1-5).", qNum: " Pregunta",
                ext: "Extraversión", agr: "Amabilidad", con: "Responsabilidad", neu: "Neuroticismo", ope: "Apertura", noResp: "Sin respuesta", sumTitle: "Resumen de Puntuaciones", detailTitle: "Detalles por Pregunta",
                nameCol: "Nombre", noCol: "Nº", traitCol: "Rasgo", qCol: "Pregunta", scoreCol: "Pts", scorePt: "pt",
                alertNoSt: "Seleccione estudiante.", alertNoData: "Sin datos.", alertDel: "¿Borrar estudiante?", alertInit: "¿Borrar todos los datos?", alertCopyOk: "Copiado al portapapeles.", alertBkOk: "Restauración exitosa.",
                gTitle: "📌 Guía de Uso", g1: "Registro: Añada nombres en [Estudiantes].", g2: "Idioma: Configure los idiomas arriba a la derecha.", g3: "Prueba: Haga clic en un estudiante para calificar.", g4: "Resultados: Exporte a TXT en la pestaña de resultados.",
                warnTitle: "⚠️ Aviso de almacenamiento de datos",
                warnDesc: "Los datos se guardan solo en su navegador actual. Pueden borrarse al reiniciar computadoras escolares. Realice una copia de seguridad antes de cerrar el navegador para no perder su información.",
                backupPath: "(💾 Cómo guardar: Clic en ⚙️ arriba a la derecha -> Copia de Seg. -> Descargar JSON)",
                goHome: "Elegir otro juego kingsmath.com"
            },
            q: { 1:"Lo que más disfruto es jugar con mis amigos durante el recreo.", 2:"Con gusto presto un borrador o lápiz si un amigo me lo pide.", 3:"Termino mis tareas escolares a tiempo sin posponerlas.", 4:"Mi corazón late muy rápido y me siento muy nervioso al hacer una presentación o un examen.", 5:"Disfruto expresando mis propias ideas creativas durante la clase de arte o escritura.", 6:"Puedo ser el primero en saludar a un amigo que nunca he conocido antes.", 7:"Cuando un amigo llora porque está triste, a mí también me duele el corazón y quiero consolarlo.", 8:"Siempre mantengo mi escritorio y el interior de mi casillero limpios y organizados.", 9:"A veces me siento muy herido y triste incluso por una broma de un amigo.", 10:"Es emocionante aprender un nuevo juego de mesa o reglas que nunca he probado antes.", 11:"Suelo tomar la iniciativa y guiar a mis amigos durante las actividades grupales.", 12:"Durante las actividades grupales, escucho bien las opiniones de mis amigos en lugar de insistir solo en las mías.", 13:"Trato de terminar una tarea o juego una vez que lo empiezo.", 14:"Me molesto y me enojo fácilmente cuando las cosas no salen como las planeé.", 15:"Siento mucha curiosidad al hacer experimentos de ciencias o al observar plantas y animales.", 16:"Estoy lleno de energía durante la clase de educación física o actividades al aire libre.", 17:"Incluso si un amigo comete un error, no me enojo y le digo que está bien.", 18:"Siempre trato de cumplir las promesas que le hago a mis profesores o padres.", 19:"Me asusto más que los demás al ver una película de miedo o al ir a un lugar oscuro.", 20:"A menudo me imagino que soy el personaje principal mientras leo cómics o libros de cuentos." }
        },
        'fr': {
            name: '🇫🇷 Français', 
            ui: { 
                title: "Roi du Test de Personnalité Big5", lang1: "Langue 1", lang2: "Langue 2", none: "Aucun", dashTitle: "Tableau de Bord",
                wait: "En attente", entered: "Saisi", completed: "Terminé", avgPrefix: "Moyenne:", avgSuffix: "participants", noData: "Aucune donnée.",
                guide: "Guide", manage: "Étudiants", indRes: "Résultats Ind.", allRes: "Résultats Globaux", backup: "Sauvegarde",
                add: "Ajouter", del: "Supprimer", phName: "Nom de l'étudiant", msgAvatar: "※ L'avatar est généré automatiquement.",
                selTarget: "Sélectionnez un étudiant", copy: "Copier", saveTxt: "Enregistrer TXT", msgAllSum: "Résumé des scores de tous les étudiants.",
                bkDownTitle: "Sauvegarder les données", bkUpTitle: "Restaurer", bkDelTitle: "Effacer les données", btnBkDown: "Télécharger JSON", btnBkUp: "Restaurer", btnBkDel: "Tout Effacer",
                saveClose: "Enregistrer & Fermer", batchInput: "Saisie groupée ➡️", batchSave: "Enregistrer tout", cancel: "⬅️ Annuler", selectScore: "Sélectionnez un score (1-5).", qNum: " Question",
                ext: "Extraversion", agr: "Agréabilité", con: "Conscience", neu: "Névrosisme", ope: "Ouverture", noResp: "Sans réponse", sumTitle: "Résumé des scores", detailTitle: "Détails par question",
                nameCol: "Nom", noCol: "Nº", traitCol: "Trait", qCol: "Question", scoreCol: "Score", scorePt: "pt",
                alertNoSt: "Sélectionnez un étudiant.", alertNoData: "Aucune donnée.", alertDel: "Supprimer cet étudiant?", alertInit: "Tout effacer?", alertCopyOk: "Copié dans le presse-papiers.", alertBkOk: "Restauration réussie.",
                gTitle: "📌 Guide d'utilisation", g1: "Inscription: Ajoutez des noms dans [Étudiants].", g2: "Langue: Configurez les langues en haut à droite.", g3: "Test: Cliquez sur un étudiant pour évaluer.", g4: "Résultats: Exportez en TXT dans l'onglet des résultats.",
                warnTitle: "⚠️ Stockage des données et précautions",
                warnDesc: "Les données sont stockées uniquement dans votre navigateur. Elles peuvent être effacées sur les ordinateurs scolaires. Veuillez effectuer une sauvegarde avant de fermer le navigateur.",
                backupPath: "(💾 Comment enregistrer : Cliquez sur ⚙️ en haut à droite -> Sauvegarde -> Télécharger JSON)",
                goHome: "Choisir un autre jeu kingsmath.com"
            },
            q: { 1:"Ce que j'aime le plus, c'est jouer avec mes amis pendant la récréation.", 2:"Je prête volontiers une gomme ou un crayon si un ami le demande.", 3:"Je termine mes devoirs à temps sans les remettre à plus tard.", 4:"Mon cœur bat très vite et je suis très nerveux lorsque je fais une présentation ou que je passe un examen.", 5:"J'aime exprimer mes propres idées créatives en cours d'art ou d'écriture.", 6:"Je peux être le premier à saluer un ami que je n'ai jamais rencontré auparavant.", 7:"Quand un ami pleure parce qu'il est triste, mon cœur a mal aussi et je veux le réconforter.", 8:"Je garde toujours mon bureau et l'intérieur de mon casier propres et organisés.", 9:"Parfois, je me sens très blessé et triste, même à cause d'une blague d'un ami.", 10:"Il est excitant d'apprendre un nouveau jeu de société ou des règles que je n'ai jamais essayés.", 11:"J'ai tendance à prendre l'initiative et à guider mes amis lors d'activités de groupe.", 12:"Pendant les activités de groupe, j'écoute bien les opinions de mes amis au lieu d'insister uniquement sur les miennes.", 13:"J'essaie de terminer une tâche ou un jeu une fois que je l'ai commencé.", 14:"Je m'énerve et me mets facilement en colère quand les choses ne se passent pas comme prévu.", 15:"Je ressens beaucoup de curiosité lors d'expériences scientifiques ou en observant la nature.", 16:"Je suis plein d'énergie pendant les cours d'EPS ou les activités de plein air.", 17:"Même si un ami fait une erreur, je ne me mets pas en colère et je lui dis que ce n'est pas grave.", 18:"J'essaie toujours de tenir les promesses faites à mes professeurs ou à mes parents.", 19:"J'ai plus peur que les autres en regardant un film effrayant ou en allant dans un endroit sombre.", 20:"Je m'imagine souvent être le personnage principal en lisant des bandes données ou des contes." }
        },
        'ru': {
            name: '🇷🇺 Русский', 
            ui: { 
                title: "Король личностного теста Big5", lang1: "Язык 1", lang2: "Язык 2", none: "Нет", dashTitle: "Панель учителя",
                wait: "Ожидание", entered: "Введено", completed: "Завершено", avgPrefix: "Ср. балл:", avgSuffix: "участников", noData: "Нет данных.",
                guide: "Гид", manage: "Студенты", indRes: "Инд. рез-ты", allRes: "Общие рез-ты", backup: "Рез. копия",
                add: "Добавить", del: "Удалить", phName: "Имя студента", msgAvatar: "※ Аватар создается автоматически.",
                selTarget: "Выберите студента", copy: "Копировать", saveTxt: "Сохранить TXT", msgAllSum: "Сводка баллов всех студентов.",
                bkDownTitle: "Скачать данные", bkUpTitle: "Восстановить", bkDelTitle: "Удалить данные", btnBkDown: "Скачать JSON", btnBkUp: "Восстановить", btnBkDel: "Удалить всё",
                saveClose: "Сохранить и Закрыть", batchInput: "Пакетный ввод ➡️", batchSave: "Сохранить все", cancel: "⬅️ Отмена", selectScore: "Выберите оценку (1-5).", qNum: " Вопрос",
                ext: "Экстраверсия", agr: "Доброжелательность", con: "Добросовестность", neu: "Нейротизм", ope: "Открытость", noResp: "Нет ответа", sumTitle: "Сводка баллов", detailTitle: "Детали по вопросам",
                nameCol: "Имя", noCol: "№", traitCol: "Черта", qCol: "Вопрос", scoreCol: "Балл", scorePt: "б.",
                alertNoSt: "Выберите студента.", alertNoData: "Нет данных.", alertDel: "Удалить студента?", alertInit: "Удалить все данные?", alertCopyOk: "Скопировано.", alertBkOk: "Успешно восстановлено.",
                gTitle: "📌 Руководство", g1: "Регистрация: Добавьте имена во вкладке [Студенты].", g2: "Язык: Настройте языки в правом верхнем углу.", g3: "Тест: Нажмите на студента для ввода.", g4: "Результаты: Экспорт в TXT во вкладке результатов.",
                warnTitle: "⚠️ Хранение данных и меры предосторожности",
                warnDesc: "Данные сохраняются только в вашем браузере. Они могут быть удалены при перезагрузке школьных компьютеров. Сделайте резервную копию перед закрытием браузера.",
                backupPath: "(💾 Как сохранить: Нажмите ⚙️ справа вверху -> Рез. копия -> Скачать JSON)",
                goHome: "Выбрать другую игру kingsmath.com"
            },
            q: { 1:"Больше всего мне нравится играть с друзьями на перемене.", 2:"Я с радостью одолжу ластик или карандаш, если друг попросит.", 3:"Я делаю домашние задания вовремя, не откладывая их.", 4:"Мое сердце бьется очень быстро, и я очень нервничаю во время ответа или теста.", 5:"Мне нравится выражать свои творческие идеи на уроках рисования или письма.", 6:"Я могу первым поздороваться с другом, которого никогда раньше не встречал.", 7:"Когда друг плачет от расстройства, мне тоже больно, и я хочу его утешить.", 8:"Я всегда держу свою парту и шкафчик в чистоте и порядке.", 9:"Иногда мне бывает очень обидно и грустно даже из-за шутки друга.", 10:"Очень интересно изучать новую настольную игру или правила.", 11:"Я склонен брать на себя инициативу во время групповых занятий.", 12:"Во время групповых занятий я прислушиваюсь к мнению друзей, а не только настаиваю на своем.", 13:"Я стараюсь доводить дело или игру до конца, если уж начал.", 14:"Я легко раздражаюсь и злюсь, когда все идет не по плану.", 15:"Я испытываю сильное любопытство, проводя научные эксперименты или наблюдая за природой.", 16:"Я полон энергии на уроках физкультуры или во время игр на улице.", 17:"Даже если друг ошибается, я не злюсь и говорю, что все в порядке.", 18:"Я всегда стараюсь сдерживать обещания, данные учителям или родителям.", 19:"Я пугаюсь больше других, когда смотрю страшный фильм или иду в темное место.", 20:"Читая комиксы или сказки, я часто представляю себя главным героем." }
        },
        'id': {
            name: '🇮🇩 Bahasa Indonesia', 
            ui: { 
                title: "Raja Tes Kepribadian Big5", lang1: "Bahasa Utama", lang2: "Bahasa Kedua", none: "Tidak ada", dashTitle: "Dasbor Guru",
                wait: "Menunggu", entered: "Dimasukkan", completed: "Selesai", avgPrefix: "Rata-rata:", avgSuffix: "peserta", noData: "Belum ada data.",
                guide: "Panduan", manage: "Kelola Siswa", indRes: "Hasil Individu", allRes: "Semua Hasil", backup: "Cadangan",
                add: "Tambah", del: "Hapus", phName: "Masukkan nama", msgAvatar: "※ Avatar dibuat secara otomatis.",
                selTarget: "Pilih siswa", copy: "Salin", saveTxt: "Simpan TXT", msgAllSum: "Ringkasan skor untuk semua siswa.",
                bkDownTitle: "Unduh Data", bkUpTitle: "Pulihkan Data", bkDelTitle: "Hapus Data", btnBkDown: "Unduh JSON", btnBkUp: "Pulihkan", btnBkDel: "Hapus Semua",
                saveClose: "Simpan & Tutup", batchInput: "Input Massal ➡️", batchSave: "Simpan Semua", cancel: "⬅️ Batal", selectScore: "Pilih skor (1-5).", qNum: " Pertanyaan",
                ext: "Ekstraversi", agr: "Keramahan", con: "Kehati-hatian", neu: "Neurotisisme", ope: "Keterbukaan", noResp: "Tidak ada", sumTitle: "Ringkasan Skor", detailTitle: "Detail Pertanyaan",
                nameCol: "Nama", noCol: "No.", traitCol: "Sifat", qCol: "Pertanyaan", scoreCol: "Skor", scorePt: "pt",
                alertNoSt: "Pilih siswa.", alertNoData: "Tidak ada data.", alertDel: "Hapus siswa?", alertInit: "Hapus semua data?", alertCopyOk: "Disalin ke papan klip.", alertBkOk: "Pemulihan berhasil.",
                gTitle: "📌 Panduan", g1: "Pendaftaran: Tambah nama di tab [Kelola Siswa].", g2: "Bahasa: Atur bahasa di menu kanan atas.", g3: "Tes: Klik kartu siswa untuk menilai.", g4: "Hasil: Ekspor ke TXT di tab hasil.",
                warnTitle: "⚠️ Penyimpanan Data & Tindakan Pencegahan",
                warnDesc: "Data hanya disimpan di browser Anda. Data mungkin terhapus saat me-restart komputer sekolah. Harap buat cadangan sebelum menutup browser.",
                backupPath: "(💾 Cara menyimpan: Klik ⚙️ di kanan atas -> Cadangan -> Unduh JSON)",
                goHome: "Pilih game lain kingsmath.com"
            },
            q: { 1:"Saya paling suka bermain dengan teman-teman saat jam istirahat.", 2:"Saya dengan senang hati meminjamkan penghapus atau pensil jika teman meminta.", 3:"Saya menyelesaikan PR sekolah tepat waktu tanpa menunda.", 4:"Jantung saya berdetak kencang dan saya merasa sangat gugup saat presentasi atau ujian.", 5:"Saya senang mengekspresikan ide kreatif saya sendiri di kelas seni atau menulis.", 6:"Saya bisa menjadi yang pertama menyapa teman yang belum pernah saya temui.", 7:"Ketika teman menangis karena sedih, hati saya juga sakit dan ingin menghiburnya.", 8:"Saya selalu menjaga meja dan loker saya tetap bersih dan rapi.", 9:"Kadang-kadang saya merasa sangat terluka dan sedih bahkan karena candaan teman.", 10:"Sangat menyenangkan mempelajari permainan papan atau aturan baru yang belum pernah saya coba.", 11:"Saya cenderung memimpin teman-teman saat kegiatan kelompok.", 12:"Saat kegiatan kelompok, saya mendengarkan pendapat teman dengan baik, tidak hanya memaksakan pendapat sendiri.", 13:"Saya berusaha menyelesaikan tugas atau permainan setelah saya memulainya.", 14:"Saya mudah kesal dan marah ketika rencana saya tidak berjalan lancar.", 15:"Saya merasa sangat penasaran saat melakukan eksperimen sains atau mengamati alam.", 16:"Saya penuh energi saat pelajaran olahraga atau aktivitas luar ruangan.", 17:"Bahkan jika teman melakukan kesalahan, saya tidak marah dan mengatakan tidak apa-apa.", 18:"Saya selalu berusaha menepati janji yang dibuat dengan guru atau orang tua.", 19:"Saya lebih takut daripada orang lain saat menonton film seram atau pergi ke tempat gelap.", 20:"Saya sering membayangkan diri saya sebagai karakter utama saat membaca buku komik atau cerita." }
        },
        'vi': {
            name: '🇻🇳 Tiếng Việt', 
            ui: { 
                title: "Vua Kiểm Tra Tính Cách Big5", lang1: "Ngôn ngữ 1", lang2: "Ngôn ngữ 2", none: "Không có", dashTitle: "Bảng Điều Khiển",
                wait: "Đang chờ", entered: "Đã nhập", completed: "Hoàn thành", avgPrefix: "Trung bình:", avgSuffix: "người tham gia", noData: "Chưa có dữ liệu.",
                guide: "Hướng dẫn", manage: "Học sinh", indRes: "Kết quả cá nhân", allRes: "Tất cả kết quả", backup: "Sao lưu",
                add: "Thêm", del: "Xóa", phName: "Nhập tên", msgAvatar: "※ Avatar được tạo tự động.",
                selTarget: "Chọn học sinh", copy: "Sao chép", saveTxt: "Lưu TXT", msgAllSum: "Tóm tắt điểm của tất cả học sinh.",
                bkDownTitle: "Tải dữ liệu", bkUpTitle: "Khôi phục", bkDelTitle: "Xóa dữ liệu", btnBkDown: "Tải JSON", btnBkUp: "Khôi phục", btnBkDel: "Xóa tất cả",
                saveClose: "Lưu & Đóng", batchInput: "Nhập hàng loạt ➡️", batchSave: "Lưu tất cả", cancel: "⬅️ Hủy", selectScore: "Chọn điểm (1-5).", qNum: " Câu hỏi",
                ext: "Hướng ngoại", agr: "Dễ chịu", con: "Tận tâm", neu: "Lo âu", ope: "Cởi mở", noResp: "Không có", sumTitle: "Tóm tắt điểm", detailTitle: "Chi tiết câu hỏi",
                nameCol: "Tên", noCol: "Số", traitCol: "Đặc điểm", qCol: "Câu hỏi", scoreCol: "Điểm", scorePt: "đ",
                alertNoSt: "Chọn một học sinh.", alertNoData: "Không có dữ liệu.", alertDel: "Xóa học sinh?", alertInit: "Xóa tất cả dữ liệu?", alertCopyOk: "Đã sao chép.", alertBkOk: "Khôi phục thành công.",
                gTitle: "📌 Hướng dẫn", g1: "Đăng ký: Thêm tên trong tab [Học sinh].", g2: "Ngôn ngữ: Đặt ngôn ngữ ở góc trên bên phải.", g3: "Kiểm tra: Nhấp vào học sinh để chấm điểm.", g4: "Kết quả: Xuất sang TXT.",
                warnTitle: "⚠️ Lưu ý về lưu trữ dữ liệu",
                warnDesc: "Dữ liệu chỉ được lưu trong trình duyệt của bạn. Nó có thể bị xóa khi khởi động lại máy tính ở trường. Hãy sao lưu trước khi đóng trình duyệt.",
                backupPath: "(💾 Cách lưu: Nhấp vào ⚙️ ở góc trên bên phải -> Sao lưu -> Tải xuống JSON)",
                goHome: "Chọn trò chơi khác kingsmath.com"
            },
            q: { 1:"Tôi thích chơi với bạn bè nhất trong giờ ra chơi.", 2:"Tôi sẵn lòng cho bạn mượn cục tẩy hoặc bút chì nếu bạn yêu cầu.", 3:"Tôi hoàn thành bài tập về nhà đúng hạn mà không trì hoãn.", 4:"Tim tôi đập rất nhanh và tôi cảm thấy rất lo lắng khi thuyết trình hoặc làm bài kiểm tra.", 5:"Tôi thích thể hiện những ý tưởng sáng tạo của riêng mình trong lớp mỹ thuật hoặc tập làm văn.", 6:"Tôi có thể là người đầu tiên chào hỏi một người bạn mà tôi chưa từng gặp.", 7:"Khi một người bạn khóc vì buồn, tôi cũng thấy đau lòng và muốn an ủi họ.", 8:"Tôi luôn giữ bàn học và tủ đồ của mình sạch sẽ, ngăn nắp.", 9:"Đôi khi tôi cảm thấy rất tổn thương và buồn bã ngay cả vì một trò đùa của bạn bè.", 10:"Thật thú vị khi học một trò chơi board game hoặc luật chơi mới mà tôi chưa từng thử.", 11:"Tôi có xu hướng chủ động dẫn dắt bạn bè trong các hoạt động nhóm.", 12:"Trong các hoạt động nhóm, tôi lắng nghe ý kiến của bạn bè thay vì chỉ khăng khăng ý kiến của mình.", 13:"Tôi cố gắng hoàn thành một nhiệm vụ hoặc trò chơi khi đã bắt đầu.", 14:"Tôi dễ bực mình và tức giận khi mọi việc không diễn ra như kế hoạch.", 15:"Tôi cảm thấy rất tò mò khi làm thí nghiệm khoa học hoặc quan sát động thực vật.", 16:"Tôi tràn đầy năng lượng trong giờ thể dục hoặc các hoạt động ngoài trời.", 17:"Ngay cả khi bạn mắc lỗi, tôi không tức giận và nói với họ rằng không sao đâu.", 18:"Tôi luôn cố gắng giữ lời hứa với giáo viên hoặc cha mẹ.", 19:"Tôi sợ hãi hơn những người khác khi xem phim ma hoặc đi đến nơi tối tăm.", 20:"Tôi thường tưởng tượng mình là nhân vật chính khi đọc truyện tranh hoặc truyện cổ tích." }
        },
        'th': {
            name: '🇹🇭 ไทย', 
            ui: { 
                title: "ราชาแบบทดสอบบุคลิกภาพ Big5", lang1: "ภาษาที่ 1", lang2: "ภาษาที่ 2", none: "ไม่มี", dashTitle: "แผงควบคุมครู",
                wait: "รอ", entered: "ป้อนแล้ว", completed: "เสร็จสิ้น", avgPrefix: "ค่าเฉลี่ย:", avgSuffix: "คน", noData: "ยังไม่มีข้อมูล",
                guide: "คำแนะนำ", manage: "นักเรียน", indRes: "ผลรายบุคคล", allRes: "ผลทั้งหมด", backup: "สำรองข้อมูล",
                add: "เพิ่ม", del: "ลบ", phName: "ใส่ชื่อ", msgAvatar: "※ อวตารสร้างอัตโนมัติ",
                selTarget: "เลือกนักเรียน", copy: "คัดลอก", saveTxt: "บันทึก TXT", msgAllSum: "สรุปคะแนนของทุกคน",
                bkDownTitle: "ดาวน์โหลดข้อมูล", bkUpTitle: "กู้คืน", bkDelTitle: "ลบข้อมูล", btnBkDown: "ดาวน์โหลด JSON", btnBkUp: "กู้คืน", btnBkDel: "ลบทั้งหมด",
                saveClose: "บันทึก & ปิด", batchInput: "ป้อนข้อมูลพร้อมกัน ➡️", batchSave: "บันทึกทั้งหมด", cancel: "⬅️ ยกเลิก", selectScore: "เลือกคะแนน (1-5)", qNum: " ข้อ",
                ext: "การแสดงออก", agr: "ความเห็นอกเห็นใจ", con: "ความรับผิดชอบ", neu: "ความไม่มั่นคงทางอารมณ์", ope: "ความเปิดเผย", noResp: "ไม่มีคำตอบ", sumTitle: "สรุปคะแนน", detailTitle: "รายละเอียด",
                nameCol: "ชื่อ", noCol: "เลขที่", traitCol: "ลักษณะ", qCol: "คำถาม", scoreCol: "คะแนน", scorePt: "คะแนน",
                alertNoSt: "เลือกนักเรียน", alertNoData: "ไม่มีข้อมูล", alertDel: "ลบนักเรียน?", alertInit: "ลบข้อมูลทั้งหมด?", alertCopyOk: "คัดลอกแล้ว", alertBkOk: "กู้คืนสำเร็จ",
                gTitle: "📌 คำแนะนำ", g1: "ลงทะเบียน: เพิ่มชื่อในแท็บ [นักเรียน]", g2: "ภาษา: ตั้งค่าภาษาที่มุมขวาบน", g3: "ทดสอบ: คลิกที่นักเรียนเพื่อให้คะแนน", g4: "ผลลัพธ์: ส่งออกเป็น TXT",
                warnTitle: "⚠️ คำแนะนำในการบันทึกข้อมูล",
                warnDesc: "ข้อมูลจะถูกบันทึกไว้ในเบราว์เซอร์นี้เท่านั้น และอาจหายไปหากรีสตาร์ทเครื่องคอมพิวเตอร์โรงเรียน โปรดสำรองข้อมูลก่อนปิดเบราว์เซอร์",
                backupPath: "(💾 วิธีบันทึก: คลิก ⚙️ ที่มุมขวาบน -> สำรองข้อมูล -> ดาวน์โหลด JSON)",
                goHome: "เลือกเกมอื่น kingsmath.com"
            },
            q: { 1:"ฉันชอบเล่นกับเพื่อนมากที่สุดในช่วงเวลาพัก", 2:"ฉันยินดีให้เพื่อนยืมยางลบหรือดินสอถ้าพวกเขาขอ", 3:"ฉันทำการบ้านเสร็จตรงเวลาโดยไม่ผลัดวันประกันพรุ่ง", 4:"ใจฉันเต้นเร็วและรู้สึกประหม่ามากเมื่อต้องนำเสนอหรือทำข้อสอบ", 5:"ฉันสนุกกับการแสดงความคิดสร้างสรรค์ของตัวเองในชั้นเรียนศิลปะหรืองานเขียน", 6:"ฉันสามารถเป็นคนแรกที่ทักทายเพื่อนที่ไม่เคยเจอมาก่อนได้อย่างยินดี", 7:"เมื่อเพื่อนร้องไห้เพราะเสียใจ ฉันก็รู้สึกเจ็บปวดและอยากปลอบโยนพวกเขา", 8:"ฉันรักษาโต๊ะเรียนและตู้เก็บของให้สะอาดและเป็นระเบียบอยู่เสมอ", 9:"บางครั้งฉันก็รู้สึกเจ็บปวดและเสียใจมากแม้แต่กับเรื่องตลกของเพื่อน", 10:"มันน่าตื่นเต้นที่ได้เรียนรู้บอร์ดเกมหรือกฎใหม่ๆ ที่ไม่เคยลองมาก่อน", 11:"ฉันมักจะเป็นผู้นำเพื่อนๆ ในช่วงกิจกรรมกลุ่ม", 12:"ในช่วงกิจกรรมกลุ่ม ฉันรับฟังความคิดเห็นของเพื่อนๆ แทนที่จะยึดติดกับความคิดของตัวเองเท่านั้น", 13:"ฉันพยายามทำภารกิจหรือเกมให้เสร็จเมื่อได้เริ่มแล้ว", 14:"ฉันหงุดหงิดและโกรธง่ายเมื่อสิ่งต่างๆ ไม่เป็นไปตามแผน", 15:"ฉันรู้สึกอยากรู้อยากเห็นมากเมื่อทำการทดลองทางวิทยาศาสตร์หรือสังเกตพืชและสัตว์", 16:"ฉันเต็มไปด้วยพลังงานในช่วงชั้นเรียนพลศึกษาหรือกิจกรรมกลางแจ้ง", 17:"แม้ว่าเพื่อนจะทำผิดพลาด ฉันก็ไม่โกรธและบอกพวกเขาว่าไม่เป็นไร", 18:"ฉันพยายามรักษาสัญญากับคุณครูหรือพ่อแม่เสมอ", 19:"ฉันกลัวมากกว่าคนอื่นเมื่อดูหนังผีหรือไปในที่มืด", 20:"ฉันมักจะจินตนาการว่าตัวเองเป็นตัวละครหลักในขณะที่อ่านหนังสือการ์ตูนหรือหนังสือนิทาน" }
        },
        'pt': {
            name: '🇧🇷 Português', 
            ui: { 
                title: "Rei do Teste de Personalidade Big5", lang1: "Idioma 1", lang2: "Idioma 2", none: "Nenhum", dashTitle: "Painel do Professor",
                wait: "Aguardando", entered: "Inserido", completed: "Concluído", avgPrefix: "Média:", avgSuffix: "participantes", noData: "Sem dados.",
                guide: "Guia", manage: "Alunos", indRes: "Resultados Ind.", allRes: "Resultados Gerais", backup: "Backup",
                add: "Adicionar", del: "Excluir", phName: "Nome do aluno", msgAvatar: "※ O avatar é gerado automaticamente.",
                selTarget: "Selecione um aluno", copy: "Copiar", saveTxt: "Salvar TXT", msgAllSum: "Resumo das pontuações de todos.",
                bkDownTitle: "Baixar Dados", bkUpTitle: "Restaurar Dados", bkDelTitle: "Apagar Dados", btnBkDown: "Baixar JSON", btnBkUp: "Restaurar", btnBkDel: "Apagar Tudo",
                saveClose: "Salvar e Fechar", batchInput: "Entrada em Lote ➡️", batchSave: "Salvar Lote", cancel: "⬅️ Cancelar", selectScore: "Selecione uma pontuação (1-5).", qNum: " Pergunta",
                ext: "Extroversão", agr: "Amabilidade", con: "Conscienciosidade", neu: "Neuroticismo", ope: "Abertura", noResp: "Sem resposta", sumTitle: "Resumo", detailTitle: "Detalhes",
                nameCol: "Nome", noCol: "Nº", traitCol: "Traço", qCol: "Pergunta", scoreCol: "Pts", scorePt: "pt",
                alertNoSt: "Selecione o aluno.", alertNoData: "Sem dados.", alertDel: "Excluir aluno?", alertInit: "Apagar todos os dados?", alertCopyOk: "Copiado.", alertBkOk: "Restauração bem-sucedida.",
                gTitle: "📌 Guia de Uso", g1: "Registro: Adicione nomes em [Alunos].", g2: "Idioma: Configure no menu superior.", g3: "Teste: Clique no aluno para avaliar.", g4: "Resultados: Exporte para TXT.",
                warnTitle: "⚠️ Aviso de Armazenamento de Dados",
                warnDesc: "Os dados são salvos apenas no seu navegador. Eles podem ser apagados em computadores escolares. Faça um backup antes de fechar o navegador.",
                backupPath: "(💾 Como salvar: Clique em ⚙️ no topo direito -> Backup -> Baixar JSON)",
                goHome: "Escolher outro gioco kingsmath.com"
            },
            q: { 1:"O que mais gosto é de brincar com meus amigos na hora do recreio.", 2:"Empresto com prazer uma borracha ou lápis se um amigo pedir.", 3:"Termino minhas tarefas escolares a tempo, sem adiá-las.", 4:"Meu coração bate muito rápido e me sinto muito nervoso ao fazer uma apresentação ou prova.", 5:"Gosto de expressar minhas próprias ideias criativas durante as aulas de arte ou redação.", 6:"Posso ser o primeiro a cumprimentar um amigo que nunca conheci antes.", 7:"Quando um amigo chora porque está triste, meu coração dói também e quero confortá-lo.", 8:"Mantenho sempre minha mesa e o interior do meu armário limpos e organizados.", 9:"Às vezes me sinto muito magoado e triste mesmo com uma piada de um amigo.", 10:"É emocionante aprender um novo jogo de tabuleiro ou regras que nunca tentei antes.", 11:"Costumo tomar a iniciativa e guiar meus amigos durante as atividades em grupo.", 12:"Durante as atividades em grupo, escuto bem as opiniões dos meus amigos em vez de insistir apenas nas minhas.", 13:"Tento terminar uma tarefa ou jogo depois de começar.", 14:"Fico irritado e zangado facilmente quando as coisas não saem como planejei.", 15:"Sinto muita curiosidade ao fazer experimentos científicos ou observar plantas e animais.", 16:"Estou cheio de energia durante as aulas de educação física ou atividades ao ar livre.", 17:"Mesmo se um amigo cometer um erro, não fico com raiva e digo que está tudo bem.", 18:"Sempre tento cumprir as promessas feitas aos meus professores ou pais.", 19:"Sinto mais medo do que os outros ao assistir a um filme assustador ou ir a um lugar escuro.", 20:"Costumo me imaginar como o personagem principal enquanto leio histórias em quadrinhos ou livros de histórias." }
        },
        'hi': {
            name: '🇮🇳 हिन्दी', 
            ui: { 
                title: "Big5 व्यक्तित्व परीक्षण किंग", lang1: "भाषा 1", lang2: "भाषा 2", none: "कोई नहीं", dashTitle: "शिक्षक डैशबोर्ड",
                wait: "प्रतीक्षारत", entered: "दर्ज किया गया", completed: "पूर्ण", avgPrefix: "औसत:", avgSuffix: "प्रतिभागी", noData: "कोई डेटा नहीं।",
                guide: "मार्गदर्शक", manage: "छात्र", indRes: "व्यक्तिगत परिणाम", allRes: "सभी परिणाम", backup: "बैकअप",
                add: "जोड़ें", del: "हटाएं", phName: "नाम दर्ज करें", msgAvatar: "※ अवतार अपने आप बनते हैं।",
                selTarget: "छात्र चुनें", copy: "कॉपी", saveTxt: "TXT सहेजें", msgAllSum: "सभी छात्रों के स्कोर का सारांश।",
                bkDownTitle: "डेटा डाउनलोड", bkUpTitle: "डेटा पुनर्स्थापित", bkDelTitle: "डेटा हटाएं", btnBkDown: "JSON डाउनलोड", btnBkUp: "पुनर्स्थापित", btnBkDel: "सब हटाएं",
                saveClose: "सहेजें और बंद करें", batchInput: "बैच इनपुट ➡️", batchSave: "सब सहेजें", cancel: "⬅️ रद्द करें", selectScore: "स्कोर चुनें (1-5)।", qNum: " प्रश्न",
                ext: "बहिर्मुखता", agr: "सहमति", con: "कर्तव्यनिष्ठा", neu: "मनोविक्षुब्धता", ope: "खुलापन", noResp: "कोई जवाब नहीं", sumTitle: "स्कोर सारांश", detailTitle: "विवरण",
                nameCol: "नाम", noCol: "क्र.", traitCol: "गुण", qCol: "प्रश्न", scoreCol: "स्कोर", scorePt: "अंक",
                alertNoSt: "छात्र चुनें।", alertNoData: "कोई डेटा नहीं।", alertDel: "छात्र हटाएं?", alertInit: "सभी डेटा हटाएं?", alertCopyOk: "कॉपी हो गया।", alertBkOk: "सफलतापूर्वक पुनर्स्थापित।",
                gTitle: "📌 उपयोग गाइड", g1: "पंजीकरण: [छात्र] में नाम जोड़ें।", g2: "भाषा: ऊपर दाईं ओर सेट करें।", g3: "टेस्ट: स्कोर के लिए छात्र पर क्लिक करें।", g4: "परिणाम: TXT में निर्यात करें।",
                warnTitle: "⚠️ डेटा भंडारण सूचना और सावधानियां",
                warnDesc: "डेटा केवल आपके ब्राउज़र में सहेजा जाता है। स्कूल के कंप्यूटरों पर यह डिलीट हो सकता है। कृपया ब्राउज़र बंद करने से पहले बैकअप ले लें।",
                backupPath: "(💾 कैसे सहेजें: ऊपर दाईं ओर ⚙️ पर क्लिक करें -> बैकअप -> JSON डाउनलोड)",
                goHome: "दूसरा गेम चुनें kingsmath.com"
            },
            q: { 1:"मुझे ब्रेक के दौरान दोस्तों के साथ खेलना सबसे अच्छा लगता है।", 2:"अगर कोई दोस्त मांगता है तो मैं खुशी-खुशी इरेज़र या पेंसिल दे देता हूँ।", 3:"मैं बिना टालमटोल किए अपना स्कूल का होमवर्क समय पर पूरा करता हूँ।", 4:"प्रेजेंटेशन देते या परीक्षा देते समय मेरा दिल बहुत तेज़ी से धड़कता है और मैं बहुत घबरा जाता हूँ।", 5:"मुझे कला या लेखन की कक्षा में अपने नए रचनात्मक विचार व्यक्त करने में मज़ा आता है।", 6:"मैं पहली बार मिलने वाले दोस्त को भी सबसे पहले खुशी से नमस्ते कर सकता हूँ।", 7:"जब कोई दोस्त दुखी होकर रोता है, तो मुझे भी बुरा लगता है और मैं उसे सांत्वना देना चाहता हूँ।", 8:"मैं हमेशा अपनी डेस्क और लॉकर के अंदर की जगह को साफ और व्यवस्थित रखता हूँ।", 9:"कभी-कभी मैं दोस्त के मज़ाक पर भी बहुत आहत और दुखी महसूस करता हूँ।", 10:"कोई नया बोर्ड गेम या नियम सीखना रोमांचक होता है जिसे मैंने पहले कभी नहीं आजमाया।", 11:"मैं समूह गतिविधियों के दौरान पहल करने और अपने दोस्तों का मार्गदर्शन करने की प्रवृत्ति रखता हूँ।", 12:"समूह गतिविधियों के दौरान, मैं केवल अपनी बात पर अड़े रहने के बजाय अपने दोस्तों की राय अच्छी तरह सुनता हूँ।", 13:"एक बार शुरू करने के बाद मैं किसी काम या खेल को पूरा करने की कोशिश करता हूँ।", 14:"जब चीजें मेरी योजना के अनुसार नहीं होती हैं तो मैं आसानी से चिढ़ जाता हूँ और क्रोधित हो जाता हूँ।", 15:"विज्ञान के प्रयोग करते समय या पौधों और जानवरों को देखते समय मुझे बहुत उत्सुकता महसूस होती है।", 16:"पीई क्लास या बाहरी गतिविधियों के दौरान मैं ऊर्जा से भरा रहता हूँ।", 17:"अगर कोई दोस्त गलती भी कर देता है, तो मैं गुस्सा नहीं करता और कहता हूँ कि कोई बात नहीं।", 18:"मैं हमेशा अपने शिक्षकों या माता-पिता से किए गए वादों को निभाने की कोशिश करता हूँ।", 19:"डरावनी फिल्म देखते समय या अंधेरी जगह पर जाते समय मुझे दूसरों की तुलना में अधिक डर लगता है।", 20:"कॉमिक बुक या कहानी की किताबें पढ़ते समय मैं अक्सर खुद को मुख्य पात्र के रूप में कल्पना करता हूँ।" }
        },
        'bn': {
            name: '🇧🇩 বাংলা', 
            ui: { 
                title: "বিগ 5 ব্যক্তিত্ব পরীক্ষা রাজা", lang1: "ভাষা 1", lang2: "ভাষা 2", none: "কোনটি নয়", dashTitle: "শিক্ষক ড্যাশবোর্ড",
                wait: "অপেক্ষমান", entered: "প্রবেশ করানো", completed: "সম্পন্ন", avgPrefix: "গড়:", avgSuffix: "অংশগ্রহণকারী", noData: "কোন ডেটা নেই।",
                guide: "গাইড", manage: "ছাত্র", indRes: "ব্যক্তিগত ফলাফল", allRes: "সমস্ত ফলাফল", backup: "ব্যাকআপ",
                add: "যোগ করুন", del: "মুছুন", phName: "নাম লিখুন", msgAvatar: "※ অবতার স্বয়ংক্রিয়ভাবে তৈরি হয়।",
                selTarget: "ছাত্র নির্বাচন করুন", copy: "কপি", saveTxt: "TXT সংরক্ষণ", msgAllSum: "সবার স্কোরের সারাংশ।",
                bkDownTitle: "ডেটা ডাউনলোড", bkUpTitle: "পুনরুদ্ধার", bkDelTitle: "ডেটা মুছুন", btnBkDown: "JSON ডাউনলোড", btnBkUp: "পুনরুদ্ধার", btnBkDel: "সব মুছুন",
                saveClose: "সংরক্ষণ ও বন্ধ", batchInput: "ব্যাচ ইনপুট ➡️", batchSave: "সব সংরক্ষণ", cancel: "⬅️ বাতিল", selectScore: "স্কোর নির্বাচন করুন (1-5)।", qNum: " প্রশ্ন",
                ext: "বহির্মুখিতা", agr: "সম্মততা", con: "বিবেকবানতা", neu: "স্নায়বিকতা", ope: "উন্মুক্ততা", noResp: "কোন উত্তর নেই", sumTitle: "স্কোর সারাংশ", detailTitle: "বিস্তারিত",
                nameCol: "নাম", noCol: "নং", traitCol: "বৈশিষ্ট্য", qCol: "প্রশ্ন", scoreCol: "স্কোর", scorePt: "পয়েন্ট",
                alertNoSt: "ছাত্র নির্বাচন করুন।", alertNoData: "কোন ডেটা নেই।", alertDel: "ছাত্র মুছবেন?", alertInit: "সব ডেটা মুছবেন?", alertCopyOk: "কপি হয়েছে।", alertBkOk: "সফলভাবে পুনরুদ্ধার করা হয়েছে।",
                gTitle: "📌 নির্দেশিকা", g1: "নিবন্ধন: [ছাত্র] এ নাম যোগ করুন।", g2: "ভাষা: উপরে ডানদিকে সেট করুন।", g3: "পরীক্ষা: স্কোরের জন্য ছাত্রে ক্লিক করুন।", g4: "ফলাফল: TXT এ রপ্তানি করুন।",
                warnTitle: "⚠️ ডেটা স্টোরেজ নোটিশ এবং সতর্কতা",
                warnDesc: "ডেটা শুধুমাত্র আপনার ব্রাউজারে সংরক্ষিত হয়। স্কুলের কম্পিউটারে এটি মুছে যেতে পারে। ব্রাউজার বন্ধ করার আগে দয়া করে ব্যাকআপ নিন।",
                backupPath: "(💾 কীভাবে সংরক্ষণ করবেন: উপরে ডানদিকে ⚙️ ক্লিক করুন -> ব্যাকআপ -> JSON ডাউনলোড)",
                goHome: "অন্য গেম বেছে নিন kingsmath.com"
            },
            q: { 1:"আমি বিরতির সময় বন্ধুদের সাথে খেলতে সবচেয়ে বেশি পছন্দ করি।", 2:"কোন বন্ধু চাইলে আমি আনন্দের সাথে ইরেজার বা পেন্সিল ধার দিই।", 3:"আমি দেরি না করে সময়মতো স্কুলের হোমওয়ার্ক শেষ করি।", 4:"উপস্থাপনা বা পরীক্ষা দেওয়ার সময় আমার বুক খুব দ্রুত স্পন্দিত হয় এবং আমি খুব নার্ভাস বোধ করি।", 5:"আমি শিল্প বা লেখার ক্লাসে আমার নিজস্ব সৃজনশীল ধারণা প্রকাশ করতে উপভোগ করি।", 6:"আমি এমন একজন বন্ধুকে প্রথমে আনন্দের সাথে অভ্যর্থনা জানাতে পারি যার সাথে আগে কখনো দেখা হয়নি।", 7:"যখন কোনো বন্ধু মন খারাপ করে কাঁদে, তখন আমারও কষ্ট হয় এবং আমি তাকে সান্ত্বনা দিতে চাই।", 8:"আমি সবসময় আমার ডেস্ক এবং লকারের ভেতরটা পরিষ্কার ও গুছিয়ে রাখি।", 9:"কখনও কখনও বন্ধুর ঠাট্টায়ও আমি খুব আঘাত পাই এবং দুঃখ বোধ করি।", 10:"আগে কখনো চেষ্টা করিনি এমন নতুন বোর্ড গেম বা নিয়ম শেখা উত্তেজনাপূর্ণ।", 11:"দলগত কার্যকলাপে আমি নেতৃত্ব দিতে এবং বন্ধুদের গাইড করতে পছন্দ করি।", 12:"দলগত কার্যকলাপে, আমি কেবল আমার নিজের মতামতের উপর জোর না দিয়ে বন্ধুদের মতামত ভালভাবে শুনি।", 13:"একবার শুরু করলে আমি কোনো কাজ বা খেলা শেষ করার চেষ্টা করি।", 14:"যখন জিনিসগুলি আমার পরিকল্পনা অনুযায়ী হয় ছল তখন আমি সহজেই বিরক্ত এবং রাগান্বিত হই।", 15:"বিজ্ঞান পরীক্ষা করার সময় বা উদ্ভিদ ও প্রাণী পর্যবেক্ষণ করার সময় আমি অনেক কৌতূহল বোধ করি।", 16:"পিই ক্লাস বা বাইরের ক্রিয়াকলাপের সময় আমি শক্তিতে ভরপুর থাকি।", 17:"এমনকি যদি কোনো বন্ধু ভুল করে, আমি রাগ করি না এবং বলি যে ঠিক আছে।", 18:"আমি সবসময় আমার শিক্ষক বা পিতামাতার কাছে দেওয়া প্রতিশ্রুতি রাখার চেষ্টা করি।", 19:"ভীতিকর সিনেমা দেখার সময় বা অন্ধকার জায়গায় যাওয়ার সময় আমি অন্যদের চেয়ে বেশি ভয় পাই।", 20:"কমিক বই বা গল্পের বই পড়ার সময় আমি প্রায়শই নিজেকে প্রধান চরিত্র হিসেবে কল্পনা করি।" }
        },
        'tr': {
            name: '🇹🇷 Türkçe', 
            ui: { 
                title: "Big5 Kişilik Testi Kralı", lang1: "Birincil Dil", lang2: "İkincil Dil", none: "Yok", dashTitle: "Öğretmen Paneli",
                wait: "Bekliyor", entered: "Girildi", completed: "Tamamlandı", avgPrefix: "Ortalama:", avgSuffix: "katılımcı", noData: "Veri yok.",
                guide: "Rehber", manage: "Öğrenciler", indRes: "Bireysel Sonuçlar", allRes: "Tüm Sonuçlar", backup: "Yedekleme",
                add: "Ekle", del: "Sil", phName: "İsim girin", msgAvatar: "※ Avatar otomatik oluşturulur.",
                selTarget: "Öğrenci seçin", copy: "Kopyala", saveTxt: "TXT Kaydet", msgAllSum: "Tüm öğrencilerin puan özeti.",
                bkDownTitle: "Veri İndir", bkUpTitle: "Geri Yükle", bkDelTitle: "Veri Sil", btnBkDown: "JSON İndir", btnBkUp: "Geri Yükle", btnBkDel: "Tümünü Sil",
                saveClose: "Kaydet ve Kapat", batchInput: "Toplu Giriş ➡️", batchSave: "Tümünü Kaydet", cancel: "⬅️ İptal", selectScore: "Puan seçin (1-5).", qNum: " Soru",
                ext: "Dışadönüklük", agr: "Uyumluluk", con: "Sorumluluk", neu: "Duygusal Denge", ope: "Açıklık", noResp: "Cevap yok", sumTitle: "Puan Özeti", detailTitle: "Detaylar",
                nameCol: "İsim", noCol: "No", traitCol: "Özellik", qCol: "Soru", scoreCol: "Puan", scorePt: "pt",
                alertNoSt: "Öğrenci seçin.", alertNoData: "Veri yok.", alertDel: "Öğrenci silinsin mi?", alertInit: "Tüm veriler silinsin mi?", alertCopyOk: "Kopyalandı.", alertBkOk: "Başarıyla geri yüklendi.",
                gTitle: "📌 Kullanım Rehberi", g1: "Kayıt: [Öğrenciler] sekmesinde isim ekleyin.", g2: "Dil: Sağ üstten dil ayarlayın.", g3: "Test: Puan için öğrenciye tıklayın.", g4: "Sonuçlar: Sonuçları TXT'ye dışa aktarın.",
                warnTitle: "⚠️ Veri Depolama Bildirimi ve Önlemler",
                warnDesc: "Veriler yalnızca tarayıcınızda saklanır. Okul bilgisayarlarında silinebilir. Lütfen tarayıcıyı kapatmadan önce yedek alın.",
                backupPath: "(💾 Nasıl kaydedilir: Sağ üstteki ⚙️ simgesine tıklayın -> Yedekleme -> JSON İndir)",
                goHome: "Başka bir oyun seç kingsmath.com"
            },
            q: { 1:"Teneffüste arkadaşlarımla oynamaktan en çok keyif alıyorum.", 2:"Bir arkadaşım isterse seve seve silgi veya kalem ödünç veririm.", 3:"Okul ödevlerimi ertelemeden zamanında bitiririm.", 4:"Sunum yaparken veya sınava girerken kalbim çok hızlı atıyor ve çok gergin oluyorum.", 5:"Resim veya yazı derslerinde kendi yaratıcı fikirlerimi ifade etmekten keyif alıyorum.", 6:"Daha önce hiç tanışmadığım bir arkadaşıma ilk selam veren ben olabilirim.", 7:"Bir arkadaşım üzülüp ağladığında benim de canım yanıyor ve onu teselli etmek istiyorum.", 8:"Masamı ve dolabımın içini her zaman temiz ve düzenli tutarım.", 9:"Bazen bir arkadaşımın şakasına bile çok kırılır ve üzülürüm.", 10:"Daha önce hiç denemediğim yeni bir kutu oyunu veya kurallar öğrenmek heyecan verici.", 11:"Grup etkinlikleri sırasında inisiyatif alma ve arkadaşlarıma rehberlik etme eğilimindeyim.", 12:"Grup etkinlikleri sırasında sadece kendi fikirlerimde ısrar etmek yerine arkadaşlarımın görüşlerini de iyi dinlerim.", 13:"Bir görevi veya oyunu bir kez başladığımda bitirmeye çalışırım.", 14:"İşler planladığım gibi gitmediğinde kolayca sinirlenir ve öfkelenirim.", 15:"Fen deneyleri yaparken veya bitki ve hayvanları gözlemlerken çok meraklıyım.", 16:"Beden eğitimi dersinde veya açık hava etkinliklerinde enerji doluyum.", 17:"Bir arkadaşım hata yapsa bile kızmam ve ona sorun olmadığını söylerim.", 18:"Öğretmenlerime veya ebeveynlerime verdiğim sözleri her zaman tutmaya çalışırım.", 19:"Korkunç bir film izlerken veya karanlık bir yere giderken diğerlerinden daha çok korkarım.", 20:"Çizgi roman veya hikaye kitapları okurken kendimi sık sık ana karakter olarak hayal ederim." }
        },
        'de': {
            name: '🇩🇪 Deutsch', 
            ui: { 
                title: "Big5 Persönlichkeitstest König", lang1: "Sprache 1", lang2: "Sprache 2", none: "Keine", dashTitle: "Lehrer-Dashboard",
                wait: "Warten", entered: "Eingegeben", completed: "Abgeschlossen", avgPrefix: "Durchschnitt:", avgSuffix: "Teilnehmer", noData: "Keine Daten.",
                guide: "Anleitung", manage: "Studenten", indRes: "Ind. Ergebnisse", allRes: "Alle Ergebnisse", backup: "Backup",
                add: "Hinzufügen", del: "Löschen", phName: "Name eingeben", msgAvatar: "※ Avatar wird automatisch generiert.",
                selTarget: "Student auswählen", copy: "Kopieren", saveTxt: "TXT speichern", msgAllSum: "Punktzusammenfassung für alle.",
                bkDownTitle: "Daten herunterladen", bkUpTitle: "Daten wiederherstellen", bkDelTitle: "Daten löschen", btnBkDown: "JSON herunterladen", btnBkUp: "Wiederherstellen", btnBkDel: "Alles löschen",
                saveClose: "Speichern & Schließen", batchInput: "Stapeleingabe ➡️", batchSave: "Alle speichern", cancel: "⬅️ Abbrechen", selectScore: "Punktzahl wählen (1-5).", qNum: " Frage",
                ext: "Extraversion", agr: "Verträglichkeit", con: "Gewissenhaftigkeit", neu: "Neurotizismus", ope: "Offenheit", noResp: "Keine Antwort", sumTitle: "Punktzusammenfassung", detailTitle: "Details",
                nameCol: "Name", noCol: "Nr.", traitCol: "Merkmal", qCol: "Frage", scoreCol: "Punkte", scorePt: "pt",
                alertNoSt: "Student auswählen.", alertNoData: "Keine Daten.", alertDel: "Student löschen?", alertInit: "Alle Daten löschen?", alertCopyOk: "Kopiert.", alertBkOk: "Erfolgreich wiederhergestellt.",
                gTitle: "📌 Anleitung", g1: "Registrierung: Namen in [Studenten] hinzufügen.", g2: "Sprache: Sprache oben rechts einstellen.", g3: "Test: Auf Student klicken für Punkte.", g4: "Ergebnisse: Als TXT exportieren.",
                warnTitle: "⚠️ Datenspeicherung & Vorsichtsmaßnahmen",
                warnDesc: "Daten werden nur in Ihrem Browser gespeichert. Auf Schul-PCs können sie gelöscht werden. Bitte erstellen Sie vor dem Schließen ein Backup.",
                backupPath: "(💾 So speichern Sie: Klicken Sie oben rechts auf ⚙️ -> Backup -> JSON herunterladen)",
                goHome: "Anderes Spiel wählen kingsmath.com"
            },
            q: { 1:"Am liebsten spiele ich in der Pause mit meinen Freunden.", 2:"Ich leihe gerne einen Radiergummi oder Bleistift aus, wenn ein Freund fragt.", 3:"Ich erledige meine Hausaufgaben pünktlich, ohne sie aufzuschieben.", 4:"Mein Herz schlägt sehr schnell und ich bin sehr nervös, wenn ich etwas präsentiere oder eine Prüfung ablege.", 5:"Ich genieße es, im Kunst- oder Schreibunterricht meine eigenen kreativen Ideen auszudrücken.", 6:"Ich kann der Erste sein, der einen Freund freudig begrüßt, den ich noch nie zuvor getroffen habe.", 7:"Wenn ein Freund weint, weil er traurig ist, tut mir das auch weh und ich möchte ihn trösten.", 8:"Ich halte meinen Schreibtisch und das Innere meines Spinds immer sauber und organisiert.", 9:"Manchmal fühle ich mich selbst durch einen Witz eines Freundes sehr verletzt und traurig.", 10:"Es ist aufregend, ein neues Brettspiel oder Regeln zu lernen, die ich noch nie ausprobiert habe.", 11:"Ich neige dazu, bei Gruppenaktivitäten die Initiative zu ergreifen und meine Freunde zu führen.", 12:"Bei Gruppenaktivitäten höre ich mir die Meinungen meiner Freunde gut an, anstatt nur auf meinen eigenen zu beharren.", 13:"Ich versuche, eine Aufgabe oder ein Spiel zu beenden, wenn ich einmal damit angefangen habe.", 14:"Ich werde leicht genervt und wütend, wenn die Dinge nicht so laufen, wie ich es geplant habe.", 15:"Ich bin sehr neugierig, wenn ich wissenschaftliche Experimente durchführe oder Pflanzen und Tiere beobachte.", 16:"Im Sportunterricht oder bei Aktivitäten im Freien bin ich voller Energie.", 17:"Auch wenn ein Freund einen Fehler macht, werde ich nicht wütend und sage ihm, dass es in Ordnung ist.", 18:"Ich versuche immer, Versprechen einzuhalten, die ich Lehrern oder Eltern gegeben habe.", 19:"Ich habe mehr Angst als andere, wenn ich einen gruseligen Film sehe oder an einen dunklen Ort gehe.", 20:"Ich stelle mir oft vor, die Hauptfigur zu sein, während ich Comics oder Märchen lese." }
        },
        'it': {
            name: '🇮🇹 Italiano', 
            ui: { 
                title: "Re del Test di Personalità Big5", lang1: "Lingua 1", lang2: "Lingua 2", none: "Nessuno", dashTitle: "Pannello Insegnante",
                wait: "In attesa", entered: "Inserito", completed: "Completato", avgPrefix: "Media:", avgSuffix: "partecipanti", noData: "Nessun dato.",
                guide: "Guida", manage: "Studenti", indRes: "Risultati Ind.", allRes: "Risultati Totali", backup: "Backup",
                add: "Aggiungi", del: "Elimina", phName: "Nome studente", msgAvatar: "※ L'avatar è generato automaticamente.",
                selTarget: "Seleziona studente", copy: "Copia", saveTxt: "Salva TXT", msgAllSum: "Riepilogo punteggi di tutti.",
                bkDownTitle: "Scarica Dati", bkUpTitle: "Ripristina", bkDelTitle: "Elimina Dati", btnBkDown: "Scarica JSON", btnBkUp: "Ripristina", btnBkDel: "Elimina Tutto",
                saveClose: "Salva e Chiudi", batchInput: "Inserimento in Blocco ➡️", batchSave: "Salva Tutto", cancel: "⬅️ Annulla", selectScore: "Seleziona punteggio (1-5).", qNum: " Domanda",
                ext: "Estroversione", agr: "Amicalità", con: "Coscienziosità", neu: "Nevroticismo", ope: "Apertura", noResp: "Nessuna risposta", sumTitle: "Riepilogo Punteggi", detailTitle: "Dettagli",
                nameCol: "Nome", noCol: "Nº", traitCol: "Tratto", qCol: "Domanda", scoreCol: "Punti", scorePt: "pt",
                alertNoSt: "Seleziona studente.", alertNoData: "Nessun dato.", alertDel: "Eliminare studente?", alertInit: "Eliminare tutti i dati?", alertCopyOk: "Copiado.", alertBkOk: "Ripristinato con successo.",
                gTitle: "📌 Guida", g1: "Registrazione: Aggiungi nomi in [Studenti].", g2: "Lingua: Imposta lingua in alto a destra.", g3: "Test: Clicca sullo studente per valutare.", g4: "Risultati: Esporta in TXT.",
                warnTitle: "⚠️ Avviso di archiviazione dati",
                warnDesc: "I dati sono salvati solo nel tuo browser. Potrebbero essere cancellati sui PC scolastici. Esegui un backup prima di chiudere il browser.",
                backupPath: "(💾 Come salvare: Clicca su ⚙️ in alto a destra -> Backup -> Scarica JSON)",
                goHome: "Scegli un altro gioco kingsmath.com"
            },
            q: { 1:"Quello che mi piace di più è giocare con i miei amici durante la ricreazione.", 2:"Presto volentieri una gomma o una matita se un amico lo chiede.", 3:"Finisco i compiti di scuola in tempo senza rimandarli.", 4:"Il mio cuore batte molto forte e mi sento molto nervoso durante una presentazione o un esame.", 5:"Mi piace esprimere le mie idee creative durante le lezioni di arte o scrittura.", 6:"Posso essere il primo a salutare con gioia un amico che non ho mai incontrato prima.", 7:"Quando un amico piange perché è triste, anche il mio cuore fa male e voglio consolarlo.", 8:"Tengo sempre la mia scrivania e l'interno del mio armadietto puliti e in ordine.", 9:"A volte mi sento molto ferito e triste anche per uno scherzo di un amico.", 10:"È emozionante imparare un nuovo gioco da tavolo o regole che non ho mai provato prima.", 11:"Tendo a prendere l'iniziativa e guidare i miei amici durante le attività di gruppo.", 12:"Durante le attività di gruppo, ascolto bene le opinioni dei miei amici invece di insistere solo sulle mie.", 13:"Cerco di finire un compito o un gioco una volta che l'ho iniziato.", 14:"Mi infastidisco e mi arrabbio facilmente quando le cose non vanno come avevo programmato.", 15:"Provo molta curiosità quando faccio esperimenti scientifici o osservo piante e animali.", 16:"Sono pieno di energia durante l'ora di educazione fisica o le attività all'aperto.", 17:"Anche se un amico commette un errore, non mi arrabbio e gli dico che va tutto bene.", 18:"Cerco sempre di mantenere le promesse fatte ai miei insegnanti o genitori.", 19:"Ho più paura degli altri quando guardo un film spaventoso o vado in un posto buio.", 20:"Spesso mi immagino di essere il protagonista mentre leggo fumetti o libri di fiabe." }
        }
    };

    const TOTAL_QUESTIONS = 20;
    const traitsMap = {
        "ext": [1, 6, 11, 16], "agr": [2, 7, 12, 17], "con": [3, 8, 13, 18], "neu": [4, 9, 14, 19], "ope": [5, 10, 15, 20]
    };

    // --- State Variables ---
    let students = [];
    let surveyData = {};
    let currentQuestionId = 1;
    let activeStudent = "";
    let selectedScore = null;
    let bulkAnswers = {};
    let myRadarChart = null;
    let currentLang1 = 'ko';
    let currentLang2 = 'none';

    // --- Init ---
    window.onload = () => {
        initLanguages();
        loadFromCache();
        updateGlobalUI();
        renderButtons();
        selectQuestion(1);
    };

    function initLanguages() {
        const sel1 = document.getElementById('lang1');
        const sel2 = document.getElementById('lang2');
        for (const [code, data] of Object.entries(dict)) {
            sel1.add(new Option(data.name, code));
            sel2.add(new Option(data.name, code));
        }
        sel1.value = 'ko';
        sel2.value = 'none';
    }

    function changeLanguage() {
        currentLang1 = document.getElementById('lang1').value;
        currentLang2 = document.getElementById('lang2').value;
        
        updateGlobalUI();
        selectQuestion(currentQuestionId);
        renderStudents();
        updateAverageDisplay(currentQuestionId);
        
        if(document.getElementById('student-input-modal').classList.contains('show')) {
            updateModalUI();
        }
        
        updateStudentSelectbox();
        renderIndividualResult(); 
        renderAllResultTable();
    }

    // --- Translation Helpers ---
    function getQText(lang, qId) { return dict[lang].q[qId]; }
    function getUIText(lang, key) { return dict[lang].ui[key]; }

    function updateGlobalUI() {
        document.getElementById("ui-title").innerText = `📝 ${getUIText(currentLang1, 'title')}`;
        document.getElementById("ui-label-lang1").innerText = getUIText(currentLang1, 'lang1');
        document.getElementById("ui-label-lang2").innerText = getUIText(currentLang1, 'lang2');
        document.getElementById("ui-opt-none").innerText = getUIText(currentLang1, 'none');

        document.getElementById("ui-dash-title").innerText = getUIText(currentLang1, 'dashTitle');
        document.getElementById("ui-tab-guide").innerHTML = `📖 ${getUIText(currentLang1, 'guide')}`;
        document.getElementById("ui-tab-manage").innerHTML = `👥 ${getUIText(currentLang1, 'manage')}`;
        document.getElementById("ui-tab-ind").innerHTML = `📊 ${getUIText(currentLang1, 'indRes')}`;
        document.getElementById("ui-tab-all").innerHTML = `📈 ${getUIText(currentLang1, 'allRes')}`;
        document.getElementById("ui-tab-backup").innerHTML = `💾 ${getUIText(currentLang1, 'backup')}`;

        document.getElementById("guide-content-area").innerHTML = `
            <h3 style="color: #3B82F6; margin-bottom: 10px;">${getUIText(currentLang1, 'gTitle')}</h3>
            <ol style="color: #E0E0E0; line-height: 1.6; padding-left: 20px; font-size: 0.95em;">
                <li style="margin-bottom: 8px;">${getUIText(currentLang1, 'g1')}</li>
                <li style="margin-bottom: 8px;">${getUIText(currentLang1, 'g2')}</li>
                <li style="margin-bottom: 8px;">${getUIText(currentLang1, 'g3')}</li>
                <li style="margin-bottom: 8px;">${getUIText(currentLang1, 'g4')}</li>
            </ol>
        `;

        document.getElementById("new-student-input").placeholder = getUIText(currentLang1, 'phName');
        document.getElementById("btn-add-st").innerText = getUIText(currentLang1, 'add');
        document.getElementById("ui-msg-avatar").innerText = getUIText(currentLang1, 'msgAvatar');
        
        document.getElementById("btn-copy").innerHTML = `📋 ${getUIText(currentLang1, 'copy')}`;
        document.getElementById("btn-save-txt-ind").innerHTML = `📄 ${getUIText(currentLang1, 'saveTxt')}`;
        document.getElementById("ui-msg-allsum").innerText = getUIText(currentLang1, 'msgAllSum');
        document.getElementById("btn-save-txt-all").innerHTML = `📑 ${getUIText(currentLang1, 'saveTxt')}`;

        document.getElementById("ui-bk-down-title").innerText = getUIText(currentLang1, 'bkDownTitle');
        document.getElementById("ui-bk-up-title").innerText = getUIText(currentLang1, 'bkUpTitle');
        document.getElementById("ui-bk-del-title").innerText = getUIText(currentLang1, 'bkDelTitle');
        document.getElementById("btn-bk-down").innerText = getUIText(currentLang1, 'btnBkDown');
        document.getElementById("btn-bk-up").innerText = getUIText(currentLang1, 'btnBkUp');
        document.getElementById("btn-bk-del").innerText = getUIText(currentLang1, 'btnBkDel');

        // 하단 안내 문구 업데이트
        document.getElementById("ui-warn-title").innerText = getUIText(currentLang1, 'warnTitle');
        document.getElementById("ui-warn-desc").innerText = getUIText(currentLang1, 'warnDesc');
        document.getElementById("ui-backup-path").innerText = getUIText(currentLang1, 'backupPath');
        document.getElementById("ui-go-home").innerText = getUIText(currentLang1, 'goHome');

        renderButtons();
    }

    function loadFromCache() {
        const savedStudents = localStorage.getItem('bigFiveStudents');
        const savedData = localStorage.getItem('bigFiveSurveyData');
        students = savedStudents ? JSON.parse(savedStudents) : [];
        surveyData = savedData ? JSON.parse(savedData) : {};
    }

    function saveToCache() {
        localStorage.setItem('bigFiveStudents', JSON.stringify(students));
        localStorage.setItem('bigFiveSurveyData', JSON.stringify(surveyData));
    }

    // --- Main UI Rendering ---
    function renderButtons() {
        const container = document.getElementById("button-container");
        container.innerHTML = "";
        for(let i = 1; i <= TOTAL_QUESTIONS; i++) {
            const btn = document.createElement("button");
            btn.className = "question-btn";
            btn.id = "btn-" + i;
            btn.innerText = "Q " + i;
            if (students.length > 0) {
                const allAnswered = students.every(st => surveyData[st] && surveyData[st][i]);
                if (allAnswered) btn.classList.add('completed');
            }
            btn.onclick = () => selectQuestion(i);
            container.appendChild(btn);
        }
        const activeBtn = document.getElementById("btn-" + currentQuestionId);
        if(activeBtn) activeBtn.classList.add('active');
    }

    function selectQuestion(id) {
        currentQuestionId = id;
        document.querySelectorAll('.question-btn').forEach(b => b.classList.remove('active'));
        const btn = document.getElementById("btn-" + id);
        if(btn) btn.classList.add('active');
        
        document.getElementById("qd-lang1").innerHTML = `<span style="color:#A0A0A0; font-size:0.6em; vertical-align:super; margin-right:8px;">${id}.</span> ${getQText(currentLang1, id)}`;
        const qdLang2 = document.getElementById("qd-lang2");
        if(currentLang2 !== 'none' && currentLang2 !== currentLang1) {
            qdLang2.innerText = getQText(currentLang2, id);
            qdLang2.style.display = 'block';
        } else {
            qdLang2.style.display = 'none';
        }

        renderStudents();
        updateAverageDisplay(id);
    }

    function getAvatarUrl(seedName) {
        return `https://api.dicebear.com/9.x/thumbs/svg?seed=${encodeURIComponent(seedName)}&backgroundColor=transparent`;
    }

    function renderStudents() {
        const container = document.getElementById("student-list");
        container.innerHTML = ""; 
        students.forEach(student => {
            const card = document.createElement("div");
            card.className = "student-card";
            card.onclick = () => openStudentModal(student);

            const hasAnswered = surveyData[student] && surveyData[student][currentQuestionId];
            const isAllCompleted = surveyData[student] && Object.keys(surveyData[student]).length === TOTAL_QUESTIONS;

            let badgeText = getUIText(currentLang1, 'wait'); let badgeClass = "status-badge";
            if (isAllCompleted) { badgeText = getUIText(currentLang1, 'completed'); badgeClass = "status-badge completed"; } 
            else if (hasAnswered) { badgeText = getUIText(currentLang1, 'entered'); badgeClass = "status-badge completed"; }

            card.innerHTML = `<img src="${getAvatarUrl(student)}" alt="avatar" class="student-avatar" loading="lazy">
                <div class="student-name" title="${student}">${student}</div>
                <div class="${badgeClass}">${badgeText}</div>`;
            container.appendChild(card);
        });
    }

    function updateAverageDisplay(questionId) {
        const avgContainer = document.getElementById("average-display");
        let sum = 0, count = 0;
        students.forEach(student => {
            if (surveyData[student] && surveyData[student][questionId]) {
                sum += parseInt(surveyData[student][questionId]);
                count++;
            }
        });
        if (count > 0) {
            avgContainer.innerText = `${getUIText(currentLang1, 'avgPrefix')} ${(sum/count).toFixed(2)} (${count}/${students.length} ${getUIText(currentLang1, 'avgSuffix')})`;
        } else {
            avgContainer.innerText = getUIText(currentLang1, 'noData');
        }
    }

    // --- Modal Handling ---
    function openStudentModal(studentName) {
        activeStudent = studentName;
        selectedScore = null;
        
        document.getElementById("modal-student-name").innerHTML = `<img src="${getAvatarUrl(studentName)}" style="width:40px; height:40px; border-radius:50%; vertical-align:middle; margin-right:10px;">${studentName}`;
        
        const existingScore = surveyData[studentName] && surveyData[studentName][currentQuestionId];
        document.querySelectorAll('#single-input-view .scale-btn').forEach((btn, idx) => {
            btn.classList.remove('selected');
            if(existingScore && idx + 1 == existingScore) btn.classList.add('selected');
        });
        
        bulkAnswers = surveyData[studentName] ? {...surveyData[studentName]} : {};
        
        updateModalUI();

        document.getElementById("single-input-view").style.display = "block";
        document.getElementById("bulk-input-view").style.display = "none";
        document.getElementById("student-input-modal").classList.add("show");
    }

    function updateModalUI() {
        document.getElementById("mq-lang1").innerHTML = `<span style="color:#A0A0A0; font-size:0.6em;">${currentQuestionId}${getUIText(currentLang1, 'qNum')}</span><br><br>${getQText(currentLang1, currentQuestionId)}`;
        const mqLang2 = document.getElementById("mq-lang2");
        if(currentLang2 !== 'none' && currentLang2 !== currentLang1) {
            mqLang2.innerHTML = `${getQText(currentLang2, currentQuestionId)}`;
            mqLang2.style.display = 'block';
        } else {
            mqLang2.style.display = 'none';
        }

        const setBtnText = (id1, id2, key) => {
            document.getElementById(id1).innerText = getUIText(currentLang1, key);
            const el2 = document.getElementById(id2);
            if(currentLang2 !== 'none' && currentLang2 !== currentLang1) {
                el2.innerText = getUIText(currentLang2, key); el2.style.display = 'block';
            } else { el2.style.display = 'none'; }
        };

        setBtnText("ui-btn-save", "ui-btn-save-l2", "saveClose");
        setBtnText("ui-btn-batch", "ui-btn-batch-l2", "batchInput");
        setBtnText("ui-msg-score", "ui-msg-score-l2", "selectScore");
        setBtnText("ui-btn-cancel", "ui-btn-cancel-l2", "cancel");
        setBtnText("ui-btn-batch-save", "ui-btn-batch-save-l2", "batchSave");

        renderBulkQuestions();
    }

    function toggleBulkInputView() {
        const single = document.getElementById("single-input-view");
        const bulk = document.getElementById("bulk-input-view");
        if(single.style.display === "none") {
            single.style.display = "block"; bulk.style.display = "none";
        } else {
            single.style.display = "none"; bulk.style.display = "block";
        }
    }

    function selectScore(score) {
        selectedScore = score;
        const btns = document.getElementById("single-input-view").querySelectorAll('.scale-btn');
        btns.forEach(btn => btn.classList.remove('selected'));
        btns[score - 1].classList.add('selected');
    }

    function saveSingleData() {
        if (!selectedScore) {
            const activeBtn = document.querySelector('#single-input-view .scale-btn.selected');
            if(activeBtn) selectedScore = parseInt(activeBtn.innerText);
            else { return; }
        }
        if (!surveyData[activeStudent]) surveyData[activeStudent] = {};
        surveyData[activeStudent][currentQuestionId] = selectedScore;
        saveToCache(); closeModal('student-input-modal'); renderButtons(); selectQuestion(currentQuestionId);
    }

    function getTraitKeyForQuestion(qId) {
        for (const [key, qNums] of Object.entries(traitsMap)) {
            if (qNums.includes(qId)) return key;
        }
        return "";
    }

    function renderBulkQuestions() {
        const container = document.getElementById('bulk-questions-container');
        container.innerHTML = "";
        for(let i = 1; i <= TOTAL_QUESTIONS; i++) {
            let div = document.createElement('div');
            div.style.marginBottom = "15px"; div.style.borderBottom = "1px solid #333"; div.style.paddingBottom = "10px";
            
            let traitKey = getTraitKeyForQuestion(i);
            let html = `<div style="color:#FFF; margin-bottom:10px; word-break:keep-all;">
                            <span style="color:#3B82F6; font-size:0.8em; margin-right:5px;">[${getUIText(currentLang1, traitKey)}]</span>
                            <strong>${i}.</strong> ${getQText(currentLang1, i)}`;
            if(currentLang2 !== 'none' && currentLang2 !== currentLang1) {
                html += `<div class="lang2-text" style="margin-left:25px;">${getQText(currentLang2, i)}</div>`;
            }
            html += `</div><div class="scale-group" id="bulk-q-${i}" style="margin:0; justify-content:flex-start; gap:10px;">`;
            for(let s=1; s<=5; s++) {
                let isSel = bulkAnswers[i] == s ? "selected" : "";
                html += `<button class="scale-btn ${isSel}" style="width:40px; height:40px; font-size:1.1em;" onclick="setBulkScore(${i}, ${s})">${s}</button>`;
            }
            html += `</div>`;
            div.innerHTML = html;
            container.appendChild(div);
        }
    }

    function setBulkScore(qId, score) {
        bulkAnswers[qId] = score;
        const group = document.getElementById(`bulk-q-${qId}`);
        group.querySelectorAll('.scale-btn').forEach(b => b.classList.remove('selected'));
        group.querySelectorAll('.scale-btn')[score-1].classList.add('selected');
    }

    function saveBulkData() {
        if (!surveyData[activeStudent]) surveyData[activeStudent] = {};
        surveyData[activeStudent] = {...bulkAnswers};
        saveToCache();
        closeModal('student-input-modal'); renderButtons(); selectQuestion(currentQuestionId);
    }

    function closeModal(id) { document.getElementById(id).classList.remove("show"); }

    // --- Teacher Dashboard ---
    function openTeacherModal() {
        document.getElementById("teacher-modal").classList.add("show");
        renderManageList(); updateStudentSelectbox(); renderAllResultTable(); switchTab('tab-guide');
    }

    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        const activeBtn = document.querySelector(`.tab-btn[onclick="switchTab('${tabId}')"]`);
        if(activeBtn) activeBtn.classList.add('active');
        document.getElementById(tabId).classList.add('active');
        if (tabId === 'tab-result-ind') updateStudentSelectbox();
        if (tabId === 'tab-result-all') renderAllResultTable();
    }

    function renderManageList() {
        const list = document.getElementById("manage-student-list");
        list.innerHTML = "";
        students.forEach((student, index) => {
            const li = document.createElement("li"); li.className = "list-item";
            li.innerHTML = `<div style="display:flex; align-items:center;">
                    <img src="${getAvatarUrl(student)}" style="width:30px; height:30px; border-radius:50%; margin-right:10px;">
                    <span style="color: #FFF;">${student}</span>
                </div>
                <button class="btn-danger" style="padding: 5px 10px; border:none; border-radius:5px;" onclick="removeStudent(${index})">${getUIText(currentLang1, 'del')}</button>`;
            list.appendChild(li);
        });
    }

    function addStudent() {
        const input = document.getElementById("new-student-input");
        const newName = input.value.trim();
        if(!newName) return;
        if(students.includes(newName)) return;
        students.push(newName);
        saveToCache(); renderManageList(); input.value = ""; renderButtons(); renderStudents();
    }

    function removeStudent(index) {
        if(confirm(getUIText(currentLang1, 'alertDel'))) {
            delete surveyData[students[index]]; students.splice(index, 1);
            saveToCache(); renderManageList(); renderButtons(); renderStudents();
        }
    }

    function calculateTraitScores(dataObj) {
        const scores = {};
        for (const [key, qNums] of Object.entries(traitsMap)) {
            let sum = 0, count = 0;
            qNums.forEach(qNum => {
                if(dataObj[qNum]) { sum += parseInt(dataObj[qNum]); count++; }
            });
            scores[key] = count > 0 ? (sum / count).toFixed(1) : null;
        }
        return scores;
    }

    function updateStudentSelectbox() {
        const select = document.getElementById("student-select");
        select.innerHTML = `<option value="">${getUIText(currentLang1, 'selTarget')}</option>`;
        students.forEach(st => { select.innerHTML += `<option value="${st}">${st}</option>`; });
    }

    function renderIndividualResult() {
        const stName = document.getElementById("student-select").value;
        const area = document.getElementById("ind-result-area");
        if(!stName) { area.style.display = "none"; return; }
        const data = surveyData[stName];
        if(!data || Object.keys(data).length === 0) { alert(getUIText(currentLang1, 'alertNoData')); area.style.display = "none"; return; }

        area.style.display = "block";
        const scores = calculateTraitScores(data);
        
        let html = `<h3><img src="${getAvatarUrl(stName)}" style="width:30px; height:30px; border-radius:50%; vertical-align:middle; margin-right:8px;">${stName} - ${getUIText(currentLang1, 'sumTitle')}</h3><div class="mt-2">`;
        ['ext', 'agr', 'con', 'neu', 'ope'].forEach(tKey => { 
            html += `<span style="display:inline-block; background:#f0f0f0; padding:10px; border-radius:8px; margin:5px; text-align:center; min-width:80px;">${getUIText(currentLang1, tKey)}: <br><span style="color:#3B82F6; font-size:1.2em; font-weight:bold;">${scores[tKey] || '-'}</span></span>`; 
        });
        html += `</div>`;
        document.getElementById("ind-scores-display").innerHTML = html;

        if(myRadarChart) myRadarChart.destroy();
        const ctx = document.getElementById('radarChart').getContext('2d');
        const chartData = ['ext', 'agr', 'con', 'neu', 'ope'].map(k => scores[k] ? parseFloat(scores[k]) : 0);
        myRadarChart = new Chart(ctx, {
            type: 'radar',
            data: { 
                labels: [getUIText(currentLang1, 'ext'), getUIText(currentLang1, 'agr'), getUIText(currentLang1, 'con'), getUIText(currentLang1, 'neu'), getUIText(currentLang1, 'ope')], 
                datasets: [{ label: `${stName}`, data: chartData, backgroundColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(54, 162, 235, 1)', pointBackgroundColor: 'rgba(54, 162, 235, 1)' }] 
            },
            options: { scales: { r: { suggestedMin: 0, suggestedMax: 5, ticks: { stepSize: 1 } } } }
        });

        let detailHtml = `<table class="result-table"><thead><tr><th width="10%">${getUIText(currentLang1, 'noCol')}</th><th width="15%">${getUIText(currentLang1, 'traitCol')}</th><th width="60%">${getUIText(currentLang1, 'qCol')}</th><th width="15%">${getUIText(currentLang1, 'scoreCol')}</th></tr></thead><tbody>`;
        for(let i = 1; i <= TOTAL_QUESTIONS; i++) {
            let traitKey = getTraitKeyForQuestion(i);
            let scoreStr = data[i] ? `<b>${data[i]}</b>` : `<span style="color:red">${getUIText(currentLang1, 'noResp')}</span>`;
            detailHtml += `<tr><td>${i}</td><td style="color:#3B82F6; font-weight:bold;">${getUIText(currentLang1, traitKey)}</td><td class="text-left">${getQText(currentLang1, i)}</td><td>${scoreStr}</td></tr>`;
        }
        detailHtml += `</tbody></table>`;
        document.getElementById("ind-detail-table-wrapper").innerHTML = detailHtml;
    }

    function copyIndividualResult() {
        const stName = document.getElementById("student-select").value;
        if(!stName) { alert(getUIText(currentLang1, 'alertNoSt')); return; }
        const data = surveyData[stName];
        if(!data || Object.keys(data).length === 0) return;

        const scores = calculateTraitScores(data);
        let textToCopy = `[ ${stName} - ${getUIText(currentLang1, 'title')} ]\n\n■ ${getUIText(currentLang1, 'sumTitle')}\n`;
        ['ext', 'agr', 'con', 'neu', 'ope'].forEach(tKey => { 
            textToCopy += `- ${getUIText(currentLang1, tKey)}: ${scores[tKey] || '-'} ${getUIText(currentLang1, 'scorePt')}\n`; 
        });
        
        textToCopy += `\n■ ${getUIText(currentLang1, 'detailTitle')}\n`;
        for(let i = 1; i <= TOTAL_QUESTIONS; i++) {
            let traitKey = getTraitKeyForQuestion(i);
            let scoreStr = data[i] ? `${data[i]}` : getUIText(currentLang1, 'noResp');
            textToCopy += `${i}. [${getUIText(currentLang1, traitKey)}] ${getQText(currentLang1, i)} : ${scoreStr}\n`;
        }

        const textarea = document.createElement("textarea");
        textarea.value = textToCopy;
        document.body.appendChild(textarea); textarea.select();
        try { document.execCommand('copy'); alert(getUIText(currentLang1, 'alertCopyOk')); } 
        catch (err) { }
        document.body.removeChild(textarea);
    }

    function downloadTXT(text, filename) {
        const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    function exportIndividualTXT() {
        const stName = document.getElementById("student-select").value;
        if(!stName) { alert(getUIText(currentLang1, 'alertNoSt')); return; }
        const data = surveyData[stName];
        if(!data || Object.keys(data).length === 0) return;

        const scores = calculateTraitScores(data);
        let text = `[ ${stName} - ${getUIText(currentLang1, 'title')} ]\n\n`;
        text += `■ ${getUIText(currentLang1, 'sumTitle')}\n`;
        ['ext', 'agr', 'con', 'neu', 'ope'].forEach(tKey => { 
            text += `- ${getUIText(currentLang1, tKey)}: ${scores[tKey] || '-'} ${getUIText(currentLang1, 'scorePt')}\n`; 
        });
        
        text += `\n■ ${getUIText(currentLang1, 'detailTitle')}\n`;
        for(let i = 1; i <= TOTAL_QUESTIONS; i++) {
            let traitKey = getTraitKeyForQuestion(i);
            let scoreStr = data[i] ? `${data[i]}` : getUIText(currentLang1, 'noResp');
            text += `${i}. [${getUIText(currentLang1, traitKey)}] ${getQText(currentLang1, i)} : ${scoreStr}\n`;
        }
        downloadTXT(text, `${stName}_big5.txt`);
    }

    function exportAllTXT() {
        let text = `[ ${getUIText(currentLang1, 'title')} - ${getUIText(currentLang1, 'allRes')} ]\n\n`;
        
        students.forEach(st => {
            text += `--------------------------------------------------\n`;
            text += `👤 ${getUIText(currentLang1, 'nameCol')}: ${st}\n`;
            const data = surveyData[st];
            if(data && Object.keys(data).length > 0) {
                const scores = calculateTraitScores(data);
                ['ext', 'agr', 'con', 'neu', 'ope'].forEach(k => {
                    text += `${getUIText(currentLang1, k)}: ${scores[k] || '-'} | `;
                });
                text += '\n';
            } else {
                text += `${getUIText(currentLang1, 'noResp')}\n`;
            }
        });
        
        downloadTXT(text, `All_Students_big5.txt`);
    }

    function renderAllResultTable() {
        let html = `<table class="result-table"><thead><tr><th>${getUIText(currentLang1, 'nameCol')}</th><th>${getUIText(currentLang1, 'ext')}</th><th>${getUIText(currentLang1, 'agr')}</th><th>${getUIText(currentLang1, 'con')}</th><th>${getUIText(currentLang1, 'neu')}</th><th>${getUIText(currentLang1, 'ope')}</th></tr></thead><tbody>`;
        students.forEach(st => {
            const data = surveyData[st];
            if(data && Object.keys(data).length > 0) {
                const scores = calculateTraitScores(data);
                html += `<tr><td><div style="display:flex; align-items:center; justify-content:center;"><img src="${getAvatarUrl(st)}" style="width:24px; height:24px; border-radius:50%; margin-right:5px;"><b>${st}</b></div></td>
                            <td>${scores['ext']||'-'}</td><td>${scores['agr']||'-'}</td><td>${scores['con']||'-'}</td><td>${scores['neu']||'-'}</td><td>${scores['ope']||'-'}</td></tr>`;
            } else {
                html += `<tr><td><div style="display:flex; align-items:center; justify-content:center;"><img src="${getAvatarUrl(st)}" style="width:24px; height:24px; border-radius:50%; margin-right:5px;"><b>${st}</b></div></td><td colspan="5" style="color:#aaa;">${getUIText(currentLang1, 'noResp')}</td></tr>`;
            }
        });
        html += `</tbody></table>`;
        document.getElementById("all-result-table-container").innerHTML = html;
    }

    // --- Backup/Restore ---
    function exportBackupData() {
        const exportObj = { students: students, surveyData: surveyData, exportDate: new Date().toLocaleString() };
        const blob = new Blob([JSON.stringify(exportObj, null, 2)], { type: "application/json" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url;
        a.download = `big5_backup_${new Date().toISOString().slice(0,10)}.json`;
        document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
    }

    function importBackupData() {
        const fileInput = document.getElementById("backup-file-input");
        if(fileInput.files.length === 0) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const obj = JSON.parse(e.target.result);
                if(obj.students && obj.surveyData) {
                    students = obj.students; surveyData = obj.surveyData; saveToCache();
                    alert(getUIText(currentLang1, 'alertBkOk')); closeModal('teacher-modal'); renderButtons(); renderStudents(); selectQuestion(currentQuestionId);
                }
            } catch(err) { }
        };
        reader.readAsText(fileInput.files[0]);
    }

    function clearAllData() {
        if(confirm(getUIText(currentLang1, 'alertInit'))) {
            localStorage.removeItem('bigFiveStudents'); localStorage.removeItem('bigFiveSurveyData');
            students = []; surveyData = {}; 
            renderManageList(); renderButtons(); renderStudents(); document.getElementById("ind-result-area").style.display="none"; renderAllResultTable();
        }
    }
</script>
</body>
</html>