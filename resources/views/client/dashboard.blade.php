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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* PSA Colors */
            --psa-blue: #003366;
            --psa-blue-light: #1a4d7a;
            --psa-red: #b22222;
            --psa-red-light: #c0392b;
            --psa-yellow: #ffd700;
            --psa-yellow-light: #ffdb4d;

            /* Neutral colors */
            --background: #f0f2f5;
            --surface: #ffffff;
            --surface-secondary: #f8f9fa;
            --text-primary: #000000;
            --text-secondary: #000000;
            --text-light: #000000;
            --border-light: #e2e8f0;
            --border-strong: #cbd5e1;
            --shadow: rgba(0, 0, 0, 0.08);
        }

        html,
        body {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

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
            filter: blur(3px) brightness(1) opacity(1);
            transform: scale(1.1);
            z-index: -1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Main Container */
        .main-container {
            position: relative;
            z-index: 10;
            height: 100vh;
            width: 100vw;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Header with PSA colors */
        .header {
            background: linear-gradient(135deg, var(--psa-blue) 100%);
            border-radius: 20px;
            padding: 16px 28px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            border: none;
            box-shadow: 0 8px 20px rgba(0, 51, 102, 0.2);
            min-height: 90px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        /* Decorative elements */
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--psa-yellow), var(--psa-red), var(--psa-yellow));
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 200px;
            height: 100px;
            background: radial-gradient(circle at bottom right, rgba(255, 215, 0, 0.1), transparent 70%);
            pointer-events: none;
        }

        .header-left {
            display: flex;
            justify-content: flex-start;
            position: relative;
            z-index: 2;
        }

        .header-left img {
            height: 75px;
            width: 250px;
        }

        .header-center {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .header-center h1 {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            letter-spacing: 0.5px;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-center .subtitle {
            color: var(--psa-yellow);
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 2px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .welcome-message {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            font-weight: 500;
            margin-top: 4px;
        }

        .header-right {
            display: flex;
            justify-content: flex-end;
            position: relative;
            z-index: 2;
        }

        .date-time {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 10px 22px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .date-time .time {
            font-size: 2.2rem;
            font-weight: 700;
            color: white;
            font-family: monospace;
            line-height: 1.2;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .date-time .date {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-top: 2px;
        }

        /* Content Area - 4 Column Layout */
        .content-area {
            display: grid;
            grid-template-columns: 1.2fr 1.2fr 1.3fr 1.3fr;
            gap: 20px;
            flex: 1;
            min-height: 0;
        }

        /* Window Cards with subtle PSA color accents */
        .window-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .window-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px 12px;
            text-align: center;
            border: 1px solid var(--border-strong);
            box-shadow: 0 4px 12px var(--shadow);
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        /* Window-specific colored accents */
        .window-card[data-window="1"] {
            border-top: 4px solid var(--psa-blue);
        }

        .window-card[data-window="2"] {
            border-top: 4px solid var(--psa-red);
        }

        .window-card[data-window="3"] {
            border-top: 4px solid var(--psa-yellow);
        }

        .window-card[data-window="4"] {
            border-top: 4px solid var(--psa-blue);
        }

        .window-card[data-window="5"] {
            border-top: 4px solid var(--psa-red);
        }

        .window-card[data-window="6"] {
            border-top: 4px solid var(--psa-yellow);
        }

        .window-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--shadow);
        }

        .window-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-secondary);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .serving-label {
            color: var(--text-light);
            font-size: 1.5rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            =
        }

        .queue-number {
            font-size: 4.2rem;
            font-weight: 800;
            color: var(--psa-red);
            line-height: 1;
            font-family: monospace;
            letter-spacing: -2px;
            margin-bottom: 6px;
            text-shadow: 0 5px 8px rgba(178, 34, 34, 0.1);
        }

        .client-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 10px;
            text-transform: uppercase;
        }

        /* Queue Sections with PSA color headers */
        .queue-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .queue-section {
            background: var(--surface);
            border-radius: 20px;
            padding: 0;
            border: 1px solid var(--border-strong);
            box-shadow: 0 4px 12px var(--shadow);
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        .queue-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 18px;
            background: linear-gradient(135deg, var(--psa-blue), var(--psa-blue-light));
            border-bottom: 3px solid var(--psa-yellow);
        }

        .queue-header svg {
            width: 22px;
            height: 22px;
            color: var(--psa-yellow);
            fill: currentColor;
        }

        .queue-header h2 {
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Queue List */
        .queue-list {
            list-style: none;
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
            align-content: start;
            overflow: visible;
            padding: 18px;
            margin: 0;
        }

        .queue-item {
            background: var(--surface-secondary);
            border-radius: 14px;
            padding: 14px 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-light);
            min-width: 0;
            transition: all 0.2s ease;
            position: relative;
        }

        /* Subtle color variations for queue items */
        .queue-item:nth-child(3n+1) {
            border-left: 3px solid var(--psa-blue);
        }

        .queue-item:nth-child(3n+2) {
            border-left: 3px solid var(--psa-red);
        }

        .queue-item:nth-child(3n+3) {
            border-left: 3px solid var(--psa-yellow);
        }

        .queue-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.1);
            border-color: var(--border-strong);
        }

        .queue-item-number {
            font-size: 1.9rem;
            font-weight: 700;
            color: var(black);
            font-family: Arial, sans-serif;
            letter-spacing: -0.5px;
            text-align: center;
            line-height: 1.2;
        }

        .empty-queue {
            grid-column: 1 / -1;
            text-align: center;
            padding: 30px 20px;
            color: var(--text-light);
            font-size: 1rem;
            background: var(--surface-secondary);
            border-radius: 16px;
            border: 1px dashed var(--border-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .empty-queue svg {
            width: 40px;
            height: 40px;
            color: var(--text-light);
            opacity: 0.5;
        }

        .empty-queue p {
            font-weight: 500;
        }

        /* Animations */
        @keyframes highlight {
            0% {
                border-color: var(--border-strong);
                box-shadow: 0 4px 12px var(--shadow);
            }

            50% {
                border-color: var(--psa-yellow);
                box-shadow: 0 8px 20px rgba(255, 215, 0, 0.2);
            }

            100% {
                border-color: var(--border-strong);
                box-shadow: 0 4px 12px var(--shadow);
            }
        }

        .window-card.active {
            animation: highlight 1.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0.6;
                transform: translateY(-2px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .queue-item.new {
            animation: fadeIn 0.4s ease;
        }

        /* Loading Modal with PSA colors */
        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 51, 102, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading-modal.show {
            display: flex;
        }

        .loading-content {
            background: var(--surface);
            padding: 32px 48px;
            border-radius: 24px;
            text-align: center;
            border: 3px solid var(--psa-yellow);
            box-shadow: 0 20px 40px rgba(0, 51, 102, 0.3);
            position: relative;
            overflow: hidden;
        }

        .loading-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--psa-blue), var(--psa-red), var(--psa-yellow));
        }

        .rotate-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            animation: rotate 1.2s linear infinite;
            filter: brightness(0) saturate(100%) invert(20%) sepia(95%) saturate(2000%) hue-rotate(190deg);
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: var(--psa-blue);
            font-weight: 700;
            font-size: 1.2rem;
        }

        /* No scrollbar */
        ::-webkit-scrollbar {
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 1600px) {
            .queue-number {
                font-size: 3.8rem;
            }

            .client-name {
                font-size: 1.2rem;
            }

            .queue-item-number {
                font-size: 1.7rem;
            }
        }

        @media (max-width: 1400px) {
            .queue-number {
                font-size: 3.5rem;
            }

            .client-name {
                font-size: 1.1rem;
            }

            .queue-item-number {
                font-size: 1.6rem;
            }

            .header-center h1 {
                font-size: 1.7rem;
            }

            .date-time .time {
                font-size: 2rem;
            }

            .date-time .date {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 1200px) {
            .queue-number {
                font-size: 3.2rem;
            }

            .client-name {
                font-size: 1rem;
            }

            .queue-item-number {
                font-size: 1.5rem;
            }

            .queue-list {
                grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header with PSA colors -->
        <div class="header">
            <div class="header-left">
                <img src="{{ asset('images/logo.png') }}" alt="National ID Logo">
            </div>
            <div class="header-center">
                <h1>NATIONAL ID CENTER</h1>
                <div class="subtitle">Queue Monitoring Display</div>
                <div class="welcome-message">We are happy to serve you</div>
            </div>
            <div class="header-right">
                <div class="date-time">
                    <div class="time" id="time"></div>
                    <div class="date" id="date"></div>
                </div>
            </div>
        </div>

        <!-- Content Area - 4 Column Layout -->
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
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <h2>STATUS INQUIRY</h2>
                    </div>
                    <ul class="queue-list" id="status-queue">
                        @forelse($nextQueues['statusInquiry'] as $item)
                            <li class="queue-item">
                                <span class="queue-item-number">{{ $item['q_id'] }}</span>
                            </li>
                        @empty
                            <li class="empty-queue">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
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
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        <h2>REGISTRATION & UPDATING</h2>
                    </div>
                    <ul class="queue-list" id="registration-queue">
                        @forelse($nextQueues['registrationUpdating'] as $item)
                            <li class="queue-item">
                                <span class="queue-item-number">{{ $item['q_id'] }}</span>
                            </li>
                        @empty
                            <li class="empty-queue">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
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
        // Update date and time
        function updateDateTime() {
            const now = new Date();

            const timeStr = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

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
                utterance.rate = 0.9;
                utterance.pitch = 1;
                utterance.volume = 1;
                window.speechSynthesis.speak(utterance);
            }
        }

        let lastCalledQueues = JSON.parse(localStorage.getItem('lastCalledQueues') || '{}');
        for (let i = 1; i <= 6; i++) {
            if (!(i in lastCalledQueues)) {
                lastCalledQueues[i] = {
                    q_id: '-',
                    lname: ''
                };
            }
        }

        function updateQueues() {
            fetch('{{ route('client.queues') }}')
                .then(response => response.json())
                .then(data => {
                    const calledQueues = data.calledQueues;
                    const nextQueues = data.nextQueues;

                    for (let w = 1; w <= 6; w++) {
                        const windowCard = document.getElementById(`window-${w}`);
                        if (!windowCard) continue;

                        const info = calledQueues[w] || {
                            q_id: '-',
                            lname: ''
                        };
                        const qIdElem = windowCard.querySelector('.queue-number');
                        const nameElem = windowCard.querySelector('.client-name');

                        if (qIdElem && qIdElem.textContent !== info.q_id) {
                            qIdElem.textContent = info.q_id;
                            windowCard.classList.add('active');
                            setTimeout(() => windowCard.classList.remove('active'), 2000);
                        }

                        if (nameElem && nameElem.textContent !== info.lname) {
                            nameElem.textContent = info.lname || '';
                        }

                        if (info.q_id && info.q_id !== '-' &&
                            (!lastCalledQueues[w] || lastCalledQueues[w].q_id !== info.q_id)) {
                            const message = `Client ${info.q_id}, proceed to Window ${w}`;
                            speakMessage(message);
                            setTimeout(() => speakMessage(message), 1500);
                        }
                    }

                    updateQueueList('status-queue', nextQueues.statusInquiry);
                    updateQueueList('registration-queue', nextQueues.registrationUpdating);

                    localStorage.setItem('lastCalledQueues', JSON.stringify(calledQueues));
                    lastCalledQueues = calledQueues;
                })
                .catch(err => console.error('Error:', err));
        }

        function updateQueueList(elementId, queueArray) {
            const ul = document.getElementById(elementId);
            ul.innerHTML = '';

            if (queueArray && queueArray.length > 0) {
                queueArray.forEach((item, index) => {
                    const li = document.createElement('li');
                    li.className = 'queue-item' + (index < 3 ? ' new' : '');
                    li.innerHTML = `
                        <span class="queue-item-number">${escapeHtml(item.q_id)}</span>
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

        function escapeHtml(text) {
            if (!text) return '';
            return String(text).replace(/[&<>"']/g, function(m) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [m];
            });
        }

        updateDateTime();
        updateQueues();
        setInterval(updateQueues, 5000);
    </script>
</body>

</html>
