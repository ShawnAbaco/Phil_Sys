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

    <style>
        /* Additional styles for change indicators and visual feedback */
        .queue-item.changed {
            animation: queueHighlight 0.6s ease-out;
        }

        .window-card .queue-number.changed {
            animation: numberPop 0.5s cubic-bezier(0.34, 1.2, 0.64, 1);
        }

        @keyframes queueHighlight {
            0% {
                background-color: rgba(46, 125, 50, 0);
                transform: scale(1);
            }

            30% {
                background-color: rgba(46, 125, 50, 0.3);
                transform: scale(1.02);
            }

            100% {
                background-color: rgba(46, 125, 50, 0);
                transform: scale(1);
            }
        }

        @keyframes numberPop {
            0% {
                transform: scale(1);
                color: inherit;
            }

            50% {
                transform: scale(1.15);
                color: #2e7d32;
            }

            100% {
                transform: scale(1);
                color: inherit;
            }
        }

        /* Connection status indicator */
        .connection-status {
            position: fixed;
            bottom: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.75);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            z-index: 1000;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Inter', sans-serif;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #4caf50;
            box-shadow: 0 0 6px rgba(76, 175, 80, 0.6);
            animation: pulse 1.5s infinite;
        }

        .status-dot.disconnected {
            background-color: #f44336;
            animation: none;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Refresh indicator */
        .refresh-indicator {
            position: fixed;
            bottom: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.6);
            color: #ccc;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-family: monospace;
            backdrop-filter: blur(4px);
            pointer-events: none;
            z-index: 1000;
        }

        /* Loading overlay for updates */
        .updating-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 999;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .updating-overlay.active {
            opacity: 1;
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

        <!-- Main Layout: Windows Left, Queue Columns and Video Right -->
        <div class="main-layout">
            <!-- Left Side: All Windows in 3x2 Grid -->
            <div class="windows-grid">
                <!-- Row 1 -->
                <div class="window-card" data-window="1" id="window-1">
                    <div class="window-number">WINDOW 1</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[1]['q_id'] ?? '-' }}</div>
                    <div class="client-name priority-text">
                        {{ $calledQueues[1]['priority_type'] ?? '' }}
                    </div>
                </div>
                <div class="window-card" data-window="2" id="window-2">
                    <div class="window-number">WINDOW 2</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[2]['q_id'] ?? '-' }}</div>
                    <div class="client-name priority-text">
                        {{ $calledQueues[2]['priority_type'] ?? '' }}
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="window-card" data-window="3" id="window-3">
                    <div class="window-number">WINDOW 3</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[3]['q_id'] ?? '-' }}</div>
                    <div class="client-name priority-text">
                        {{ $calledQueues[3]['priority_type'] ?? '' }}
                    </div>
                </div>
                <div class="window-card" data-window="4" id="window-4">
                    <div class="window-number">WINDOW 4</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[4]['q_id'] ?? '-' }}</div>
                    <div class="client-name priority-text">
                        {{ $calledQueues[4]['priority_type'] ?? '' }}
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="window-card" data-window="5" id="window-5">
                    <div class="window-number">WINDOW 5</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[5]['q_id'] ?? '-' }}</div>
                    <div class="client-name priority-text">
                        {{ $calledQueues[5]['priority_type'] ?? '' }}
                    </div>
                </div>
                <div class="window-card" data-window="6" id="window-6">
                    <div class="window-number">WINDOW 6</div>
                    <div class="serving-label">Now Serving</div>
                    <div class="queue-number">{{ $calledQueues[6]['q_id'] ?? '-' }}</div>
                    <div class="client-name priority-text">
                        {{ $calledQueues[6]['priority_type'] ?? '' }}
                    </div>
                </div>
            </div>

            <!-- Right Side: Queue Columns (Top) and Video (Bottom) -->
            <div class="right-side">
                <!-- Queue Columns: STATUS INQUIRY, REGISTRATION, UPDATING -->
                <div class="queue-columns">
                    <!-- Status Inquiry -->
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
                            @forelse($nextQueues['statusInquiry'] ?? [] as $item)
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

                    <!-- Registration -->
                    <div class="queue-section">
                        <div class="queue-header">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <h2>REGISTRATION</h2>
                        </div>
                        <ul class="queue-list" id="registration-queue">
                            @forelse($nextQueues['registration'] ?? [] as $item)
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

                    <!-- Updating -->
                    <div class="queue-section">
                        <div class="queue-header">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                    clip-rule="evenodd" />
                            </svg>
                            <h2>UPDATING</h2>
                        </div>
                        <ul class="queue-list" id="updating-queue">
                            @forelse($nextQueues['updating'] ?? [] as $item)
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

                <!-- Video Section with Blur Background -->
                <div class="video-section">
                    <!-- Background Blur Video -->
                    <div class="video-background">
                        <video id="backgroundVideo" class="background-video" autoplay muted loop playsinline>
                            <source src="{{ asset('videos/2. National ID Check .mp4') }}" type="video/mp4">
                        </video>
                    </div>

                    <!-- Main Foreground Video -->
                    <div class="video-container">
                        <video id="tutorialVideo" class="tutorial-video" autoplay muted playsinline>
                            <source src="{{ asset('videos/2. National ID Check .mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
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

        <!-- Connection and refresh status indicators -->
        <div class="connection-status" id="connectionStatus">
            <span class="status-dot" id="statusDot"></span>
            <span id="statusText">Connected</span>
        </div>
        <div class="updating-overlay" id="updatingOverlay"></div>

        <script>
            // Video playlist
            const videos = [
                '{{ asset('videos/2. National ID Check .mp4') }}',
                '{{ asset('videos/3. National ID eVerify .mp4') }}',
            ];

            let currentVideoIndex = 0;
            let isLooping = true;
            const videoPlayer = document.getElementById('tutorialVideo');
            const backgroundVideo = document.getElementById('backgroundVideo');

            // Data state management
            let currentDataState = {
                calledQueues: {},
                nextQueues: {
                    statusInquiry: [],
                    registration: [],
                    updating: []
                }
            };

            let lastCalledQueuesForSpeech = {};
            let refreshTimer = null;
            let isRefreshing = false;
            let consecutiveErrors = 0;
            let dynamicInterval = 5000; // Start with 5 seconds, can adapt

            // Initialize with server data from Blade (if any)
            function initializeDataFromServer() {
                const initialCalled = {!! json_encode($calledQueues) !!} || {};
                const initialNext = {!! json_encode($nextQueues) !!} || {};

                for (let i = 1; i <= 6; i++) {
                    if (!initialCalled[i]) {
                        initialCalled[i] = {
                            q_id: '-',
                            priority_type: ''
                        };
                    }
                }

                currentDataState.calledQueues = initialCalled;
                currentDataState.nextQueues = {
                    statusInquiry: initialNext.statusInquiry || [],
                    registration: initialNext.registration || [],
                    updating: initialNext.updating || []
                };

                lastCalledQueuesForSpeech = JSON.parse(JSON.stringify(initialCalled));

                // Initial render
                renderWindowsFromState();
                renderQueueListsFromState();
            }

            // Deep comparison functions for detecting actual changes
            function areQueuesEqual(queue1, queue2) {
                if (!queue1 && !queue2) return true;
                if (!queue1 || !queue2) return false;
                return queue1.q_id === queue2.q_id && queue1.priority_type === queue2.priority_type;
            }

            function areCalledQueuesChanged(newCalled, oldCalled) {
                for (let i = 1; i <= 6; i++) {
                    const newQ = newCalled[i] || {
                        q_id: '-',
                        priority_type: ''
                    };
                    const oldQ = oldCalled[i] || {
                        q_id: '-',
                        priority_type: ''
                    };
                    if (newQ.q_id !== oldQ.q_id || newQ.priority_type !== oldQ.priority_type) {
                        return true;
                    }
                }
                return false;
            }

            function areNextQueuesChanged(newNext, oldNext) {
                // Compare each queue type arrays by stringifying (order matters for queue display)
                const types = ['statusInquiry', 'registration', 'updating'];
                for (let type of types) {
                    const newArr = newNext[type] || [];
                    const oldArr = oldNext[type] || [];
                    if (newArr.length !== oldArr.length) return true;

                    for (let i = 0; i < newArr.length; i++) {
                        const newItem = newArr[i];
                        const oldItem = oldArr[i];
                        if (!newItem || !oldItem) return true;
                        if (newItem.q_id !== oldItem.q_id) return true;
                    }
                }
                return false;
            }

            // DOM update functions with change detection per element
            function updateWindowUI(windowNum, newData, oldData) {
                const windowCard = document.getElementById(`window-${windowNum}`);
                if (!windowCard) return false;

                const queueNumDiv = windowCard.querySelector('.queue-number');
                const clientNameDiv = windowCard.querySelector('.client-name');

                let changed = false;

                // Update queue number with animation if changed
                const newQueueId = newData.q_id || '-';
                const oldQueueId = oldData ? oldData.q_id : '-';
                if (queueNumDiv && newQueueId !== oldQueueId) {
                    queueNumDiv.textContent = newQueueId;
                    queueNumDiv.classList.add('changed');
                    setTimeout(() => queueNumDiv.classList.remove('changed'), 500);
                    changed = true;
                } else if (queueNumDiv) {
                    queueNumDiv.textContent = newQueueId;
                }

                // Update priority text
                const newPriority = newData.priority_type || '';
                const oldPriority = oldData ? oldData.priority_type : '';
                if (clientNameDiv && newPriority !== oldPriority) {
                    clientNameDiv.textContent = newPriority;
                    changed = true;
                } else if (clientNameDiv) {
                    clientNameDiv.textContent = newPriority;
                }

                // Update active class
                const isActive = newQueueId !== '-';
                const wasActive = oldQueueId !== '-';
                if (isActive !== wasActive) {
                    if (isActive) {
                        windowCard.classList.add('active-serving');
                    } else {
                        windowCard.classList.remove('active-serving');
                    }
                    changed = true;
                }

                return changed;
            }

            function renderWindowsFromState() {
                for (let w = 1; w <= 6; w++) {
                    const newData = currentDataState.calledQueues[w] || {
                        q_id: '-',
                        priority_type: ''
                    };
                    const oldData = window._prevWindowStates ? window._prevWindowStates[w] : null;
                    const hasChanged = updateWindowUI(w, newData, oldData);

                    // Speech announcement for new calls (only when queue number changes to a non-dash)
                    if (oldData && newData.q_id !== '-' && newData.q_id !== oldData.q_id) {
                        console.log(`New call detected at Window ${w}: ${newData.q_id}`);
                        const priorityText = newData.priority_type ? ` (${newData.priority_type})` : '';
                        speakMessage(`Window ${w}, now serving ${newData.q_id}${priorityText}`);
                    }
                }
                // Store current states for next comparison
                window._prevWindowStates = JSON.parse(JSON.stringify(currentDataState.calledQueues));
            }

            function renderQueueListsFromState() {
                updateQueueListWithChangeDetection('status-queue', currentDataState.nextQueues.statusInquiry || [], 'status');
                updateQueueListWithChangeDetection('registration-queue', currentDataState.nextQueues.registration || [],
                    'registration');
                updateQueueListWithChangeDetection('updating-queue', currentDataState.nextQueues.updating || [], 'updating');
            }

            // Store previous queue list states for change detection
            let prevQueueStates = {
                status: [],
                registration: [],
                updating: []
            };

            function updateQueueListWithChangeDetection(elementId, queueArray, queueType) {
                const ul = document.getElementById(elementId);
                if (!ul) return;

                const prevQueues = prevQueueStates[queueType] || [];
                const hasChanged = queueArray.length !== prevQueues.length ||
                    queueArray.some((item, idx) => {
                        const prevItem = prevQueues[idx];
                        return !prevItem || item.q_id !== prevItem.q_id;
                    });

                if (!hasChanged && queueArray.length === prevQueues.length) {
                    return; // No change, skip DOM update
                }

                // Update DOM with visual feedback for changed items
                ul.innerHTML = '';

                if (queueArray && queueArray.length > 0) {
                    queueArray.forEach((item, index) => {
                        const li = document.createElement('li');
                        li.className = 'queue-item';
                        li.innerHTML = `<span class="queue-item-number">${escapeHtml(item.q_id)}</span>`;

                        // Add animation if this specific queue number is new compared to previous
                        const prevItem = prevQueues[index];
                        if (!prevItem || prevItem.q_id !== item.q_id) {
                            li.classList.add('changed');
                            setTimeout(() => li.classList.remove('changed'), 600);
                        }

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

                // Store new state
                prevQueueStates[queueType] = JSON.parse(JSON.stringify(queueArray));
            }

            // Core fetch with intelligent refresh - only updates state if data changed
            async function fetchQueuesWithConditionalRefresh() {
                if (isRefreshing) return;
                isRefreshing = true;

                const startTime = performance.now();
                let connectionOk = true;

                try {
                    const response = await fetch('{{ route('client.queues') }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Cache-Control': 'no-cache'
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP ${response.status}`);

                    const data = await response.json();
                    consecutiveErrors = 0;
                    connectionOk = true;
                    updateConnectionStatus(true);

                    const newCalledQueues = data.calledQueues || {};
                    const newNextQueues = data.nextQueues || {
                        statusInquiry: [],
                        registration: [],
                        updating: []
                    };

                    // Ensure all windows have data
                    for (let i = 1; i <= 6; i++) {
                        if (!newCalledQueues[i]) {
                            newCalledQueues[i] = {
                                q_id: '-',
                                priority_type: ''
                            };
                        }
                    }

                    // Detect actual changes
                    const calledChanged = areCalledQueuesChanged(newCalledQueues, currentDataState.calledQueues);
                    const nextChanged = areNextQueuesChanged(newNextQueues, currentDataState.nextQueues);

                    if (calledChanged || nextChanged) {
                        console.log('Data changes detected - updating display');

                        // Update state only when real changes exist
                        const oldCalled = {
                            ...currentDataState.calledQueues
                        };
                        currentDataState.calledQueues = newCalledQueues;
                        currentDataState.nextQueues = {
                            statusInquiry: newNextQueues.statusInquiry || [],
                            registration: newNextQueues.registration || [],
                            updating: newNextQueues.updating || []
                        };

                        // Re-render only changed sections
                        if (calledChanged) {
                            renderWindowsFromState();
                        }
                        if (nextChanged) {
                            renderQueueListsFromState();
                        }

                        // Brief visual feedback on refresh indicator
                        const refreshSpan = document.getElementById('lastRefreshTime');
                        if (refreshSpan) {
                            const now = new Date();
                            refreshSpan.textContent = now.toLocaleTimeString();
                            refreshSpan.style.color = '#4caf50';
                            setTimeout(() => {
                                if (refreshSpan) refreshSpan.style.color = '#ccc';
                            }, 500);
                        }
                    } else {
                        console.log('No data changes, skipping DOM update');
                        // Still update timestamp but no visual refresh
                        const refreshSpan = document.getElementById('lastRefreshTime');
                        if (refreshSpan) {
                            const now = new Date();
                            refreshSpan.textContent = now.toLocaleTimeString();
                        }
                    }

                    // Adjust dynamic interval based on activity (optional: if changes frequent, keep faster)
                    if (calledChanged || nextChanged) {
                        dynamicInterval = Math.max(3000, dynamicInterval - 200);
                    } else {
                        dynamicInterval = Math.min(8000, dynamicInterval + 300);
                    }

                    // Reset and schedule next refresh with dynamic timing
                    if (refreshTimer) clearTimeout(refreshTimer);
                    refreshTimer = setTimeout(() => fetchQueuesWithConditionalRefresh(), dynamicInterval);

                } catch (error) {
                    console.error('Fetch error:', error);
                    consecutiveErrors++;
                    connectionOk = false;
                    updateConnectionStatus(false);

                    // Exponential backoff on errors (max 30 seconds)
                    const backoffTime = Math.min(30000, 5000 * Math.pow(1.5, consecutiveErrors));
                    if (refreshTimer) clearTimeout(refreshTimer);
                    refreshTimer = setTimeout(() => fetchQueuesWithConditionalRefresh(), backoffTime);
                } finally {
                    isRefreshing = false;
                    const elapsed = performance.now() - startTime;
                    if (elapsed > 1000) {
                        console.warn(`Slow refresh: ${elapsed.toFixed(0)}ms`);
                    }
                }
            }

            // Connection status UI
            function updateConnectionStatus(isConnected) {
                const statusDot = document.getElementById('statusDot');
                const statusTextSpan = document.getElementById('statusText');
                if (!statusDot || !statusTextSpan) return;

                if (isConnected) {
                    statusDot.className = 'status-dot';
                    statusTextSpan.textContent = 'Connected';
                } else {
                    statusDot.className = 'status-dot disconnected';
                    statusTextSpan.textContent = 'Reconnecting...';
                }
            }

            // Speech synthesis (improved)
            function speakMessage(text) {
                if (!('speechSynthesis' in window)) return;
                window.speechSynthesis.cancel();

                const utterance1 = new SpeechSynthesisUtterance(text);
                utterance1.rate = 0.9;
                utterance1.pitch = 1;
                utterance1.volume = 1;
                utterance1.lang = 'en-US';
                window.speechSynthesis.speak(utterance1);

                setTimeout(() => {
                    const matches = text.match(/Window (\d+), now serving (.+)/);
                    if (matches) {
                        const secondText = `Please proceed to window ${matches[1]}, ${matches[2]}`;
                        const utterance2 = new SpeechSynthesisUtterance(secondText);
                        utterance2.rate = 0.9;
                        window.speechSynthesis.speak(utterance2);
                    }
                }, 1800);
            }

            // Check for operator announcements (unchanged but optimized)
            let lastAnnouncementId = null;
            async function checkForAnnouncements() {
                try {
                    const response = await fetch('{{ route('client.check-announcement') }}');
                    if (!response.ok) return;
                    const data = await response.json();
                    if (data.announcement && data.announcement.id !== lastAnnouncementId) {
                        lastAnnouncementId = data.announcement.id;
                        const ann = data.announcement;
                        let message = ann.name && ann.name.trim() ?
                            `Window ${ann.window}, now serving ${ann.queue}, ${ann.name}` :
                            `Window ${ann.window}, now serving ${ann.queue}`;
                        speakMessage(message);
                    }
                } catch (err) {
                    console.error('Announcement check error:', err);
                }
            }

            // Helper functions
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

            function updateDateTime() {
                const now = new Date();
                document.getElementById('time').textContent = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
                document.getElementById('date').textContent = now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            // Video functions (unchanged)
            function playNextVideo() {
                if (isLooping) {
                    currentVideoIndex = (currentVideoIndex + 1) % videos.length;
                    const newSrc = videos[currentVideoIndex];
                    videoPlayer.src = newSrc;
                    backgroundVideo.src = newSrc;
                    videoPlayer.load();
                    backgroundVideo.load();
                    videoPlayer.play().catch(e => console.log('Play error:', e));
                    backgroundVideo.play().catch(e => console.log('BG error:', e));
                }
            }

            function initializeVideoPlayback() {
                videoPlayer.addEventListener('ended', () => playNextVideo());
                videoPlayer.play().catch(() => {
                    document.body.addEventListener('click', function startVideos() {
                        videoPlayer.play();
                        backgroundVideo.play();
                        document.body.removeEventListener('click', startVideos);
                    }, {
                        once: true
                    });
                });
            }

            // Initialize everything
            initializeDataFromServer();
            updateDateTime();
            setInterval(updateDateTime, 1000);
            initializeVideoPlayback();

            // Start intelligent refresh system
            fetchQueuesWithConditionalRefresh();

            // Check announcements separately (every 3 seconds)
            setInterval(checkForAnnouncements, 3000);

            // Store initial window states for change detection
            window._prevWindowStates = JSON.parse(JSON.stringify(currentDataState.calledQueues));
        </script>
</body>

</html>
