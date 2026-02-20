<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');

$file = 'users.json';

// Helper function to read users
function getUsers() {
    global $file;
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

// Helper function to save users
function saveUsers($users) {
    global $file;
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

$action = $_GET['action'] ?? '';

// --- LOGIN ---
if ($action === 'login') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    $users = getUsers();
    foreach ($users as $u) {
        if ($u['username'] === $username && $u['password'] === $password) {
            echo json_encode(['status' => 'success', 'user' => $u]);
            exit;
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Username atau Password salah']);
    exit;
}

// --- GET ALL USERS (Owner Only logic checked in frontend, but ideally backend too) ---
if ($action === 'get_users') {
    echo json_encode(['status' => 'success', 'data' => getUsers()]);
    exit;
}

// --- ADD USER ---
if ($action === 'add_user') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    $role = $input['role'] ?? 'staff';

    if (!$username || !$password) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        exit;
    }

    $users = getUsers();
    // Check duplicate
    foreach ($users as $u) {
        if ($u['username'] === $username) {
            echo json_encode(['status' => 'error', 'message' => 'Username sudah ada']);
            exit;
        }
    }

    $newUser = [
        'id' => time(), // Simple unique ID
        'username' => $username,
        'password' => $password,
        'role' => $role
    ];
    $users[] = $newUser;
    saveUsers($users);

    echo json_encode(['status' => 'success', 'message' => 'User berhasil ditambahkan']);
    exit;
}

// --- EDIT USER ---
if ($action === 'edit_user') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    $role = $input['role'] ?? 'staff';

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
        exit;
    }

    $users = getUsers();
    $found = false;
    foreach ($users as &$u) {
        if ($u['id'] == $id) {
            $u['username'] = $username;
            if (!empty($password)) { // Only update password if provided
                $u['password'] = $password;
            }
            $u['role'] = $role;
            $found = true;
            break;
        }
    }

    if ($found) {
        saveUsers($users);
        echo json_encode(['status' => 'success', 'message' => 'User berhasil diupdate']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User tidak ditemukan']);
    }
    exit;
}

// --- DELETE USER ---
if ($action === 'delete_user') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
        exit;
    }

    $users = getUsers();
    $newUsers = array_filter($users, function($u) use ($id) {
        return $u['id'] != $id;
    });

    if (count($users) === count($newUsers)) {
        echo json_encode(['status' => 'error', 'message' => 'User tidak ditemukan']);
    } else {
        saveUsers(array_values($newUsers)); // Reindex array
        echo json_encode(['status' => 'success', 'message' => 'User berhasil dihapus']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Action tidak valid']);
