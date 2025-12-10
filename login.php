<?php
session_start();
include 'config/koneksi.php';

// --- LOGIC PHP (Gak Berubah) ---
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            
            $_SESSION['info'] = [
                'type' => 'success',
                'title' => 'Login Berhasil',
                'message' => 'Welcome back, ' . $row['name']
            ];

            if ($row['role'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BakeryPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }
        
        /* ANIMASI MONSTER (Tetep dipake) */
        .pupil, .eyelid, .mouth-orange, .hand-group { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .eyelid { height: 0; }
        .hands-up { opacity: 0; pointer-events: none; }
        .hands-down { opacity: 1; }
        
        .shy .hands-up { opacity: 1; transform: translateY(-5px); }
        .shy .hands-down { opacity: 0; transform: translateY(10px); }
        .shy .eyelid { height: 18px; } 
        .shy .mouth-orange { d: path("M -4 22 Q 0 25 4 22"); }

        .illustration-bg { background-color: #FFF5F2; }
        
        /* STYLE INPUT CUSTOM (UNDERLINE) */
        .input-underline {
            border: none;
            border-bottom: 1px solid #e5e7eb; /* Gray-200 */
            border-radius: 0;
            padding: 0.75rem 0;
            background: transparent;
            transition: all 0.3s;
        }
        .input-underline:focus {
            border-bottom-color: #000;
            box-shadow: none;
            outline: none;
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-5xl h-[600px] flex overflow-hidden">
        
        <div class="hidden md:flex w-1/2 illustration-bg items-center justify-center relative">
            <svg id="monster-scene" width="400" height="400" viewBox="-50 -50 300 300">
                <g transform="translate(40, 50)">
                    <rect x="0" y="0" width="50" height="110" rx="25" fill="#8B5CF6"/>
                    <g transform="translate(12, 30)">
                        <circle cx="0" cy="0" r="6" fill="white"/>
                        <circle class="pupil" cx="0" cy="0" r="2" fill="#1F2937"/>
                        <rect class="eyelid" x="-6" y="-6" width="12" height="0" fill="#7C3AED" rx="2"/> 
                    </g>
                    <g transform="translate(38, 30)">
                        <circle cx="0" cy="0" r="6" fill="white"/>
                        <circle class="pupil" cx="0" cy="0" r="2" fill="#1F2937"/>
                        <rect class="eyelid" x="-6" y="-6" width="12" height="0" fill="#7C3AED" rx="2"/>
                    </g>
                    <path d="M 20 70 Q 25 75 30 70" fill="none" stroke="#5B21B6" stroke-width="2" stroke-linecap="round"/>
                </g>
                <g transform="translate(140, 120)">
                    <circle cx="20" cy="20" r="20" fill="#10B981"/>
                    <g transform="translate(20, 15)">
                        <circle cx="0" cy="0" r="8" fill="white"/>
                        <circle class="pupil" cx="0" cy="0" r="3" fill="#1F2937"/>
                        <rect class="eyelid" x="-8" y="-8" width="16" height="0" fill="#059669" rx="4"/>
                    </g>
                </g>
                <g id="monster-orange" transform="translate(70, 90)">
                    <path d="M 0 40 Q 0 0 40 0 Q 80 0 80 40 L 80 80 Q 80 90 70 90 L 10 90 Q 0 90 0 80 Z" fill="#F97316"/>
                    <g transform="translate(40, 35)">
                        <circle cx="-15" cy="0" r="8" fill="white"/>
                        <circle class="pupil" cx="-15" cy="0" r="3" fill="#1F2937"/> 
                        <circle cx="15" cy="0" r="8" fill="white"/>
                        <circle class="pupil" cx="15" cy="0" r="3" fill="#1F2937"/>
                        <path class="mouth-orange" d="M -5 15 Q 0 20 5 15" fill="none" stroke="#7C2D12" stroke-width="3" stroke-linecap="round"/>
                    </g>
                    <g class="hands-down hand-group">
                        <circle cx="-5" cy="60" r="11" fill="#F97316" stroke="#C2410C" stroke-width="2"/>
                        <circle cx="85" cy="60" r="11" fill="#F97316" stroke="#C2410C" stroke-width="2"/>
                    </g>
                    <g class="hands-up hand-group">
                        <circle cx="25" cy="35" r="12" fill="#F97316" stroke="#C2410C" stroke-width="2"/>
                        <circle cx="55" cy="35" r="12" fill="#F97316" stroke="#C2410C" stroke-width="2"/>
                    </g>
                </g>
                <ellipse cx="100" cy="190" rx="90" ry="10" fill="#000" opacity="0.1"/>
            </svg>
        </div>

        <div class="w-full md:w-1/2 p-12 flex flex-col justify-center relative">
            
            <div class="flex justify-center mb-6">
                <div class="w-10 h-10 bg-black text-white rounded-lg flex items-center justify-center text-xl font-bold">
                    <i class="fas fa-bread-slice"></i>
                </div>
            </div>

            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back!</h2>
                <p class="text-gray-500 text-sm">Please enter your details</p>
            </div>

            <?php if(isset($error)) : ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 text-center border border-red-100">
                    Email atau password salah!
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="w-full max-w-sm mx-auto space-y-6">
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-1">Email</label>
                    <input type="email" id="email" name="email" required 
                        class="input-underline w-full text-gray-800 placeholder-gray-300"
                        placeholder="Enter your email">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required 
                            class="input-underline w-full text-gray-800 placeholder-gray-300"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-0 top-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i class="far fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center text-xs">
                    <label class="flex items-center text-gray-500 cursor-pointer">
                        <input type="checkbox" class="form-checkbox text-black rounded border-gray-300 focus:ring-0 mr-2">
                        Remember me
                    </label>
                    <a href="#" class="text-gray-500 hover:text-black font-medium">Forgot password?</a>
                </div>

                <div class="space-y-4 pt-4">
                    <button type="submit" name="login" class="w-full bg-black text-white font-bold py-3 rounded-full hover:bg-gray-800 transition transform hover:-translate-y-0.5 shadow-lg">
                        Log in
                    </button>
                    
                    <button type="button" class="w-full border border-gray-300 text-gray-700 font-bold py-3 rounded-full hover:bg-gray-50 transition flex items-center justify-center gap-2">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">
                        Log in with Google
                    </button>
                </div>
            </form>
            
            <p class="mt-10 text-center text-xs text-gray-500">
                Don't have an account? <a href="register.php" class="text-black font-bold hover:underline">Sign Up</a>
            </p>
        </div>
    </div>

    <script>
        const monsterScene = document.getElementById('monster-scene');
        const pupils = document.querySelectorAll('.pupil');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        // 1. MATA GERAK
        window.addEventListener('mousemove', (e) => {
            if (document.activeElement === passwordInput) return;
            pupils.forEach((pupil) => {
                const rect = pupil.getBoundingClientRect();
                const x = rect.left + (rect.width / 2);
                const y = rect.top + (rect.height / 2);
                const xMove = (e.clientX - x) / window.innerWidth * 6;
                const yMove = (e.clientY - y) / window.innerHeight * 6;
                pupil.style.transform = `translate(${xMove}px, ${yMove}px)`;
            });
        });

        // 2. TOGGLE PASSWORD VISIBILITY
        function togglePassword() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
                
                // Kalo password keliatan, monster JANGAN tutup mata (Intip mode)
                monsterScene.classList.remove('shy');
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
                
                // Kalo password sembunyi, monster tutup mata lagi
                monsterScene.classList.add('shy');
            }
        }

        // 3. LOGIC PEEKABOO (Tutup Mata Pas Ngetik)
        passwordInput.addEventListener('focus', () => {
            if (passwordInput.type === "password") {
                monsterScene.classList.add('shy');
            }
        });
        passwordInput.addEventListener('blur', () => monsterScene.classList.remove('shy'));

        // 4. LOGIC EMAIL (Lirik)
        emailInput.addEventListener('focus', () => {
            pupils.forEach((pupil) => pupil.style.transform = `translate(3px, 2px)`);
        });
    </script>
</body>
</html>