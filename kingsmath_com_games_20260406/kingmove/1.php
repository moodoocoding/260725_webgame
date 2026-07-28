<!DOCTYPE html>

<html lang="ko">

<head><?php

// [통계 연결] 킹수학 폴더에 있는 counter.php를 불러와서 실행해라!

include_once $_SERVER['DOCUMENT_ROOT'] . '/counter.php';

?>

<script async src="https://www.googletagmanager.com/gtag/js?id=G-5YW0T2C109"></script>

<script>

  window.dataLayer = window.dataLayer || [];

  function gtag(){dataLayer.push(arguments);}

  gtag('js', new Date());

  gtag('config', 'G-5YW0T2C109');

</script>



<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<meta name="theme-color" content="#0F4C3A">



<title id="pageTitle">소수점 이동킹 - 소수점 위치 찾기 배틀</title>

<meta id="pageDesc" name="description" content="소수점 이동킹! 10배, 100배, 1/10! 숫자의 변화를 보고 소수점을 이동시키는 두뇌 게임.">

<link rel="canonical" href="/">



<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">



<style>

@import url('https://fonts.googleapis.com/css2?family=Jua&family=Noto+Sans:wght@400;600;700&display=swap');



/* 폰트 설정: 한글은 Jua, 그 외 언어는 Noto Sans 등 기본 폰트 적용 */

body { font-family: 'Jua', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-weight: normal; }

body.lang-non-ko { font-family: 'Noto Sans', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-weight: 600; }



:root {

    --bg: #121212;

    --panel-bg: #1e1e2e;

    --dark: #e0e0e0;

    --text-muted: #a0a0a0;

    --border-color: #333333;



    --dog-color: #332b00;

    --cat-color: #1e1b2e;

    --rabbit-color: #2e151a;

    --hamster-color: #2e1e0f;

    --panda-color: #0f2e26;

    --monkey-color: #221826;



    --correct: #69db7c;

    --wrong: #ff6b6b;

    --eliminated: #333333;

}



body {

    margin: 0; background: var(--bg); color: var(--dark); overflow: hidden;

    user-select: none; -webkit-user-select: none; 

    touch-action: pan-y; 

    display: flex; flex-direction: column; height: 100vh; height: 100dvh;

    transition: background 0.3s, color 0.3s;

}



/* 폰트어썸 아이콘 깨짐 방지용 예외 처리 */

i[class^="fa-"] {

    font-family: "Font Awesome 6 Free" !important;

    font-weight: 900 !important;

}



/* 모달창 내부 박스 사이징 강제 보정 */

#langModal, #langModal * {

    box-sizing: border-box !important;

}



/* 커스텀 스크롤바 (모달 내부 스크롤용) */

.custom-scrollbar::-webkit-scrollbar { width: 8px; }

.custom-scrollbar::-webkit-scrollbar-track { background: rgba(31, 41, 55, 0.5); border-radius: 4px; }

.custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }

.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }



/* 모달 등장 애니메이션 */

.modal-overlay { opacity: 0; visibility: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

.modal-overlay.active { opacity: 1; visibility: visible; }

.modal-content { transform: scale(0.95) translateY(-10px); opacity: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

.modal-overlay.active .modal-content { transform: scale(1) translateY(0); opacity: 1; }



/* 토스트 알림 애니메이션 */

.toast-anim { transform: translateY(100%); opacity: 0; transition: all 0.3s ease; }

.toast-anim.show { transform: translateY(0); opacity: 1; }



/* 텍스트 넘침 방지 반응형 클래스 */

.dynamic-text { word-wrap: break-word; white-space: normal; line-height: 1.2; }



/* --- UI Components --- */

#overlay {

    position: fixed; inset: 0; background: var(--bg); opacity: 0.98;

    display: flex; flex-direction: column; justify-content: center; align-items: center;

    z-index: 100; backdrop-filter: blur(15px);

    text-align: center; overflow-y: auto; padding-bottom: 50px;

}

.title-area { margin: 30px 0 20px 0; }

#msgTitle { font-size: 3.5rem; color: var(--dark); margin: 0; text-shadow: 0 4px 10px rgba(0,0,0,0.2); font-weight: normal; line-height: 1.2; padding: 0 10px;}

body.lang-non-ko #msgTitle { font-weight: bold; }

.subtitle { color: var(--text-muted); font-size: 1.2rem; margin-top: 10px; font-weight: normal; }

body.lang-non-ko .subtitle { font-weight: 600; }

.top-link { color: #339af0; text-decoration: none; font-weight: bold; }

#setupArea { width: 500px; max-width: 95vw; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }

.setup-group { text-align: left; }

.setup-label { font-size: 1.3rem; color: var(--dark); margin-bottom: 8px; display: block; font-weight: normal; }

body.lang-non-ko .setup-label { font-weight: bold; }

.custom-select {

    width: 100%; font-family: inherit; font-size: 1.2rem; padding: 15px;

    border-radius: 15px; border: 2px solid var(--border-color);

    background: var(--panel-bg); color: var(--dark); outline: none;

    cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-weight: normal;

}

body.lang-non-ko .custom-select { font-weight: 600; }

.btn-large {

    font-size: 2rem; padding: 15px 10px; border-radius: 50px; border: none;

    background: #339af0; color: white; cursor: pointer; height: auto; min-height: 70px;

    box-shadow: 0 6px 0 #1c7ed6; transition: 0.1s; font-family: inherit;

    width: 100%; margin-top: 20px; font-weight: normal; display: flex; align-items: center; justify-content: center; text-align: center;

}

body.lang-non-ko .btn-large { font-weight: bold; font-size: 1.6rem; }

.btn-large:active { transform: translateY(4px); box-shadow: 0 2px 0 #1c7ed6; }

#otherGameLink { display: block; margin-top: 20px; color: var(--text-muted); text-decoration: underline; font-size: 1.1rem; }



.side-btn {

    position: fixed; top: 50%; transform: translateY(-50%);

    font-size: 2.1rem; background: rgba(0,0,0,0.5); border: 2px solid rgba(255,255,255,0.2);

    border-radius: 50%; width: 56px; height: 56px;

    display: flex; align-items: center; justify-content: center;

    cursor: pointer; z-index: 9999; color: #fff; text-decoration: none;

    transition: 0.2s; -webkit-tap-highlight-color: transparent;

}

.side-btn:active { transform: translateY(-50%) scale(0.9); background: rgba(0,0,0,0.8); }

.left-btn { left: 20px; }

.right-btn { right: 20px; }



/* --- Game Screen Structure --- */

#topZone { position: relative; display: flex; flex-direction: column; z-index: 10; }

#header {

    height: 100px; background: var(--panel-bg); display: flex;

    align-items: center; justify-content: center;

    padding: 0 20px; border-bottom: 2px solid var(--border-color);

    position: relative;

}

.header-title { 

    font-size: 2rem; color: var(--dark); display: flex; align-items: center; gap: 10px; 

    position: absolute; left: 20px; font-weight: normal;

}

body.lang-non-ko .header-title { font-weight: bold; font-size: 1.5rem;}

.timer-box {

    font-size: 4rem; font-weight: normal; color: #ff6b6b;

    background: rgba(0,0,0,0.1); padding: 5px 30px; border-radius: 20px;

    min-width: 120px; text-align: center;

}

.timer-urgent { color: #fff; background: #ff6b6b; animation: pulse 0.5s infinite; }



#mainQuestionBoard {

    background: var(--panel-bg); padding: 10px; text-align: center;

    border-bottom: 1px solid var(--border-color);

    display: flex; flex-direction: column; align-items: center; justify-content: center;

    min-height: 200px; flex-grow: 1; position: relative;

}

#qNumber { font-size: 1.5rem; color: var(--text-muted); margin-bottom: 5px; font-weight: normal; }

#bigQuestion { 

    font-size: clamp(2.5rem, 6vw, 4rem); color: var(--dark); line-height: 1.2; 

    font-family: inherit; font-weight: normal; width: 100%;

}



/* Result Popup */

#resultPopup {

    display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0;

    background: rgba(30, 30, 46, 0.98); backdrop-filter: blur(10px);

    z-index: 100; flex-direction: column; align-items: center; justify-content: center;

    border-bottom: 4px solid var(--border-color);

}

#resultTitle { font-size: 3rem; margin: 0 0 20px 0; color: var(--dark); font-weight: normal; }

body.lang-non-ko #resultTitle { font-weight: bold; }

.result-list { 

    list-style: none; padding: 0; margin: 0; 

    width: 80%; max-width: 600px; max-height: 60%; overflow-y: auto; 

}

.result-item {

    display: flex; justify-content: space-between; padding: 15px;

    background: rgba(128,128,128,0.1); margin-bottom: 8px; border-radius: 12px;

    font-size: 1.5rem; color: var(--dark); border: 1px solid var(--border-color);

    font-weight: normal;

}

.rank-1 { background: #ffd43b; color: #000; font-weight: normal; border: none; }



/* Player Arena */

#arena { display: flex; flex: 1; width: 100vw; overflow: hidden; background: var(--bg); }

.player-col {

    flex: 1; border-right: 1px solid var(--border-color);

    display: flex; flex-direction: column; align-items: center;

    padding: 10px 5px; position: relative; transition: opacity 0.3s;

}

.player-col:last-child { border-right: none; }

.player-col.eliminated { opacity: 0.3; filter: grayscale(100%); pointer-events: none; }



.p-header { text-align: center; margin-bottom: 5px; width: 100%; flex-shrink: 0;}

.p-emoji { font-size: 2rem; display: block; }

.p-name { font-size: 1rem; white-space: nowrap; font-weight: normal; }

body.lang-non-ko .p-name { font-weight: bold; font-size: 0.85rem;}

.p-info {

    font-size: 1rem; background: rgba(0,0,0,0.2); padding: 2px 8px;

    border-radius: 10px; margin-top: 2px; color: var(--dark); font-weight: normal;

}



.mini-question {

    font-size: 1.1rem; color: var(--dark); margin: 5px 0;

    text-align: center; word-break: break-all;

    background: rgba(0,0,0,0.2); padding: 5px; border-radius: 8px;

    width: 90%; min-height: 1.5em; font-weight: normal;

}



/* Decimal Display and Controls */

.p-current-display {

    font-size: 2.2rem; font-family: inherit; color: #fff;

    background: rgba(0, 0, 0, 0.4); margin: 10px 0; padding: 10px;

    border-radius: 10px; width: 90%; text-align: center;

    border: 2px solid #555; height: 3rem; display: flex;

    align-items: center; justify-content: center;

    letter-spacing: 2px;

}



.choice-grid {

    display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 6px;

    width: 95%; max-width: 260px; flex: 1; align-content: center; padding-bottom: 10px;

}

.choice-btn {

    background: var(--panel-bg); border: 2px solid var(--border-color);

    border-radius: 12px; color: var(--dark); font-size: 1.5rem;

    font-family: inherit; cursor: pointer; display: flex; align-items: center; justify-content: center;

    box-shadow: 0 4px 0 rgba(0,0,0,0.1); transition: 0.1s;

    padding: 10px; min-height: 50px; font-weight: normal;

}

body.lang-non-ko .choice-btn { font-size: 1rem; font-weight: bold; }

.choice-btn:active { transform: translateY(2px); box-shadow: none; }

.btn-submit { background: #ffd43b; color: #000; border-color: #f5c000; font-weight: bold; }

.btn-submit:active { box-shadow: 0 2px 0 #d4a000; }



.bump-anim { animation: bump 0.15s ease-out; }

@keyframes bump { 0% { transform: scale(1); } 50% { transform: scale(1.15); color: #ffd43b; } 100% { transform: scale(1); } }



/* Feedback & Effects */

.feedback-overlay {

    position: absolute; inset: 0; background: rgba(0,0,0,0.7);

    display: none; align-items: center; justify-content: center;

    flex-direction: column; font-size: 5rem; z-index: 20; border-radius: 0;

}

.feedback-overlay.secret { background: rgba(0,0,0,0.85) !important; opacity: 1; display: flex; color: white; transition: none; animation: none; }

.feedback-overlay.show-result { display: flex; animation: none; }

.submitted-text { font-size: 1.5rem; margin-top: 15px; color: #fff; text-align: center; line-height: 1.5; font-weight: normal; }

body.lang-non-ko .submitted-text { font-size: 1.1rem; font-weight: bold; }



.final-status {

    position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);

    width: 85%; box-sizing: border-box; background: rgba(0,0,0,0.85); padding: 15px 10px; border-radius: 15px;

    text-align: center; z-index: 50; border: 2px solid #fff;

    display: none; animation: popIn 0.5s; word-break: keep-all; overflow-wrap: break-word;

}

.final-status h3 { margin: 0; font-size: 1.2rem; color: #fff; font-weight: normal; }

.final-status p { margin: 5px 0 0 0; font-size: 1.8rem; color: #ffd43b; font-weight: normal; line-height: 1.2;}



/* Countdown */

#countdownLayer {

    position: fixed; inset: 0; background: #000000; z-index: 2000;

    display: none; align-items: center; justify-content: center;

}

#countdownText { 

    font-size: 25rem; color: #ffec99; text-shadow: 0 0 50px rgba(255, 212, 59, 0.8); 

    font-weight: normal; opacity: 0;

}

@keyframes countPop {

    0% { transform: scale(0.5); opacity: 0; }

    50% { transform: scale(1.2); opacity: 1; }

    100% { transform: scale(1.0); opacity: 0; }

}

.count-animate { animation: countPop 0.8s ease-in-out forwards; }



.main-question-box {

    font-size: 3.5rem; background: rgba(0, 0, 0, 0.3); padding: 20px 40px;

    border-radius: 30px; border: 4px solid #ffd43b; color: #fff;

    display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.5); animation: popIn 0.5s;

    word-break: keep-all;

}

@keyframes popIn { 0% { transform: scale(0.8); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }



/* Large Screen */

@media (min-width: 900px) {

    #header { height: 140px; }

    .header-title { font-size: 3rem; }

    .timer-box { font-size: 6rem; padding: 10px 50px; }

    #qNumber { font-size: 2rem; margin-bottom: 15px; }

    .p-emoji { font-size: 3rem; }

    .p-name { font-size: 1.5rem; }

    .p-info { font-size: 1.3rem; padding: 5px 15px; }

    .mini-question { font-size: 1.4rem; margin: 10px 0; min-height: 2em; }

    .p-current-display { font-size: 3.5rem; height: 5rem; }

    .choice-grid { max-width: 350px; gap: 15px; }

    .choice-btn { font-size: 2rem; min-height: 60px; border-radius: 20px; border-width: 3px; }

    #resultTitle { font-size: 4rem; margin-bottom: 40px; }

    .result-item { font-size: 1.5rem; padding: 9px; }

    .btn-large { font-size: 2.5rem; padding: 20px 0; }

    #msgTitle { font-size: 5rem; }

    .setup-label { font-size: 1.8rem; }

    .custom-select { font-size: 1.5rem; padding: 20px; }

}



/* Character Colors */

.bg-dog { background: var(--dog-color); }

.bg-cat { background: var(--cat-color); }

.bg-rabbit { background: var(--rabbit-color); }

.bg-hamster { background: var(--hamster-color); }

.bg-panda { background: var(--panda-color); }

.bg-monkey { background: var(--monkey-color); }



</style>

</head>



<body>

    <div id="langBtnContainer" class="absolute top-4 right-4 sm:top-6 sm:right-6" style="z-index: 99999;">

        <button id="langTriggerBtn" class="flex items-center gap-2 bg-gray-700/80 hover:bg-gray-700 border border-gray-600 rounded-full px-4 py-2 transition-all duration-200 text-sm font-medium shadow-lg backdrop-blur-md">

            <i class="fa-solid fa-globe text-gray-400"></i>

            <img id="currentFlag" src="https://flagcdn.com/w40/kr.png" alt="KR" class="w-5 h-auto rounded-[2px] shadow-sm">

            <span id="currentLangName" class="hidden sm:inline-block text-white">한국어</span>

            <i class="fa-solid fa-chevron-down text-gray-400 ml-1 text-xs"></i>

        </button>

    </div>



    <div id="langModal" class="modal-overlay fixed inset-0 flex items-start sm:items-center justify-center bg-black/60 backdrop-blur-sm pt-16 sm:pt-0 px-4" style="z-index: 99999;">

        <div class="modal-content bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden text-left">



            <div class="flex items-center justify-between p-5 border-b border-gray-800 bg-gray-900/95 sticky top-0 z-10">

                <div>

                    <h2 class="text-xl font-bold text-white flex items-center gap-2 m-0" style="font-family:'Noto Sans', sans-serif;">

                        <i class="fa-solid fa-language text-yellow-500"></i>

                        <span>Language / 언어</span>

                    </h2>

                </div>

                <button id="closeModalBtn" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-colors border-none cursor-pointer">

                    <i class="fa-solid fa-xmark text-lg"></i>

                </button>

            </div>



            <div class="p-5 pb-2 bg-gray-900">

                <div class="relative">

                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>

                    <input type="text" id="searchInput" placeholder="Search / 검색 ..." 

                        class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 block pl-11 p-3.5 outline-none transition-all placeholder-gray-500" style="font-family:'Noto Sans', sans-serif;">

                </div>

            </div>



            <div class="p-5 overflow-y-auto custom-scrollbar flex-1">

                <div id="languageGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">

                    </div>

                <div id="noResult" class="hidden flex-col items-center justify-center py-12 text-gray-500">

                    <i class="fa-regular fa-face-frown-open text-4xl mb-3"></i>

                    <p style="font-family:'Noto Sans', sans-serif;">No results / 검색 결과 없음</p>

                </div>

            </div>

        </div>

    </div>



    <div id="toastNotification" class="toast-anim fixed bottom-6 right-6 bg-gray-800 border border-gray-700 shadow-lg rounded-xl p-4 flex items-center gap-3" style="z-index: 99999;">

        <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center text-green-400">

            <i class="fa-solid fa-check"></i>

        </div>

        <div class="text-left">

            <p id="ui-toast" class="text-sm font-bold text-white m-0" style="font-family:'Noto Sans', sans-serif;">언어가 변경되었습니다.</p>

            <p id="toastLangName" class="text-xs text-gray-400 m-0" style="font-family:'Noto Sans', sans-serif;">Korean</p>

        </div>

    </div>



    <button id="btn-home" class="side-btn left-btn" title="Home">🏠</button>

    <button id="fullscreenBtn" class="side-btn right-btn" title="Full Screen">⛶</button>



    <div id="overlay">

        <div class="title-area">

            <span style="font-size: 1.2rem; color: #339af0;" class="dynamic-text">

                <span data-lang-key="intro">게임으로 수학의 킹이 되어라.</span> 

                <a id="topLink" href="/" target="_self" class="top-link">킹수학.com</a>

            </span><br>

            <h1 id="msgTitle" data-lang-key="title" class="dynamic-text">🎯소수점 이동킹</h1>

            <div class="subtitle dynamic-text" data-lang-key="slogan">소수점을 움직여 정답을 찾으세요!</div>

        </div>



        <div id="setupArea">

            <div class="setup-group">

                <label class="setup-label dynamic-text" data-lang-key="label_diff">📚 문제 유형 선택</label>

                <select id="difficulty" class="custom-select"></select>

            </div>

            <div class="setup-group">

                <label class="setup-label dynamic-text" data-lang-key="label_mode">🏆 게임 모드</label>

                <select id="gameMode" class="custom-select"></select>

            </div>

            <div class="setup-group">

                <label class="setup-label dynamic-text" data-lang-key="label_player">👥 참가 인원</label>

                <select id="playerCount" class="custom-select"></select>

            </div>

            <button id="startBtn" class="btn-large dynamic-text" data-lang-key="btn_start">소수점 이동킹 시작하기</button>

            <a id="otherGameLink" href="/" target="_self" class="dynamic-text" data-lang-key="link_other" style="color: var(--text-muted); text-decoration: underline; font-size: 1.1rem;">🎮 다른 게임 고르기</a>

        </div>

    </div>



    <div id="countdownLayer">

        <div id="countdownText">3</div>

    </div>



    <div id="topZone">

        <header id="header">

            <div class="header-title">

                <span style="font-size:2rem;">👑</span>

                <span data-lang-key="header_title">이동킹</span>

            </div>

            <div id="mainTimer" class="timer-box">30</div>

        </header>



        <div id="mainQuestionBoard">

            <div id="qNumber">라운드 1 / 10</div>

            <div id="bigQuestion">문제 생성 중...</div>

        </div>



        <div id="resultPopup">

            <h2 id="resultTitle" data-lang-key="result_title" class="dynamic-text">결과 발표</h2>

            <ul id="finalResultList" class="result-list"></ul>

            <button id="restartBtn" class="btn-large dynamic-text" style="margin-top:20px; font-size:1.5rem; width: auto; padding: 10px 40px;" data-lang-key="btn_restart">처음으로 돌아가기</button>

        </div>

    </div>



    <div id="arena"></div>



    <script>

    // -------------------------------------------------------------

    // 다국어 지원용 번역 데이터베이스 (16개국)

    // -------------------------------------------------------------

    const STRINGS = {

        ko: {

            title: "🎯소수점 이동킹", pageTitle: "소수점 이동킹 - 소수점 위치 찾기 게임", desc: "소수점 이동킹! 10배, 100배, 1/10! 숫자의 변화를 보고 소수점을 이동시키는 게임.",

            intro: "게임으로 수학의 킹이 되어라.", slogan: "소수점을 움직여 정답을 찾으세요!",

            label_diff: "📚 문제 유형 선택", label_mode: "🏆 게임 모드", label_player: "👥 참가 인원",

            btn_start: "소수점 이동킹 시작하기", btn_restart: "처음으로 돌아가기", link_other: "🎮 다른 게임 고르기",

            header_title: "이동킹", result_title: "결과 발표", q_num: "라운드", ready: "문제 생성 중...",

            submitted: "제출 완료", score_pt: "점", survived_count: "라운드 생존", eliminated: "탈락", winner: "우승!", rank: "위", final_score: "최종 점수", record: "기록",

            diff_dec_easy: "소수 10배, 100배... (확대)",
            diff_dec_normal: "소수 1/10, 1/100... (축소)",
            diff_dec_mix: "소수 랜덤 출제",
            diff_int_easy: "자연수 10배, 100배... (확대)",
            diff_int_normal: "자연수 1/10, 1/100... (축소)",
            diff_int_mix: "자연수 랜덤 출제",
            diff_all_mix: "소수 자연수 랜덤 출제",

            mode_chall: "🏅 챌린지 (10라운드)", mode_surv: "☠️ 서바이벌 (갈수록 시간단축)",

            p_1: "1인 연습", p_n: "인 플레이", domain_txt: "킹수학.com", domain_url: "/",

            format_mul: "의 {0}", txt_answer_label: "정답", btn_submit: "제출",

            team_dog: "강아지", team_cat: "고양이", team_rabbit: "토끼", team_hamster: "햄스터", team_panda: "판다", team_monkey: "원숭이"

        },

        en: {

            title: "🎯Decimal King", pageTitle: "Decimal King - Math Logic Battle", desc: "Move the decimal point to find the correct answer!",

            intro: "Play to be the King of Math.", slogan: "Shift the decimal to win!",

            label_diff: "📚 Difficulty", label_mode: "🏆 Game Mode", label_player: "👥 Players",

            btn_start: "Start Game", btn_restart: "Back to Title", link_other: "🎮 More Games",

            header_title: "Decimal King", result_title: "Final Results", q_num: "Round", ready: "Generating...",

            submitted: "Submitted", score_pt: "pts", survived_count: "Survived", eliminated: "Out", winner: "Winner!", rank: "", final_score: "Final Score", record: "Record",

            diff_dec_easy: "Decimal x10, x100... (Expand)",
            diff_dec_normal: "Decimal 1/10, 1/100... (Shrink)",
            diff_dec_mix: "Decimal Random",
            diff_int_easy: "Integer x10, x100... (Expand)",
            diff_int_normal: "Integer 1/10, 1/100... (Shrink)",
            diff_int_mix: "Integer Random",
            diff_all_mix: "Decimal & Integer Random",

            mode_chall: "🏅 Challenge (10 Rds)", mode_surv: "☠️ Survival (Time Shrinks)",

            p_1: "1 Player", p_n: " Players", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Answer", btn_submit: "Submit",

            team_dog: "Dog", team_cat: "Cat", team_rabbit: "Rabbit", team_hamster: "Hamster", team_panda: "Panda", team_monkey: "Monkey"

        },

        zh: {

            title: "🎯小数点之王", pageTitle: "小数点之王 - 数学逻辑对决", desc: "移动小数点，找到正确答案！",

            intro: "玩游戏，成为数学之王。", slogan: "移动小数点来获胜！",

            label_diff: "📚 难度选择", label_mode: "🏆 游戏模式", label_player: "👥 参加人数",

            btn_start: "开始挑战", btn_restart: "返回首页", link_other: "🎮 其他游戏",

            header_title: "小数点王", result_title: "结果公布", q_num: "轮", ready: "生成中...",

            submitted: "已提交", score_pt: "分", survived_count: "轮生存", eliminated: "淘汰", winner: "冠军!", rank: "名", final_score: "最终得分", record: "记录",

            diff_easy: "乘法 (10倍, 100倍)", diff_normal: "除法 (1/10, 1/100)", diff_mix: "混合",

            mode_chall: "🏅 挑战模式 (10题)", mode_surv: "☠️ 生存模式 (缩短时间)",

            p_1: "1人 练习", p_n: "人 游戏", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " 的 {0}", txt_answer_label: "答案", btn_submit: "提交",

            team_dog: "小狗", team_cat: "小猫", team_rabbit: "兔子", team_hamster: "仓鼠", team_panda: "熊猫", team_monkey: "猴子"

        },

        ja: {

            title: "🎯小数点の王", pageTitle: "小数点の王 - 数学バトル", desc: "小数点を移動して正解を見つけよう！",

            intro: "ゲームで数学の王になろう。", slogan: "小数点を動かして勝負！",

            label_diff: "📚 問題タイプ", label_mode: "🏆 ゲームモード", label_player: "👥 参加人数",

            btn_start: "スタート", btn_restart: "最初に戻る", link_other: "🎮 他のゲーム",

            header_title: "小数点の王", result_title: "結果発表", q_num: "ラウンド", ready: "問題生成中...",

            submitted: "提出完了", score_pt: "点", survived_count: "問 生存", eliminated: "脱落", winner: "優勝!", rank: "位", final_score: "最終スコア", record: "記録",

            diff_easy: "10倍, 100倍...", diff_normal: "1/10, 1/100...", diff_mix: "ミックス",

            mode_chall: "🏅 チャレンジ (10問)", mode_surv: "☠️ サバイバル (時間短縮)",

            p_1: "1人 練習", p_n: "人 プレイ", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " の {0}", txt_answer_label: "正解", btn_submit: "提出",

            team_dog: "犬", team_cat: "猫", team_rabbit: "うさぎ", team_hamster: "ハムスター", team_panda: "パンダ", team_monkey: "猿"

        },

        es: {

            title: "🎯Rey del Decimal", pageTitle: "Rey del Decimal - Batalla Matemática", desc: "¡Mueve el punto decimal para encontrar la respuesta correcta!",

            intro: "Conviértete en el Rey de las Matemáticas.", slogan: "¡Desplaza el decimal!",

            label_diff: "📚 Dificultad", label_mode: "🏆 Modo de Juego", label_player: "👥 Jugadores",

            btn_start: "Empezar", btn_restart: "Volver", link_other: "🎮 Más Juegos",

            header_title: "Rey Decimal", result_title: "Resultados", q_num: "Ronda", ready: "Generando...",

            submitted: "Enviado", score_pt: "pts", survived_count: "Superadas", eliminated: "Eliminado", winner: "¡Ganador!", rank: "º", final_score: "Puntuación", record: "Récord",

            diff_easy: "Multiplicar (x10, x100)", diff_normal: "Dividir (1/10, 1/100)", diff_mix: "Mix All",

            mode_chall: "🏅 Desafío (10)", mode_surv: "☠️ Supervivencia",

            p_1: "1 Jugador", p_n: " Jugadores", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Respuesta", btn_submit: "Enviar",

            team_dog: "Perro", team_cat: "Gato", team_rabbit: "Conejo", team_hamster: "Hámster", team_panda: "Panda", team_monkey: "Mono"

        },

        fr: {

            title: "🎯Roi de la Virgule", pageTitle: "Roi de la Virgule - Quiz de Maths", desc: "Déplacez la virgule pour trouver la bonne réponse !",

            intro: "Devenez le Roi des Maths.", slogan: "Déplacez la virgule !",

            label_diff: "📚 Difficulté", label_mode: "🏆 Mode de Jeu", label_player: "👥 Joueurs",

            btn_start: "Commencer", btn_restart: "Retour", link_other: "🎮 Autres Jeux",

            header_title: "Roi Virgule", result_title: "Résultats", q_num: "Round", ready: "Génération...",

            submitted: "Envoyé", score_pt: "pts", survived_count: "Réussites", eliminated: "Éliminé", winner: "Vainqueur!", rank: "e", final_score: "Score Final", record: "Record",

            diff_easy: "Multiplier (x10, x100)", diff_normal: "Diviser (1/10, 1/100)", diff_mix: "Mixte",

            mode_chall: "🏅 Challenge (10)", mode_surv: "☠️ Survie",

            p_1: "1 Joueur", p_n: " Joueurs", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Réponse", btn_submit: "Valider",

            team_dog: "Chien", team_cat: "Chat", team_rabbit: "Lapin", team_hamster: "Hamster", team_panda: "Panda", team_monkey: "Singe"

        },

        ru: {

            title: "🎯Король Запятой", pageTitle: "Король Запятой - Математическая битва", desc: "Переместите десятичную запятую, чтобы найти правильный ответ!",

            intro: "Стань королем математики.", slogan: "Двигай запятую!",

            label_diff: "📚 Сложность", label_mode: "🏆 Режим", label_player: "👥 Игроки",

            btn_start: "Начать", btn_restart: "Главная", link_other: "🎮 Другие игры",

            header_title: "Король Запятой", result_title: "Результаты", q_num: "Раунд", ready: "Генерация...",

            submitted: "Принято", score_pt: "очк.", survived_count: "Выжито", eliminated: "Выбыл", winner: "Победа!", rank: "", final_score: "Счет", record: "Рекорд",

            diff_easy: "Умножить (x10, x100)", diff_normal: "Разделить (1/10)", diff_mix: "Микс",

            mode_chall: "🏅 Вызов (10)", mode_surv: "☠️ Выживание",

            p_1: "1 Игрок", p_n: " Игроков", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Ответ", btn_submit: "Ответить",

            team_dog: "Собака", team_cat: "Кот", team_rabbit: "Кролик", team_hamster: "Хомяк", team_panda: "Панда", team_monkey: "Обезьяна"

        },

        id: {

            title: "🎯Raja Desimal", pageTitle: "Raja Desimal - Pertempuran Matematika", desc: "Pindahkan koma desimal untuk menemukan jawaban yang benar!",

            intro: "Bermain untuk menjadi Raja Matematika.", slogan: "Geser desimalnya!",

            label_diff: "📚 Kesulitan", label_mode: "🏆 Mode Permainan", label_player: "👥 Pemain",

            btn_start: "Mulai", btn_restart: "Kembali", link_other: "🎮 Game Lain",

            header_title: "Raja Desimal", result_title: "Hasil", q_num: "Ronde", ready: "Menghasilkan...",

            submitted: "Dikirim", score_pt: "poin", survived_count: "Bertahan", eliminated: "Gugur", winner: "Pemenang!", rank: "", final_score: "Skor", record: "Rekor",

            diff_easy: "Kali (x10, x100)", diff_normal: "Bagi (1/10, 1/100)", diff_mix: "Campuran",

            mode_chall: "🏅 Tantangan (10)", mode_surv: "☠️ Bertahan Hidup",

            p_1: "1 Pemain", p_n: " Pemain", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Jawaban", btn_submit: "Kirim",

            team_dog: "Anjing", team_cat: "Kucing", team_rabbit: "Kelinci", team_hamster: "Hamster", team_panda: "Panda", team_monkey: "Monyet"

        },

        vi: {

            title: "🎯Vua Thập Phân", pageTitle: "Vua Thập Phân - Trận Chiến Toán Học", desc: "Di chuyển dấu thập phân để tìm câu trả lời đúng!",

            intro: "Chơi để trở thành Vua Toán Học.", slogan: "Dịch chuyển số thập phân!",

            label_diff: "📚 Độ Khó", label_mode: "🏆 Chế Độ", label_player: "👥 Người Chơi",

            btn_start: "Bắt Đầu", btn_restart: "Trở Về", link_other: "🎮 Trò Chơi Khác",

            header_title: "Vua Thập Phân", result_title: "Kết Quả", q_num: "Vòng", ready: "Đang tạo...",

            submitted: "Đã Gửi", score_pt: "điểm", survived_count: "Sống sót", eliminated: "Bị Loại", winner: "Chiến Thắng!", rank: "", final_score: "Điểm Số", record: "Kỷ Lục",

            diff_easy: "Nhân (x10, x100)", diff_normal: "Chia (1/10, 1/100)", diff_mix: "Hỗn Hợp",

            mode_chall: "🏅 Thử Thách (10)", mode_surv: "☠️ Sinh Tồn",

            p_1: "1 Người", p_n: " Người", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Đáp án", btn_submit: "Gửi",

            team_dog: "Chó", team_cat: "Mèo", team_rabbit: "Thỏ", team_hamster: "Chuột", team_panda: "Gấu Trúc", team_monkey: "Khỉ"

        },

        th: {

            title: "🎯ราชาจุดทศนิยม", pageTitle: "ราชาจุดทศนิยม - ศึกคณิตศาสตร์", desc: "เลื่อนจุดทศนิยมเพื่อหาคำตอบที่ถูกต้อง!",

            intro: "เล่นเพื่อเป็นราชาคณิตศาสตร์", slogan: "เลื่อนจุดทศนิยม!",

            label_diff: "📚 ความยาก", label_mode: "🏆 โหมดเกม", label_player: "👥 ผู้เล่น",

            btn_start: "เริ่มเกม", btn_restart: "กลับหน้าแรก", link_other: "🎮 เกมอื่น ๆ",

            header_title: "ราชาทศนิยม", result_title: "ผลลัพธ์", q_num: "รอบ", ready: "กำลังสร้าง...",

            submitted: "ส่งแล้ว", score_pt: "คะแนน", survived_count: "รอดชีวิต", eliminated: "ตกรอบ", winner: "ผู้ชนะ!", rank: "", final_score: "คะแนนรวม", record: "สถิติ",

            diff_easy: "คูณ (x10, x100)", diff_normal: "หาร (1/10, 1/100)", diff_mix: "ผสม",

            mode_chall: "🏅 ท้าทาย (10)", mode_surv: "☠️ เอาชีวิตรอด",

            p_1: "1 ผู้เล่น", p_n: " ผู้เล่น", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "คำตอบ", btn_submit: "ส่ง",

            team_dog: "หมา", team_cat: "แมว", team_rabbit: "กระต่าย", team_hamster: "หนูแฮมสเตอร์", team_panda: "แพนด้า", team_monkey: "ลิง"

        },

        'pt-br': {

            title: "🎯Rei do Decimal", pageTitle: "Rei do Decimal - Batalha de Lógica", desc: "Mova a vírgula decimal para encontrar a resposta correta!",

            intro: "Jogue para ser o Rei da Matemática.", slogan: "Mova a vírgula para vencer!",

            label_diff: "📚 Dificuldade", label_mode: "🏆 Modo de Jogo", label_player: "👥 Jogadores",

            btn_start: "Começar Jogo", btn_restart: "Voltar ao Início", link_other: "🎮 Mais Jogos",

            header_title: "Rei Decimal", result_title: "Resultados", q_num: "Rodada", ready: "Gerando...",

            submitted: "Enviado", score_pt: "pts", survived_count: "Sobreviveu", eliminated: "Eliminado", winner: "Vencedor!", rank: "º", final_score: "Pontuação Final", record: "Recorde",

            diff_easy: "Multiplicar (x10)", diff_normal: "Dividir (1/10)", diff_mix: "Misturado",

            mode_chall: "🏅 Desafio (10)", mode_surv: "☠️ Sobrevivência",

            p_1: "1 Jogador", p_n: " Jogadores", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Resposta", btn_submit: "Enviar",

            team_dog: "Cachorro", team_cat: "Gato", team_rabbit: "Coelho", team_hamster: "Hamster", team_panda: "Panda", team_monkey: "Macaco"

        },

        hi: {

            title: "🎯दशमलव किंग", pageTitle: "दशमलव किंग - गणित लॉजिक लड़ाई", desc: "सही उत्तर खोजने के लिए दशमलव को घुमाएं!",

            intro: "गणित का राजा बनने के लिए खेलें।", slogan: "दशमलव शिफ्ट करें!",

            label_diff: "📚 कठिनाई", label_mode: "🏆 गेम मोड", label_player: "👥 खिलाड़ी",

            btn_start: "गेम शुरू करें", btn_restart: "वापस जाएं", link_other: "🎮 और गेम",

            header_title: "दशमलव किंग", result_title: "परिणाम", q_num: "राउंड", ready: "उत्पन्न हो रहा है...",

            submitted: "जमा किया", score_pt: "अंक", survived_count: "बचे", eliminated: "बाहर", winner: "विजेता!", rank: "", final_score: "स्कोर", record: "रिकॉर्ड",

            diff_easy: "गुणा (x10, x100)", diff_normal: "भाग (1/10, 1/100)", diff_mix: "मिक्स",

            mode_chall: "🏅 चुनौती (10)", mode_surv: "☠️ सर्वाइवल",

            p_1: "1 खिलाड़ी", p_n: " खिलाड़ी", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "उत्तर", btn_submit: "जमा करें",

            team_dog: "कुत्ता", team_cat: "बिल्ली", team_rabbit: "खरगोश", team_hamster: "हम्सटर", team_panda: "पांडा", team_monkey: "बंदर"

        },

        bn: {

            title: "🎯দশমিক রাজা", pageTitle: "দশমিক রাজা - গণিত লজিক যুদ্ধ", desc: "সঠিক উত্তর খুঁজতে দশমিক বিন্দু সরান!",

            intro: "গণিতের রাজা হতে খেলুন।", slogan: "দশমিক সরান!",

            label_diff: "📚 অসুবিধা", label_mode: "🏆 গেম মোড", label_player: "👥 খেলোয়াড়",

            btn_start: "খেলা শুরু", btn_restart: "ফিরে যান", link_other: "🎮 আরো গেম",

            header_title: "দশমিক রাজা", result_title: "ফলাফল", q_num: "রাউন্ড", ready: "তৈরি হচ্ছে...",

            submitted: "জমা দেওয়া হয়েছে", score_pt: "পয়েন্ট", survived_count: "টিকে আছে", eliminated: "বাদ", winner: "বিজয়ী!", rank: "", final_score: "স্কোর", record: "রেকর্ড",

            diff_easy: "গুণ (x10, x100)", diff_normal: "ভাগ (1/10)", diff_mix: "মিশ্র",

            mode_chall: "🏅 চ্যালেঞ্জ (10)", mode_surv: "☠️ সারভাইভাল",

            p_1: "1 খেলোয়াড়", p_n: " খেলোয়াড়", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "উত্তর", btn_submit: "জমা দিন",

            team_dog: "কুকুর", team_cat: "বিড়াল", team_rabbit: "খরগোশ", team_hamster: "হ্যামস্টার", team_panda: "পান্ডা", team_monkey: "বানর"

        },

        tr: {

            title: "🎯Ondalık Kralı", pageTitle: "Ondalık Kralı - Matematik Mantık Savaşı", desc: "Doğru cevabı bulmak için ondalık virgülü kaydırın!",

            intro: "Matematik Kralı olmak için oyna.", slogan: "Virgülü kaydır!",

            label_diff: "📚 Zorluk", label_mode: "🏆 Oyun Modu", label_player: "👥 Oyuncular",

            btn_start: "Oyuna Başla", btn_restart: "Başa Dön", link_other: "🎮 Daha Fazla Oyun",

            header_title: "Ondalık Kralı", result_title: "Sonuçlar", q_num: "Tur", ready: "Hazırlanıyor...",

            submitted: "Gönderildi", score_pt: "puan", survived_count: "Hayatta Kaldı", eliminated: "Elendi", winner: "Kazanan!", rank: ".", final_score: "Final Puanı", record: "Rekor",

            diff_easy: "Çarp (x10, x100)", diff_normal: "Böl (1/10, 1/100)", diff_mix: "Karışık",

            mode_chall: "🏅 Meydan Okuma (10)", mode_surv: "☠️ Hayatta Kalma",

            p_1: "1 Oyuncu", p_n: " Oyuncu", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Cevap", btn_submit: "Gönder",

            team_dog: "Köpek", team_cat: "Kedi", team_rabbit: "Tavşan", team_hamster: "Hamster", team_panda: "Panda", team_monkey: "Maymun"

        },

        de: {

            title: "🎯Dezimal König", pageTitle: "Dezimal König - Mathe Logik", desc: "Verschieben Sie das Komma, um die richtige Antwort zu finden!",

            intro: "Werde der König der Mathematik.", slogan: "Verschiebe das Komma!",

            label_diff: "📚 Schwierigkeit", label_mode: "🏆 Spielmodus", label_player: "👥 Spieler",

            btn_start: "Spiel Starten", btn_restart: "Zurück", link_other: "🎮 Mehr Spiele",

            header_title: "Dezimal König", result_title: "Ergebnisse", q_num: "Runde", ready: "Generieren...",

            submitted: "Eingereicht", score_pt: "Pkt", survived_count: "Überlebt", eliminated: "Ausgeschieden", winner: "Gewinner!", rank: ".", final_score: "Endstand", record: "Rekord",

            diff_easy: "Multiplizieren (x10)", diff_normal: "Dividieren (1/10)", diff_mix: "Mischen",

            mode_chall: "🏅 Herausforderung (10)", mode_surv: "☠️ Überleben",

            p_1: "1 Spieler", p_n: " Spieler", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Antwort", btn_submit: "Senden",

            team_dog: "Hund", team_cat: "Katze", team_rabbit: "Hase", team_hamster: "Hamster", team_panda: "Panda", team_monkey: "Affe"

        },

        it: {

            title: "🎯Re dei Decimali", pageTitle: "Re dei Decimali - Battaglia Matematica", desc: "Sposta la virgola per trovare la risposta corretta!",

            intro: "Diventa il Re della Matematica.", slogan: "Sposta la virgola!",

            label_diff: "📚 Difficoltà", label_mode: "🏆 Modalità", label_player: "👥 Giocatori",

            btn_start: "Inizia Gioco", btn_restart: "Torna alla Home", link_other: "🎮 Altri Giochi",

            header_title: "Re Decimali", result_title: "Risultati", q_num: "Round", ready: "Generazione...",

            submitted: "Inviato", score_pt: "pt", survived_count: "Sopravvissuto", eliminated: "Eliminato", winner: "Vincitore!", rank: "º", final_score: "Punteggio Finale", record: "Record",

            diff_easy: "Moltiplicare (x10)", diff_normal: "Dividere (1/10)", diff_mix: "Misto",

            mode_chall: "🏅 Sfida (10)", mode_surv: "☠️ Sopravvivenza",

            p_1: "1 Giocatore", p_n: " Giocatori", domain_txt: "KingsMath.com", domain_url: "/",

            format_mul: " x {0}", txt_answer_label: "Risposta", btn_submit: "Invia",

            team_dog: "Cane", team_cat: "Gatto", team_rabbit: "Coniglio", team_hamster: "Criceto", team_panda: "Panda", team_monkey: "Scimmia"

        }

    };



    // 토스트 알림 번역

    const toastTranslations = {

        'ko': '언어가 변경되었습니다.', 'en': 'Language changed.', 'zh': '语言已更改。', 'ja': '言語が変更されました。',

        'es': 'Idioma cambiado.', 'fr': 'Langue modifiée.', 'ru': 'Язык изменен.', 'vi': 'Ngôn ngữ đã được thay đổi.',

        'id': 'Bahasa telah diubah.', 'th': 'เปลี่ยนภาษาเรียบร้อยแล้ว', 'pt-br': 'Idioma alterado.', 'hi': 'भाषा बदल दी गई है.',

        'bn': 'ভাষা পরিবর্তন করা হয়েছে.', 'tr': 'Dil değiştirildi.', 'de': 'Sprache geändert.', 'it': 'Lingua cambiata.'

    };



    // 16개국 언어 목록

    const languages = [

        { code: 'ko', country: 'kr', name: '한국어', enName: 'Korean' },

        { code: 'en', country: 'us', name: 'English', enName: 'English' },

        { code: 'zh', country: 'cn', name: '中文 (简体)', enName: 'Chinese (Simplified)' },

        { code: 'ja', country: 'jp', name: '日本語', enName: 'Japanese' },

        { code: 'es', country: 'es', name: 'Español', enName: 'Spanish' },

        { code: 'fr', country: 'fr', name: 'Français', enName: 'French' },

        { code: 'de', country: 'de', name: 'Deutsch', enName: 'German' },

        { code: 'it', country: 'it', name: 'Italiano', enName: 'Italian' },

        { code: 'pt-br', country: 'br', name: 'Português', enName: 'Portuguese (Brazil)' },

        { code: 'ru', country: 'ru', name: 'Русский', enName: 'Russian' },

        { code: 'hi', country: 'in', name: 'हिन्दी', enName: 'Hindi' },

        { code: 'bn', country: 'bd', name: 'বাংলা', enName: 'Bengali' },

        { code: 'id', country: 'id', name: 'Bahasa Indonesia', enName: 'Indonesian' },

        { code: 'vi', country: 'vn', name: 'Tiếng Việt', enName: 'Vietnamese' },

        { code: 'th', country: 'th', name: 'ไทย', enName: 'Thai' },

        { code: 'tr', country: 'tr', name: 'Türkçe', enName: 'Turkish' }

    ];



    let currentLang = 'en';



    // -------------------------------------------------------------

    // 다국어 UI 제어 로직

    // -------------------------------------------------------------

    const langBtnContainer = document.getElementById('langBtnContainer');

    const langTriggerBtn = document.getElementById('langTriggerBtn');

    const langModal = document.getElementById('langModal');

    const closeModalBtn = document.getElementById('closeModalBtn');

    const searchInput = document.getElementById('searchInput');

    const languageGrid = document.getElementById('languageGrid');

    const noResult = document.getElementById('noResult');

    const currentFlag = document.getElementById('currentFlag');

    const currentLangName = document.getElementById('currentLangName');



    const toast = document.getElementById('toastNotification');

    const toastLangName = document.getElementById('toastLangName');

    let toastTimeout;



    function renderLanguages(filterText = '') {

        languageGrid.innerHTML = '';

        const lowerFilter = filterText.toLowerCase();



        const filteredLangs = languages.filter(lang => 

            lang.name.toLowerCase().includes(lowerFilter) || 

            lang.enName.toLowerCase().includes(lowerFilter) ||

            lang.code.toLowerCase().includes(lowerFilter)

        );



        if (filteredLangs.length === 0) {

            languageGrid.classList.add('hidden');

            noResult.classList.remove('hidden');

            noResult.classList.add('flex');

        } else {

            languageGrid.classList.remove('hidden');

            noResult.classList.add('hidden');

            noResult.classList.remove('flex');



            filteredLangs.forEach(lang => {

                const isSelected = lang.code === currentLang;

                const baseClasses = "flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all duration-200 group relative overflow-hidden text-left";

                const stateClasses = isSelected 

                    ? "bg-gray-800 border-yellow-500 ring-1 ring-yellow-500/50" 

                    : "bg-gray-800/40 border-gray-700 hover:bg-gray-700 hover:border-gray-500";

                const flagUrl = `https://flagcdn.com/w40/${lang.country}.png`;



                const btn = document.createElement('div');

                btn.className = `${baseClasses} ${stateClasses}`;

                const dirAttr = lang.dir === 'rtl' ? 'dir="rtl"' : '';



                btn.innerHTML = `

                    <div class="shrink-0 w-8 h-6 rounded overflow-hidden shadow-sm border border-gray-700/50">

                        <img src="${flagUrl}" alt="${lang.country}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">

                    </div>

                    <div class="flex-1 min-w-0 flex flex-col items-start" ${dirAttr} style="font-family:'Noto Sans', sans-serif;">

                        <span class="text-sm font-bold text-white truncate w-full ${isSelected ? 'text-yellow-400' : ''}">${lang.name}</span>

                        <span class="text-xs text-gray-400 truncate w-full">${lang.enName}</span>

                    </div>

                    ${isSelected ? '<i class="fa-solid fa-circle-check text-yellow-500 text-lg ml-2"></i>' : ''}

                `;



                btn.addEventListener('click', () => {

                    selectLanguage(lang);

                });

                languageGrid.appendChild(btn);

            });

        }

    }



    function selectLanguage(lang) {

        changeLanguage(lang.code);

        closeModal();



        const toastMsg = toastTranslations[lang.code] || toastTranslations['en'];

        document.getElementById('ui-toast').textContent = toastMsg;

        toastLangName.textContent = lang.name;



        toast.classList.add('show');

        clearTimeout(toastTimeout);

        toastTimeout = setTimeout(() => { toast.classList.remove('show'); }, 3000);



        renderLanguages(searchInput.value);

    }



    function openModal() {

        langModal.classList.add('active');

        document.body.style.overflow = 'hidden';

        setTimeout(() => searchInput.focus(), 100);

    }



    function closeModal() {

        langModal.classList.remove('active');

        document.body.style.overflow = '';

    }



    langTriggerBtn.addEventListener('click', openModal);

    closeModalBtn.addEventListener('click', closeModal);

    langModal.addEventListener('click', (e) => { if (e.target === langModal) closeModal(); });

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && langModal.classList.contains('active')) closeModal(); });

    searchInput.addEventListener('input', (e) => renderLanguages(e.target.value));





    // -------------------------------------------------------------

    // 언어 적용 및 게임 로직

    // -------------------------------------------------------------

    function detectLanguage() {

        const params = new URLSearchParams(window.location.search);

        const urlLang = params.get('lang');

        if (urlLang && STRINGS[urlLang]) return urlLang;



        const saved = localStorage.getItem('kingsmath_lang');

        if (saved && STRINGS[saved]) return saved;



        const navLang = navigator.language.slice(0, 2);

        if (STRINGS[navLang]) return navLang;



        return 'en';

    }

    function tr(key) {
        if (STRINGS[currentLang] && STRINGS[currentLang][key]) return STRINGS[currentLang][key];
        if (STRINGS.en && STRINGS.en[key]) return STRINGS.en[key];
        return key;
    }

    function normalizeNumber(num) {
        return parseFloat(Number(num).toFixed(10));
    }

    function updateSelectOptions() {
        const txt = STRINGS[currentLang];

        const diffSelect = document.getElementById('difficulty');
        const diffVal = diffSelect.value;

        const diffOptions = [
            ['dec_easy', tr('diff_dec_easy')],
            ['dec_normal', tr('diff_dec_normal')],
            ['dec_mix', tr('diff_dec_mix')],
            ['int_easy', tr('diff_int_easy')],
            ['int_normal', tr('diff_int_normal')],
            ['int_mix', tr('diff_int_mix')],
            ['all_mix', tr('diff_all_mix')]
        ];

        diffSelect.innerHTML = diffOptions
            .map(([value, label]) => `<option value="${value}">${label}</option>`)
            .join('');

        const validDiffValues = diffOptions.map(([value]) => value);
        diffSelect.value = validDiffValues.includes(diffVal) ? diffVal : 'all_mix';

        const modeSelect = document.getElementById('gameMode');
        const modeVal = modeSelect.value;
        modeSelect.innerHTML = `
            <option value="challenge">${txt.mode_chall}</option>
            <option value="survival">${txt.mode_surv}</option>
        `;
        modeSelect.value = modeVal || 'challenge';

        const playerSelect = document.getElementById('playerCount');
        const playerVal = playerSelect.value;
        playerSelect.innerHTML = '';

        for(let i=1; i<=6; i++) {
            const label = i === 1 ? txt.p_1 : `${i}${txt.p_n}`;
            const opt = document.createElement('option');
            opt.value = i;
            opt.innerText = label;
            playerSelect.appendChild(opt);
        }

        playerSelect.value = playerVal || '6';
    }



    function changeLanguage(langCode) {

        if(!STRINGS[langCode]) return;

        currentLang = langCode;

        localStorage.setItem('kingsmath_lang', langCode);



        const url = new URL(window.location);

        url.searchParams.set('lang', langCode);

        window.history.pushState({}, '', url);



        // 업데이트 상단 트리거 버튼 UI

        const langInfo = languages.find(l => l.code === langCode) || languages[1];

        currentFlag.src = `https://flagcdn.com/w40/${langInfo.country}.png`;

        currentLangName.textContent = langInfo.name;



        // body에 폰트 제어용 클래스 토글 (한국어=Jua, 이외=기본 Sans-serif)

        if(langCode === 'ko') document.body.classList.remove('lang-non-ko');

        else document.body.classList.add('lang-non-ko');



        applyLanguage();

    }



    function applyLanguage() {

        const txt = STRINGS[currentLang];

        const isMobile = window.innerWidth <= 768;



        document.querySelectorAll('[data-lang-key]').forEach(el => {

            const key = el.getAttribute('data-lang-key');

            if (txt[key]) el.innerText = txt[key];

        });



        // SEO 및 타이틀 업데이트

        document.title = txt.pageTitle;

        document.getElementById('pageDesc').setAttribute('content', txt.desc);



        // 하이퍼링크 및 로고 텍스트 도메인 분기

        const topLink = document.getElementById('topLink');

        topLink.innerText = txt.domain_txt;

        topLink.href = txt.domain_url; // 하드코딩



        document.getElementById('otherGameLink').href = txt.domain_url;



        updateSelectOptions();

    }



    // 초기 오디오 및 사운드 시스템 유지

    const AudioContext = window.AudioContext || window.webkitAudioContext;

    let audioCtx;



    function initAudio() { if (!audioCtx) audioCtx = new AudioContext(); }



    function playSound(type) {

        if(!audioCtx) return;

        const osc = audioCtx.createOscillator();

        const gain = audioCtx.createGain();

        const t = audioCtx.currentTime;



        if (type === 'start') {

            osc.type = 'triangle'; osc.frequency.setValueAtTime(440, t); osc.frequency.linearRampToValueAtTime(880, t + 0.3);

            gain.gain.setValueAtTime(0.3, t); gain.gain.linearRampToValueAtTime(0, t + 0.5);

            osc.connect(gain); gain.connect(audioCtx.destination); osc.start(t); osc.stop(t + 0.5);

        } else if (type === 'count') {

            osc.type = 'sine'; osc.frequency.setValueAtTime(600, t);

            gain.gain.setValueAtTime(0.3, t); gain.gain.exponentialRampToValueAtTime(0.01, t + 0.1);

            osc.connect(gain); gain.connect(audioCtx.destination); osc.start(t); osc.stop(t + 0.1);

        } else if (type === 'click') {

            osc.type = 'square'; osc.frequency.setValueAtTime(1200, t);

            gain.gain.setValueAtTime(0.1, t); gain.gain.exponentialRampToValueAtTime(0.01, t + 0.05);

            osc.connect(gain); gain.connect(audioCtx.destination); osc.start(t); osc.stop(t + 0.05);

        } else if (type === 'correct') {

            const osc2 = audioCtx.createOscillator(); const gain2 = audioCtx.createGain();

            osc.frequency.setValueAtTime(523.25, t); osc2.frequency.setValueAtTime(659.25, t + 0.1);

            gain.gain.setValueAtTime(0.3, t); gain.gain.linearRampToValueAtTime(0, t + 0.4);

            gain2.gain.setValueAtTime(0, t); gain2.gain.linearRampToValueAtTime(0.3, t + 0.1); gain2.gain.linearRampToValueAtTime(0, t + 0.5);

            osc.connect(gain); gain.connect(audioCtx.destination); osc2.connect(gain2); gain2.connect(audioCtx.destination);

            osc.start(t); osc.stop(t + 0.4); osc2.start(t); osc2.stop(t + 0.5);

        } else if (type === 'wrong') {

            osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, t); osc.frequency.linearRampToValueAtTime(100, t + 0.3);

            gain.gain.setValueAtTime(0.3, t); gain.gain.linearRampToValueAtTime(0, t + 0.3);

            osc.connect(gain); gain.connect(audioCtx.destination); osc.start(t); osc.stop(t + 0.3);

        } else if (type === 'end') {

            const freqs = [523.25, 659.25, 783.99, 1046.50];

            freqs.forEach((f, i) => {

                const o = audioCtx.createOscillator(); const g = audioCtx.createGain();

                o.type = 'triangle'; o.frequency.value = f;

                g.gain.setValueAtTime(0.2, t + i*0.1); g.gain.linearRampToValueAtTime(0, t + i*0.1 + 0.5);

                o.connect(g); g.connect(audioCtx.destination); o.start(t + i*0.1); o.stop(t + i*0.1 + 0.5);

            });

        }

    }



    const state = {

        players: [], mode: 'challenge', difficulty: 'all_mix',

        currentQ: 0, maxQ: 10, timer: 30, timerInterval: null,

        phase: 'setup', problem: null

    };



    const TEAM_DATA = [

        { id: 'dog', icon: '🐶', color: 'bg-dog' },

        { id: 'cat', icon: '🐱', color: 'bg-cat' },

        { id: 'rabbit', icon: '🐰', color: 'bg-rabbit' },

        { id: 'hamster', icon: '🐹', color: 'bg-hamster' },

        { id: 'panda', icon: '🐼', color: 'bg-panda' },

        { id: 'monkey', icon: '🐵', color: 'bg-monkey' }

    ];



    function getRandomInt(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }



    function getMultiplierText(mulValue) {

        const lang = currentLang;

        const makeFraction = (bottom) => `<span style="display:inline-flex; flex-direction:column; vertical-align:middle; text-align:center; font-size: 0.75em; line-height: 1.1; margin: 0 4px;"><span style="border-bottom: 0.08em solid currentColor; padding-bottom: 2px;">1</span><span style="padding-top: 2px;">${bottom}</span></span>`;



        if(lang === 'ko') {

            if(mulValue === 10) return "10배";

            if(mulValue === 100) return "100배";

            if(mulValue === 1000) return "1000배";

            if(mulValue === 10000) return "10000배";

            if(mulValue === 0.1) return makeFraction(10);

            if(mulValue === 0.01) return makeFraction(100);

            if(mulValue === 0.001) return makeFraction(1000);

            if(mulValue === 0.0001) return makeFraction(10000);

        } else {

            if(mulValue >= 10) return "x" + mulValue;

            if(mulValue === 0.1) return makeFraction(10);

            if(mulValue === 0.01) return makeFraction(100);

            if(mulValue === 0.001) return makeFraction(1000);

            if(mulValue === 0.0001) return makeFraction(10000);

        }

        return mulValue;

    }



    function getFormattedNumber(base, shift) {
        let val = normalizeNumber(base * Math.pow(10, shift));
        let str = val.toFixed(10).replace(/\.?0+$/, '');

        if (!str.includes('.')) {
            str += '<span style="color: #888;">.</span>';
        }

        return str;
    }

    function resolveProblemType(diff) {
        switch (diff) {
            case 'dec_easy':
                return { numberType: 'decimal', opType: 'expand' };
            case 'dec_normal':
                return { numberType: 'decimal', opType: 'shrink' };
            case 'dec_mix':
                return { numberType: 'decimal', opType: 'random' };

            case 'int_easy':
                return { numberType: 'integer', opType: 'expand' };
            case 'int_normal':
                return { numberType: 'integer', opType: 'shrink' };
            case 'int_mix':
                return { numberType: 'integer', opType: 'random' };

            case 'all_mix':
            default:
                return { numberType: 'random', opType: 'random' };
        }
    }

    function createDecimalBase() {
        while (true) {
            const dp = getRandomInt(1, 4);
            const scale = Math.pow(10, dp);
            const rawMax = Math.pow(10, dp + 4) - 1;
            const raw = getRandomInt(1, rawMax);

            if (raw % scale === 0) continue;

            const base = normalizeNumber(raw / scale);

            if (base >= 0.0001 && base <= 9999.9999) {
                return base;
            }
        }
    }

    function createIntegerBase() {
        return getRandomInt(1, 999999);
    }

    function generateProblem(diff) {
        const config = resolveProblemType(diff);

        const numberType = config.numberType === 'random'
            ? (Math.random() > 0.5 ? 'decimal' : 'integer')
            : config.numberType;

        const opType = config.opType === 'random'
            ? (Math.random() > 0.5 ? 'expand' : 'shrink')
            : config.opType;

        const possibleMuls = opType === 'expand'
            ? [10, 100, 1000, 10000]
            : [0.1, 0.01, 0.001, 0.0001];

        const mul = possibleMuls[getRandomInt(0, possibleMuls.length - 1)];
        const mulText = getMultiplierText(mul);

        let base = 0, ans = 0;

        while (true) {
            base = numberType === 'integer'
                ? createIntegerBase()
                : createDecimalBase();

            ans = normalizeNumber(base * mul);

            if (numberType === 'integer') {
                break;
            } else {
                if (ans <= 10000 && ans >= 0.001 && base <= 10000 && base >= 0.001) break;
            }
        }

        const baseStr = getFormattedNumber(base, 0);
        const txt = STRINGS[currentLang];
        const formatStr = txt.format_mul.replace('{0}', mulText);

        const qHtml = `<div class="main-question-box"><span style="color:#ffd43b;">${baseStr}</span><span style="color:#69db7c;">${formatStr}</span></div>`;
        const miniQ = `<div style="font-size:1.3rem; color:#ffd43b; font-weight:bold;">${baseStr}${formatStr}</div>`;

        return { q: qHtml, miniQ: miniQ, base: base, mul: mul, correctAns: ans, mulText: mulText, baseStr: baseStr, numberType: numberType, opType: opType };
    }



    function initGame() {

        initAudio();

        const pCount = parseInt(document.getElementById('playerCount').value);

        state.difficulty = document.getElementById('difficulty').value;

        state.mode = document.getElementById('gameMode').value;

        state.currentQ = 0;

        state.players = [];



        const arena = document.getElementById('arena');

        arena.innerHTML = '';


        const txt = STRINGS[currentLang];



        TEAM_DATA[0].name = txt.team_dog;

        TEAM_DATA[1].name = txt.team_cat;

        TEAM_DATA[2].name = txt.team_rabbit;

        TEAM_DATA[3].name = txt.team_hamster;

        TEAM_DATA[4].name = txt.team_panda;

        TEAM_DATA[5].name = txt.team_monkey;



        for(let i=0; i<pCount; i++) {

            state.players.push({ ...TEAM_DATA[i], score: 0, survived: 0, alive: true, answered: false, shift: 0, base: 0 });
            const col = document.createElement('div');

            col.className = `player-col ${state.players[i].color}`;

            col.id = `p-${i}`;

            col.innerHTML = `

                <div class="p-header">

                    <span class="p-emoji">${state.players[i].icon}</span>

                    <span class="p-name">${state.players[i].name}</span>

                    <div class="p-info" id="p-info-${i}">${state.mode==='challenge' ? '0' : '0'}${state.mode==='challenge' ? txt.score_pt : ''}</div>

                </div>

                <div class="mini-question" id="p-q-${i}">...</div>

                <div class="p-current-display" id="p-disp-${i}">0<span style="color: #888;">.</span></div>

                <div class="choice-grid" id="p-choices-${i}">

                    <button class="choice-btn" onpointerdown="event.preventDefault(); moveDecimal(${i}, -1)">◀</button>

                    <button class="choice-btn btn-submit" onpointerdown="event.preventDefault(); submitAnswer(${i})">${txt.btn_submit}</button>

                    <button class="choice-btn" onpointerdown="event.preventDefault(); moveDecimal(${i}, 1)">▶</button>

                </div>

                <div class="feedback-overlay" id="p-fb-${i}"></div>

                <div class="final-status" id="p-final-${i}"></div>

            `;

            arena.appendChild(col);

        }



        // 게임 시작 시 언어 선택 UI 숨김

        langBtnContainer.style.display = 'none';


        document.getElementById('overlay').style.display = 'none';

        document.getElementById('resultPopup').style.display = 'none';


        playSound('start');

        nextTurn();

    }



    async function nextTurn() {

        const aliveCount = state.players.filter(p=>p.alive).length;

        if (state.mode === 'survival' && aliveCount <= 1 && state.players.length > 1) { endGame(); return; }

        if (state.mode === 'survival' && aliveCount === 0) { endGame(); return; }

        if (state.mode === 'challenge' && state.currentQ >= 10) { endGame(); return; }



        state.currentQ++;

        state.phase = 'counting';

        state.problem = generateProblem(state.difficulty);



        state.players.forEach((p, idx) => {

            if(p.alive) {

                p.answered = false; 

                p.shift = 0;

                p.base = state.problem.base;

                const fb = document.getElementById(`p-fb-${idx}`);

                fb.className = 'feedback-overlay'; fb.innerHTML = '';

                document.getElementById(`p-q-${idx}`).innerHTML = '';

                document.getElementById(`p-disp-${idx}`).innerHTML = getFormattedNumber(p.base, p.shift);

            }

        });



        const maxQText = state.mode==='challenge' ? '10' : '∞';

        const txt = STRINGS[currentLang];


        document.getElementById('qNumber').innerText = `${txt.q_num} ${state.currentQ} / ${maxQText}`;

        document.getElementById('bigQuestion').innerText = txt.ready;


        await runCountdown();



        state.phase = 'playing';

        document.getElementById('bigQuestion').innerHTML = state.problem.q;



        state.players.forEach((p, idx) => {

            if(p.alive) {

                document.getElementById(`p-q-${idx}`).innerHTML = state.problem.miniQ;

            }

        });


        startTimer();

    }



    async function runCountdown() {

        const layer = document.getElementById('countdownLayer');

        const txt = document.getElementById('countdownText');

        layer.style.display = 'flex';


        for(let i=3; i>0; i--) {

            txt.innerText = i; 

            txt.classList.remove('count-animate');

            void txt.offsetWidth; 

            txt.classList.add('count-animate');

            playSound('count'); 

            await new Promise(r => setTimeout(r, 1000));

        }

        layer.style.display = 'none';

    }



    function startTimer() {

        if(state.mode === 'survival') state.timer = Math.max(5, 30 - (state.currentQ - 1) * 2);

        else state.timer = 30;



        const tBox = document.getElementById('mainTimer');

        tBox.innerText = state.timer; tBox.classList.remove('timer-urgent');


        if(state.timerInterval) clearInterval(state.timerInterval);

        state.timerInterval = setInterval(() => {

            state.timer--; tBox.innerText = state.timer;

            if(state.timer <= 5) { tBox.classList.add('timer-urgent'); playSound('count'); }

            if(state.timer <= 0) finishRound();

        }, 1000);

    }



    window.moveDecimal = function(pIdx, dir) {

        if(state.phase !== 'playing') return;

        const p = state.players[pIdx];

        if(p.answered || !p.alive) return;



        playSound('click');

        p.shift += dir;


        if (p.shift > 6) p.shift = 6;

        if (p.shift < -6) p.shift = -6;



        const disp = document.getElementById(`p-disp-${pIdx}`);

        disp.innerHTML = getFormattedNumber(p.base, p.shift);


        disp.classList.remove('bump-anim');

        void disp.offsetWidth;

        disp.classList.add('bump-anim');

    }



    window.submitAnswer = function(pIdx) {

        if(state.phase !== 'playing') return;

        const p = state.players[pIdx];

        if(p.answered || !p.alive) return;



        playSound('click');

        p.answered = true;


        const fb = document.getElementById(`p-fb-${pIdx}`);

        const txt = STRINGS[currentLang];

        fb.innerHTML = `<div style="font-size:3rem; margin-bottom:10px;">🔒</div><div class="submitted-text">${txt.submitted}</div>`;

        fb.classList.add('secret'); 


        const living = state.players.filter(pl => pl.alive);

        if(living.every(pl => pl.answered)) finishRound();

    }



    function finishRound() {

        clearInterval(state.timerInterval);

        state.phase = 'result';


        const correctAnsVal = state.problem.correctAns;

        const correctAnsStr = getFormattedNumber(correctAnsVal, 0);

        let someoneWrong = false;

        const txt = STRINGS[currentLang];



        const mainBoard = document.getElementById('bigQuestion');

        mainBoard.innerHTML = `

            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; animation: popIn 0.5s;">

                <div style="font-size:1.5rem; color:#aaa; margin-bottom:10px;">${txt.txt_answer_label}</div>

                <div style="font-size: clamp(3.5rem, 6vw, 5rem); color: #69db7c; font-weight: bold; font-family: inherit;">

                    ${correctAnsStr}

                </div>

            </div>

        `;



        state.players.forEach((p, idx) => {

            if(!p.alive) return;

            const fb = document.getElementById(`p-fb-${idx}`);

            const info = document.getElementById(`p-info-${idx}`);


            fb.classList.remove('secret'); fb.innerHTML = '';


            const playerVal = normalizeNumber(p.base * Math.pow(10, p.shift));

            const isCorrect = (playerVal === correctAnsVal);



            if(isCorrect) {

                fb.innerHTML = '<span style="color:#69db7c">⭕</span>'; fb.classList.add('show-result');

                p.survived++;

                if(state.mode === 'challenge') { 

                    p.score += 10; 

                    info.innerText = `${p.score}${txt.score_pt}`; 

                } else { 

                    if(currentLang === 'ko') info.innerText = `${p.survived}문제 생존`; 

                    else info.innerText = `${p.survived} ${txt.survived_count}`;

                }

            } else {

                someoneWrong = true;

                fb.innerHTML = '<span style="color:#ff6b6b">❌</span>'; fb.classList.add('show-result');

                if(state.mode === 'survival') {

                    p.alive = false;

                    setTimeout(() => {

                        document.getElementById(`p-${idx}`).classList.add('eliminated');

                        info.innerText = `${txt.eliminated} (${p.survived})`;

                    }, 1500);

                }

            }

        });



        if(!someoneWrong) playSound('correct'); else playSound('wrong');

        setTimeout(() => nextTurn(), 3000);

    }



    function endGame() {

        clearInterval(state.timerInterval);

        state.phase = 'gameover';

        playSound('end');


        // 게임 오버시 언어 선택 UI 다시 표시

        langBtnContainer.style.display = 'block';



        const popup = document.getElementById('resultPopup');

        const list = document.getElementById('finalResultList');

        popup.style.display = 'flex'; list.innerHTML = '';


        const txt = STRINGS[currentLang];



        state.players.forEach((p, idx) => {

            const finalDiv = document.getElementById(`p-final-${idx}`);

            finalDiv.style.display = 'block';

            if (state.mode === 'challenge') { 

                finalDiv.innerHTML = `<h3>${txt.final_score}</h3><p>${p.score}${txt.score_pt}</p>`; 

            } else { 

                const survText = currentLang === 'ko' ? `${p.survived}문제` : `${p.survived}`;

                finalDiv.innerHTML = p.alive ? `<h3>${txt.record}</h3><p>${txt.winner}</p>` : `<h3>${txt.record}</h3><p>${survText}</p>`; 

                if(p.alive) finalDiv.style.border = "4px solid #ffd43b"; 

            }

        });



        let ranked = [...state.players];

        if(state.mode === 'challenge') ranked.sort((a,b) => b.score - a.score);

        else ranked.sort((a,b) => { if (a.alive && !b.alive) return -1; if (!a.alive && b.alive) return 1; return b.survived - a.survived; });



        let currentRank = 1;

        ranked.forEach((p, i) => {

            if (i > 0) {

                const prev = ranked[i-1];

                let isTie = false;


                if (state.mode === 'challenge') { if (p.score === prev.score) isTie = true; } 

                else {

                    if (p.alive && prev.alive) isTie = true;

                    else if (!p.alive && !prev.alive && p.survived === prev.survived) isTie = true;

                }


                if (!isTie) currentRank = i + 1;

            } else { currentRank = 1; }



            const li = document.createElement('li');

            li.className = `result-item ${currentRank===1 ? 'rank-1' : ''}`;


            let status;

            if (state.mode === 'challenge') status = `${p.score}${txt.score_pt}`;

            else status = p.alive ? txt.winner : `${p.survived}`;



            let rankStr = `${currentRank}${txt.rank}`;

            if(currentLang === 'zh') rankStr = `第${currentRank}名`;

            else if(currentLang === 'ru') rankStr = `${currentRank} место`;



            li.innerHTML = `<span>${rankStr} ${p.icon} ${p.name}</span><span>${status}</span>`;

            list.appendChild(li);

        });

    }



    document.getElementById('fullscreenBtn').addEventListener('click', () => {

        if (!document.fullscreenElement) {

            document.documentElement.requestFullscreen().catch(err => {

                console.log(`Error: ${err.message}`);

            });

        } else { document.exitFullscreen(); }

    });



    document.getElementById('startBtn').addEventListener('click', initGame);



    // 처음으로 돌아가기 버튼

    document.getElementById('restartBtn').addEventListener('click', () => { 

        document.getElementById('overlay').style.display = 'flex'; 

        document.getElementById('resultPopup').style.display = 'none'; 

    });



    // 홈(첫화면) 이모지 클릭 이벤트 처리 (전체 화면 유지 조건 반영)

    document.getElementById('btn-home').addEventListener('click', () => {

        // 1. 전체 화면 모드라면

        if (document.fullscreenElement) {

            // 화면 초기화 로직 실행 (새로고침 안함)

            state.phase = 'setup';

            clearInterval(state.timerInterval);


            langBtnContainer.style.display = 'block'; // 홈으로 올 때 언어버튼 다시 활성화

            document.getElementById('resultPopup').style.display = 'none'; 

            document.getElementById('countdownLayer').style.display = 'none'; 

            document.getElementById('overlay').style.display = 'flex'; 

            document.getElementById('arena').innerHTML = ''; 

            document.getElementById('mainTimer').innerText = '30';

            document.getElementById('mainTimer').classList.remove('timer-urgent');

            document.getElementById('bigQuestion').innerText = STRINGS[currentLang].ready;


            if(window.playSound) window.playSound('click');

        } else {

            // 2. 전체 화면이 아니라면 그냥 새로고침

            location.reload();

        }

    });



    // 부가 이벤트 제어

    document.addEventListener('dblclick', function(event) { event.preventDefault(); }, { passive: false });

    document.addEventListener('contextmenu', event => event.preventDefault());

    document.addEventListener('dragstart', event => event.preventDefault());

    document.addEventListener('wheel', function(e) { if (e.ctrlKey) { e.preventDefault(); } }, { passive: false });

    document.addEventListener('touchstart', function(e) { if (e.touches.length > 1) { e.preventDefault(); } }, { passive: false });



    window.addEventListener('DOMContentLoaded', () => {

        // 렌더링 호출

        renderLanguages();


        // 기본 언어 세팅

        const langCode = detectLanguage();

        changeLanguage(langCode);

    });



    </script>

</body>

</html>
