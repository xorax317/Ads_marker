<?php
require 'functions.php';

$users = getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Google Sans', sans-serif;
        }
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 90%;
            animation: popIn 0.3s ease-out;
        }
        @keyframes popIn {
            0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Popup Modal -->
    <div id="popup-modal" class="popup hidden">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i data-lucide="info" class="w-8 h-8 text-blue-600"></i>
            </div>
        </div>
        <h3 id="popup-title" class="text-lg font-bold mb-2">Info</h3>
        <p id="popup-message" class="text-gray-600 mb-4">Message goes here</p>
        <button id="close-popup" class="bg-blue-600 text-white px-6 py-2 rounded-lg">OK</button>
    </div>

    <div class="overlay hidden"></div>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">User Management</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ban/Unban User</label>
                <div class="flex">
                    <input type="text" id="ban-user-id" class="flex-1 p-2 border border-gray-300 rounded-l-lg" placeholder="Enter User ID">
                    <input type="text" id="ban-reason" class="flex-1 p-2 border border-gray-300" placeholder="Enter Ban Reason">
                    <button id="ban-btn" class="bg-red-600 text-white px-4 py-2 rounded-r-lg">Ban</button>
                    <button id="unban-btn" class="bg-green-600 text-white px-4 py-2 ml-2 rounded-lg">Unban</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">User ID</th>
                            <th class="py-2 px-4 border-b">Name</th>
                            <th class="py-2 px-4 border-b">Balance</th>
                            <th class="py-2 px-4 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($user['balance']); ?></td>
                            <td class="py-2 px-4 border-b">
                                <?php echo $user['is_banned'] ? 'Banned: ' . htmlspecialchars($user['ban_reason']) : 'Active'; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Popup logic
        function showPopup(title, message) {
            document.getElementById('popup-title').textContent = title;
            document.getElementById('popup-message').textContent = message;
            document.getElementById('popup-modal').classList.remove('hidden');
            document.querySelector('.overlay').classList.remove('hidden');
        }

        document.getElementById('close-popup').addEventListener('click', function() {
            document.getElementById('popup-modal').classList.add('hidden');
            document.querySelector('.overlay').classList.add('hidden');
        });

        // Ban user
        document.getElementById('ban-btn').addEventListener('click', () => {
            const userId = document.getElementById('ban-user-id').value.trim();
            const reason = document.getElementById('ban-reason').value.trim();
            if (userId) {
                fetch('ban_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}&reason=${reason}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showPopup('Success', 'User banned successfully!');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showPopup('Error', 'Failed to ban user.');
                    }
                });
            } else {
                showPopup('Error', 'Please enter a User ID');
            }
        });

        // Unban user
        document.getElementById('unban-btn').addEventListener('click', () => {
            const userId = document.getElementById('ban-user-id').value.trim();
            if (userId) {
                fetch('unban_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showPopup('Success', 'User unbanned successfully!');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showPopup('Error', 'Failed to unban user.');
                    }
                });
            } else {
                showPopup('Error', 'Please enter a User ID');
            }
        });
    </script>
</body>
</html>
