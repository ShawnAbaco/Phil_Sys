<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Display - National ID System</title>

    <!-- Favicon / Logo -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/loading.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/loading.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --psa-red: #CE1126;
            --psa-blue: #0038A8;
            --psa-yellow: #FCD116;
            --psa-red-light: rgba(206, 17, 38, 0.1);
            --psa-blue-light: rgba(0, 56, 168, 0.1);
            --psa-yellow-light: rgba(252, 209, 22, 0.1);
        }

        html, body {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--psa-blue);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* PSA Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('/images/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(4px) brightness(0.8);
            transform: scale(1.1);
            z-index: -1;
        }

        /* PSA Arrow Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                linear-gradient(45deg,
                    transparent 0%,
                    transparent 45%,
                    var(--psa-yellow) 45%,
                    var(--psa-yellow) 55%,
                    transparent 55%,
                    transparent 100%),
                linear-gradient(135deg,
                    var(--psa-red) 0%,
                    var(--psa-red) 30%,
                    transparent 30%,
                    transparent 70%,
                    var(--psa-blue) 70%,
                    var(--psa-blue) 100%);
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
            animation: arrowMove 15s ease-in-out infinite;
        }

        @keyframes arrowMove {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(2%, 2%) rotate(2deg); }
            66% { transform: translate(-2%, -2%) rotate(-2deg); }
        }

        /* Main Container - Full Height with Flex */
        .main-container {
            position: relative;
            z-index: 10;
            height: 100vh;
            width: 100vw;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Header - Fixed height */
        .psa-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 12px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 3px solid var(--psa-yellow);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            height: 90px;
            flex-shrink: 0;
        }

        .psa-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--psa-red), var(--psa-blue), var(--psa-yellow));
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-area img {
            height: 50px;
            width: auto;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
        }

        .title-area h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--psa-blue);
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .title-area p {
            color: var(--psa-red);
            font-weight: 600;
            font-size: 0.9rem;
            position: relative;
            display: inline-block;
        }

        .title-area p::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--psa-red), var(--psa-blue), var(--psa-yellow));
        }

        .date-time {
            background: linear-gradient(135deg, var(--psa-red), var(--psa-blue));
            padding: 10px 25px;
            border-radius: 50px;
            color: white;
            text-align: center;
            border: 2px solid var(--psa-yellow);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .date-time .time {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
            font-family: monospace;
            text-shadow: 1px 1px 0 var(--psa-red), 2px 2px 0 var(--psa-blue);
        }

        .date-time .date {
            font-size: 0.85rem;
            opacity: 0.95;
            font-weight: 500;
        }

        /* Content Area - 4 Column Layout with Perfect Centering */
        .content-area {
            display: grid;
            grid-template-columns: 1.2fr 1.2fr 1.3fr 1.3fr;
            gap: 15px;
            flex: 1;
            min-height: 0;
        }

        /* Window Cards */
        .window-column {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .window-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 15px 10px;
            text-align: center;
            border: 3px solid var(--psa-yellow);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
        }

        .window-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--psa-red), var(--psa-blue), var(--psa-yellow));
        }

        .window-card::after {
            content: '';
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            background: var(--psa-yellow);
            opacity: 0.1;
            clip-path: polygon(0 0, 100% 100%, 100% 0);
        }

        .window-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--psa-blue);
            margin-bottom: 5px;
            text-shadow: 1px 1px 0 var(--psa-yellow);
            letter-spacing: -0.5px;
        }

        .serving-label {
            color: var(--psa-red);
            font-size: 0.9rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .queue-number {
            font-size: 4rem;
            font-weight: 800;
            color: var(--psa-red);
            line-height: 1;
            margin-bottom: 5px;
            text-shadow: 3px 3px 0 var(--psa-yellow), 6px 6px 0 var(--psa-blue);
            font-family: monospace;
            letter-spacing: -3px;
        }

        .client-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--psa-blue);
            text-shadow: 1px 1px 0 var(--psa-yellow);
            word-break: break-word;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 10px;
        }

        /* Queue Sections */
        .queue-column {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .queue-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 15px;
            border: 3px solid var(--psa-yellow);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .queue-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--psa-red), var(--psa-blue), var(--psa-yellow));
        }

        .queue-section::after {
            content: '';
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: var(--psa-blue);
            opacity: 0.1;
            clip-path: polygon(0 0, 100% 100%, 100% 0);
        }

        .queue-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--psa-yellow);
            position: relative;
            flex-shrink: 0;
        }

        .queue-header svg {
            width: 24px;
            height: 24px;
            color: var(--psa-red);
            flex-shrink: 0;
        }

        .queue-header h2 {
            color: var(--psa-blue);
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Queue List - Grid Layout */
        .queue-list {
            list-style: none;
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 8px;
            align-content: start;
            overflow: visible;
        }

        .queue-item {
            background: white;
            border-radius: 12px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 2px solid var(--psa-yellow);
            transition: all 0.2s ease;
            animation: slideIn 0.3s ease;
            position: relative;
            overflow: hidden;
            min-width: 0;
        }

        .queue-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(135deg, var(--psa-red), var(--psa-blue));
        }

        .queue-item-number {
            font-size: 1rem;
            font-weight: 700;
            color: var(--psa-red);
            min-width: 55px;
            font-family: monospace;
            text-shadow: 1px 1px 0 var(--psa-yellow);
            flex-shrink: 0;
        }

        .queue-item-name {
            font-size: 0.85rem;
            color: var(--psa-blue);
            flex: 1;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .queue-item-badge {
            background: var(--psa-yellow);
            color: var(--psa-blue);
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            border: 1px solid var(--psa-red);
            flex-shrink: 0;
        }

        .empty-queue {
            grid-column: 1 / -1;
            text-align: center;
            padding: 15px;
            color: var(--psa-blue);
            font-size: 0.9rem;
            background: var(--psa-yellow-light);
            border-radius: 12px;
            border: 2px dashed var(--psa-yellow);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .empty-queue svg {
            width: 30px;
            height: 30px;
            color: var(--psa-red);
            opacity: 0.5;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 var(--psa-yellow); }
            70% { box-shadow: 0 0 0 15px rgba(252, 209, 22, 0); }
            100% { box-shadow: 0 0 0 0 rgba(252, 209, 22, 0); }
        }

        .window-card.active {
            animation: pulse 1.5s infinite;
            border-color: var(--psa-yellow);
        }

        @keyframes flash {
            0%, 100% { background: white; }
            50% { background: var(--psa-yellow-light); }
        }

        .queue-item.new {
            animation: flash 1s ease;
        }

        /* Loading Modal */
        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading-modal.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .loading-content {
            background: white;
            padding: 25px 35px;
            border-radius: 24px;
            text-align: center;
            border: 3px solid var(--psa-yellow);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        .rotate-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
            animation: rotate 1.2s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .loading-text {
            color: var(--psa-blue);
            font-weight: 600;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* No scrollbar needed */
        ::-webkit-scrollbar {
            display: none;
        }

        /* Perfect Centering Adjustment */
        @media (min-width: 1600px) {
            .content-area {
                grid-template-columns: 1.25fr 1.25fr 1.25fr 1.25fr;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="main-container">
        <!-- PSA-themed Header -->
        <div class="psa-header">
            <div class="logo-area">
                <img src="{{ asset('images/logo.png') }}" alt="National ID Logo">
                <div class="title-area">
                    <h1>NATIONAL ID SYSTEM</h1>
                    <p>Queue Monitoring Display</p>
                </div>
            </div>
            <div class="date-time">
                <div class="time" id="time"></div>
                <div class="date" id="date"></div>
            </div>
        </div>

        <!-- Content Area - 4 Column Layout with Perfect Centering -->
        <div class="content-area">
            <!-- Column 1: Windows 1-3 -->
            <div class="window-column">
                <div class="window-card" data-window="1" id="window-1">
                    <div class="window-number">WINDOW 1</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[1]['q_id'] ?? '-' }}</div>
                    <div class="client-name">{{ $calledQueues[1]['lname'] ?? '' }}</div>
                </div>
                <div class="window-card" data-window="2" id="window-2">
                    <div class="window-number">WINDOW 2</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[2]['q_id'] ?? '-' }}</div>
                    <div class="client-name">{{ $calledQueues[2]['lname'] ?? '' }}</div>
                </div>
                <div class="window-card" data-window="3" id="window-3">
                    <div class="window-number">WINDOW 3</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[3]['q_id'] ?? '-' }}</div>
                    <div class="client-name">{{ $calledQueues[3]['lname'] ?? '' }}</div>
                </div>
            </div>

            <!-- Column 2: Windows 4-6 -->
            <div class="window-column">
                <div class="window-card" data-window="4" id="window-4">
                    <div class="window-number">WINDOW 4</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[4]['q_id'] ?? '-' }}</div>
                    <div class="client-name">{{ $calledQueues[4]['lname'] ?? '' }}</div>
                </div>
                <div class="window-card" data-window="5" id="window-5">
                    <div class="window-number">WINDOW 5</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[5]['q_id'] ?? '-' }}</div>
                    <div class="client-name">{{ $calledQueues[5]['lname'] ?? '' }}</div>
                </div>
                <div class="window-card" data-window="6" id="window-6">
                    <div class="window-number">WINDOW 6</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[6]['q_id'] ?? '-' }}</div>
                    <div class="client-name">{{ $calledQueues[6]['lname'] ?? '' }}</div>
                </div>
            </div>

            <!-- Column 3: Status Inquiry -->
            <div class="queue-column">
                <div class="queue-section">
                    <div class="queue-header">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <h2>STATUS INQUIRY</h2>
                    </div>
                    <ul class="queue-list" id="status-queue">
                        @forelse($nextQueues['statusInquiry'] as $item)
                            <li class="queue-item">
                                <span class="queue-item-number">{{ $item['q_id'] }}</span>
                                <span class="queue-item-name">{{ ucfirst(strtolower($item['lname'])) }}</span>
                                <span class="queue-item-badge">WAITING</span>
                            </li>
                        @empty
                            <li class="empty-queue">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                <p>No queues waiting</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Column 4: Registration & Updating -->
            <div class="queue-column">
                <div class="queue-section">
                    <div class="queue-header">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        <h2>REGISTRATION & UPDATING</h2>
                    </div>
                    <ul class="queue-list" id="registration-queue">
                        @forelse($nextQueues['registrationUpdating'] as $item)
                            <li class="queue-item">
                                <span class="queue-item-number">{{ $item['q_id'] }}</span>
                                <span class="queue-item-name">{{ ucfirst(strtolower($item['lname'])) }}</span>
                                <span class="queue-item-badge">WAITING</span>
                            </li>
                        @empty
                            <li class="empty-queue">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                <p>No queues waiting</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="loading-modal" id="loadingModal">
        <div class="loading-content">
            <img src="{{ asset('images/loading.png') }}" alt="Loading..." class="rotate-logo">
            <p class="loading-text">Please wait...</p>
        </div>
    </div>

    <script>
        // Sentence case function
        function sentenceCase(str) {
            if (!str) return '';
            str = str.toLowerCase();
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Update date and time
        function updateDateTime() {
            const now = new Date();

            // Format time
            const timeStr = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

            // Format date
            const dateStr = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('time').textContent = timeStr;
            document.getElementById('date').textContent = dateStr;
        }

        setInterval(updateDateTime, 1000);

        // Speech synthesis
        function speakMessage(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.rate = 1;
                utterance.pitch = 1;
                utterance.volume = 1;
                window.speechSynthesis.speak(utterance);
            }
        }

        // Initialize last called queues
        let lastCalledQueues = JSON.parse(localStorage.getItem('lastCalledQueues') || '{}');
        for (let i = 1; i <= 6; i++) {
            if (!(i in lastCalledQueues)) {
                lastCalledQueues[i] = { q_id: '-', lname: '' };
            }
        }

        // Update queues
        function updateQueues() {
            fetch('{{ route("client.queues") }}')
                .then(response => response.json())
                .then(data => {
                    const calledQueues = data.calledQueues;
                    const nextQueues = data.nextQueues;

                    // Update windows
                    for (let w = 1; w <= 6; w++) {
                        const windowCard = document.getElementById(`window-${w}`);
                        if (!windowCard) continue;

                        const info = calledQueues[w] || { q_id: '-', lname: '' };
                        const qIdElem = windowCard.querySelector('.queue-number');
                        const nameElem = windowCard.querySelector('.client-name');

                        if (qIdElem && qIdElem.textContent !== info.q_id) {
                            qIdElem.textContent = info.q_id;
                            windowCard.classList.add('active');
                            setTimeout(() => windowCard.classList.remove('active'), 2000);
                        }

                        const lnameFormatted = sentenceCase(info.lname);
                        if (nameElem && nameElem.textContent !== lnameFormatted) {
                            nameElem.textContent = lnameFormatted;
                        }

                        // Announce new queue
                        if (info.q_id && info.q_id !== '-' &&
                            (!lastCalledQueues[w] || lastCalledQueues[w].q_id !== info.q_id)) {
                            const message = `Client ${info.q_id}, proceed to Window ${w}`;
                            speakMessage(message);
                            setTimeout(() => speakMessage(message), 1500);
                        }
                    }

                    // Update queue lists
                    updateQueueList('status-queue', nextQueues.statusInquiry);
                    updateQueueList('registration-queue', nextQueues.registrationUpdating);

                    // Save current queues
                    localStorage.setItem('lastCalledQueues', JSON.stringify(calledQueues));
                    lastCalledQueues = calledQueues;
                })
                .catch(err => console.error('Error:', err));
        }

        // Update queue list
        function updateQueueList(elementId, queueArray) {
            const ul = document.getElementById(elementId);
            ul.innerHTML = '';

            if (queueArray && queueArray.length > 0) {
                queueArray.forEach((item, index) => {
                    const li = document.createElement('li');
                    li.className = 'queue-item' + (index < 3 ? ' new' : '');
                    li.innerHTML = `
                        <span class="queue-item-number">${escapeHtml(item.q_id)}</span>
                        <span class="queue-item-name">${sentenceCase(item.lname)}</span>
                        <span class="queue-item-badge">WAITING</span>
                    `;
                    ul.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.className = 'empty-queue';
                li.innerHTML = `
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    <p>No queues waiting</p>
                `;
                ul.appendChild(li);
            }
        }

        // Escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            return String(text).replace(/[&<>"']/g, function(m) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[m];
            });
        }

        // Initialize
        updateDateTime();
        updateQueues();
        setInterval(updateQueues, 5000);
    </script>
</body>
</html>
