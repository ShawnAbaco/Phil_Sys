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
                            <source src="{{ asset('videos/1. DIGITAL NATIONAL ID AUGUST 2024.mp4') }}"
                                type="video/mp4">
                        </video>
                    </div>

                    <!-- Main Foreground Video -->
                    <div class="video-container">
                        <video id="tutorialVideo" class="tutorial-video" autoplay muted playsinline>
                            <source src="{{ asset('videos/1. DIGITAL NATIONAL ID AUGUST 2024.mp4') }}"
                                type="video/mp4">
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

        <script>
            // Video playlist configuration
            const videos = [
                '{{ asset('videos/1. DIGITAL NATIONAL ID AUGUST 2024.mp4') }}',
                '{{ asset('videos/2. National ID Check .mp4') }}',
                '{{ asset('videos/3. National ID eVerify .mp4') }}',
            ];

            let currentVideoIndex = 0;
            const videoPlayer = document.getElementById('tutorialVideo');
            const backgroundVideo = document.getElementById('backgroundVideo');
            const progressBar = document.getElementById('videoProgress');

            // Function to change video
            function changeVideo() {
                currentVideoIndex = (currentVideoIndex + 1) % videos.length;
                const newVideoSrc = videos[currentVideoIndex];

                // Update both foreground and background videos
                videoPlayer.src = newVideoSrc;
                backgroundVideo.src = newVideoSrc;

                videoPlayer.load();
                backgroundVideo.load();

                videoPlayer.play().catch(e => console.log('Autoplay prevented:', e));
                backgroundVideo.play().catch(e => console.log('Background autoplay prevented:', e));
            }

            // Update progress bar
            function updateProgress() {
                if (videoPlayer.duration) {
                    const progress = (videoPlayer.currentTime / videoPlayer.duration) * 100;
                    progressBar.style.width = progress + '%';
                }
            }

            // Sync background video with foreground
            videoPlayer.addEventListener('play', function() {
                backgroundVideo.play();
            });

            videoPlayer.addEventListener('pause', function() {
                backgroundVideo.pause();
            });

            videoPlayer.addEventListener('seeked', function() {
                backgroundVideo.currentTime = videoPlayer.currentTime;
            });

            // Event listeners for video
            videoPlayer.addEventListener('ended', changeVideo);
            videoPlayer.addEventListener('timeupdate', updateProgress);

            // Handle video errors
            videoPlayer.addEventListener('error', function(e) {
                console.error('Video error:', e);
                // Skip to next video on error
                changeVideo();
            });

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
                    // First announcement
                    const utterance1 = new SpeechSynthesisUtterance(text);
                    utterance1.rate = 0.9;
                    utterance1.pitch = 1;
                    utterance1.volume = 1;
                    window.speechSynthesis.speak(utterance1);

                    // Second announcement with "Please proceed to" prefix
                    setTimeout(() => {
                        // Extract window and queue number from the text
                        const matches = text.match(/Window (\d+), now serving (.+)/);
                        if (matches) {
                            const windowNum = matches[1];
                            const queueNum = matches[2];
                            const secondText = `Please proceed to window ${windowNum}, queue number ${queueNum}`;

                            const utterance2 = new SpeechSynthesisUtterance(secondText);
                            utterance2.rate = 0.9;
                            utterance2.pitch = 1;
                            utterance2.volume = 1;
                            window.speechSynthesis.speak(utterance2);
                        }
                    }, 1500);
                }
            }

            let lastCalledQueues = JSON.parse(localStorage.getItem('lastCalledQueues') || '{}');
            for (let i = 1; i <= 6; i++) {
                if (!(i in lastCalledQueues)) {
                    lastCalledQueues[i] = {
                        q_id: '-',
                        priority_type: ''
                    };
                }
            }

            function updateQueues() {
                fetch('{{ route('client.queues') }}')
                    .then(response => response.json())
                    .then(data => {
                        const calledQueues = data.calledQueues;
                        const nextQueues = data.nextQueues;

                        // Update windows
                        for (let w = 1; w <= 6; w++) {
                            const windowCard = document.getElementById(`window-${w}`);
                            if (windowCard && calledQueues[w]) {
                                const queueNum = windowCard.querySelector('.queue-number');
                                const clientNameDiv = windowCard.querySelector('.client-name');

                                if (queueNum) queueNum.textContent = calledQueues[w].q_id || '-';

                                // Update priority text
                                if (clientNameDiv) {
                                    clientNameDiv.textContent = calledQueues[w].priority_type || '';
                                }

                                // Add serving indicator class
                                if (calledQueues[w].q_id !== '-') {
                                    windowCard.classList.add('active-serving');
                                } else {
                                    windowCard.classList.remove('active-serving');
                                }

                                // Check if this is a new call
                                if (lastCalledQueues[w] && lastCalledQueues[w].q_id !== calledQueues[w].q_id &&
                                    calledQueues[w].q_id !== '-') {
                                    windowCard.classList.add('new-call');
                                    setTimeout(() => windowCard.classList.remove('new-call'), 3000);

                                    // Announce the new call
                                    speakMessage(`Window ${w}, now serving ${calledQueues[w].q_id}`);
                                }
                            }
                        }

                        // Update queue lists - using the correct structure from the original code
                        updateQueueList('status-queue', nextQueues.statusInquiry || []);
                        updateQueueList('registration-queue', nextQueues.registration || []);
                        updateQueueList('updating-queue', nextQueues.updating || []);

                        localStorage.setItem('lastCalledQueues', JSON.stringify(calledQueues));
                        lastCalledQueues = calledQueues;
                    })
                    .catch(err => console.error('Error:', err));
            }

            function updateQueueList(elementId, queueArray) {
                const ul = document.getElementById(elementId);
                if (!ul) return;

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
