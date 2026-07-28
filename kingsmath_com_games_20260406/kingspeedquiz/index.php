
 
<!DOCTYPE html>
<html lang="ko">
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>스피드퀴즈 킹 👑</title>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5YW0T2C109"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-5YW0T2C109');
  </script>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
  <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
  
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

  <style>
    body { font-family: 'Pretendard', -apple-system, BlinkMacSystemFont, system-ui, Roboto, 'Helvetica Neue', 'Segoe UI', 'Apple SD Gothic Neo', 'Noto Sans KR', 'Malgun Gothic', sans-serif; }
    /* 드래그 방지 설정 */
    .select-none { user-select: none; -webkit-user-select: none; }
  </style>
</head>
<body class="bg-gray-900 text-white selection:bg-blue-500/30">
  <div id="root"></div>

  <script type="text/babel">
    const { useState, useEffect, useCallback } = React;

    // --- 웹 오디오 API를 이용한 효과음 발생 함수 ---
    const playSound = (type) => {
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      if (!AudioContext) return;
      const ctx = new AudioContext();
      const now = ctx.currentTime;

      const playTone = (freq, oscType, duration, startTime) => {
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = oscType;
        osc.frequency.setValueAtTime(freq, startTime);
        gain.gain.setValueAtTime(0.1, startTime);
        gain.gain.exponentialRampToValueAtTime(0.001, startTime + duration);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start(startTime);
        osc.stop(startTime + duration);
      };

      if (type === 'correct') { // 딩동댕
        playTone(523.25, 'sine', 0.1, now);       // 도
        playTone(659.25, 'sine', 0.1, now + 0.1); // 미
        playTone(783.99, 'sine', 0.3, now + 0.2); // 솔
      } else if (type === 'pass') { // 띠리
        playTone(329.63, 'square', 0.15, now);    // 미 (짧게)
      } else if (type === 'warn') { // 뚜뚜 (경고)
        playTone(880, 'sine', 0.1, now);          // 높은 라
      } else if (type === 'tick') { // 두두두두 (랜덤 뽑기 중)
        playTone(400, 'square', 0.05, now);
      } else if (type === 'tada') { // 빠라바밥 (당첨)
        playTone(392.00, 'square', 0.1, now);     // 솔
        playTone(523.25, 'square', 0.4, now + 0.1); // 높은 도
      } else if (type === 'countdown') { // 3, 2, 1 카운트다운
        playTone(440, 'square', 0.1, now);
      } else if (type === 'start') { // 시작!
        playTone(880, 'square', 0.4, now);
      }
    };

    // --- 문제 데이터 셋 ---
    const QUIZ_DATASETS = [
      {
        id: 'english_grade3',
        label: '영어 3학년',
        words: [
          "a (하나의)", "Hi (안녕)", "dad (아빠)", "mommy (엄마)", "Okay (좋아!, 알았어요)", 
          "Hello (안녕하세요, 여보세요)", "I (나는, 내가)", "you (당신, 너, 여러분)", "It (그것, 이것)", 
          "thank (감사하다, 고맙습니다, 고마움)", "name (이름, 명칭, 지명하다)", "this (이것, 이런, 지금)", 
          "that (저것)", "ruler (통치자, 지도자, 지배자, 자)", "sorry (미안한, 죄송한, 유감스러운)", 
          "apple (사과, 사과나무)", "orange (오렌지, 오렌지색의)", "banana (바나나)", "grape (포도)", 
          "monkey (원숭이)", "cow (소, 암소, 젖소)", "cat (고양이)", "kangaroo (캥거루)", "dog (개)", 
          "for (~을 위하여)", "down (아래에, 내리다)", "here (여기, 이곳, 이쪽)", "up (위로, 오르다, 증가하다)", 
          "too (너무, 또한)", "on (위에, 계속하여)", "yes (예, 맞아, 네)", "no (아니오, 없다, 반대)", 
          "great (위대한, 큰, 훌륭한)", "happy (행복한, 즐거운)", "hungry (배고픈)", "cold (추운, 감기, 추위)", 
          "big (큰, 중요한)", "open (열다, 열린)", "sure (확실한)", "please (제발, 부디)", 
          "outside (외부의, 밖의)", "many (많은, 다수의)", "how (어떻게, 얼마나)", "what (무엇, 무슨)", 
          "small (작은, 적은)", "mouth (입)", "nose (코, 후각)", "shoe (신발, 구두)", 
          "snow (눈, 눈이 오다)", "snowman (눈사람)", "eye (눈, 시력, 시각)", "glove (장갑, 글러브)", 
          "hand (손, 주다, 도움)", "sweater (스웨터)", "bag (가방, 자루, 주머니)", "book (책, 도서, 서적)", 
          "chair (의자, 자리)", "door (문, 도어, 현관)", "bear (곰)", "lion (사자)", "pants (바지)", 
          "pear (배)", "pen (펜)", "pencil (연필)", "window (창문, 창)", "desk (책상)", 
          "point (점, 포인트, 요점)", "face (얼굴, 직면하다, 표정)", "fish (물고기, 낚시하다)", "pig (돼지)", 
          "time (시간, 때)", "everyone (모든 사람, 모두)", "birthday (생일)", "chicken (닭, 치킨, 닭고기, 병아리)", 
          "lunch (점심)", "welcome (환영하다)", "run (달리다, 운영하다)", "have (가지다, 얻다, 소유하다)", 
          "close (가까운, 닫다)", "fly (날다, 비행하다, 파리)", "go (가다, 떠나다, 출발하다)", "like (좋아하다, ~같은)", 
          "put (놓다, 두다)", "ski (스키)", "skate (스케이트, 스케이트를 타다)", "draw (그리다, 끌다)", 
          "touch (만지다, 감동시키다, 접촉)", "swim (수영하다)", "dance (춤, 춤추다)", "wait (기다리다)", 
          "can (할 수 있다, 캔)", "come (오다, 되다, 나오다)", "help (돕다, 도움)", "jump (뛰어오르다, 도약, 점프)", 
          "make (만들다)", "look (보다, 찾다, 바라보다)", "sit (앉다)", "stand (서다, 서 있다, 입장)", 
          "wash (씻다, 세탁하다)", "bye (안녕, 잘가요)"
        ]
      },
      {
        id: 'english_grade4',
        label: '영어 4학년',
        words: [
          "good (좋은, 잘, 착한)", "morning (아침, 오전)", "weather (날씨, 기상, 기후)", "sunny (맑은, 햇살의)", 
          "rain (비, 비가 오다)", "cloudy (흐린, 구름이 낀)", "afternoon (오후, 점심)", "evening (저녁)", 
          "breakfast (아침식사, 아침)", "dinner (저녁식사, 저녁)", "bird (새, 조류)", "flower (꽃)", 
          "king (왕, 국왕)", "bed (침대)", "friend (친구)", "girl (소녀)", "rice (쌀)", "school (학교)", 
          "he (그는, 그 사람은)", "she (그녀, 그 여자)", "year (연도, 나이)", "bat (박쥐, 방망이)", "black (검은)", 
          "body (몸, 신체)", "brother (형제)", "case (경우, 사건)", "crayon (크레용)", "eraser (지우개)", 
          "father (아버지)", "mother (어머니)", "notebook (노트북, 공책)", "puppy (강아지)", "red (빨간, 레드)", 
          "baseball (야구, 베이스볼)", "basketball (농구)", "tennis (테니스)", "cake (케이크)", "candy (사탕, 캔디)", 
          "cap (모자, 캡, 뚜껑)", "color (색깔, 색, 컬러)", "white (흰색의, 흰, 백색의)", "yellow (노란색, 노란)", 
          "green (녹색의)", "blue (파란, 푸른)", "doll (인형)", "soccer (축구, 싸커)", "table (테이블, 식탁)", 
          "shirt (셔츠)", "soup (수프, 죽)", "card (카드, 엽서)", "chocolate (초콜릿)", "computer (컴퓨터)", 
          "ribbon (리본)", "Xray (엑스레이)", "zoo (동물원)", "sports (스포츠, 운동)", "blouse (블라우스)", 
          "juice (주스)", "party (파티)", "radio (라디오, 무선, 무전)", "bike (자전거)", "cheese (치즈)", 
          "robot (로봇)", "sister (여동생, 자매, 누나)", "who (누구)", "coat (코트, 외투)", "game (게임, 경기, 시합)", 
          "group (그룹, 단체, 집단)", "hamburger (햄버거)", "hundred (백, 100)", "classroom (교실, 수업, 학급)", 
          "day (날, 하루, 낮)", "teacher (선생님, 교사)", "hot (뜨거운, 더운, 매운)", "nice (멋진, 좋은)", 
          "windy (바람이 부는)", "beautiful (아름다운, 멋있는)", "late (늦은, 늦게)", "much (많은, 대부분)", 
          "now (지금, 이제)", "o'clock (… 시)", "old (나이든, 늙은, 오래된)", "strong (강한, 튼튼한)", 
          "very (매우)", "sick (아픈, 병든)", "tired (피곤한, 싫증난)", "there (거기, 그곳)", "cute (귀여운)", 
          "pretty (예쁜, 꽤, 상당한)", "right (옳은, 권리)", "all (모든, 모두)", "bad (나쁜, 좋지 않은)", 
          "know (알다, 인식하다)", "meet (만나다)", "see (보다, 알다, 이해하다)", "watch (보다, 지켜보다, 시계)", 
          "play (놀다, 연주하다)", "want (원하다, 바라다)", "stop (멈추다, 막다)", "walk (걷다, 보행, 산책하다)"
        ]
      },
      {
        id: 'english_grade5',
        label: '영어 5학년',
        words: [
          "clean (깨끗한, 청소하다)", "wonderful (멋진, 훌륭한, 놀라운)", "home (집, 가정, 홈)", "angry (화난, 성난)", 
          "really (정말, 사실상, 실제로)", "well (잘, 충분히, 우물)", "middle (중앙의, 중간의)", "tall (키가 큰, 높은)", 
          "every (모든, 모두)", "long (긴, 오래, 오랫동안)", "short (짧은, 단기의, 작은)", "straight (똑바로, 직선, 바로)", 
          "ahead (앞서, 앞에, 미리)", "busy (바쁜, 분주한)", "full (가득한, 완전한)", "more (더 많은, 더욱)", 
          "ready (준비된, 사전에)", "some (몇몇, 일부, ~중에는)", "today (오늘, 현재, 지금)", 
          "favorite (가장 좋아하는, 마음에 드는)", "fun (재미있는, 즐거운)", "with (함께)", "living (살아 있는, 생활의)", 
          "yesterday (어제)", "everywhere (어디나)", "free (자유의, 무료의)", "about (~에 대하여)", 
          "from (~로 부터, ~에서)", "over (~이상, 위의)", "under (아래의, ~의 밑에)", "art (예술, 미술, 아트)", 
          "bookcase (책장)", "box (상자, 박스)", "egg (달걀, 알, 계란)", "fine (좋은, 벌금, 미세한)", "goat (염소)", 
          "headache (두통, 골칫거리)", "music (음악, 노래)", "prince (왕자, 세자, 프린스)", "science (과학, 사이언스)", 
          "song (노래, 곡)", "we (우리)", "ball (공, 볼)", "bank (은행, 강둑)", "city (도시, 시내, 도심)", 
          "elephant (코끼리)", "elevator (엘리베이터)", "excuse (변명, 핑계)", "idea (생각, 아이디어)", "left (왼쪽)", 
          "picnic (소풍, 피크닉)", "rock (바위, 돌, 암석)", "sky (하늘, 상공)", "tower (타워, 탑, 고층 빌딩)", 
          "where (어디, 장소)", "church (교회, 성당)", "ear (귀, 청각)", "glass (유리, 잔)", "hair (머리카락)", 
          "homework (숙제)", "hospital (병원)", "night (밤, 야간, 어둠)", "problem (문제, 과제)", "singer (가수, 싱어)", 
          "study (공부, 연구)", "way (방법, 길, 방식)", "boat (배, 보트)", "젓가락 (젓가락)", "glue (풀, 접착제, 붙이다)", 
          "knife (칼, 나이프)", "lesson (수업, 단원, 교훈)", "paper (종이, 신문, 서류)", "scissors (가위)", "sock (양말)", 
          "sound (소리, 들리다, 사운드)", "uncle (삼촌, 아저씨)", "camping (캠핑, 야영)", "backyard (뒷마당, 뒤뜰)", 
          "bathroom (화장실, 욕실)", "bedroom (침실)", "cousin (사촌, 친척)", "dish (접시, 요리)", "fork (포크)", 
          "future (미래, 장래)", "house (집, 주택)", "holiday (휴일, 공휴일, 휴가)", "people (사람들, 국민)", "zoo (동물원)", 
          "kitchen (부엌, 주방)", "museum (박물관, 미술관)", "room (방, 공간)", "spoon (숟가락, 스푼)", 
          "job (직업, 일자리)", "movie (영화, 무비)", "park (공원, 주차하다)", "potato (감자, 포테이토)", "river (강, 하천)", 
          "then (그 때, 그 다음에, 그 당시에)", "shopping (쇼핑, 가게 구경)", "together (함께, 같이, 서로)", 
          "again (한 번 더, 다시)", "love (사랑하다, 좋아하다)", "hurry (서두르다, 급하다)", "back (돌아가다, 뒤, 등)", 
          "get (얻다, 받다, 가지다)", "miss (놓치다, 그리워하다)", "turn (돌리다, 바꾸다, 전환하다)", "try (노력하다, 시도하다)", 
          "use (이용하다, 쓰다)", "cook (요리하다)", "enjoy (즐기다)", "kick (차다)", "think (생각하다, 상상하다)", 
          "speak (말하다, 이야기하다)", "stay (머물다, 지내다)", "must (~해야 한다, 꼭~해야 한다)", "buy (사다, 구입하다)", 
          "join (참여하다, 가입하다)", "listen (듣다, 귀를 기울이다)", "visit (방문하다)"
        ]
      },
      {
        id: 'english_grade6',
        label: '영어 6학년',
        words: [
          "aunt (이모, 고모)", "grandfather (할아버지)", "grandmother (할머니)", "bookstore (서점)", "building (건물, 건축)", 
          "corner (구석, 모퉁이)", "office (사무실, 회사)", "post (우편)", "restaurant (식당, 레스토랑)", "watermelon (수박)", 
          "than (~보다는)", "because (왜냐하면, ~때문에)", "fall (가을, 떨어지다)", "season (계절, 시기, 시즌)", 
          "spring (봄, 스프링)", "summer (여름)", "winter (겨울)", "mouse (쥐)", "rabbit (토끼)", "snake (뱀)", 
          "feel (느끼다, 감정)", "change (변하다, 바꾸다)", "show (보여주다, 쇼)", "hope (희망하다, 바라다)", 
          "invite (초대하다)", "drink (마시다)", "work (일하다, 작업)", "read (읽다)", "ride (타다)", "talk (말하다)", 
          "hold (잡다)", "move (움직이다, 이동하다)", "paint (그리다, 칠하다, 페인트)", "pass (지나가다, 통과하다)", 
          "worry (걱정하다, 우려하다)", "eat (먹다)", "write (쓰다)", "sleep (자다, 잠)", "see (보다)", "toy (장난감)", 
          "concert (공연, 콘서트)", "trip (여행)", "course (과정, 코스)", "guitar (기타)", "musical (음악의, 뮤지컬의)", 
          "bucket (양동이)", "model (모델, 모형, 방법)", "congratulation (축하, 축하하다)", "date (날짜)", "tomorrow (내일)", 
          "soon (곧, 조만간, 금방)", "already (이미, 벌써)", "yet (아직)", "earache (귀앓이, 귀아픔)", "stomachache (복통, 배탈)", 
          "car (자동차)", "helicopter (헬리콥터, 헬기)", "airplane (비행기)", "behind (~뒤에, 숨겨진)", "between (~사이에)", 
          "near (근처에, 가깝게)", "street (거리, 길)", "away (떨어져, 떠나)", "food (음식)", "water (물)", "when (언제)", 
          "why (왜)", "leaf (나뭇잎)", "may (~일지도 모른다)", "will (~할 것이다)", "doctor (의사, 박사)", "nurse (간호사)", 
          "driver (운전사, 기사)", "pilot (조종사, 비행사)", "police officer (경찰관)", "singer (가수)", "floor (바닥, 층)", 
          "present (선물, 현재의)", "elementary (초보의, 기초의, 기본의)", "last (지난, 마지막의)", "lucky (운이 좋은, 행운의)", 
          "expensive (비싼)", "delicious (맛있는, 맛좋은)", "same (똑같은, 같은)", "cool (시원한, 냉각)", "warm (따뜻한, 온난한)", 
          "little (작은, 조금, 약간의)", "fast (빠른, 빨리, 단식하다)", "surprise (놀라다, 깜짝)", "heavy (무거운)", 
          "able (~할 수 있는, 유능한)", "agree (동의하다, 승낙하다)", "enough (충분히)", "introduce (소개하다, 도입하다)", 
          "popular (인기 있는, 유명한)", "army (군대)", "death (죽음, 사망)", "spend (쓰다, 보내다, 사용하다)", 
          "subject (과목, 주제, 대상)", "exercise (운동, 연습, 훈련)", "excuse (변명, 핑계)", "share (공유하다, 나누다)", 
          "pick (고르다, 선택하다, 뽑다)", "habit (습관, 버릇)", "health (건강)", "possible (가능한, 할 수 있는)", 
          "check (확인하다, 점검하다)", "follow (따르다, 뒤를 잇다)", "south (남쪽)", "north (북쪽)", "social (사회의, 사교적인)", 
          "someone (누군가)", "useful (유용한, 쓸모 있는)", "war (전쟁)", "factory (공장)", "result (결과)", "shape (형태, 모양)", 
          "daily (매일의, 일상적인)", "allow (허락하다)", "image (이미지, 영상, 그림)", "mistake (실수, 잘못)", 
          "national (국가의, 전국민의)", "nobody (아무도 ~ 않다)", "crowd (군중, 붐비다)", "raise (올리다, 높이다)", 
          "serve (제공하다, 봉사하다)", "success (성공)", "improve (개선하다, 향상하다)", "accept (받아들이다, 인정하다)", 
          "appear (나타나다)", "product (상품, 제품, 생산하다)", "express (표현하다, 나타내다)", "forever (영원히, 항상, 계속)", 
          "law (법, 법률)", "natural (자연의, 천연의)", "poem (시)", "notice (알아차리다, 게시판)", "suggest (제안하다, 제의하다)", 
          "disease (질병, 병)", "soldier (군인, 병사)", "sudden (갑작스러운, 느닷없이)", "provide (제공하다, 공급하다)", 
          "support (지원하다, 지지하다)", "average (평균, 보통의)", "deliver (배달하다, 전달하다)", "remain (남다, 머무르다)", 
          "regular (규칙적인, 정기적인)", "difficulty (어려움, 곤경)", "purpose (목적, 목표)", "experiment (실험, 시도)"
        ]
      },
      {
        id: 'food',
        label: '음식/간식',
        words: [
          "떡볶이", "김밥", "라면", "치킨", "피자", "햄버거", "돈까스", "탕후루", "마카롱", "아이스크림", 
          "솜사탕", "초콜릿", "사탕", "젤리", "과자", "감자튀김", "핫도그", "샌드위치", "토스트", "붕어빵", 
          "호떡", "떡꼬치", "소떡소떡", "닭강정", "짜장면", "짬뽕", "탕수육", "볶음밥", "비빔밥", "불고기", 
          "갈비", "삼겹살", "된장찌개", "김치찌개", "미역국", "떡국", "만두", "칼국수", "수제비", "라볶이", 
          "우동", "냉면", "파스타", "스테이크", "치즈볼", "소시지", "팝콘", "츄러스", "와플", "팬케이크", 
          "크레페", "팥빙수", "달고나", "붕어싸만코", "새우깡", "빼빼로", "홈런볼", "포카칩", "칸쵸", "꼬북칩", 
          "허니버터칩", "바나나우유", "딸기우유", "초코우유", "요구르트", "콜라", "사이다", "환타", "주스", "보리차", 
          "식혜", "수정과", "치즈", "계란프라이", "김", "멸치볶음", "소시지야채볶음", "시리얼", "김치", "깍두기", 
          "볶음우동", "닭갈비", "곱창", "오므라이스", "카레라이스", "짜파게티", "비빔면", "핫바", "호두과자", "군밤", 
          "군고구마", "뻥튀기", "건빵", "약과", "찹쌀떡", "팥빵", "크루아상", "마늘빵", "베이글", "타코야끼"
        ]
      },
      {
        id: 'animal',
        label: '동물',
        words: [
          "강아지", "고양이", "토끼", "사자", "호랑이", "코끼리", "기린", "원숭이", "곰", "여우", 
          "늑대", "사슴", "돼지", "소", "말", "양", "염소", "쥐", "다람쥐", "청설모", 
          "하마", "코뿔소", "악어", "거북이", "뱀", "개구리", "두꺼비", "닭", "오리", "거위", 
          "독수리", "참새", "비둘기", "펭귄", "타조", "부엉이", "올빼미", "앵무새", "까치", "까마귀", 
          "백조", "고래", "상어", "돌고래", "물개", "바다표범", "오징어", "문어", "꽃게", "새우", 
          "조개", "해파리", "불가사리", "해마", "나비", "벌", "잠자리", "매미", "무당벌레", "개미", 
          "메뚜기", "사마귀", "장수풍뎅이", "사슴벌레", "모기", "파리", "거미", "지렁이", "달팽이", "너구리", 
          "코알라", "캥거루", "판다", "북극곰", "알파카", "치타", "표범", "얼룩말", "낙타", "박쥐", 
          "미어캣", "수달", "해달", "스컹크", "고슴도치", "두더지", "멧돼지", "살쾡이", "퓨마", "재규어", 
          "하이에나", "플라밍고", "펠리컨", "갈매기", "기러기", "칠면조", "가오리", "카멜레온", "이구아나", "코모도왕도마뱀"
        ]
      },
      {
        id: 'plant',
        label: '식물',
        words: [
          "사과", "배", "포도", "수박", "딸기", "참외", "귤", "오렌지", "바나나", "복숭아", 
          "자두", "살구", "감", "토마토", "방울토마토", "파인애플", "키위", "망고", "레몬", "체리", 
          "무궁화", "장미", "해바라기", "튤립", "벚꽃", "개나리", "진달래", "철쭉", "민들레", "나팔꽃", 
          "카네이션", "코스모스", "백합", "국화", "선인장", "소나무", "대나무", "은행나무", "단풍나무", "버드나무", 
          "야자수", "참나무", "밤나무", "감나무", "사과나무", "배나무", "포도나무", "복숭아나무", "고추", "마늘", 
          "양파", "파", "배추", "무", "당근", "감자", "고구마", "오이", "호박", "가지", 
          "상추", "깻잎", "콩", "팥", "깨", "벼", "보리", "밀", "옥수수", "수수", 
          "미역", "다시마", "파래", "버섯", "팽이버섯", "표고버섯", "송이버섯", "양송이버섯", "강아지풀", "네잎클로버", 
          "자몽", "멜론", "무화과", "모과", "석류", "매화", "목련", "동백꽃", "수국", "제비꽃", 
          "토끼풀", "갈대", "억새", "연꽃", "알로에", "율무", "들깨", "인삼", "도라지", "고사리"
        ]
      },
      {
        id: 'character',
        label: '캐릭터',
        words: [
          "뽀로로", "크롱", "패티", "루피", "에디", "포비", "해리", "짱구", "흰둥이", "짱아", 
          "피카츄", "파이리", "꼬부기", "이상해씨", "도라에몽", "헬로키티", "마이멜로디", "쿠로미", "폼폼푸린", "시나모롤", 
          "펭수", "춘식이", "라이언", "어피치", "무지", "콘", "튜브", "네오", "프로도", "미키마우스", 
          "미니마우스", "도널드덕", "곰돌이푸", "피글렛", "티거", "스폰지밥", "뚱이", "징징이", "다람이", "집게사장", 
          "플랑크톤", "로보카폴리", "엠버", "로이", "헬리", "타요", "로기", "가니", "라니", "핑크퐁", 
          "아기상어", "미니언즈", "엘사", "안나", "올라프", "스파이더맨", "아이언맨", "캡틴아메리카", "헐크", "배트맨", 
          "슈퍼맨", "원더우먼", "마리오", "루이지", "피치공주", "쿠파", "요시", "커비", "너굴", "잔망루피", 
          "엉덩이탐정", "브레드", "윌크", "초코", "하츄핑", "바로핑", "아자핑", "차차핑", "해핑", "티니핑", 
          "쿠키런", "용감한쿠키", "마인크래프트", "스티브", "크리퍼", "엔더맨", "다오", "배찌", "몰랑이", "브라운", 
          "코니", "샐리", "쵸파", "조로", "상디", "나루토", "보루토", "코난", "유명한", "미란이"
        ]
      },
      {
        id: 'animation',
        label: '애니메이션',
        words: [
          "뽀롱뽀롱뽀로로", "짱구는못말려", "포켓몬스터", "도라에몽", "명탐정코난", "원피스", "나루토", "귀멸의칼날", "스파이패밀리", "신비아파트", 
          "캐치티니핑", "브레드이발소", "로보카폴리", "꼬마버스타요", "헬로카봇", "터닝메카드", "엉덩이탐정", "요괴워치", "안녕자두야", "검정고무신", 
          "아따맘마", "네모바지스폰지밥", "미니언즈", "겨울왕국", "토이스토리", "인사이드아웃", "주토피아", "코코", "모아나", "라푼젤", 
          "인어공주", "알라딘", "미녀와야수", "라이온킹", "센과치히로의행방불명", "이웃집토토로", "벼랑위의포뇨", "하울의움직이는성", "슈렉", "쿵푸팬더", 
          "마다가스카르", "드래곤길들이기", "아이스에이지", "보스베이비", "마이펫의이중생활", "씽", "미라큘러스", "레고닌자고", "우당탕탕아이쿠", "라바", 
          "마법천자문", "메이플스토리", "런닝맨", "쥬라기캅스", "고고다이노", "슈퍼윙스", "엄마까투리", "콩순이", "시크릿쥬쥬", "소피루비", 
          "디지몬어드벤처", "달빛천사", "카드캡터체리", "세일러문", "텔레토비", "방귀대장뿡뿡이", "딩동댕유치원", "번개맨", "어벤져스", "베이블레이드", 
          "페파피그", "마이리틀포니", "슈퍼마리오", "소닉", "장화신은고양이", "트롤", "엘리멘탈", "메카드볼", "미니특공대", "공룡메카드", 
          "바다탐험대옥토넛", "스머프", "톰과제리", "파워레인저", "프리큐어", "탑블레이드", "텐카이나이트", "요괴메카드", "빠샤메카드", "출동슈퍼윙스", 
          "출동파자마삼총사", "마샤와곰", "레이디버그", "피터팬", "타잔", "뮬란", "포카혼타스", "헤라클레스", "노트르담의꼽추", "밤비"
        ]
      },
      {
        id: 'movie',
        label: '영화',
        words: [
          "겨울왕국", "토이스토리", "인사이드아웃", "엘리멘탈", "주토피아", "알라딘", "인어공주", "미녀와야수", "라이온킹", "어벤져스", 
          "스파이더맨", "아이언맨", "캡틴아메리카", "토르", "헐크", "앤트맨", "블랙팬서", "슈퍼맨", "배트맨", "원더우먼", 
          "해리포터", "반지의제왕", "아바타", "쥬라기공원", "트랜스포머", "미션임파서블", "스타워즈", "캐리비안의해적", "나홀로집에", "찰리와초콜릿공장", 
          "박물관이살아있다", "마틸다", "미니언즈", "슈퍼마리오브라더스", "쿵푸팬더", "슈렉", "드래곤길들이기", "코코", "모아나", "빅히어로", 
          "업(UP)", "월-E", "라푼젤", "뮬란", "신데렐라", "백설공주", "피터팬", "정글북", "타잔", "이상한나라의앨리스", 
          "웡카", "명탐정피카츄", "소닉", "패딩턴", "피터래빗", "명량", "한산", "노량", "신과함께", "극한직업", 
          "해운대", "부산행", "엑시트", "도둑들", "베테랑", "암살", "괴물", "7번방의선물", "국가대표", "우리생애최고의순간", 
          "터미네이터", "매트릭스", "이티(E.T.)", "킹콩", "고질라", "신비한동물사전", "가디언즈오브갤럭시", "아쿠아맨", "미니특공대극장판", "짱구극장판", 
          "마션", "그래비티", "인터스텔라", "캐스트어웨이", "트와일라잇", "헝거게임", "메이즈러너", "스파이키드", "나니아연대기", "쥬만지", 
          "구니스", "백투더퓨처", "인디아나존스", "쥬라기월드", "킹스맨", "맨인블랙", "닥터스트레인지", "캡틴마블", "블랙위도우", "데드풀"
        ]
      },
      {
        id: 'sports',
        label: '스포츠',
        words: [
          "축구", "야구", "농구", "배구", "탁구", "배드민턴", "테니스", "수영", "태권도", "유도", 
          "검도", "합기도", "복싱", "레슬링", "씨름", "육상", "달리기", "이어달리기", "마라톤", "멀리뛰기", 
          "높이뛰기", "체조", "리듬체조", "피겨스케이팅", "쇼트트랙", "스피드스케이팅", "스키", "스노보드", "썰매", "봅슬레이", 
          "컬링", "아이스하키", "양궁", "사격", "펜싱", "역도", "승마", "사이클", "자전거", "인라인스케이트", 
          "스케이트보드", "롤러스케이트", "볼링", "골프", "당구", "피구", "발야구", "티볼", "족구", "핸드볼", 
          "럭비", "미식축구", "수상스키", "서핑", "다이빙", "싱크로나이즈", "조정", "요트", "패러글라이딩", "스카이다이빙", 
          "암벽등반", "등산", "줄다리기", "박터트리기", "2인3각", "기계체조", "뜀틀", "철봉", "평행봉", "훌라후프", 
          "줄넘기", "이단뛰기", "요가", "필라테스", "에어로빅", "댄스스포츠", "무에타이", "주짓수", "킥복싱", "플라잉요가", 
          "스쿼시", "게이트볼", "크리켓", "폴로", "수구", "트라이애슬론", "근대5종", "비치발리볼", "세팍타크로", "우슈", 
          "가라테", "카누", "카약", "스켈레톤", "루지", "바이애슬론", "노르딕복합", "프리스타일스키", "파도타기", "클라이밍"
        ]
      },
      {
        id: 'hobby',
        label: '취미',
        words: [
          "독서", "그림그리기", "피아노치기", "노래부르기", "춤추기", "게임하기", "레고조립", "종이접기", "퍼즐맞추기", "요리하기", 
          "베이킹", "사진찍기", "영화감상", "음악감상", "동영상보기", "유튜브보기", "블로그하기", "일기쓰기", "시쓰기", "글쓰기", 
          "악기연주", "기타치기", "바이올린", "플루트", "드럼", "리코더", "오카리나", "단소", "십자수", "뜨개질", 
          "비즈공예", "슬라임만들기", "클레이아트", "컬러링북", "캘리그라피", "우표수집", "동전수집", "딱지치기", "팽이치기", "공기놀이", 
          "숨바꼭질", "술래잡기", "무궁화꽃이피었습니다", "얼음땡", "보드게임", "체스", "바둑", "장기", "오목", "루미큐브", 
          "할리갈리", "부루마불", "캠핑", "낚시", "자전거타기", "인라인타기", "산책하기", "등산하기", "수영하기", "태권도하기", 
          "축구하기", "농구하기", "야구하기", "배드민턴치기", "줄넘기하기", "반려동물돌보기", "화분가꾸기", "곤충채집", "식물채집", "쇼핑하기", 
          "친구와놀기", "수다떨기", "만화책보기", "웹툰보기", "넷플릭스보기", "마술배우기", "큐브맞추기", "다꾸", "폰꾸", "폴꾸", 
          "프라모델조립", "피규어수집", "스크랩북", "십자말풀이", "스도쿠", "낱말퍼즐", "가죽공예", "비누만들기", "향수만들기", "캔들만들기", 
          "드론날리기", "rc카조종", "스케이트보드타기", "킥보드타기", "웹소설읽기", "팬픽읽기", "굿즈수집", "콘서트가기", "빵지순례", "맛집탐방"
        ]
      },
      {
        id: 'history',
        label: '역사 인물/위인',
        words: [
          "단군", "주몽", "박혁거세", "온조", "광개토대왕", "장수왕", "을지문덕", "연개소문", "대조영", "김유신", 
          "김춘추", "무열왕", "문무왕", "선덕여왕", "장보고", "왕건", "강감찬", "서희", "최무선", "최영", 
          "이성계", "태조", "세종대왕", "이순신", "장영실", "신사임당", "율곡이이", "퇴계이황", "한석봉", "허준", 
          "정약용", "김정호", "김홍도", "신윤복", "안창호", "김구", "안중근", "유관순", "윤봉길", "이봉창", 
          "방정환", "주시경", "이승만", "링컨", "워싱턴", "에디슨", "아인슈타인", "뉴턴", "갈릴레이", "퀴리부인", 
          "헬렌켈러", "나이팅게일", "슈바이처", "테레사수녀", "간디", "마틴루터킹", "넬슨만델라", "콜럼버스", "마젤란", "라이트형제", 
          "모차르트", "베토벤", "바흐", "쇼팽", "피카소", "고흐", "레오나르도다빈치", "미켈란젤로", "셰익스피어", "톨스토이", 
          "안데르센", "월트디즈니", "스티브잡스", "빌게이츠", "노벨", "안네프랑크", "잔다르크", "클레오파트라", "나폴레옹", "칭기즈칸", 
          "근초고왕", "진흥왕", "의자왕", "계백", "김부식", "일연", "정도전", "조광조", "황진이", "논개", 
          "전봉준", "소크라테스", "플라톤", "아리스토텔레스", "알렉산더대왕", "제갈량", "관우", "장비", "유비", "조조"
        ]
      },
      {
        id: 'country_city',
        label: '나라/도시',
        words: [
          "대한민국", "북한", "일본", "중국", "대만", "홍콩", "몽골", "베트남", "태국", "필리핀", 
          "인도네시아", "말레이시아", "싱가포르", "인도", "네팔", "사우디아라비아", "아랍에미리트", "이스라엘", "튀르키예", "러시아", 
          "미국", "캐나다", "멕시코", "브라질", "아르헨티나", "칠레", "페루", "콜롬비아", "영국", "프랑스", 
          "독일", "이탈리아", "스페인", "포르투갈", "네덜란드", "벨기에", "스위스", "오스트리아", "그리스", "스웨덴", 
          "노르웨이", "덴마크", "핀란드", "이집트", "남아프리카공화국", "케냐", "나이지리아", "가나", "호주", "뉴질랜드", 
          "서울", "부산", "대구", "인천", "광주", "대전", "울산", "제주도", "수원", "창원", 
          "도쿄", "오사카", "베이징", "상하이", "뉴욕", "로스앤젤레스", "워싱턴", "시카고", "파리", "런던", 
          "로마", "마드리드", "베를린", "모스크바", "방콕", "마닐라", "시드니", "카이로", "두바이", "바티칸", 
          "캄보디아", "라오스", "미얀마", "방글라데시", "스리랑카", "몰디브", "파키스탄", "이란", "이라크", "체코", 
          "헝가리", "폴란드", "루마니아", "불가리아", "쿠바", "자메이카", "하와이", "괌", "사이판", "발리"
        ]
      },
      {
        id: 'job',
        label: '직업',
        words: [
          "선생님", "의사", "간호사", "경찰관", "소방관", "판사", "검사", "변호사", "군인", "과학자", 
          "발명가", "우주비행사", "조종사", "승무원", "요리사", "파티시에", "제빵사", "미용사", "메이크업아티스트", "패션디자이너", 
          "가수", "배우", "영화감독", "아나운서", "기자", "피디(PD)", "작가", "화가", "피아니스트", "운동선수", 
          "축구선수", "야구선수", "프로게이머", "유튜버", "크리에이터", "웹툰작가", "애니메이터", "사진작가", "건축가", "목수", 
          "농부", "어부", "환경미화원", "우편집배원", "택배기사", "버스기사", "택시기사", "은행원", "회사원", "대통령", 
          "국회의원", "외교관", "통역사", "번역가", "사육사", "수의사", "플로리스트", "경호원", "탐정", "마술사", 
          "모델", "댄서", "안무가", "작곡가", "성우", "프로그래머", "게임개발자", "약사", "한의사", "치과의사", 
          "안경사", "물리치료사", "사회복지사", "보육교사", "유치원교사", "교수", "연구원", "바리스타", "소믈리에", "일기예보관", 
          "영양사", "피부관리사", "애견미용사", "웨딩플래너", "파티플래너", "조경사", "용접원", "배관공", "전기기사", "카센터직원", 
          "펀드매니저", "회계사", "세무사", "노무사", "관세사", "감정평가사", "도슨트", "큐레이터", "사서", "기관사"
        ]
      },
      {
        id: 'proverb',
        label: '속담',
        words: [
          "가는말이고와야오는말이고운법", "티끌모아태산", "누워서떡먹기", "식은죽먹기", "원숭이도나무에서떨어진다", "개구리올챙이적생각못한다", "고래싸움에새우등터진다", "소잃고외양간고친다", "돌다리도두들겨보고건너라", "세살버릇여든까지간다", 
          "바늘도둑이소도둑된다", "발없는말이천리간다", "낮말은새가듣고밤말은쥐가듣는다", "콩심은데콩나고팥심은데팥난다", "낫놓고기역자도모른다", "그림의떡", "꿩먹고알먹고", "도랑치고가재잡고", "마른하늘에날벼락", "벼는익을수록고개를숙인다", 
          "사공이많으면배가산으로간다", "우물안개구리", "천리길도한걸음부터", "하룻강아지범무서운줄모른다", "호랑이도제말하면온다", "아니땐굴뚝에연기날까", "가는날이장날", "고생끝에낙이온다", "구슬이서말이라도꿰어야보배", "달면삼키고쓰면뱉는다", 
          "무소식이희소식", "배보다배꼽이더크다", "백지장도맞들면낫다", "사촌이땅을사면배가아프다", "서당개삼년에풍월을읊는다", "소문난잔치에먹을것없다", "수박겉핥기", "십년이면강산도변한다", "아는것이힘이다", "얌전한고양이가부뚜막에먼저올라간다", 
          "열번찍어안넘어가는나무없다", "오르지못할나무는쳐다보지도마라", "옥에도티가있다", "우물을파도한우물을파라", "원수는외나무다리에서만난다", "자라보고놀란가슴솥뚜껑보고놀란다", "작은고추가맵다", "종로에서뺨맞고한강에서눈흘긴다", "지렁이도밟으면꿈틀한다", "첫술에배부르랴", 
          "칼을뽑았으면무라도썰어야지", "핑계없는무덤없다", "하늘의별따기", "하늘이무너져도솟아날구멍이있다", "하룻밤을자도만리장성을쌓는다", "한귀로듣고한귀로흘린다", "호랑이에게물려가도정신만차리면산다", "윗물이맑아야아랫물도맑다", "입에쓴약이병에는좋다", "빈수레가요란하다", 
          "말한마디에천냥빚도갚는다", "금강산도식후경", "남의떡이더커보인다", "도둑이제발저린다", "똥묻은개가겨묻은개나무란다", "길고짧은것은대보아야안다", "쥐구멍에도볕뜰날있다", "찬물도위아래가있다", "고양이목에방울달기", "꿀먹은벙어리", 
          "다된밥에재뿌리기", "닭쫓던개지붕쳐다보듯", "동문서답", "등잔밑이어둡다", "마이동풍", "물에빠진놈보따리내놓으라한다", "벼룩의간을내어먹는다", "비온뒤에땅이굳는다", "뛰는놈위에나는놈있다", "개똥도약에쓰려면없다", 
          "꿩대신닭", "누울자리를보고발을뻗어라", "밑빠진독에물붓기", "빛좋은개살구", "사후약방문", "어물전망신은꼴뚜기가시킨다", "우물가에서숭늉찾는다", "원숭이도나무에서떨어질때가있다", "핑계없는무덤은없다", "하늘을보아야별을따지", 
          "강건너불구경", "긁어부스럼", "꼬리가길면밟힌다", "뚝배기보다장맛", "미운놈떡하나더준다", "사공이많으면배가산으로", "어부지리", "열길물속은알아도한길사람속은모른다", "모기보고칼뺀다", "미꾸라지한마리가온웅덩이를흐린다"
        ]
      },
      {
        id: 'idiom',
        label: '사자성어',
        words: [
          "다다익선", "일석이조", "동문서답", "작심삼일", "우왕좌왕", "이심전심", "유비무환", "고진감래", "설상가상", "금상첨화", 
          "일취월장", "천고마비", "구사일생", "막상막하", "새옹지마", "대기만성", "백발백중", "조삼모사", "다재다능", "십시일반", 
          "부전자전", "오합지졸", "동병상련", "죽마고우", "자업자득", "적반하장", "진퇴양난", "토사구팽", "호가호위", "칠전팔기", 
          "권선징악", "대의명분", "사필귀정", "결초보은", "개과천선", "감탄고토", "결자해지", "고군분투", "군계일학", "금과옥조", 
          "금의환향", "기사회생", "노발대발", "다정다감", "독불장군", "동분서주", "마이동풍", "명실상부", "반신반의", "배은망덕", 
          "백의종군", "불치하문", "산전수전", "삼고초려", "상부상조", "수수방관", "승승장구", "아전인수", "안하무인", "양약고구", 
          "어부지리", "역지사지", "연전연승", "온고지신", "왈가왈부", "우공이산", "유유상종", "이구동성", "일기당천", "일사천리", 
          "일파만파", "자포자기", "전대미문", "전무후무", "전전긍긍", "전화위복", "점입가경", "청출어람", "타산지석", "호시탐탐", 
          "막무가내", "무용지물", "반포지효", "백골난망", "백년해로", "사상누각", "산명수려", "살신성인", "삼삼오오", "선견지명", 
          "설왕설래", "소탐대실", "속수무책", "식자우환", "심사숙고", "십벌지목", "아비규환", "안분지족", "오비이락", "일진일퇴"
        ]
      },
      {
        id: 'school',
        label: '학교/교실',
        words: [
          "선생님", "교장선생님", "반장", "부반장", "짝꿍", "학생", "칠판", "분필", "지우개", "보드마카", 
          "책상", "의자", "교과서", "공책", "필통", "연필", "볼펜", "샤프", "자", "가위", 
          "풀", "색연필", "사인펜", "크레파스", "물감", "붓", "스케치북", "알림장", "일기장", "시간표", 
          "쉬는시간", "점심시간", "체육시간", "음악시간", "미술시간", "국어시간", "수학시간", "사회시간", "과학시간", "영어시간", 
          "급식", "식판", "숟가락", "젓가락", "우유", "실내화", "실내화주머니", "사물함", "신발장", "화장실", 
          "교무실", "보건실", "도서관", "과학실", "음악실", "미술실", "컴퓨터실", "강당", "체육관", "운동장", 
          "조회대", "철봉", "미끄럼틀", "그네", "축구골대", "농구골대", "모래사장", "수돗가", "청소당번", "주번", 
          "빗자루", "쓰레기통", "걸레", "분리수거", "게시판", "상장", "트로피", "책가방", "소풍", "운동회", 
          "지각", "교감선생님", "보건선생님", "영양선생님", "행정실", "숙제", "시험", "발표", "준비물", "일기", 
          "독후감", "받아쓰기", "방학", "개학", "졸업식", "입학식", "현장체험학습", "수련회", "수학여행", "학예회", "학부모총회"
        ]
      }
    ];

    function App() {
      const [screen, setScreen] = useState('home'); 
      const [topic, setTopic] = useState('animal'); 
      const [customWords, setCustomWords] = useState('');
      const [gameMode, setGameMode] = useState('time'); 
      const [modeValue, setModeValue] = useState(60); 
      const [numTeams, setNumTeams] = useState(4);
      const [teams, setTeams] = useState([]);
      
      const [currentTeam, setCurrentTeam] = useState(null);
      const [questions, setQuestions] = useState([]);
      const [fullPool, setFullPool] = useState([]); // 중복 방지를 위한 전체 문제 풀 보관용
      const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
      const [correctCount, setCorrectCount] = useState(0);
      const [elapsedTime, setElapsedTime] = useState(0);
      const [remainingTime, setRemainingTime] = useState(0);
      const [countdown, setCountdown] = useState(3);
      const [menuOpen, setMenuOpen] = useState(false);
      const [showAnswers, setShowAnswers] = useState(false);

      const [randomState, setRandomState] = useState({ isPicking: false, name: '', finalId: null });

      const handleStartGame = () => {
        const newTeams = Array.from({ length: numTeams }, (_, i) => ({
          id: i + 1,
          name: `${i + 1}팀`,
          score: 0,
          timeTaken: 0,
          played: false,
          pokemonId: Math.floor(Math.random() * 1010) + 1
        }));
        setTeams(newTeams);
        setScreen('teamSelect');

        // 게임 시작 시 한 번만 전체 문제 풀을 생성하고 섞어서 저장 (중복 방지 핵심)
        let pool = [];
        if (topic === 'random') {
          pool = QUIZ_DATASETS.flatMap(d => d.words);
        } else if (topic === 'custom') {
          pool = customWords.split(/[\n,]+/).map(w => w.trim()).filter(w => w);
        } else {
          const selectedDataset = QUIZ_DATASETS.find(d => d.id === topic);
          if (selectedDataset) pool = [...selectedDataset.words];
        }
        
        pool.sort(() => Math.random() - 0.5);
        setFullPool([...pool]);
        setQuestions(pool);
        setCurrentQuestionIndex(0);
      };

      const startCountdown = (teamId) => {
        setCurrentTeam(teamId);
        // 팀이 바뀔 때 문제를 초기화하지 않고 그대로 이어서 사용합니다.
        setCorrectCount(0);
        setElapsedTime(0);
        setRemainingTime(gameMode === 'time' ? modeValue : 0);
        setCountdown(4);
        setScreen('countdown');
      };

      const pickRandomTeam = () => {
        const unplayed = teams.filter(t => !t.played);
        if (unplayed.length === 0) return;
        
        setRandomState({ isPicking: true, name: '...', finalId: null });
        let ticks = 0;
        const maxTicks = 20; 
        
        const interval = setInterval(() => {
          playSound('tick');
          const rand = unplayed[Math.floor(Math.random() * unplayed.length)];
          setRandomState({ isPicking: true, name: rand.name, finalId: null });
          ticks++;
          
          if (ticks >= maxTicks) {
            clearInterval(interval);
            const finalTeam = unplayed[Math.floor(Math.random() * unplayed.length)];
            setRandomState({ isPicking: false, name: finalTeam.name, finalId: finalTeam.id });
            playSound('tada');
          }
        }, 100);
      };

      const updateScore = (teamId, delta) => {
        setTeams(prev => prev.map(t => 
          t.id === teamId ? { ...t, score: Math.max(0, t.score + delta) } : t
        ));
      };

      const toggleFullscreen = () => {
        if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen().catch(err => console.log(err));
        } else {
          document.exitFullscreen();
        }
        setMenuOpen(false);
      };

      const handleGameOver = useCallback(() => {
        setScreen('result');
        setTeams(prev => prev.map(t =>
          t.id === currentTeam
            ? { ...t, played: true, score: correctCount, timeTaken: elapsedTime }
            : t
        ));
      }, [currentTeam, correctCount, elapsedTime]);

      useEffect(() => {
        if (screen === 'countdown') {
          if (countdown > 0 && countdown <= 3) {
            playSound('countdown');
          } else if (countdown === 0) {
            playSound('start');
          }

          if (countdown >= 0) {
            const timer = setTimeout(() => setCountdown(countdown - 1), 1000);
            return () => clearTimeout(timer);
          } else {
            setScreen('playing');
          }
        }
      }, [screen, countdown]);

      useEffect(() => {
        if (screen !== 'playing') return;

        const timer = setInterval(() => {
          if (gameMode === 'time') {
            setRemainingTime(prev => {
              if (prev <= 1) return 0;
              if (prev <= 11) playSound('warn');
              return prev - 1;
            });
          } else {
            setElapsedTime(prev => prev + 1);
          }
        }, 1000);

        return () => clearInterval(timer);
      }, [screen, gameMode]);

      useEffect(() => {
        if (screen === 'playing' && gameMode === 'time' && remainingTime === 0) {
          handleGameOver();
        }
      }, [screen, gameMode, remainingTime, handleGameOver]);

      useEffect(() => {
        if (screen === 'playing' && gameMode === 'count' && correctCount >= modeValue) {
          handleGameOver();
        }
      }, [screen, gameMode, correctCount, modeValue, handleGameOver]);

      const nextQuestion = () => {
        if (currentQuestionIndex >= questions.length - 2) {
          // 모든 답안을 소진했을 때만 원래 풀(fullPool)을 새롭게 섞어서 뒤에 이어 붙입니다.
          const more = [...fullPool].sort(() => Math.random() - 0.5);
          setQuestions(prev => [...prev, ...more]);
        }
        setCurrentQuestionIndex(prev => prev + 1);
      };

      const handleCorrect = () => {
        playSound('correct');
        setCorrectCount(prev => prev + 1);
        nextQuestion();
      };

      const handlePass = () => {
        playSound('pass');
        nextQuestion();
      };

      const formatTime = (seconds) => {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${m}:${s.toString().padStart(2, '0')}`;
      };

      const renderMenu = (positionClasses = "relative") => (
        <div className={`${positionClasses} z-50`}>
          <button
            onClick={() => setMenuOpen(!menuOpen)}
            className="flex items-center space-x-2 bg-gray-800/90 hover:bg-gray-700 p-3 rounded-xl border border-gray-600 backdrop-blur-sm text-sm font-bold text-gray-300 shadow-lg"
          >
            <i className="fas fa-cog" style={{fontSize: '18px'}}></i>
            <span>메뉴</span>
          </button>
          {menuOpen && (
            <div className="absolute top-full left-0 mt-3 w-48 bg-gray-800 border border-gray-600 rounded-2xl shadow-2xl overflow-hidden flex flex-col z-50">
              <button onClick={() => { setMenuOpen(false); setScreen('home'); }} className="flex items-center p-4 hover:bg-gray-700 text-left font-bold border-b border-gray-700">
                <i className="fas fa-home mr-3 text-blue-400" style={{fontSize: '18px'}}></i> 처음으로
              </button>
              <button onClick={() => { setMenuOpen(false); setScreen('teamSelect'); }} className="flex items-center p-4 hover:bg-gray-700 text-left font-bold border-b border-gray-700">
                <i className="fas fa-undo mr-3 text-green-400" style={{fontSize: '18px'}}></i> 모둠 선택
              </button>
              <button onClick={() => toggleFullscreen()} className="flex items-center p-4 hover:bg-gray-700 text-left font-bold">
                <i className="fas fa-expand mr-3 text-purple-400" style={{fontSize: '18px'}}></i> 전체화면
              </button>
            </div>
          )}
        </div>
      );

      const getAnswerDataText = () => {
        if (topic === 'random') return "🌟 모든 카테고리의 문제가 무작위로 섞여서 출제됩니다.";
        if (topic === 'custom') return customWords ? customWords : "아직 입력된 단어가 없습니다.";
        const selected = QUIZ_DATASETS.find(d => d.id === topic);
        return selected ? selected.words.join(', ') : '';
      };

      const renderHome = () => {
        const isStartDisabled = topic === 'custom' && customWords.trim().length === 0;

        return (
          <div className="flex flex-col items-center justify-center min-h-screen p-8 max-w-5xl mx-auto space-y-6">
            <h1 className="text-6xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500 mb-4 drop-shadow-lg text-center">
              스피드퀴즈 킹 👑
            </h1>

            <div className="w-full bg-gray-800 p-6 rounded-2xl shadow-xl border border-gray-700 relative">
              <div className="flex justify-between items-center mb-5">
                <h2 className="text-2xl font-bold text-blue-300 flex items-center">
                  <i className="fas fa-check mr-2"></i> 1. 주제 선택
                </h2>
                <button
                  onClick={() => setShowAnswers(true)}
                  className="flex items-center text-sm font-bold bg-blue-600/20 text-blue-300 hover:bg-blue-600/40 px-4 py-2 rounded-xl transition"
                >
                  <i className="fas fa-eye mr-2" style={{fontSize: '18px'}}></i> 정답 보기
                </button>
              </div>
              
              <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                {QUIZ_DATASETS.map(dataset => (
                  <button
                    key={dataset.id}
                    onClick={() => setTopic(dataset.id)}
                    className={`py-3 px-2 rounded-xl font-bold text-sm md:text-base transition-all break-keep ${
                      topic === dataset.id ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/50 scale-[1.02]' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                    }`}
                  >
                    {dataset.label}
                  </button>
                ))}
                <button
                  onClick={() => setTopic('random')}
                  className={`py-3 px-2 rounded-xl font-bold text-sm md:text-base transition-all break-keep ${
                    topic === 'random' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/50 scale-[1.02]' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                  }`}
                >
                  전체 랜덤
                </button>
                <button
                  onClick={() => setTopic('custom')}
                  className={`py-3 px-2 rounded-xl font-bold text-sm md:text-base transition-all break-keep ${
                    topic === 'custom' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/50 scale-[1.02]' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                  }`}
                >
                  ✍️ 직접 입력
                </button>
              </div>
              
              {topic === 'custom' && (
                <textarea
                  className="w-full h-32 bg-gray-900 border-2 border-blue-500/50 rounded-xl p-4 mt-4 text-lg text-white placeholder-gray-500 focus:outline-none focus:border-blue-500"
                  placeholder="단어를 직접 입력하세요. (엔터 줄바꿈 혹은 쉼표(,)로 구분)"
                  value={customWords}
                  onChange={(e) => setCustomWords(e.target.value)}
                />
              )}
            </div>

            <div className="w-full grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="bg-gray-800 p-6 rounded-2xl shadow-xl border border-gray-700">
                <h2 className="text-2xl font-bold mb-4 text-green-300 flex items-center"><i className="fas fa-clock mr-2"></i> 2. 게임 방법</h2>
                <div className="flex space-x-3 mb-4">
                  <button
                    onClick={() => { setGameMode('time'); setModeValue(60); }}
                    className={`flex-1 py-3 rounded-xl font-bold transition-all ${
                      gameMode === 'time' ? 'bg-green-600 text-white shadow-lg shadow-green-500/50' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                    }`}
                  >
                    시간내 맞추기
                  </button>
                  <button
                    onClick={() => { setGameMode('count'); setModeValue(5); }}
                    className={`flex-1 py-3 rounded-xl font-bold transition-all ${
                      gameMode === 'count' ? 'bg-green-600 text-white shadow-lg shadow-green-500/50' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                    }`}
                  >
                    빨리 맞추기
                  </button>
                </div>
                <div className="grid grid-cols-4 gap-2">
                  {gameMode === 'time' ? (
                    [60, 120, 180, 300].map(val => (
                      <button
                        key={val}
                        onClick={() => setModeValue(val)}
                        className={`py-2 rounded-xl font-bold text-sm transition-all ${
                          modeValue === val ? 'bg-yellow-500 text-gray-900 shadow-lg' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                        }`}
                      >
                        {val / 60}분
                      </button>
                    ))
                  ) : (
                    [5, 10, 15, 20].map(val => (
                      <button
                        key={val}
                        onClick={() => setModeValue(val)}
                        className={`py-2 rounded-xl font-bold text-sm transition-all ${
                          modeValue === val ? 'bg-yellow-500 text-gray-900 shadow-lg' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                        }`}
                      >
                        {val}문제
                      </button>
                    ))
                  )}
                </div>
              </div>

              <div className="bg-gray-800 p-6 rounded-2xl shadow-xl border border-gray-700 flex flex-col justify-center items-center">
                <h2 className="text-2xl font-bold text-purple-300 flex items-center mb-6 w-full"><i className="fas fa-users mr-2"></i> 3. 팀 수 선택</h2>
                <div className="flex items-center space-x-8">
                  <button onClick={() => setNumTeams(Math.max(1, numTeams - 1))} className="w-14 h-14 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center text-3xl font-bold transition transform hover:scale-105">-</button>
                  <span className="text-5xl font-black w-20 text-center text-white">{numTeams}</span>
                  <button onClick={() => setNumTeams(Math.min(10, numTeams + 1))} className="w-14 h-14 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center text-3xl font-bold transition transform hover:scale-105">+</button>
                </div>
              </div>
            </div>

            <button
              onClick={handleStartGame}
              disabled={isStartDisabled}
              className={`w-full py-6 rounded-2xl text-3xl font-black transition-all mt-4 ${
                isStartDisabled ? 'bg-gray-600 text-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 text-white shadow-xl shadow-orange-500/20 transform hover:scale-[1.02]'
              }`}
            >
              게임 준비 완료
            </button>

            {showAnswers && (
              <div className="fixed inset-0 bg-black/80 flex flex-col items-center justify-center z-[100] p-6 backdrop-blur-sm">
                <div className="bg-gray-800 rounded-3xl w-full max-w-3xl max-h-[80vh] flex flex-col border border-gray-600 shadow-2xl">
                  <div className="flex justify-between items-center p-6 border-b border-gray-700">
                    <h3 className="text-2xl font-bold text-yellow-400 flex items-center">
                      <i className="fas fa-eye mr-3"></i>
                      [{topic === 'random' ? '전체 랜덤' : topic === 'custom' ? '직접 입력' : QUIZ_DATASETS.find(d => d.id === topic)?.label}] 정답 데이터
                    </h3>
                    <button onClick={() => setShowAnswers(false)} className="text-gray-400 hover:text-white transition bg-gray-700 hover:bg-gray-600 p-2 rounded-full">
                      <i className="fas fa-times" style={{fontSize: '24px'}}></i>
                    </button>
                  </div>
                  <div className="overflow-y-auto flex-1 p-6 text-lg leading-relaxed text-gray-200 whitespace-pre-wrap">
                    {getAnswerDataText()}
                  </div>
                </div>
              </div>
            )}

          </div>
        );
      };

      const renderTeamSelect = () => {
        const allPlayed = teams.length > 0 && teams.every(t => t.played);

        return (
          <div className="flex flex-col items-center min-h-screen p-8 relative">
            {renderMenu("absolute top-6 left-6")}
            <h2 className="text-4xl font-black mb-12 text-blue-400 mt-4">도전할 팀을 선택하세요</h2>
            
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 w-full max-w-6xl">
              {teams.map(team => (
                <div
                  key={team.id}
                  onClick={() => !team.played && startCountdown(team.id)}
                  className={`p-6 rounded-3xl flex flex-col items-center justify-center transition-all relative overflow-hidden
                    ${team.played ? 'bg-gray-800/60 border-gray-700/50' : 'bg-blue-600 hover:bg-blue-500 cursor-pointer shadow-xl shadow-blue-900/40 transform hover:-translate-y-1'}
                    border-2 ${team.played ? 'border-gray-700' : 'border-blue-400'}`}
                >
                  <div className="relative w-24 h-24 md:w-32 md:h-32 mb-4">
                    <img
                      src={`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${team.pokemonId}.png`}
                      alt="pokemon"
                      className={`w-full h-full object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.2)] ${team.played ? 'opacity-30 grayscale' : 'opacity-100 hover:scale-110 transition-transform'}`}
                    />
                  </div>

                  <div className={`text-3xl font-black mb-6 ${team.played ? 'text-gray-400' : 'text-white'}`}>{team.name}</div>
                  
                  {/* 반응형 및 shrink-0 추가로 깨짐 현상 수정 */}
                  <div className="flex items-center justify-between w-full bg-gray-900/50 rounded-2xl p-1 md:p-2 mb-2">
                    <button
                      onClick={(e) => { e.stopPropagation(); updateScore(team.id, -1); }}
                      className="w-8 h-8 md:w-10 md:h-10 shrink-0 bg-red-500/20 hover:bg-red-500/40 text-red-400 rounded-xl flex items-center justify-center text-xl md:text-2xl font-bold transition"
                    ><i className="fas fa-minus" style={{fontSize: '16px'}}></i></button>
                    <div className={`text-2xl md:text-3xl font-black text-center ${team.played ? 'text-yellow-600' : 'text-yellow-400'}`}>{team.score}점</div>
                    <button
                      onClick={(e) => { e.stopPropagation(); updateScore(team.id, 1); }}
                      className="w-8 h-8 md:w-10 md:h-10 shrink-0 bg-green-500/20 hover:bg-green-500/40 text-green-400 rounded-xl flex items-center justify-center text-xl md:text-2xl font-bold transition"
                    ><i className="fas fa-plus" style={{fontSize: '16px'}}></i></button>
                  </div>

                  {team.played && (
                    <button
                      onClick={(e) => { e.stopPropagation(); startCountdown(team.id); }}
                      className="mt-4 px-6 py-2 bg-purple-600/80 hover:bg-purple-500 rounded-xl text-sm font-bold shadow transition flex items-center"
                    >
                      <i className="fas fa-undo mr-2" style={{fontSize: '16px'}}></i> 재도전
                    </button>
                  )}
                </div>
              ))}
            </div>

            <div className="absolute bottom-12 flex space-x-6 z-10">
              {!allPlayed && (
                <button
                  onClick={pickRandomTeam}
                  className="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-400 hover:to-purple-500 text-white px-8 py-5 rounded-3xl shadow-2xl flex items-center text-xl font-bold transform hover:scale-105 transition-all"
                >
                  <i className="fas fa-random mr-3" style={{fontSize: '28px'}}></i> 랜덤 뽑기
                </button>
              )}
              
              <button
                onClick={() => setScreen('finalRank')}
                className="bg-blue-600 hover:bg-blue-500 text-white px-8 py-5 rounded-3xl shadow-2xl flex items-center text-xl font-bold transform hover:scale-105 transition-all"
              >
                <i className="fas fa-trophy mr-3" style={{fontSize: '28px'}}></i> 결과 보기
              </button>
            </div>

            {allPlayed && (
              <button
                onClick={() => setScreen('home')}
                className="absolute bottom-32 bg-gray-700 hover:bg-gray-600 px-8 py-4 rounded-2xl font-bold flex items-center text-xl shadow-lg transition-all"
              >
                <i className="fas fa-home mr-3"></i> 처음으로
              </button>
            )}

            {(randomState.isPicking || randomState.finalId) && (
              <div className="fixed inset-0 bg-black/80 flex flex-col items-center justify-center z-50 backdrop-blur-sm">
                <h3 className="text-4xl text-pink-400 font-bold mb-8">도전 팀 무작위 추첨</h3>
                <div className={`text-8xl md:text-9xl font-black text-white px-12 py-8 bg-gray-800 border-4 ${randomState.finalId ? 'border-green-400 scale-110 shadow-[0_0_50px_rgba(74,222,128,0.5)]' : 'border-pink-500 animate-pulse'} rounded-3xl transition-all duration-300`}>
                  {randomState.name}
                </div>
                {randomState.finalId && (
                  <button
                    onClick={() => {
                      const id = randomState.finalId;
                      setRandomState({ isPicking: false, name: '', finalId: null });
                      startCountdown(id);
                    }}
                    className="mt-12 text-4xl font-black bg-green-500 hover:bg-green-400 text-white px-16 py-6 rounded-full shadow-2xl transform hover:scale-105 transition animate-bounce"
                  >
                    도전 시작!
                  </button>
                )}
              </div>
            )}
          </div>
        );
      };

      const renderCountdown = () => {
        const team = teams.find(t => t.id === currentTeam);
        let displayStr = countdown;
        if (countdown === 4) displayStr = `${team?.name} 준비!`;
        else if (countdown === 0) displayStr = '시작!';

        return (
          <div className="flex items-center justify-center min-h-screen">
            <div className={`font-black text-yellow-400 drop-shadow-[0_0_30px_rgba(250,204,21,0.5)] animate-pulse ${countdown === 4 ? 'text-7xl md:text-9xl' : 'text-[25vw]'}`}>
              {displayStr}
            </div>
          </div>
        );
      };

   const renderPlaying = () => {
        // 현재 단어 가져오기
        const currentWord = questions[currentQuestionIndex] || "";
        
        // 글자 수에 따른 동적 스타일 결정
        let fontSizeClass = "text-[8vw] md:text-[10vw]"; // 기본 크기
        let breakClass = "break-keep"; // 단어 단위 줄바꿈 (짧은 단어)

        if (currentWord.length > 12) {
          fontSizeClass = "text-[5.5vw] md:text-[6.5vw]"; // 아주 긴 경우 (속담 등)
          breakClass = "break-all"; // 글자 단위 줄바꿈 허용 (2줄 유도)
        } else if (currentWord.length > 7) {
          fontSizeClass = "text-[7vw] md:text-[8.5vw]"; // 중간 길이
          breakClass = "break-all";
        }

        return (
          <div className="flex w-full h-screen bg-gray-900 overflow-hidden select-none">
            {/* 왼쪽 버튼 영역 */}
            <div className="w-24 md:w-36 flex flex-col h-full shrink-0 z-10 border-r border-gray-700">
              <button onClick={handleCorrect} className="flex-1 bg-green-600/20 hover:bg-green-600/40 text-green-500 flex items-center justify-center text-4xl md:text-6xl font-black border-b border-gray-700 transition active:bg-green-500 active:text-white">
                정답
              </button>
              <button onClick={handlePass} className="flex-1 bg-red-600/20 hover:bg-red-600/40 text-red-500 flex items-center justify-center text-4xl md:text-6xl font-black transition active:bg-red-500 active:text-white">
                패스
              </button>
            </div>

            {/* 중앙 문제 영역 */}
            <div className="flex-1 flex flex-col relative h-full">
              <div className="absolute top-6 left-6 z-20 flex flex-col items-start">
                <div className={`text-6xl md:text-7xl font-mono font-black drop-shadow-md flex items-center mb-4 transition-colors ${gameMode === 'time' && remainingTime <= 10 ? 'text-red-500 animate-pulse' : 'text-yellow-400'}`}>
                  <i className="fas fa-clock mr-3" style={{fontSize: '48px'}}></i>
                  {gameMode === 'time' ? formatTime(remainingTime) : formatTime(elapsedTime)}
                </div>
                {renderMenu("relative")}
              </div>

              <div className="absolute top-6 right-6 z-20">
                <div className="text-4xl md:text-5xl font-black text-green-400 drop-shadow-md flex items-center bg-gray-800/50 px-6 py-3 rounded-2xl border border-gray-700/50">
                  <i className="fas fa-hashtag mr-2" style={{fontSize: '36px'}}></i>
                  {gameMode === 'time' ? `${correctCount} 개` : `${correctCount} / ${modeValue}`}
                </div>
              </div>

              {/* 문제 텍스트 출력 부분 수정 */}
              <div className="flex-1 flex items-center justify-center p-8 px-12 z-0">
                <h1 className={`${fontSizeClass} ${breakClass} leading-[1.2] font-black text-white text-center drop-shadow-2xl tracking-tight max-w-[90%]`}>
                  {currentWord}
                </h1>
              </div>
            </div>

            {/* 오른쪽 버튼 영역 */}
            <div className="w-24 md:w-36 flex flex-col h-full shrink-0 z-10 border-l border-gray-700">
              <button onClick={handleCorrect} className="flex-1 bg-green-600/20 hover:bg-green-600/40 text-green-500 flex items-center justify-center text-4xl md:text-6xl font-black border-b border-gray-700 transition active:bg-green-500 active:text-white">
                정답
              </button>
              <button onClick={handlePass} className="flex-1 bg-red-600/20 hover:bg-red-600/40 text-red-500 flex items-center justify-center text-4xl md:text-6xl font-black transition active:bg-red-500 active:text-white">
                패스
              </button>
            </div>
          </div>
        );
      };

      const renderResult = () => {
        const team = teams.find(t => t.id === currentTeam);
        return (
          <div className="flex flex-col items-center justify-center min-h-screen bg-gray-900 p-8">
            <h2 className="text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600 mb-6 drop-shadow-xl animate-bounce">
              게임 종료!
            </h2>
            <div className="text-6xl font-black text-white mb-10 bg-gray-800 px-12 py-6 rounded-3xl border border-gray-700 shadow-2xl">
              {team?.name}
            </div>
            
            <div className="text-5xl font-bold mb-16 text-blue-300 flex items-center">
              {gameMode === 'time' ? (
                 <>맞춘 개수 : <span className="text-yellow-400 text-6xl ml-4">{correctCount}</span> 개</>
              ) : (
                 <>기록 : <span className="text-yellow-400 text-6xl ml-4">{formatTime(elapsedTime)}</span></>
              )}
            </div>
            
            <button
              onClick={() => setScreen('teamSelect')}
              className="px-10 py-5 bg-blue-600 hover:bg-blue-500 rounded-3xl text-2xl font-bold transition shadow-xl shadow-blue-900/50 flex items-center transform hover:scale-105"
            >
              <i className="fas fa-users mr-3" style={{fontSize: '28px'}}></i> 팀 고르기 화면으로
            </button>
          </div>
        );
      };

const renderFinalRank = () => {
        // 1. 성적순 정렬 (점수 내림차순, 시간 오름차순)
        const sortedTeams = [...teams].sort((a, b) => {
          if (b.score !== a.score) return b.score - a.score;
          if (a.timeTaken !== b.timeTaken) return a.timeTaken - b.timeTaken;
          return 0;
        });

        // 2. 등수 계산을 위한 변수 선언
        let currentRank = 1;

        return (
          <div className="flex flex-col items-center min-h-screen bg-gray-900 p-8 relative">
            {renderMenu("absolute top-6 left-6")}
            <h2 className="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500 mb-12 drop-shadow-xl mt-8">
              🏆 최종 결과 🏆
            </h2>
            
            <div className="w-full max-w-4xl bg-gray-800 rounded-3xl p-8 border border-gray-700 shadow-2xl space-y-2">
              {sortedTeams.map((team, index) => {
                // 이전 팀과 점수가 같다면 등수를 유지, 아니면 현재 index + 1로 갱신
                if (index > 0) {
                  const prevTeam = sortedTeams[index - 1];
                  const isTied = (team.score === prevTeam.score);
                  
                  if (!isTied) {
                    currentRank = index + 1;
                  }
                }

                // 표 깨짐 현상 수정 (반응형 flex-col 추가 및 텍스트 조절)
                return (
                  <div key={team.id} className="flex flex-col md:flex-row md:items-center justify-between py-6 border-b border-gray-700 last:border-0 gap-4 md:gap-0">
                    <div className="flex items-center space-x-4 md:space-x-6">
                      {/* 계산된 currentRank 사용 */}
                      <div className={`text-3xl md:text-4xl font-black w-16 md:w-24 text-center ${currentRank === 1 ? 'text-yellow-400 text-4xl md:text-5xl' : currentRank === 2 ? 'text-gray-300' : currentRank === 3 ? 'text-amber-600' : 'text-gray-500'}`}>
                        {currentRank}위
                      </div>
                      <div className="text-3xl md:text-4xl font-bold text-white">{team.name}</div>
                    </div>
                    <div className="text-3xl md:text-4xl font-black text-green-400 pl-20 md:pl-0">
                      {team.score}점
                      <span className="text-sm text-gray-500 ml-2 font-normal">({formatTime(team.timeTaken)})</span>
                    </div>
                  </div>
                );
              })}
            </div>

            <button
              onClick={() => setScreen('teamSelect')}
              className="mt-12 px-10 py-5 bg-gray-700 hover:bg-gray-600 rounded-3xl text-2xl font-bold transition shadow-xl flex items-center"
            >
              <i className="fas fa-undo mr-3" style={{fontSize: '28px'}}></i> 팀 선택으로 돌아가기
            </button>
          </div>
        );
      };


      return (
        <React.Fragment>
          {screen === 'home' && renderHome()}
          {screen === 'teamSelect' && renderTeamSelect()}
          {screen === 'countdown' && renderCountdown()}
          {screen === 'playing' && renderPlaying()}
          {screen === 'result' && renderResult()}
          {screen === 'finalRank' && renderFinalRank()}
        </React.Fragment>
      );
    }

    const root = ReactDOM.createRoot(document.getElementById('root'));
    root.render(<App />);
  </script>
</body>
</html>