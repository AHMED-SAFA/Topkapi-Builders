<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login User</title>
    <style>
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #080710;
        }

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#1845ad,
                    #23a2f6);
            left: -80px;
            top: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right,
                    #ff512f,
                    #f09819);
            right: -30px;
            bottom: -80px;
        }

        form {
            height: 520px;
            width: 400px;
            background-color: rgba(255, 255, 255, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }

        input {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }

        ::placeholder {
            color: #e5e5e5;
        }



        .social {
            margin-top: 30px;
            display: flex;
        }

        .login_form{
            animation: fadeIn 3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .social div {
            background: red;
            width: 150px;
            border-radius: 3px;
            padding: 5px 10px 10px 5px;
            background-color: rgba(255, 255, 255, 0.27);
            color: #eaf0fb;
            text-align: center;
        }

        .social div:hover {
            background-color: rgba(255, 255, 255, 0.47);
        }

        .social .fb {
            margin-left: 25px;
        }

        .social i {
            margin-right: 4px;
        }

        /* ------------navbar edits-------------------------- */
        .text-white.dropdown-menu.bg-transparent li a:hover {
            background-color: black;
        }

        .dropdown-menu {
            backdrop-filter: blur(18px) !important;
            background-color: rgba(10, 10, 10, 0.4) !important;
        }

        ul li:hover {
            border-left: 1px solid red;
            transition: all 0.15s ease-in;
        }

        .navbar {
            background-color: rgba(10, 10, 10, 0.4);
            backdrop-filter: blur(2px);
        }

        .blur-background {
            background-color: rgba(5, 5, 5, 0.1);
            backdrop-filter: blur(5px);
        }

        /* ------------navbar edits ends-------------------------- */
    </style>
</head>

<body>
    @include('layouts.navbar')
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div style="margin-top: 70px" class="container col-4">
        @if(session()->has('fail'))
            <div class="alert alert-danger">
                <p>{{ session()->get('fail') }}</p>
            </div>
        @endif
    </div>
    <form class="mt-5 login_form" action="loginUser" method="POST">
        @csrf
        <h3>Login Here</h3>

        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email address" id="email"
            required>

        <label for="password" class="form-label">Password</label>
        <input required type="password" class="form-control mb-4" placeholder="Enter your password" name="password"
            id="password">

        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        <a type="submit" href="/register-user" class="mt-3 btn btn-primary w-100">Register User</a>
        <a type="submit" href="/admin-register" class="mt-3 mb-4 btn btn-primary w-100">Register Admin</a>
    </form>

</body>

</html>
