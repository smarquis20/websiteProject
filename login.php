<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/bsa.css">
</head>
<body class="logback">
    <div class="session">
        <div class="left">
            <?xml version="1.0" encoding="UTF-8"?>
        </div>
        <form action="authenticate.php" class="log-in" method="post" autocomplete="off">
            <img src="../images/ldsSmallLogo.png" alt="Logo" class="logo">
            <div class="floating-label">
                <input placeholder="Email" type="text" name="username" id="username" autocomplete="off" required>
                <label class="loginlabel" for="email">Email:</label>
                <div class="icon">
                    <?xml version="1.0" encoding="UTF-8"?>
                    <svg enable-background="new 0 0 100 100" version="1.1" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                        <style type="text/css">
                            .st0{fill:none;}
                        </style>
                        <g transform="translate(0 -952.36)">
                            <path d="m17.5 977c-1.3 0-2.4 1.1-2.4 2.4v45.9c0 1.3 1.1 2.4 2.4 2.4h64.9c1.3 0 2.4-1.1 2.4-2.4v-45.9c0-1.3-1.1-2.4-2.4-2.4h-64.9zm2.4 4.8h60.2v1.2l-30.1 22-30.1-22v-1.2zm0 7l28.7 21c0.8 0.6 2 0.6 2.8 0l28.7-21v34.1h-60.2v-34.1z"/>
                        </g>
                        <rect class="st0" width="100" height="100"/>
                    </svg>
                </div>
            </div>
            <div class="floating-label">
                <input placeholder="Password" type="password" name="password" id="password" autocomplete="off" required>
                <label class="loginlabel" for="password">Password:</label>
                <div class="icon">
                    <?xml version="1.0" encoding="UTF-8"?>
                    <svg enable-background="new 0 0 24 24" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                        <style type="text/css">
                            .st0{fill:none;}
                            .st1{fill:#111111;}
                        </style>
                        <rect class="st0" width="24" height="24"/>
                        <path class="st1" d="M19,21H5V9h14V21z M6,20h12V10H6V20z"/>
                        <path class="st1" d="M16.5,10h-1V7c0-1.9-1.6-3.5-3.5-3.5S8.5,5.1,8.5,7v3h-1V7c0-2.5,2-4.5,4.5-4.5s4.5,2,4.5,4.5V10z"/>
                        <path class="st1" d="m12 16.5c-0.8 0-1.5-0.7-1.5-1.5s0.7-1.5 1.5-1.5 1.5 0.7 1.5 1.5-0.7 1.5-1.5 1.5zm0-2c-0.3 0-0.5 0.2-0.5 0.5s0.2 0.5 0.5 0.5 0.5-0.2 0.5-0.5-0.2-0.5-0.5-0.5z"/>
                    </svg>
                </div>
                <div class="show-password-icon">
                    <svg id="togglePassword" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0V0z" fill="none"/>
                        <path d="M12 4.5C7.05 4.5 2.73 7.61 1 12c1.73 4.39 6.05 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 16.95 4.5 12 4.5zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8.5c-1.93 0-3.5 1.57-3.5 3.5s1.57 3.5 3.5 3.5 3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5z"/>
                    </svg> 
                </div>
            </div>
            <button type="submit" value="Login">Log in</button>
        </form>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>

