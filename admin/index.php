<?php

include '../config.php';

$sql = "SELECT SUM(balance) AS total_funds FROM `funds`";

$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: row;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            height: 100vh;
        }

        .side-container {
            flex: 0 0 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding: 30px 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .side-container::before {
            content: "Banking System";
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .main-container {
            flex: 1;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 40px;
            padding: 40px;
            flex-wrap: wrap;
            overflow-y: auto;
        }

        a {
            text-decoration: none;
            color: white;
            font-size: 1.1rem;
            margin: 15px 0;
            padding: 12px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: block;
            text-align: center;
            border-left: 4px solid transparent;
        }

        a:hover {
            background-color: rgba(255, 255, 255, 0.15);
            border-left-color: #ffd700;
            transform: translateX(5px);
        }

        a:first-of-type {
            margin-top: 0;
        }

        .members, .fund {
            width: 320px;
            height: 180px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .members:hover, .fund:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .fund {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .members {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .members::before, .fund::before {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .fund::before {
            content: "💰";
        }

        .members::before {
            content: "👥";
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 10px;
        }

        /* Form Styling for Dynamically Loaded Content */
        .form-container {
            background-color: white;
            border-radius: 12px;
            padding: 50px 40px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .form-container .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* Form Elements */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        form input:focus,
        form textarea:focus {
            outline: none;
            border-color: #667eea;
            background-color: #f8f9ff;
            box-shadow: 0 0 12px rgba(102, 126, 234, 0.2);
        }

        form input::placeholder,
        form textarea::placeholder {
            color: #bbb;
        }

        form button {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        form button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        form button:active {
            transform: translateY(-1px);
            box-shadow: 0 5px 10px rgba(102, 126, 234, 0.2);
        }

        .form-info {
            text-align: center;
            color: #666;
            font-size: 13px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        /* Inline Form Styles (when loaded in main container) */
        .main-container .register-form,
        .main-container .deposit-form,
        .main-container .withdraw-form {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.5s ease-out;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .side-container {
                flex: 0 0 auto;
                height: auto;
                padding: 20px;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .main-container {
                flex-direction: column;
                padding: 20px;
                gap: 20px;
            }

            .members, .fund {
                width: 100%;
                max-width: 300px;
            }

            a {
                display: inline-block;
                margin: 10px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="side-container">
        <a href="./index.php">Home</a>
        <a href="./register.html" class="nav-link">Register a member</a>
        <a href="./deposit.html" class="nav-link">Deposit</a>
        <a href="./withdraw.html" class="nav-link">Withdraw</a>
    </div>
    <div class="main-container">
        <?php
        if (mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $total_funds = $row['total_funds'];
            echo "<div class='fund'><strong>".$total_funds."</strong><div class='stat-label'>Total Funds</div></div>";
        } else {
            echo "<div class='fund'>No funds found.</div>";
        }

        $members_sql = "SELECT COUNT(*) AS total_members FROM `members`";
        $members_result = mysqli_query($conn, $members_sql);
        if (mysqli_num_rows($members_result) > 0){
            $row = mysqli_fetch_assoc($members_result);
            $total_members = $row['total_members'];
            echo "<div class='members'><strong>".$total_members."</strong><div class='stat-label'>Total Members</div></div>";
        } else {
            echo "<div class='members'>No members found.</div>";
        }
        ?>   
    </div>
<script>
    alert("welcome to admin dashboard");
    // JavaScript for handling navigation and content loading
    const links = document.querySelectorAll('.nav-link');
    links.forEach(link => {
        link.addEventListener('click', e =>{
            e.preventDefault();
            loadContent(link.getAttribute('href'));
        });
    });

    function loadContent(url){
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.querySelector('.main-container').innerHTML = data;
            });
    }
    // Load initial content

    // JavaScript for handling registration form submission
       document.addEventListener('submit', function(e){
            // Register form
            if(e.target.classList.contains('register-form')){
                e.preventDefault();

                const accountName = document.getElementById('account-name').value;
                const password = document.getElementById('password').value;

                const formData = new FormData();
                formData.append('account_name', accountName);
                formData.append('password', password);

                fetch('register.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                });
            } 
            // Deposit form
            else if(e.target.classList.contains('deposit-form')){
                e.preventDefault();

                const accountNumber = document.getElementById('accountNumber').value;
                const amount = document.getElementById('Amount').value;

                const formData = new FormData();
                formData.append('account_number', accountNumber); // ✅ use correct variable
                formData.append('amount', amount);

                fetch('deposit.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.dep_message); // ✅ match PHP key
                });
            }
            //deposit form
            else if(e.target.classList.contains('withdraw-form')){
                e.preventDefault();

                const acc_number = document.getElementById('acc-number').value;
                const withdraw_amount = document.getElementById('withdraw-amount').value;

                const formData = new FormData();
                formData.append('acc_number', acc_number); // ✅ use correct variable
                formData.append('w_amount', withdraw_amount);

                fetch('withdraw.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.withd_message); // ✅ match PHP key
                });
            }
        });
    

        //java handling deposit form
</script>
</body>
</html>
