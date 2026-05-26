<?php
    session_start();
    include '../koneksi/koneksi.php';
    require_once '../koneksi/validasi.php';

    function create_user($post) {
        global $conn;

        $nama_depan     = trim($post['namaDepan'] ?? '');
        $nama_belakang  = trim($post['namaBelakang'] ?? '');
        $nama_lengkap   = $nama_depan . ' ' . $nama_belakang;
        $email          = trim($post['email'] ?? '');
        $username       = trim($post['username'] ?? '');
        $plainPassword  = $post['kataSandi'] ?? '';
        $role           = $post['role'] ?? '';
        $status         = $post['status'] ?? '';
        $expertise      = $post['expertise'] ?? '';

        $error = validate_required($nama_depan, 'Nama depan') ?: validate_max_length($nama_depan, 50, 'Nama depan')
               ?: validate_required($nama_belakang, 'Nama belakang') ?: validate_max_length($nama_belakang, 50, 'Nama belakang')
               ?: validate_required($email, 'Email') ?: validate_email($email)
               ?: validate_required($username, 'Username') ?: validate_min_length($username, 3, 'Username') ?: validate_max_length($username, 30, 'Username')
               ?: validate_required($plainPassword, 'Password') ?: validate_min_length($plainPassword, 6, 'Password')
               ?: validate_in($role, ['admin', 'mentor', 'student'], 'Role');
        if ($error) {
            $_SESSION['createUser_error'] = $error;
            return false;
        }

        if ($role === 'student') {
            $err = validate_in($status, ['Mahasiswa', 'Siswa'], 'Status');
            if ($err) { $_SESSION['createUser_error'] = $err; return false; }
        }
        if ($role === 'mentor') {
            $err = validate_required($expertise, 'Expertise');
            if ($err) { $_SESSION['createUser_error'] = $err; return false; }
        }

        $password = password_hash($plainPassword, PASSWORD_BCRYPT);

        // Cek apakah email sudah terdaftar
        $stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['createUser_error'] = 'Email sudah terdaftar!';
            $stmt->close();
            return false;
        }
        $stmt->close();

        // Cek apakah username sudah terdaftar
        $stmt = $conn->prepare("SELECT username FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['createUser_error'] = 'Username sudah terdaftar!';
            $stmt->close();
            return false;
        }
        $stmt->close();

        // Menambah user ke tabel user
        $stmt = $conn->prepare("INSERT INTO user (nama_depan, nama_belakang, nama_lengkap, username, email, password, role)
                                           VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nama_depan, $nama_belakang, $nama_lengkap, $username, $email, $password, $role);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        if ($role == 'student') {
            $stmt = $conn->prepare("INSERT INTO students (user_id, nama_depan, nama_belakang, status)
                                                 VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $nama_depan, $nama_belakang, $status);
            if (!$stmt->execute()) {
                die("Error student: " . $stmt->error);
            }
            $stmt->close();
        } elseif ($role == 'mentor') {
            $stmt = $conn->prepare("INSERT INTO mentors (user_id, nama_depan, nama_belakang, expertise)
                                                  VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $nama_depan, $nama_belakang, $expertise);
            if (!$stmt->execute()) {
                die("Error mentor: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO admin (user_id, nama_depan, nama_belakang)
                                                 VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $nama_depan, $nama_belakang);
            if (!$stmt->execute()) {
                die("Error admin: " . $stmt->error);
            }
            $stmt->close();
        }

        return true;
    }

    function update_user($post){
        global $conn;

         $user_id        = (int)($post['userId'] ?? 0);
         $nama_depan     = trim($post['namaDepan'] ?? '');
         $nama_belakang  = trim($post['namaBelakang'] ?? '');
         $nama_lengkap   = $nama_depan . ' ' . $nama_belakang;
         $email          = trim($post['email'] ?? '');
         $username       = trim($post['username'] ?? '');
         $role           = $post['role'] ?? '';
         $status         = $post['status'] ?? '';
         $expertise      = $post['expertise'] ?? '';

         $error = validate_positive($user_id, 'User ID')
                ?: validate_required($nama_depan, 'Nama depan') ?: validate_max_length($nama_depan, 50, 'Nama depan')
                ?: validate_required($nama_belakang, 'Nama belakang') ?: validate_max_length($nama_belakang, 50, 'Nama belakang')
                ?: validate_required($email, 'Email') ?: validate_email($email)
                ?: validate_required($username, 'Username') ?: validate_min_length($username, 3, 'Username') ?: validate_max_length($username, 30, 'Username')
                ?: validate_in($role, ['admin', 'mentor', 'student'], 'Role');
         if ($error) {
             $_SESSION['updateUser_error'] = $error;
             return false;
         }

         if ($role === 'student') {
             $err = validate_in($status, ['Mahasiswa', 'Siswa'], 'Status');
             if ($err) { $_SESSION['updateUser_error'] = $err; return false; }
         }
         if ($role === 'mentor') {
             $err = validate_required($expertise, 'Expertise');
             if ($err) { $_SESSION['updateUser_error'] = $err; return false; }
         }

         // Cek email duplikat
         $stmt = $conn->prepare("SELECT email FROM user WHERE email = ? AND user_id != ?");
         $stmt->bind_param("si", $email, $user_id);
         $stmt->execute();
         if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['updateUser_error'] = 'Email sudah terdaftar!';
            $stmt->close();
            return false;
        }
        $stmt->close();

        // Cek username duplikat
        $stmt = $conn->prepare("SELECT username FROM user WHERE username = ? AND user_id != ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['updateUser_error'] = 'Username sudah terdaftar!';
            $stmt->close();
            return false;
        }
        $stmt->close();

         // Update tabel user
         $stmt = $conn->prepare("UPDATE user SET nama_depan = ?, nama_belakang = ?, nama_lengkap = ?,
             email = ?, username = ?, role = ? WHERE user_id = ?");
         $stmt->bind_param("ssssssi", $nama_depan, $nama_belakang, $nama_lengkap, $email, $username, $role, $user_id);
         $stmt->execute();
         $stmt->close();

         if ($role == 'student') {
             $stmt = $conn->prepare("UPDATE students SET nama_depan = ?, nama_belakang = ?, status = ? WHERE user_id = ?");
             $stmt->bind_param("sssi", $nama_depan, $nama_belakang, $status, $user_id);
             $stmt->execute();
             $stmt->close();
         } elseif ($role == 'mentor') {
             $stmt = $conn->prepare("UPDATE mentors SET nama_depan = ?, nama_belakang = ?, expertise = ? WHERE user_id = ?");
             $stmt->bind_param("sssi", $nama_depan, $nama_belakang, $expertise, $user_id);
             $stmt->execute();
             $stmt->close();
         } else {
             $stmt = $conn->prepare("UPDATE admin SET nama_depan = ?, nama_belakang = ? WHERE user_id = ?");
             $stmt->bind_param("ssi", $nama_depan, $nama_belakang, $user_id);
             $stmt->execute();
             $stmt->close();
         }
         return true;
    }

    function delete_user($user_id) {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM user WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $stmt->close();
            return 1;
        } else {
            $stmt->close();
            return 0;
        }
    }

?>