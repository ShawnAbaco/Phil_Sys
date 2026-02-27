<!DOCTYPE html>
<html>
<head>
    <title>Client Dashboard - NID Operation</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial; background: white; color: #222; padding: 20px; margin: 0; }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 15px 0;
            font-size: 1.2em;
            color: #555;
        }
        .datetime {
            text-align: center;
            font-size: 1.1em;
            margin-bottom: 30px;
            color: #333;
        }
        .container {
            display: flex;
            max-width: 1500px;
            margin: auto;
            gap: 20px;
        }
        .windows {
            flex: 3;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
        }
        .window-box {
            background: #f0f0f0;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .window-box h2 {
            margin-bottom: 10px;
            color: #007BFF;
        }
        .window-box p {
            margin: 0;
            font-size: 18px;
            color: #555;
        }
        .window-box strong {
            font-size: 70px;
            color: #222;
            display: block;
            margin-bottom: 8px;
        }
        .window-box .lname {
            font-size: 20px;
            color: #444;
            font-weight: 600;
        }
        .next-queues-container {
            flex: 2;
            display: flex;
            gap: 20px;
        }
        .next-queue {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            max-height: 600px;
            overflow-y: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .next-queue h2 {
            margin-bottom: 15px;
            color: #007BFF;
            text-align: center;
            flex-shrink: 0;
        }
        .next-queue ul {
            list-style: none;
            padding-left: 0;
            font-size: 22px;
            color: #333;
            overflow-y: auto;
            margin: 0;
            flex-grow: 1;
            border: none;
            border-radius: 4px;
        }
        .next-queue li {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }
        .next-queue li:last-child {
            border-bottom: none;
        }
    </style>
    <script>
    // Convert string to Sentence case
    function sentenceCase(str) {
        if (!str) return '';
        str = str.toLowerCase();
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Update date and time every second
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString(undefined, options);
        const timeStr = now.toLocaleTimeString();
        document.getElementById('datetime').textContent = dateStr + ' - ' + timeStr;
    }
    setInterval(updateDateTime, 1000);

    window.onload = function() {
        updateDateTime();

        // Speech synthesis for new queue calls
        function speakMessage(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.rate = 1;
                utterance.pitch = 1;
                window.speechSynthesis.speak(utterance);
            } else {
                console.log('Speech synthesis not supported in this browser.');
            }
        }

        // Initialize lastCalledQueues with all windows
        let lastCalledQueues = JSON.parse(localStorage.getItem('lastCalledQueues') || '{}');
        for (let i = 1; i <= 6; i++) {
            if (!(i in lastCalledQueues)) lastCalledQueues[i] = { q_id: '-', lname: '' };
        }

        function updateQueues() {
            fetch('{{ route("client.queues") }}')
                .then(response => response.json())
                .then(data => {
                    const calledQueues = data.calledQueues;
                    const nextQueues = data.nextQueues;

                    // Update windows display
                    for (const [windowNum, info] of Object.entries(calledQueues)) {
                        const windowBox = document.querySelector(`.window-box[data-window="${windowNum}"]`);
                        if (!windowBox) continue;

                        const qIdElem = windowBox.querySelector('strong');
                        const lnameElem = windowBox.querySelector('.lname');

                        if (qIdElem && qIdElem.textContent !== info.q_id) {
                            qIdElem.textContent = info.q_id;
                        }

                        const lnameFormatted = sentenceCase(info.lname);
                        if (lnameElem && lnameElem.textContent !== lnameFormatted) {
                            lnameElem.textContent = lnameFormatted;
                        }

                        // Announce new queue number if changed
                        if (info.q_id && info.q_id !== '-' && (!lastCalledQueues[windowNum] || lastCalledQueues[windowNum].q_id !== info.q_id)) {
                            const message = `Client ${info.q_id}, proceed to Window ${windowNum}`;
                            speakMessage(message);
                            // Repeat announcement after 1.5 seconds
                            setTimeout(() => speakMessage(message), 1000);
                        }
                    }

                    // Helper to update next queue lists
                    function updateNextQueueList(containerId, queueArray) {
                        const ul = document.getElementById(containerId);
                        ul.innerHTML = '';
                        if (queueArray && queueArray.length > 0) {
                            queueArray.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = item.q_id + ' - ' + sentenceCase(item.lname);
                                ul.appendChild(li);
                            });
                        } else {
                            const li = document.createElement('li');
                            li.textContent = 'No queues waiting';
                            ul.appendChild(li);
                        }
                    }

                    updateNextQueueList('next-queue-status', nextQueues.statusInquiry);
                    updateNextQueueList('next-queue-reg-upd', nextQueues.registrationUpdating);

                    // Save current queues for next comparison
                    localStorage.setItem('lastCalledQueues', JSON.stringify(calledQueues));
                    lastCalledQueues = calledQueues;
                })
                .catch(err => {
                    console.error('Error fetching queue data:', err);
                });
        }

        updateQueues();
        setInterval(updateQueues, 10000);
    };
    </script>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="National ID Center Logo" />
        <h1>National ID Center</h1>
        <p>We are happy to serve you</p>
    </div>
    <div class="datetime" id="datetime"></div>

    <div class="container">
        <div class="windows" id="windows-container">
            @foreach($calledQueues as $w => $info)
                <div class="window-box" data-window="{{ $w }}">
                    <h2>Window {{ $w }}</h2>
                    <p>Now Serving:</p>
                    <strong>{{ $info['q_id'] }}</strong>
                    <div class="lname">{{ $info['lname'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="next-queues-container">
            <div class="next-queue" id="next-queue-status-container">
                <h2>Status Inquiry Queues</h2>
                <ul id="next-queue-status">
                    @forelse($nextQueues['statusInquiry'] as $item)
                        <li>{{ $item['q_id'] . ' - ' . ucfirst(strtolower($item['lname'])) }}</li>
                    @empty
                        <li>No queues waiting</li>
                    @endforelse
                </ul>
            </div>

            <div class="next-queue" id="next-queue-reg-upd-container">
                <h2>Registration &amp; Updating Queues</h2>
                <ul id="next-queue-reg-upd">
                    @forelse($nextQueues['registrationUpdating'] as $item)
                        <li>{{ $item['q_id'] . ' - ' . ucfirst(strtolower($item['lname'])) }}</li>
                    @empty
                        <li>No queues waiting</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
