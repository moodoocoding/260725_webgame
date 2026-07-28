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
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="theme-color" content="#0F4C3A">

<title id="pageTitle">문해력킹 골든벨</title>
<meta id="pageDesc" name="description" content="어휘력을 키워보세요! 문해력킹 골든벨.">
<meta id="pageKeywords" name="keywords" content="문해력, 국어, 골든벨, 교육게임, 초등교육, 어휘력">
<link rel="canonical" href="/">

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
@import url('https://fonts.googleapis.com/css2?family=Jua&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap');

/* 기존 게임 기본 폰트 설정 (유지) */
body { font-family: 'Jua', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-weight: normal; }

/* 폰트어썸 아이콘 깨짐 방지용 예외 처리 */
i[class^="fa-"], i[class*=" fa-"] {
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
}

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
    touch-action: none; 
    display: flex; flex-direction: column; height: 100vh; height: 100dvh;
    transition: background 0.3s, color 0.3s;
}

.custom-scrollbar::-webkit-scrollbar { width: 8px; }
.custom-scrollbar::-webkit-scrollbar-track { background: rgba(31, 41, 55, 0.5); border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }

.dynamic-text-container {
    display: inline-block;
    white-space: nowrap;
    transition: transform 0.2s;
    transform-origin: left center;
}

/* --- Game UI Components --- */
#overlay {
    position: fixed; inset: 0; background: var(--bg); opacity: 0.98;
    display: flex; flex-direction: column; justify-content: center; align-items: center;
    z-index: 100; backdrop-filter: blur(15px);
    text-align: center; overflow-y: auto; padding-bottom: 50px;
}

.title-area { margin: 30px 0 20px 0; }
#msgTitle { font-size: 3.5rem; color: var(--dark); margin: 0; text-shadow: 0 4px 10px rgba(0,0,0,0.2); font-weight: normal; line-height: 1.2; padding: 0 10px; transition: font-size 0.3s;}
.subtitle { color: var(--text-muted); font-size: 1.2rem; margin-top: 10px; font-weight: normal; }
.top-link { color: #339af0; text-decoration: none; font-weight: normal; }

#setupArea { width: 500px; max-width: 95vw; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }
.setup-group { text-align: left; }
.setup-label { font-size: 1.3rem; color: var(--dark); margin-bottom: 8px; display: block; font-weight: normal; }

.custom-select {
    width: 100%; font-family: inherit; font-size: 1.2rem; padding: 15px;
    border-radius: 15px; border: 2px solid var(--border-color);
    background: var(--panel-bg); color: var(--dark); outline: none;
    cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-weight: normal;
}

.btn-large {
    font-size: 2rem; padding: 15px 0; border-radius: 50px; border: none;
    background: #339af0; color: white; cursor: pointer;
    box-shadow: 0 6px 0 #1c7ed6; transition: 0.1s; font-family: inherit;
    width: 100%; margin-top: 20px; font-weight: normal; overflow: hidden;
}
.btn-large:active { transform: translateY(4px); box-shadow: 0 2px 0 #1c7ed6; }
.btn-large:disabled { background: #555; box-shadow: 0 6px 0 #333; cursor: not-allowed; }
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

@media (max-width: 768px) {
    .side-btn { width: 45px; height: 45px; font-size: 1.5rem; }
    .left-btn { left: 10px; }
    .right-btn { right: 10px; }
}

/* --- Game Screen Structure --- */
#topZone { position: relative; display: flex; flex-direction: column; z-index: 10; }
#header { height: 100px; background: var(--panel-bg); display: flex; align-items: center; justify-content: center; padding: 0 20px; border-bottom: 2px solid var(--border-color); position: relative; }
.header-title { font-size: 2rem; color: var(--dark); display: flex; align-items: center; gap: 10px; position: absolute; left: 90px; font-weight: normal; }
.timer-box { font-size: 4rem; font-weight: normal; color: #ff6b6b; background: rgba(0,0,0,0.1); padding: 5px 30px; border-radius: 20px; min-width: 120px; text-align: center; }
.timer-urgent { color: #fff; background: #ff6b6b; animation: pulse 0.5s infinite; }

#mainQuestionBoard { background: var(--panel-bg); padding: 10px; text-align: center; border-bottom: 1px solid var(--border-color); display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 250px; flex-grow: 1; position: relative; }
#qNumber { font-size: 1.5rem; color: var(--text-muted); margin-bottom: 5px; font-weight: normal; }
#bigQuestion { font-size: clamp(2.5rem, 6vw, 4rem); color: var(--dark); line-height: 1.3; font-family: inherit; font-weight: normal; word-break: keep-all; }

/* Result Popup */
#resultPopup { display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(30, 30, 46, 0.98); backdrop-filter: blur(10px); z-index: 100; flex-direction: column; align-items: center; justify-content: center; border-bottom: 4px solid var(--border-color); }
#resultTitle { font-size: 3rem; margin: 0 0 20px 0; color: var(--dark); font-weight: normal; text-align: center; white-space: pre-line; }
.result-list { list-style: none; padding: 0; margin: 0; width: 80%; max-width: 600px; max-height: 60%; overflow-y: auto; }
.result-item { display: flex; justify-content: space-between; padding: 15px; background: rgba(128,128,128,0.1); margin-bottom: 8px; border-radius: 12px; font-size: 1.5rem; color: var(--dark); border: 1px solid var(--border-color); font-weight: normal; }
.rank-1 { background: #ffd43b; color: #000; font-weight: normal; border: none; }

/* Player Arena */
#arena { display: flex; flex: 1; width: 100vw; overflow: hidden; background: var(--bg); }
.player-col { flex: 1; border-right: 1px solid var(--border-color); display: flex; flex-direction: column; align-items: center; padding: 10px 5px; position: relative; transition: opacity 0.3s; }
.player-col:last-child { border-right: none; }
.player-col.eliminated { opacity: 0.3; filter: grayscale(100%); pointer-events: none; }

.p-header { text-align: center; margin-bottom: 5px; width: 100%; flex-shrink: 0;}
.p-emoji { font-size: 3rem; display: block; }
.p-name { font-size: 1.8rem; white-space: nowrap; font-weight: normal; margin-top: 5px; }

.choice-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; width: 100%; max-width: 260px; flex: 1; align-content: center; padding-bottom: 10px; }
.choice-btn { background: var(--panel-bg); border: 2px solid var(--border-color); border-radius: 12px; color: var(--dark); font-size: 0.9rem; font-family: inherit; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 0 rgba(0,0,0,0.1); transition: 0.1s; word-break: keep-all; padding: 10px; min-height: 70px; font-weight: normal; overflow: hidden; text-align: center; flex-direction: column; }
.choice-btn:active { transform: translateY(2px); box-shadow: none; }
.choice-btn.selected { background: #339af0; color: white; border-color: #1c7ed6; }
.choice-btn.correct { background: var(--correct); color: #fff; border-color: #40c057; animation: bounce 0.5s; }
.choice-btn.wrong { background: var(--wrong); color: #fff; border-color: #fa5252; opacity: 0.5; }

/* Font size modifiers for choice buttons based on content length */
.text-small-option { font-size: 0.85rem !important; }
.text-large-option { font-size: 1.3rem !important; }

/* Feedback Overlay */
.feedback-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.7); display: none; align-items: center; justify-content: center; flex-direction: column; font-size: 5rem; z-index: 20; border-radius: 0; }
.feedback-overlay.secret { background: #000000 !important; opacity: 1; display: flex; color: white; transition: none; animation: none; }
.feedback-overlay.show-result { display: flex; animation: none; }
.submitted-text { font-size: 1.5rem; margin-top: 15px; color: #fff; text-align: center; line-height: 1.5; font-weight: normal; }
.final-status { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 85%; box-sizing: border-box; background: rgba(0,0,0,0.85); padding: 15px 10px; border-radius: 15px; text-align: center; z-index: 50; border: 2px solid #fff; display: none; animation: popIn 0.5s; word-break: keep-all; overflow-wrap: break-word; }
.final-status h3 { margin: 0; font-size: 1.2rem; color: #fff; font-weight: normal; }
.final-status p { margin: 5px 0 0 0; font-size: 1.8rem; color: #ffd43b; font-weight: normal; line-height: 1.2;}

/* Countdown */
#countdownLayer { position: fixed; inset: 0; background: #000000; z-index: 2000; display: none; align-items: center; justify-content: center; }
#countdownText { font-size: 25rem; color: #ffec99; text-shadow: 0 0 50px rgba(255, 212, 59, 0.8); font-weight: normal; opacity: 0; }
@keyframes countPop { 0% { transform: scale(0.5); opacity: 0; } 50% { transform: scale(1.2); opacity: 1; } 100% { transform: scale(1.0); opacity: 0; } }
.count-animate { animation: countPop 0.8s ease-in-out forwards; }

/* 1920x1080 & Large Screen Optimization */
@media (min-width: 1600px) {
    #header { height: 140px; } .header-title { font-size: 3rem; left: 110px; } .timer-box { font-size: 6rem; padding: 10px 50px; } #mainQuestionBoard { min-height: 350px; padding: 20px; } #qNumber { font-size: 2rem; margin-bottom: 15px; } #bigQuestion { font-size: 5rem; } .p-emoji { font-size: 4rem; } .p-name { font-size: 2.2rem; } .choice-grid { max-width: 350px; gap: 15px; } .choice-btn { min-height: 90px; border-radius: 20px; border-width: 3px; } #resultTitle { font-size: 4rem; margin-bottom: 40px; } .result-item { font-size: 1.5rem; padding: 9px; } .btn-large { font-size: 2.5rem; padding: 20px 0; } #msgTitle { font-size: 5rem; } .setup-label { font-size: 1.8rem; } .custom-select { font-size: 1.5rem; padding: 20px; }
}
@media (max-width: 768px) {
    #bigQuestion { font-size: 2rem; } .choice-btn { font-size: 0.65rem !important; min-height: 50px; padding: 5px; } .header-title span:last-child { display: none; } .p-name { display: none; }
}

.bg-dog { background: var(--dog-color); } .bg-cat { background: var(--cat-color); } .bg-rabbit { background: var(--rabbit-color); } .bg-hamster { background: var(--hamster-color); } .bg-panda { background: var(--panda-color); } .bg-monkey { background: var(--monkey-color); }
</style>
</head>

<body>

    <div id="btn-home" class="side-btn left-btn" title="Home" style="cursor:pointer;">🏠</div>
    <button id="fullscreenBtn" class="side-btn right-btn" title="Full Screen" style="top: calc(50% + 70px);">⛶</button>

    <div id="overlay">
        <div class="title-area">
            <a href="/" target="_self" id="domainLink" class="top-link">KingsMath.com</a><br>
            <span style="font-size: 1.2rem; color: #339af0; margin-top:10px; display:inline-block;">
                초등 어휘와 문해력을 키워보세요.
            </span><br>
            <h1 id="msgTitle">📖 문해력킹</h1>
            <div class="subtitle">당신의 문해력은 어디까지인가요?</div>
        </div>

        <div id="setupArea">
            <div class="setup-group">
                <label class="setup-label">📚 난이도 선택</label>
                <select id="difficulty" class="custom-select">
                    <option value="easy">쉬움 (1~2학년 어휘)</option>
                    <option value="normal" selected>보통 (3~4학년 어휘)</option>
                    <option value="hard">어려움 (5~6학년 어휘)</option>
                    <option value="mix">랜덤 믹스 (전체 학년)</option>
                </select>
            </div>
            <div class="setup-group">
                <label class="setup-label">🏆 게임 모드</label>
                <select id="gameMode" class="custom-select">
                    <option value="challenge">🏅 챌린지 (10문제 대결)</option>
                    <option value="survival">☠️ 서바이벌 (10문제)</option>
                </select>
            </div>
            <div class="setup-group">
                <label class="setup-label">👥 인원</label> 
                <select id="playerCount" class="custom-select">
                    <option value="1">1인 연습</option>
                    <option value="2">2인 플레이</option>
                    <option value="3">3인 플레이</option>
                    <option value="4">4인 플레이</option>
                    <option value="5">5인 플레이</option>
                    <option value="6" selected>6인 플레이</option>
                </select>
            </div>
            <button id="startBtn" class="btn-large" disabled>
                <div class="dynamic-text-container" id="startBtnText">데이터 불러오는 중...</div>
            </button>
            <a id="otherGameLink" href="/" target="_self">🎮 다른 게임 고르기</a>
        </div>
    </div>

    <div id="countdownLayer">
        <div id="countdownText">3</div>
    </div>

    <div id="topZone">
        <header id="header">
            <div class="header-title">
                <span style="font-size:2rem;">📖</span>
                <span>문해력킹</span>
            </div>
            <div id="mainTimer" class="timer-box">30</div>
        </header>

        <div id="mainQuestionBoard">
            <div id="qNumber">문제 1 / 10</div>
            <div id="bigQuestion">준비...</div>
        </div>

        <div id="resultPopup">
            <h2 id="resultTitle">결과 발표</h2>
            <ul id="finalResultList" class="result-list"></ul>
            <button id="restartBtn" class="btn-large" style="margin-top:20px; font-size:1.5rem; width: auto; padding: 10px 40px;">
                <div class="dynamic-text-container">처음으로 돌아가기</div>
            </button>
        </div>
    </div>

    <div id="arena"></div>

<script>
/* =========================================
   Vocabulary Database (Loaded from data.json)
   ========================================= */
let vocabData = {
    easy: [],
    medium: [],
    hard: []
};

// Fetch data.json on load
async function loadVocabData() {
    try {
        const response = await fetch('data.json');
        const data = await response.json();
        
        vocabData.easy = data.grade1_2 || [];
        vocabData.medium = data.grade3_4 || [];
        vocabData.hard = data.grade5_6 || [];

        const startBtn = document.getElementById('startBtn');
        document.getElementById('startBtnText').innerText = "골든벨 시작하기";
        startBtn.disabled = false;
    } catch (error) {
        console.error('Error loading vocabulary data:', error);
        document.getElementById('startBtnText').innerText = "데이터 로드 실패";
    }
}

const TEAM_DATA = [
    { id: 'dog', icon: '🐶', color: 'bg-dog', name: '강아지' },
    { id: 'cat', icon: '🐱', color: 'bg-cat', name: '고양이' },
    { id: 'rabbit', icon: '🐰', color: 'bg-rabbit', name: '토끼' },
    { id: 'hamster', icon: '🐹', color: 'bg-hamster', name: '햄스터' },
    { id: 'panda', icon: '🐼', color: 'bg-panda', name: '판다' },
    { id: 'monkey', icon: '🐵', color: 'bg-monkey', name: '원숭이' }
];

const state = {
    players: [], mode: 'challenge', difficulty: 'normal',
    currentQ: 0, maxQ: 10, timer: 30, timerInterval: null,
    phase: 'setup', problem: null, availableVocab: { easy: [], medium: [], hard: [] },
    currentOrder: 0
};

const AudioContext = window.AudioContext || window.webkitAudioContext;
let audioCtx;
function initAudio() { if (!audioCtx) audioCtx = new AudioContext(); }

function playSound(type) {
    if(!audioCtx) return;
    const osc = audioCtx.createOscillator(); const gain = audioCtx.createGain(); const t = audioCtx.currentTime;
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
        const freqs = [523.25, 659.25, 783.99, 1046.50, 1318.51, 1567.98];
        freqs.forEach((f, i) => {
            const o = audioCtx.createOscillator(); const g = audioCtx.createGain();
            o.type = 'triangle'; o.frequency.value = f;
            g.gain.setValueAtTime(0.2, t + i*0.1); g.gain.linearRampToValueAtTime(0, t + i*0.1 + 0.5);
            o.connect(g); g.connect(audioCtx.destination); o.start(t + i*0.1); o.stop(t + i*0.1 + 0.5);
        });
    }
}

/* =========================================
   Problem Generator
   ========================================= */
function generateProblem(qNum) {
    let poolType = state.difficulty;

    if (state.mode === 'survival') {
        if (qNum <= 3) poolType = 'easy';
        else if (qNum <= 7) poolType = 'medium';
        else poolType = 'hard';
    } else if (poolType === 'mix') {
        const types = ['easy', 'medium', 'hard'];
        poolType = types[Math.floor(Math.random() * types.length)];
    } else if (poolType === 'normal') {
        poolType = 'medium'; 
    }

    let pool = state.availableVocab[poolType];
    if (!pool || pool.length < 4) {
        // Refill pool if empty or not enough for distractors
        pool = [...vocabData[poolType]];
        state.availableVocab[poolType] = pool;
    }

    let randIdx = Math.floor(Math.random() * pool.length);
    let target = pool.splice(randIdx, 1)[0]; // Pull the target word

    let distractorPool = [...vocabData[poolType]];
    let distractors = [];
    while(distractors.length < 3) {
        let d = distractorPool[Math.floor(Math.random() * distractorPool.length)];
        if (d.word !== target.word && !distractors.find(x => x.word === d.word)) {
            distractors.push(d);
        }
    }

    let qType = Math.floor(Math.random() * 3);
    let qStr, aVal, options = [];

    if (qType === 0) {
        // 1. 단어 뜻 보여주고 4개 단어 중 선택
        qStr = `<div style="font-size: clamp(1.8rem, 4vw, 3rem); line-height: 1.4; padding: 0 10px; font-family: 'Noto Sans KR', sans-serif;">${target.meaning}</div>`;
        aVal = target.word;
        options = [target, ...distractors].map(x => ({ 
            label: `<span class="text-large-option" style="font-family: 'Jua', sans-serif;">${x.word}</span>`, 
            value: x.word 
        }));
    } else if (qType === 1) {
        // 2. 단어 보여주고 4개 뜻 중 선택
        qStr = `<div style="font-size: clamp(3rem, 6vw, 5rem); color: #ffd43b; font-family: 'Jua', sans-serif;">${target.word}</div>`;
        aVal = target.meaning;
        options = [target, ...distractors].map(x => ({ 
            label: `<span class="text-small-option" style="font-family: 'Noto Sans KR', sans-serif;">${x.meaning}</span>`, 
            value: x.meaning 
        }));
    } else {
        // 3. OX 퀴즈
        let isTrue = Math.random() < 0.5;
        let displayTarget = isTrue ? target : distractors[0];
        
        qStr = `<div style="font-size: clamp(2rem, 5vw, 3rem); color: #ffd43b; margin-bottom: 10px; font-family: 'Jua', sans-serif;">[ ${target.word} ]</div>
                <div style="font-size: clamp(1.5rem, 3vw, 2.2rem); font-family: 'Noto Sans KR', sans-serif;">${displayTarget.meaning}</div>`;
        aVal = isTrue ? "O" : "X";
        
        options = [
            { label: "<span class='text-blue-500 text-5xl font-bold font-sans'>O</span>", value: "O" },
            { label: "<span class='text-red-500 text-5xl font-bold font-sans'>X</span>", value: "X" }
        ];
    }
    
    // Shuffle options except for OX quiz
    if (qType !== 2) {
        options.sort(() => Math.random() - 0.5);
    }
    
    return { q: qStr, aVal: aVal, options: options, type: qType, target: target };
}

/* =========================================
   Game Lifecycle
   ========================================= */
function initGame() {
    initAudio();
    const pCount = parseInt(document.getElementById('playerCount').value);
    state.difficulty = document.getElementById('difficulty').value;
    state.mode = document.getElementById('gameMode').value;
    state.currentQ = 0;
    state.players = [];

    // Clone available pools
    state.availableVocab.easy = [...vocabData.easy];
    state.availableVocab.medium = [...vocabData.medium];
    state.availableVocab.hard = [...vocabData.hard];

    const arena = document.getElementById('arena');
    arena.innerHTML = '';
    
    for(let i=0; i<pCount; i++) {
        let teamInfo = TEAM_DATA[i];
        state.players.push({ 
            id: teamInfo.id, icon: teamInfo.icon, color: teamInfo.color, name: teamInfo.name, 
            score: 0, survived: 0, alive: true, answered: false, selected: null, isGoldenBellWinner: false, rank: 0 
        });
        
        const col = document.createElement('div');
        col.className = `player-col ${state.players[i].color}`;
        col.id = `p-${i}`;
        col.innerHTML = `
            <div class="p-header">
                <span class="p-emoji">${state.players[i].icon}</span>
                <span class="p-name">${state.players[i].name}</span>
                <div id="p-score-${i}" style="font-size: 1.2rem; color: #ffd43b; margin-top: 5px; ${state.mode === 'challenge' ? '' : 'display: none;'}">0 점</div>
            </div>
            <div class="choice-grid" id="p-choices-${i}"></div>
            <div class="feedback-overlay" id="p-fb-${i}"></div>
            <div class="final-status" id="p-final-${i}"></div>
        `;
        arena.appendChild(col);
    }

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

    if (state.mode === 'survival' && state.currentQ >= 10 && aliveCount > 0) {
        triggerGoldenBellWin();
        return;
    }

    state.currentQ++;
    state.phase = 'counting';
    state.currentOrder = 0; // 순서 초기화
    state.players.forEach((p, idx) => {
        if(p.alive) {
            p.answered = false; p.selected = null;
            const fb = document.getElementById(`p-fb-${idx}`);
            fb.className = 'feedback-overlay'; fb.innerHTML = '';
        }
    });

    state.problem = generateProblem(state.currentQ);
    
    const maxQText = state.mode === 'challenge' ? '10' : '10';
    
    document.getElementById('qNumber').innerText = `문제 ${state.currentQ} / ${maxQText}`;
    document.getElementById('bigQuestion').innerHTML = `<span style="font-family:'Jua', sans-serif;">준비...</span>`;
    
    await runCountdown();

    state.phase = 'playing';
    document.getElementById('bigQuestion').innerHTML = state.problem.q;

    state.players.forEach((p, idx) => {
        if(!p.alive) return;
        const grid = document.getElementById(`p-choices-${idx}`);
        grid.innerHTML = '';
        
        // --- 수정된 부분: 플레이어별 보기 섞기 ---
        let playerOptions = [...state.problem.options];
        if (state.problem.type !== 2) { // OX 퀴즈가 아닐 때만 섞기
            playerOptions.sort(() => Math.random() - 0.5);
        }

        playerOptions.forEach(optObj => {
            const btn = document.createElement('div');
            btn.className = 'choice-btn';
            btn.innerHTML = optObj.label;
            btn.dataset.val = optObj.value;
            btn.onpointerdown = (e) => { e.preventDefault(); handleAnswer(idx, optObj.value, btn); };
            grid.appendChild(btn);
        });
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
    state.timer = 30;
    const tBox = document.getElementById('mainTimer');
    tBox.innerText = 30; tBox.classList.remove('timer-urgent');
    if(state.timerInterval) clearInterval(state.timerInterval);
    state.timerInterval = setInterval(() => {
        state.timer--; tBox.innerText = state.timer;
        if(state.timer <= 5) { tBox.classList.add('timer-urgent'); playSound('count'); }
        if(state.timer <= 0) finishRound();
    }, 1000);
}

function handleAnswer(pIdx, val, btnElem) {
    if(state.phase !== 'playing') return;
    const p = state.players[pIdx];
    if(p.answered || !p.alive) return;

    playSound('click');
    p.answered = true; p.selected = val;
    p.answerOrder = ++state.currentOrder; // 빨리 선택한 순서 기록
    
    const allBtns = document.getElementById(`p-choices-${pIdx}`).children;
    for(let b of allBtns) b.style.opacity = '0.5';
    btnElem.style.opacity = '1'; btnElem.classList.add('selected');

    const fb = document.getElementById(`p-fb-${pIdx}`);
    fb.innerHTML = `
        <div style="font-size:3rem; margin-bottom:10px;">🔒</div>
        <div class="submitted-text">답안 제출 완료</div>
    `;
    fb.classList.add('secret'); 
    
    const living = state.players.filter(pl => pl.alive);
    if(living.every(pl => pl.answered)) finishRound();
}

function finishRound() {
    clearInterval(state.timerInterval);
    state.phase = 'result';
    const correctAns = state.problem.aVal;
    let someoneWrong = false;

    // 문제 하나 풀고 나면 3초간 정답 (어휘 - 뜻) 보여주기 로직
    document.getElementById('bigQuestion').innerHTML = `
        <div style="font-size: clamp(2.5rem, 5vw, 4rem); color: #69db7c; margin-bottom: 15px; font-family: 'Jua', sans-serif;">${state.problem.target.word}</div>
        <div style="font-size: clamp(1.5rem, 3vw, 2.2rem); font-family: 'Noto Sans KR', sans-serif; color: #fff;">${state.problem.target.meaning}</div>
    `;

    state.players.forEach((p, idx) => {
        if(!p.alive) return;
        const fb = document.getElementById(`p-fb-${idx}`);
        const choices = document.getElementById(`p-choices-${idx}`).children;

        fb.classList.remove('secret'); fb.innerHTML = '';
        
        for(let btn of choices) {
            let bVal = btn.dataset.val;
            if(bVal === correctAns) btn.classList.add('correct');
            else if(p.selected === bVal) btn.classList.add('wrong');
        }

        if(p.selected === correctAns) {
            fb.innerHTML = '<span style="color:#69db7c">⭕</span>'; fb.classList.add('show-result');
            p.survived++;
            if(state.mode === 'challenge') {
                // 빨리 선택한 사람에게 더 높은 점수 부여 (남은시간 x 10 + 선택순서 보너스)
                p.score += (state.timer * 10) + Math.max(10, (100 - p.answerOrder * 10));
                document.getElementById(`p-score-${idx}`).innerText = `${p.score} 점`;
            }
        } else {
            someoneWrong = true;
            fb.innerHTML = '<span style="color:#ff6b6b">❌</span>'; fb.classList.add('show-result');
            if(state.mode === 'survival') {
                p.alive = false;
                setTimeout(() => {
                    document.getElementById(`p-${idx}`).classList.add('eliminated');
                }, 1500);
            }
        }
    });
    if(!someoneWrong) playSound('correct'); else playSound('wrong');
    setTimeout(() => nextTurn(), 3000);
}

function assignRanks() {
    let ranked = [...state.players];
    if(state.mode === 'challenge') {
        ranked.sort((a,b) => b.score - a.score);
    } else {
        ranked.sort((a,b) => {
            if (a.alive && !b.alive) return -1;
            if (!a.alive && b.alive) return 1;
            return b.survived - a.survived;
        });
    }
    
    let currentRank = 1;
    ranked.forEach((p, i) => {
        if (i > 0) {
            const prev = ranked[i-1];
            let isTie = false;
            if (state.mode === 'challenge') {
                if (p.score === prev.score) isTie = true;
            } else {
                if (p.alive && prev.alive) isTie = true;
                else if (!p.alive && !prev.alive && p.survived === prev.survived) isTie = true;
            }
            if (!isTie) currentRank = i + 1;
        } else {
            currentRank = 1;
        }
        const originalPlayer = state.players.find(pl => pl.id === p.id);
        originalPlayer.rank = currentRank;
    });
}

function getRankEmojiHtml(rank) {
    let rankEmoji = '';
    if (rank === 1) rankEmoji = '🥇';
    else if (rank === 2) rankEmoji = '🥈';
    else if (rank === 3) rankEmoji = '🥉';
    else if (rank === 4) rankEmoji = '<span style="font-size: 70%;">4️⃣</span>';
    else if (rank === 5) rankEmoji = '<span style="font-size: 70%;">5️⃣</span>';
    else if (rank === 6) rankEmoji = '<span style="font-size: 70%;">6️⃣</span>';
    return `<div style="font-size: 3rem; margin-bottom: 5px;">${rankEmoji}</div>`;
}

function triggerGoldenBellWin() {
    clearInterval(state.timerInterval);
    state.phase = 'gameover';
    playSound('end');

    state.players.forEach(p => {
        if(p.alive) p.isGoldenBellWinner = true;
    });
    
    assignRanks();

    const popup = document.getElementById('resultPopup');
    const list = document.getElementById('finalResultList');
    
    document.getElementById('resultTitle').innerHTML = "🎉 골든벨 달성! 🎉<br><span style='font-size: 2rem; color: #ffd43b;'>생존자 전원 승리!</span>";
    popup.style.display = 'flex'; list.innerHTML = '';
    
    state.players.forEach((p, idx) => {
        const finalDiv = document.getElementById(`p-final-${idx}`);
        finalDiv.style.display = 'block';
        const rankHtml = getRankEmojiHtml(p.rank);

        if (p.isGoldenBellWinner) {
            finalDiv.innerHTML = `${rankHtml}<h3>영광의 우승</h3><p>🔔 골든벨 🔔</p>`;
            finalDiv.style.border = "4px solid #ffd43b";
            finalDiv.style.background = "rgba(255, 212, 59, 0.2)";
        } else {
            finalDiv.innerHTML = `${rankHtml}<h3>기록</h3><p>${p.survived} 문제 통과</p>`;
        }
    });

    renderResultList(true);
}

function endGame() {
    clearInterval(state.timerInterval);
    state.phase = 'gameover';
    playSound('end');
    
    assignRanks();

    const popup = document.getElementById('resultPopup');
    document.getElementById('resultTitle').innerText = "결과 발표";
    popup.style.display = 'flex';
    
    state.players.forEach((p, idx) => {
        const finalDiv = document.getElementById(`p-final-${idx}`);
        finalDiv.style.display = 'block';
        const rankHtml = getRankEmojiHtml(p.rank);

        if (state.mode === 'challenge') { 
            finalDiv.innerHTML = `${rankHtml}<h3>최종 점수</h3><p>${p.score} 점</p>`; 
        } else { 
            finalDiv.innerHTML = p.alive ? `${rankHtml}<h3>기록</h3><p>우승!</p>` : `${rankHtml}<h3>기록</h3><p>${p.survived} 문제 통과</p>`; 
            if(p.alive) finalDiv.style.border = "4px solid #ffd43b"; 
        }
    });

    renderResultList(false);
}

function renderResultList(isGoldenBell) {
    const list = document.getElementById('finalResultList');
    list.innerHTML = '';

    let ranked = [...state.players].sort((a,b) => a.rank - b.rank);

    ranked.forEach((p, i) => {
        const li = document.createElement('li');
        li.className = `result-item ${p.rank === 1 ? 'rank-1' : ''}`;
        
        let status;
        if (isGoldenBell && p.isGoldenBellWinner) status = "🔔 골든벨 우승!";
        else if (state.mode === 'challenge') status = `${p.score} 점`;
        else status = p.alive ? "우승!" : `${p.survived} 문제 통과`;

        li.innerHTML = `<span>${p.rank}위 ${p.icon} ${p.name}</span><span>${status}</span>`;
        list.appendChild(li);
    });
}

document.getElementById('fullscreenBtn').addEventListener('click', () => {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => { console.log(err.message); });
    } else { document.exitFullscreen(); }
});

document.getElementById('startBtn').addEventListener('click', initGame);
document.getElementById('restartBtn').addEventListener('click', () => { 
    document.getElementById('overlay').style.display = 'flex'; 
    document.getElementById('resultPopup').style.display = 'none'; 
});

// Block specific gestures for whiteboard use
document.addEventListener('dblclick', function(event) { event.preventDefault(); }, { passive: false });
document.addEventListener('contextmenu', event => event.preventDefault());
document.addEventListener('dragstart', event => event.preventDefault());

document.getElementById('btn-home').addEventListener('click', () => {
    if (document.fullscreenElement) {
        clearInterval(state.timerInterval);
        state.phase = 'setup';
        
        document.getElementById('resultPopup').style.display = 'none';
        document.getElementById('countdownLayer').style.display = 'none';
        document.getElementById('arena').innerHTML = '';
        document.getElementById('mainTimer').innerText = '30';
        document.getElementById('mainTimer').classList.remove('timer-urgent');
        document.getElementById('bigQuestion').innerHTML = '준비...';
        document.getElementById('overlay').style.display = 'flex';
        
        if(window.playSound) window.playSound('click');
    } else {
        location.reload();
    }
});

/* =========================================
   초기 시작점
   ========================================= */
window.addEventListener('DOMContentLoaded', () => {
    loadVocabData();
});

</script>
</body>
</html>