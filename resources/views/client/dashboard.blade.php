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
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">

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
