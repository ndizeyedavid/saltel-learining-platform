<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../");
    exit();
}
include '../../include/imports.php';
require_once '../../php/connect.php';

$student_id = $_SESSION['user_id'];

// Get student profile
$student_query = "SELECT * FROM students WHERE user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Create conference rooms table if not exists
$create_table_query = "CREATE TABLE IF NOT EXISTS conference_rooms (
    room_id VARCHAR(50) PRIMARY KEY,
    room_name VARCHAR(255) NOT NULL,
    created_by INT NOT NULL,
    room_key VARCHAR(10) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES students(student_id)
)";
$conn->query($create_table_query);

// Handle room creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_room') {
    $room_name = trim($_POST['room_name']);
    $room_key = strtoupper(substr(md5(uniqid()), 0, 6));
    $room_id = 'room_' . uniqid();

    $insert_query = "INSERT INTO conference_rooms (room_id, room_name, created_by, room_key) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ssis", $room_id, $room_name, $user['student_id'], $room_key);

    if ($insert_stmt->execute()) {
        $success_message = "Room created successfully! Room Key: " . $room_key;
    } else {
        $error_message = "Failed to create room.";
    }
}

// Get user's created rooms
$rooms_query = "SELECT * FROM conference_rooms WHERE created_by = ? AND is_active = TRUE ORDER BY created_at DESC";
$rooms_stmt = $conn->prepare($rooms_query);
$rooms_stmt->bind_param("i", $user['student_id']);
$rooms_stmt->execute();
$user_rooms = $rooms_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom - Saltel Learning Platform</title>
    <?php include '../../include/imports.php'; ?>
    <!-- WebRTC Video Conferencing - No external dependencies needed -->
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .video-container {
            position: relative;
            width: 100%;
            height: 600px;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }

        .participant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            height: 100%;
        }

        .participant-video {
            position: relative;
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
        }

        .participant-video video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .participant-name {
            position: absolute;
            bottom: 8px;
            left: 8px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .controls-bar {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            background: rgba(0, 0, 0, 0.8);
            padding: 10px;
            border-radius: 25px;
        }

        .control-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .control-btn.active {
            background: #ef4444;
        }

        .control-btn.inactive {
            background: #374151;
        }

        .room-card {
            transition: all 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <div class="mx-auto max-w-7xl">

                    <!-- Page Header -->
                    <div class="mb-8">
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">Virtual Classroom</h1>
                        <p class="text-gray-600">Start or join video conferences for collaborative learning</p>
                    </div>

                    <?php if (isset($success_message)): ?>
                        <div class="p-4 mb-6 text-green-700 bg-green-100 border border-green-400 rounded-lg">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error_message)): ?>
                        <div class="p-4 mb-6 text-red-700 bg-red-100 border border-red-400 rounded-lg">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Video Conference Area -->
                    <div id="videoSection" class="hidden mb-8">
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-semibold text-gray-900">Conference Room</h2>
                                <div class="flex items-center space-x-4">
                                    <span id="roomInfo" class="text-sm text-gray-600"></span>
                                    <button id="leaveCall" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                                        Leave Call
                                    </button>
                                </div>
                            </div>

                            <!-- Video Container -->
                            <div class="video-container">
                                <div id="participantGrid" class="participant-grid">
                                    <!-- Participants will be rendered here -->
                                </div>

                                <!-- Controls -->
                                <div class="controls-bar">
                                    <button id="toggleVideo" class="control-btn active">
                                        <i class="fas fa-video"></i>
                                    </button>
                                    <button id="toggleAudio" class="control-btn active">
                                        <i class="fas fa-microphone"></i>
                                    </button>
                                    <button id="shareScreen" class="control-btn inactive">
                                        <i class="fas fa-desktop"></i>
                                    </button>
                                    <button id="toggleChat" class="control-btn inactive">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Management -->
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

                        <!-- Create/Join Room -->
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Start or Join Conference</h2>

                            <!-- Create Room -->
                            <div class="mb-8">
                                <h3 class="mb-4 text-lg font-medium text-gray-900">Create New Room</h3>
                                <form method="POST" class="space-y-4">
                                    <input type="hidden" name="action" value="create_room">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Room Name</label>
                                        <input type="text" name="room_name" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Enter room name">
                                    </div>
                                    <button type="submit"
                                        class="w-full px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        <i class="mr-2 fas fa-plus"></i>Create Room
                                    </button>
                                </form>
                            </div>

                            <!-- Join Room -->
                            <div>
                                <h3 class="mb-4 text-lg font-medium text-gray-900">Join Existing Room</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Room Key</label>
                                        <input type="text" id="joinRoomKey"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Enter 6-digit room key">
                                    </div>
                                    <button onclick="joinRoom()"
                                        class="w-full px-4 py-2 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                        <i class="mr-2 fas fa-sign-in-alt"></i>Join Room
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- My Rooms -->
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">My Conference Rooms</h2>

                            <div class="space-y-4">
                                <?php if (!empty($user_rooms)): ?>
                                    <?php foreach ($user_rooms as $room): ?>
                                        <div class="p-4 border border-gray-200 rounded-lg room-card">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="font-medium text-gray-900"><?php echo htmlspecialchars($room['room_name']); ?></h4>
                                                    <p class="text-sm text-gray-600">
                                                        Key: <span class="font-mono font-bold text-blue-600"><?php echo $room['room_key']; ?></span>
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        Created: <?php echo date('M j, Y g:i A', strtotime($room['created_at'])); ?>
                                                    </p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button onclick="joinMyRoom('<?php echo $room['room_id']; ?>', '<?php echo htmlspecialchars($room['room_name']); ?>')"
                                                        class="px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                                        Join
                                                    </button>
                                                    <button onclick="copyRoomKey('<?php echo $room['room_key']; ?>')"
                                                        class="px-3 py-1 text-sm text-white bg-gray-600 rounded hover:bg-gray-700">
                                                        Copy Key
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="py-8 text-center text-gray-500">
                                        <i class="mb-2 text-3xl fas fa-video"></i>
                                        <p class="text-sm">No conference rooms created yet</p>
                                        <p class="text-xs">Create your first room to get started!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Pass user data to JavaScript -->
        <script>
            window.userData = {
                userId: '<?php echo $student_id; ?>',
                userName: '<?php echo htmlspecialchars(($user['first_name'] ?? 'User') . ' ' . ($user['last_name'] ?? '')); ?>',
                userImage: '<?php echo $user['profile_picture'] ?? 'https://ui-avatars.com/api/?name=' . urlencode(($user['first_name'] ?? 'User') . ' ' . ($user['last_name'] ?? '')); ?>'
            };
            console.log('User data loaded:', window.userData);
        </script>

        <!-- WebRTC Video Conference JavaScript -->
        <script>
            let localStream = null;
            let remoteStreams = new Map();
            let peerConnections = new Map();
            let isVideoEnabled = true;
            let isAudioEnabled = true;
            let currentRoomId = null;
            let signalingInterval = null;
            let lastMessageId = 0;
            let participants = new Set();

            // WebRTC configuration
            const rtcConfiguration = {
                iceServers: [{
                        urls: 'stun:stun.l.google.com:19302'
                    },
                    {
                        urls: 'stun:stun1.l.google.com:19302'
                    }
                ]
            };

            // Initialize WebRTC video conferencing
            async function initializeWebRTC() {
                try {
                    console.log('Initializing WebRTC...');

                    // Get user media
                    localStream = await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: true
                    });

                    console.log('Local media stream obtained');
                    return true;
                } catch (error) {
                    console.error('Failed to get user media:', error);
                    throw new Error('Camera/microphone access denied');
                }
            }

            // Signaling functions
            async function sendSignalingMessage(type, data, targetUserId = null) {
                try {
                    const response = await fetch('../api/signaling-server.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            room_id: currentRoomId,
                            type: type,
                            data: data,
                            to_user_id: targetUserId
                        })
                    });
                    return await response.json();
                } catch (error) {
                    console.error('Failed to send signaling message:', error);
                }
            }

            async function pollSignalingMessages() {
                if (!currentRoomId) return;

                try {
                    const response = await fetch(`../api/signaling-server.php?room_id=${currentRoomId}&last_id=${lastMessageId}`);
                    const result = await response.json();

                    if (result.messages) {
                        for (const message of result.messages) {
                            await handleSignalingMessage(message);
                            lastMessageId = Math.max(lastMessageId, message.id);
                        }
                    }
                } catch (error) {
                    console.error('Failed to poll signaling messages:', error);
                }
            }

            async function handleSignalingMessage(message) {
                const {
                    from_user_id,
                    type,
                    data
                } = message;

                console.log('Received signaling message:', type, 'from user:', from_user_id);

                switch (type) {
                    case 'user-joined':
                        await handleUserJoined(from_user_id, data);
                        break;
                    case 'offer':
                        await handleOffer(from_user_id, data);
                        break;
                    case 'answer':
                        await handleAnswer(from_user_id, data);
                        break;
                    case 'ice-candidate':
                        await handleIceCandidate(from_user_id, data);
                        break;
                    case 'user-left':
                        handleUserLeft(from_user_id);
                        break;
                }
            }

            // Create peer connection
            function createPeerConnection(userId) {
                const peerConnection = new RTCPeerConnection(rtcConfiguration);

                // Add local stream tracks
                if (localStream) {
                    localStream.getTracks().forEach(track => {
                        peerConnection.addTrack(track, localStream);
                    });
                }

                // Handle remote stream
                peerConnection.ontrack = (event) => {
                    console.log('Received remote stream from:', userId);
                    const remoteStream = event.streams[0];
                    remoteStreams.set(userId, remoteStream);
                    displayRemoteVideo(userId, remoteStream);
                };

                // Handle ICE candidates
                peerConnection.onicecandidate = (event) => {
                    if (event.candidate) {
                        sendSignalingMessage('ice-candidate', {
                            candidate: event.candidate
                        }, userId);
                    }
                };

                peerConnections.set(userId, peerConnection);
                return peerConnection;
            }

            // Handle user joined
            async function handleUserJoined(userId, userData) {
                if (participants.has(userId)) return;

                participants.add(userId);
                console.log('User joined:', userId, userData);

                // Create peer connection and send offer
                const peerConnection = createPeerConnection(userId);

                try {
                    const offer = await peerConnection.createOffer();
                    await peerConnection.setLocalDescription(offer);

                    await sendSignalingMessage('offer', {
                        offer: offer,
                        user_data: {
                            name: window.userData.userName,
                            image: window.userData.userImage
                        }
                    }, userId);
                } catch (error) {
                    console.error('Failed to create offer for user:', userId, error);
                }
            }

            // Handle offer
            async function handleOffer(userId, data) {
                if (!participants.has(userId)) {
                    participants.add(userId);
                }

                const peerConnection = createPeerConnection(userId);

                try {
                    await peerConnection.setRemoteDescription(data.offer);
                    const answer = await peerConnection.createAnswer();
                    await peerConnection.setLocalDescription(answer);

                    await sendSignalingMessage('answer', {
                        answer: answer,
                        user_data: {
                            name: window.userData.userName,
                            image: window.userData.userImage
                        }
                    }, userId);
                } catch (error) {
                    console.error('Failed to handle offer from user:', userId, error);
                }
            }

            // Handle answer
            async function handleAnswer(userId, data) {
                const peerConnection = peerConnections.get(userId);
                if (peerConnection) {
                    try {
                        await peerConnection.setRemoteDescription(data.answer);
                    } catch (error) {
                        console.error('Failed to handle answer from user:', userId, error);
                    }
                }
            }

            // Handle ICE candidate
            async function handleIceCandidate(userId, data) {
                const peerConnection = peerConnections.get(userId);
                if (peerConnection) {
                    try {
                        await peerConnection.addIceCandidate(data.candidate);
                    } catch (error) {
                        console.error('Failed to add ICE candidate from user:', userId, error);
                    }
                }
            }

            // Handle user left
            function handleUserLeft(userId) {
                participants.delete(userId);

                // Close peer connection
                const peerConnection = peerConnections.get(userId);
                if (peerConnection) {
                    peerConnection.close();
                    peerConnections.delete(userId);
                }

                // Remove remote stream
                remoteStreams.delete(userId);

                // Remove video element
                const videoElement = document.getElementById(`remoteVideo_${userId}`);
                if (videoElement && videoElement.parentElement) {
                    videoElement.parentElement.remove();
                }

                console.log('User left:', userId);
            }

            // Display local video
            function displayLocalVideo() {
                const videoGrid = document.querySelector('.participant-grid');
                if (!videoGrid) return;

                let localVideoElement = document.getElementById('localVideo');
                if (!localVideoElement) {
                    localVideoElement = document.createElement('video');
                    localVideoElement.id = 'localVideo';
                    localVideoElement.autoplay = true;
                    localVideoElement.muted = true;
                    localVideoElement.playsInline = true;

                    const participantDiv = document.createElement('div');
                    participantDiv.className = 'participant-video';
                    participantDiv.appendChild(localVideoElement);

                    const nameDiv = document.createElement('div');
                    nameDiv.className = 'participant-name';
                    nameDiv.textContent = 'You';
                    participantDiv.appendChild(nameDiv);

                    videoGrid.appendChild(participantDiv);
                }

                localVideoElement.srcObject = localStream;
            }

            // Display remote video
            function displayRemoteVideo(userId, stream) {
                const videoGrid = document.querySelector('.participant-grid');
                if (!videoGrid) return;

                let remoteVideoElement = document.getElementById(`remoteVideo_${userId}`);
                if (!remoteVideoElement) {
                    remoteVideoElement = document.createElement('video');
                    remoteVideoElement.id = `remoteVideo_${userId}`;
                    remoteVideoElement.autoplay = true;
                    remoteVideoElement.playsInline = true;
                    remoteVideoElement.style.width = '100%';
                    remoteVideoElement.style.height = '100%';
                    remoteVideoElement.style.objectFit = 'cover';

                    const participantDiv = document.createElement('div');
                    participantDiv.className = 'participant-video relative bg-gray-900 rounded-lg overflow-hidden';
                    participantDiv.style.minHeight = '200px';
                    participantDiv.appendChild(remoteVideoElement);

                    const nameDiv = document.createElement('div');
                    nameDiv.className = 'participant-name absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-sm';
                    nameDiv.textContent = `Participant ${userId}`;
                    participantDiv.appendChild(nameDiv);

                    // Add connection status indicator
                    const statusDiv = document.createElement('div');
                    statusDiv.className = 'absolute top-2 right-2 w-3 h-3 bg-green-500 rounded-full';
                    statusDiv.title = 'Connected';
                    participantDiv.appendChild(statusDiv);

                    videoGrid.appendChild(participantDiv);
                }

                remoteVideoElement.srcObject = stream;
                console.log('Remote video displayed for user:', userId);
            }


            // Join room by key
            async function joinRoom() {
                const roomKey = document.getElementById('joinRoomKey').value.trim().toUpperCase();

                if (!roomKey || roomKey.length !== 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Room Key',
                        text: 'Please enter a valid 6-digit room key',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                try {
                    // Check if room exists
                    const response = await fetch('../api/check-room.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            room_key: roomKey
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        await startVideoCall(result.room_id, result.room_name);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Room Not Found',
                            text: 'No active room found with this key',
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                } catch (error) {
                    console.error('Error joining room:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error',
                        text: 'Unable to join room. Please try again.',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            }

            // Join user's own room
            async function joinMyRoom(roomId, roomName) {
                await startVideoCall(roomId, roomName);
            }

            // Start video call with WebRTC
            async function startVideoCall(roomId, roomName) {
                try {
                    console.log('Starting WebRTC video call for room:', roomId, roomName);

                    // Initialize WebRTC
                    await initializeWebRTC();
                    currentRoomId = roomId;

                    // Show video section
                    document.getElementById('videoSection').classList.remove('hidden');
                    document.getElementById('roomInfo').textContent = `Room: ${roomName}`;

                    // Display local video
                    displayLocalVideo();

                    // Set up video controls
                    setupVideoControls();

                    // Announce user joined to other participants
                    await sendSignalingMessage('user-joined', {
                        name: window.userData.userName,
                        image: window.userData.userImage
                    });

                    // Start polling for signaling messages
                    signalingInterval = setInterval(pollSignalingMessages, 1000);

                    Swal.fire({
                        icon: 'success',
                        title: 'Joined Room!',
                        text: `You've successfully joined ${roomName}. Camera and microphone are now active.`,
                        confirmButtonColor: '#3b82f6',
                        timer: 3000
                    });

                    console.log('WebRTC video call started successfully');

                } catch (error) {
                    console.error('Error starting video call:', error);

                    let errorMessage = 'Unable to start video call. Please check your camera and microphone permissions.';

                    if (error.message.includes('Camera/microphone access denied')) {
                        errorMessage = 'Camera/microphone access denied. Please allow access and try again.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Join',
                        text: errorMessage,
                        confirmButtonColor: '#3b82f6',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            }

            // Setup video controls
            function setupVideoControls() {
                // Video toggle
                const videoToggle = document.getElementById('toggleVideo');
                if (videoToggle) {
                    videoToggle.onclick = toggleVideo;
                }

                // Audio toggle
                const audioToggle = document.getElementById('toggleAudio');
                if (audioToggle) {
                    audioToggle.onclick = toggleAudio;
                }

                // Leave call
                const leaveCall = document.getElementById('leaveCall');
                if (leaveCall) {
                    leaveCall.onclick = endCall;
                }
            }

            // Toggle video
            function toggleVideo() {
                if (localStream) {
                    const videoTrack = localStream.getVideoTracks()[0];
                    if (videoTrack) {
                        isVideoEnabled = !isVideoEnabled;
                        videoTrack.enabled = isVideoEnabled;

                        const videoButton = document.getElementById('toggleVideo');
                        if (videoButton) {
                            videoButton.innerHTML = isVideoEnabled ?
                                '<i class="fas fa-video"></i>' :
                                '<i class="fas fa-video-slash"></i>';
                            videoButton.className = isVideoEnabled ?
                                'bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors' :
                                'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors';
                        }
                    }
                }
            }

            // Toggle audio
            function toggleAudio() {
                if (localStream) {
                    const audioTrack = localStream.getAudioTracks()[0];
                    if (audioTrack) {
                        isAudioEnabled = !isAudioEnabled;
                        audioTrack.enabled = isAudioEnabled;

                        const audioButton = document.getElementById('toggleAudio');
                        if (audioButton) {
                            audioButton.innerHTML = isAudioEnabled ?
                                '<i class="fas fa-microphone"></i>' :
                                '<i class="fas fa-microphone-slash"></i>';
                            audioButton.className = isAudioEnabled ?
                                'bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors' :
                                'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors';
                        }
                    }
                }
            }

            // End call
            function endCall() {
                try {
                    // Announce user leaving
                    if (currentRoomId) {
                        sendSignalingMessage('user-left', {
                            name: window.userData.userName
                        });
                    }

                    // Stop polling
                    if (signalingInterval) {
                        clearInterval(signalingInterval);
                        signalingInterval = null;
                    }

                    // Stop local stream
                    if (localStream) {
                        localStream.getTracks().forEach(track => track.stop());
                        localStream = null;
                    }

                    // Close peer connections
                    peerConnections.forEach(pc => pc.close());
                    peerConnections.clear();
                    remoteStreams.clear();
                    participants.clear();

                    // Hide video section
                    document.getElementById('videoSection').classList.add('hidden');

                    // Clear video grid
                    const videoGrid = document.querySelector('.participant-grid');
                    if (videoGrid) {
                        videoGrid.innerHTML = '';
                    }

                    currentRoomId = null;
                    lastMessageId = 0;

                    Swal.fire({
                        icon: 'info',
                        title: 'Call Ended',
                        text: 'You have left the video call',
                        confirmButtonColor: '#3b82f6',
                        timer: 2000
                    });

                } catch (error) {
                    console.error('Error ending call:', error);
                }
            }

            // Initialize page
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Classroom page loaded - WebRTC ready');
            });

            // Copy room key to clipboard
            function copyRoomKey(roomKey) {
                navigator.clipboard.writeText(roomKey).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: `Room key ${roomKey} copied to clipboard`,
                        confirmButtonColor: '#3b82f6',
                        timer: 1500,
                        timerProgressBar: true
                    });
                });
            }
        </script>

        <script src="../../assets/js/app.js"></script>
</body>

</html>