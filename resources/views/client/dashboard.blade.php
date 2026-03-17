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
        .no-show-badge {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            margin-left: 8px;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .window-card .client-name {
            font-size: 1.5rem;
            font-weight: 500;
            color: #323a44;
            
            margin-top: 5px;
        }
        
        .priority-text {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        <!-- Main Content: Video (left) and Windows Grid (right) -->
        <div class="main-content">
            <!-- Video Section (Left) -->
            <div class="video-section">
                <div class="video-container">
                    <video id="tutorialVideo" class="tutorial-video" autoplay muted playsinline>
                        <source src="{{ asset('videos/video1.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-overlay">
                        <div class="video-title">Philippine Statistics Authority (NATIONAL ID CENTER)</div>
                        <div class="video-progress">
                            <div class="video-progress-bar" id="videoProgress"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Windows Grid Section (Right) - 2x3 Grid -->
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
                '{{ asset('videos/video1.mp4') }}',
                '{{ asset('videos/video2.mp4') }}',
                '{{ asset('videos/video3.mp4') }}',
                '{{ asset('videos/video4.mp4') }}'
            ];

            let currentVideoIndex = 0;
            const videoPlayer = document.getElementById('tutorialVideo');
            const progressBar = document.getElementById('videoProgress');

            // Function to change video
            function changeVideo() {
                currentVideoIndex = (currentVideoIndex + 1) % videos.length;
                videoPlayer.src = videos[currentVideoIndex];
                videoPlayer.load();
                videoPlayer.play().catch(e => console.log('Autoplay prevented:', e));
            }

            // Update progress bar
            function updateProgress() {
                if (videoPlayer.duration) {
                    const progress = (videoPlayer.currentTime / videoPlayer.duration) * 100;
                    progressBar.style.width = progress + '%';
                }
            }

            // Event listeners for video
            videoPlayer.addEventListener('ended', changeVideo);
            videoPlayer.addEventListener('timeupdate', updateProgress);

            // Handle video errors
            videoPlayer.addEventListener('error', function(e) {
                console.error('Video error:', e);
                // Skip to next video on error
                changeVideo();
            });

            // Preload next video
            videoPlayer.addEventListener('loadedmetadata', function() {
                // Preload next video
                const nextIndex = (currentVideoIndex + 1) % videos.length;
                const nextVideo = new Audio();
                nextVideo.src = videos[nextIndex];
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

                        // Update windows - only showing serving status
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

                        localStorage.setItem('lastCalledQueues', JSON.stringify(calledQueues));
                        lastCalledQueues = calledQueues;
                    })
                    .catch(err => console.error('Error:', err));
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