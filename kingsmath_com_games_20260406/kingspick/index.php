<?php
$dataFile = 'kingspick_data.json';

// POST 요청 시 JSON 데이터를 파일로 저장
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputJSON = file_get_contents('php://input');
    file_put_contents($dataFile, $inputJSON);
    echo json_encode(['status' => 'success']);
    exit;
}

// 초기 실행 시 JSON 파일이 없으면 기본 데이터 생성
if (!file_exists($dataFile)) {
    $defaultData = [
        'categories' => [
            ['id' => uniqid('cat_'), 'name' => '미술'],
            ['id' => uniqid('cat_'), 'name' => '체육']
        ],
        'videos' => []
    ];
    file_put_contents($dataFile, json_encode($defaultData, JSON_UNESCAPED_UNICODE));
}

$jsonData = file_get_contents($dataFile);
?>
<!DOCTYPE html>
<html lang="ko">
<head> <script async src="https://www.googletagmanager.com/gtag/js?id=G-5YW0T2C109"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-5YW0T2C109');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>킹스픽 (킹수학 추천 유튜브 큐레이션)</title>
    <style>
        :root {
            --primary-color: #4A90E2;
            --bg-color: #F5F7FA;
            --text-color: #333;
            --sidebar-bg: #FFFFFF;
        }
        body { font-family: 'Malgun Gothic', sans-serif; margin: 0; background-color: var(--bg-color); color: var(--text-color); display: flex; flex-direction: column; height: 100vh; }
        
        /* Header */
        header { background-color: var(--primary-color); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 10; }
        header h1 { margin: 0; font-size: 24px; }
        .setting-btn { background: transparent; border: none; color: white; padding: 5px; font-size: 24px; cursor: pointer; transition: transform 0.2s; }
        .setting-btn:hover { transform: rotate(45deg); }

        /* Main Layout */
        .container { display: flex; flex: 1; overflow: hidden; }
        
        /* Sidebar */
        aside { width: 200px; background-color: var(--sidebar-bg); border-right: 1px solid #ddd; overflow-y: auto; }
        .category-item { padding: 15px 20px; cursor: pointer; border-bottom: 1px solid #eee; font-size: 16px; transition: background 0.2s; }
        .category-item:hover { background-color: #f0f4f8; }
        .category-item.active { background-color: #e2eeff; border-left: 4px solid var(--primary-color); font-weight: bold; }

        /* Content */
        main { flex: 1; padding: 30px; overflow-y: auto; }
        .video-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .video-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); cursor: pointer; transition: transform 0.2s; display: flex; flex-direction: column; }
        .video-card:hover { transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
        .video-card img { width: 100%; aspect-ratio: 16/9; object-fit: cover; }
        
        /* 카드 내부 콘텐츠 영역 */
        .card-content { padding: 15px; display: flex; flex-direction: column; flex: 1; }
        /* 카테고리와 자료 버튼을 양쪽 정렬하기 위한 헤더 영역 */
        .card-top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .card-category { font-size: 13px; color: var(--primary-color); font-weight: bold; }
        .card-content .title { font-weight: bold; font-size: 16px; line-height: 1.4; margin: 0; word-break: keep-all; }
        
        /* 자료 링크 버튼 */
        .resource-btn { background: #e2eeff; color: var(--primary-color); border: none; padding: 4px 10px; border-radius: 20px; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; font-weight: bold; transition: background 0.2s; }
        .resource-btn:hover { background: #cce0ff; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; justify-content: center; align-items: center; }
        .modal-content { background: white; width: 800px; max-width: 90%; max-height: 90vh; border-radius: 10px; display: flex; flex-direction: column; overflow: hidden; }
        .modal-header { padding: 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h2 { margin: 0; }
        .close-btn { background: none; border: none; font-size: 24px; cursor: pointer; }
        .modal-body { padding: 20px; overflow-y: auto; display: flex; gap: 30px; }
        
        .modal-section { flex: 1; display: flex; flex-direction: column; gap: 15px; }
        .modal-section h3 { margin: 0 0 10px 0; border-bottom: 2px solid var(--primary-color); padding-bottom: 5px; }
        input, select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 100%; box-sizing: border-box; }
        button.action-btn { padding: 8px 12px; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer; }
        button.action-btn:disabled { background: #ccc; cursor: not-allowed; }
        button.danger-btn { background: #e74c3c; }
        
        .list-item { display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #f9f9f9; border: 1px solid #eee; border-radius: 5px; margin-bottom: 5px; gap: 5px; }
        .video-list-item { display: flex; gap: 10px; align-items: center; padding: 10px; background: #f9f9f9; border: 1px solid #eee; border-radius: 5px; margin-bottom: 10px; }
        .video-list-item img { width: 120px; border-radius: 4px; }
        .video-list-item-info { flex: 1; }
        .video-list-item-title { font-weight: bold; margin-bottom: 5px; }
        .video-list-item-link { font-size: 11px; color: #666; margin-bottom: 5px; word-break: break-all; }
    </style>
</head>
<body>

    <header>
        <h1>킹스픽 (킹수학 추천 유튜브 큐레이션)</h1>
        <button class="setting-btn" onclick="checkPasswordAndOpen()" title="관리자 설정">⚙️</button>
    </header>

    <div class="container">
        <aside id="categorySidebar">
            </aside>

        <main>
            <div class="video-grid" id="videoGrid">
                </div>
        </main>
    </div>

    <div class="modal-overlay" id="settingsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>⚙️ 킹스픽 관리자 설정</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>카테고리 관리</h3>
                    <div style="display: flex; gap: 5px;">
                        <input type="text" id="newCatName" placeholder="새 카테고리 이름">
                        <button class="action-btn" onclick="addCategory()">추가</button>
                    </div>
                    <div id="adminCategoryList" style="margin-top: 10px;"></div>
                </div>

                <div class="modal-section" style="flex: 1.5;">
                    <h3>영상 관리</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px; background:#f0f4f8; padding:15px; border-radius:5px;">
                        <select id="newVideoCat"></select>
                        <input type="text" id="newVideoUrl" placeholder="유튜브 링크 (URL) 필수">
                        <input type="text" id="newVideoTitle" placeholder="킹수학에서 보여줄 제목 (필수)">
                        <input type="text" id="newResourceUrl" placeholder="설명 자료 링크 (선택: 인디스쿨, 블로그 등)">
                        <button class="action-btn" onclick="addVideo()">영상 추가</button>
                    </div>
                    <div id="adminVideoList" style="margin-top: 15px; max-height: 400px; overflow-y: auto;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let appData = <?php echo $jsonData; ?>;
        // 초기 로딩 시 '전체 보기'를 기본값으로 설정
        let currentCategoryId = 'all';

        function init() {
            renderSidebar();
            renderVideos();
        }

        // --- 화면 렌더링 ---
        function renderSidebar() {
            const sidebar = document.getElementById('categorySidebar');
            sidebar.innerHTML = '';
            
            // '전체 보기' 메뉴
            const allDiv = document.createElement('div');
            allDiv.className = `category-item ${currentCategoryId === 'all' ? 'active' : ''}`;
            allDiv.innerText = '전체 보기';
            allDiv.onclick = () => {
                currentCategoryId = 'all';
                renderSidebar();
                renderVideos();
            };
            sidebar.appendChild(allDiv);

            // 카테고리 렌더링
            appData.categories.forEach(cat => {
                const div = document.createElement('div');
                div.className = `category-item ${cat.id === currentCategoryId ? 'active' : ''}`;
                div.innerText = cat.name;
                div.onclick = () => {
                    currentCategoryId = cat.id;
                    renderSidebar();
                    renderVideos();
                };
                sidebar.appendChild(div);
            });
        }

        function renderVideos() {
            const grid = document.getElementById('videoGrid');
            grid.innerHTML = '';
            
            let filteredVideos = [];
            if (currentCategoryId === 'all') {
                filteredVideos = appData.videos;
            } else if (currentCategoryId) {
                filteredVideos = appData.videos.filter(v => v.categoryId === currentCategoryId);
            }

            if(filteredVideos.length === 0) {
                grid.innerHTML = '<p style="color:#999; grid-column: 1 / -1; text-align:center;">등록된 영상이 없습니다.</p>';
                return;
            }

            [...filteredVideos].reverse().forEach(video => {
                const card = document.createElement('div');
                card.className = 'video-card';
                card.onclick = () => window.open(`https://www.youtube.com/watch?v=${video.youtubeId}`, '_blank');
                
                const imgUrl = `https://img.youtube.com/vi/${video.youtubeId}/mqdefault.jpg`;
                const catName = appData.categories.find(c => c.id === video.categoryId)?.name || '기타';
                
                // 자료 링크가 존재하면 우측에 들어갈 버튼 HTML 생성
                let resourceHtml = '';
                if(video.resourceLink && video.resourceLink.trim() !== '') {
                    resourceHtml = `<button class="resource-btn" onclick="openResource(event, '${video.resourceLink}')">📁 자료</button>`;
                }

                // card-top-row를 이용해 [카테고리] 와 [자료 버튼]을 양쪽 끝으로 정렬
                card.innerHTML = `
                    <img src="${imgUrl}" alt="thumbnail">
                    <div class="card-content">
                        <div class="card-top-row">
                            <div class="card-category">[${catName}]</div>
                            ${resourceHtml}
                        </div>
                        <div class="title">${video.title}</div>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        function openResource(event, url) {
            event.stopPropagation(); 
            window.open(url, '_blank');
        }

        // --- 비밀번호 확인 및 모달 ---
        function checkPasswordAndOpen() {
            const pwd = prompt("관리자 비밀번호를 입력하세요:");
            if (pwd === "zldtngkr01!") {
                openModal();
            } else if (pwd !== null) {
                alert("비밀번호가 일치하지 않습니다.");
            }
        }

        function openModal() {
            document.getElementById('settingsModal').style.display = 'flex';
            renderAdmin();
        }

        function closeModal() {
            document.getElementById('settingsModal').style.display = 'none';
        }

        function renderAdmin() {
            const catList = document.getElementById('adminCategoryList');
            const catSelect = document.getElementById('newVideoCat');
            catList.innerHTML = '';
            catSelect.innerHTML = '';

            appData.categories.forEach((cat, index) => {
                const isFirst = index === 0;
                const isLast = index === appData.categories.length - 1;

                const div = document.createElement('div');
                div.className = 'list-item';
                div.innerHTML = `
                    <span style="flex: 1; font-weight: bold;">${cat.name}</span>
                    <button class="action-btn" style="padding: 4px 8px; font-size: 12px;" onclick="moveCategory(${index}, -1)" ${isFirst ? 'disabled' : ''}>▲</button>
                    <button class="action-btn" style="padding: 4px 8px; font-size: 12px;" onclick="moveCategory(${index}, 1)" ${isLast ? 'disabled' : ''}>▼</button>
                    <button class="action-btn danger-btn" style="padding: 4px 8px; font-size: 12px; margin-left: 5px;" onclick="deleteCategory('${cat.id}')">삭제</button>
                `;
                catList.appendChild(div);

                const opt = document.createElement('option');
                opt.value = cat.id;
                opt.innerText = cat.name;
                catSelect.appendChild(opt);
            });

            const vidList = document.getElementById('adminVideoList');
            vidList.innerHTML = '';
            [...appData.videos].reverse().forEach(video => {
                const catName = appData.categories.find(c => c.id === video.categoryId)?.name || '분류없음';
                const imgUrl = `https://img.youtube.com/vi/${video.youtubeId}/mqdefault.jpg`;
                const resourceText = video.resourceLink ? `📁 자료: ${video.resourceLink}` : '';
                
                const div = document.createElement('div');
                div.className = 'video-list-item';
                div.innerHTML = `
                    <img src="${imgUrl}" alt="thumb">
                    <div class="video-list-item-info">
                        <div style="font-size:12px; color:var(--primary-color)">[${catName}]</div>
                        <div class="video-list-item-title">${video.title}</div>
                        <div class="video-list-item-link">${resourceText}</div>
                        <div style="display:flex; gap:5px;">
                            <button class="action-btn" style="font-size:12px; padding:4px 8px;" onclick="editVideo('${video.id}')">수정</button>
                            <button class="action-btn danger-btn" style="font-size:12px; padding:4px 8px;" onclick="deleteVideo('${video.id}')">삭제</button>
                        </div>
                    </div>
                `;
                vidList.appendChild(div);
            });
        }

        // --- 데이터 처리 로직 ---
        function moveCategory(index, direction) {
            const targetIndex = index + direction;
            if (targetIndex < 0 || targetIndex >= appData.categories.length) return;
            const temp = appData.categories[index];
            appData.categories[index] = appData.categories[targetIndex];
            appData.categories[targetIndex] = temp;
            saveDataAndRefresh();
        }

        function addCategory() {
            const name = document.getElementById('newCatName').value.trim();
            if(!name) return alert('카테고리 이름을 입력하세요.');
            appData.categories.push({ id: 'cat_' + Date.now(), name: name });
            document.getElementById('newCatName').value = '';
            saveDataAndRefresh();
        }

        function deleteCategory(id) {
            if(!confirm('이 카테고리와 포함된 모든 영상이 삭제됩니다. 계속하시겠습니까?')) return;
            appData.categories = appData.categories.filter(c => c.id !== id);
            appData.videos = appData.videos.filter(v => v.categoryId !== id);
            if (currentCategoryId === id) {
                currentCategoryId = 'all';
            }
            saveDataAndRefresh();
        }

        function addVideo() {
            const catId = document.getElementById('newVideoCat').value;
            const url = document.getElementById('newVideoUrl').value.trim();
            const title = document.getElementById('newVideoTitle').value.trim();
            const resourceUrl = document.getElementById('newResourceUrl').value.trim();

            if(!catId || !url || !title) return alert('유튜브 링크와 제목을 입력해주세요.');
            
            const ytId = extractYouTubeId(url);
            if(!ytId) return alert('유효한 유튜브 링크가 아닙니다.');

            appData.videos.push({
                id: 'vid_' + Date.now(),
                categoryId: catId,
                youtubeId: ytId,
                title: title,
                resourceLink: resourceUrl
            });

            document.getElementById('newVideoUrl').value = '';
            document.getElementById('newVideoTitle').value = '';
            document.getElementById('newResourceUrl').value = '';
            saveDataAndRefresh();
        }

        function editVideo(id) {
            const video = appData.videos.find(v => v.id === id);
            
            // 1. 제목 수정 프롬프트 (취소 시 함수 종료)
            const newTitle = prompt('새로운 제목을 입력하세요:', video.title);
            if(newTitle === null) return; 
            
            // 2. 자료 링크 수정 프롬프트 (취소 시 함수 종료)
            const newLink = prompt('설명 자료 링크를 입력하세요 (없으면 비워두세요):', video.resourceLink || '');
            if(newLink === null) return;

            // 값이 있으면 업데이트 (제목은 빈칸이 아니면 업데이트, 링크는 빈칸이면 삭제됨)
            if(newTitle.trim() !== '') {
                video.title = newTitle.trim();
            }
            video.resourceLink = newLink.trim();
            
            saveDataAndRefresh();
        }

        function deleteVideo(id) {
            if(!confirm('영상을 삭제하시겠습니까?')) return;
            appData.videos = appData.videos.filter(v => v.id !== id);
            saveDataAndRefresh();
        }

        function extractYouTubeId(url) {
            const match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
            return match ? match[1] : null;
        }

        function saveDataAndRefresh() {
            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(appData)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    init();
                    if(document.getElementById('settingsModal').style.display === 'flex') {
                        renderAdmin();
                    }
                }
            })
            .catch(err => alert('저장 중 오류가 발생했습니다. 애널리틱스 코드가 <head> 태그 안쪽에 있는지 확인해주세요.'));
        }

        init();
    </script>
</body>
</html>