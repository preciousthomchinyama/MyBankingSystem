<?php

include 'config.php';

$sql = "SELECT SUM(balance) AS total_funds FROM `funds`";

$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>banking system</title>

    <style>
        body{
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: row;
        }
        .side-container{
            flex-grow:1;
            background-color: blue;
            display: flex;
            flex-direction: column;
            height: 100vh;
            align-items: center;
            padding: 2%;

        }
        .main-container{
            flex-grow:9;
            background-color: white;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 30px;
        }
        a
        {
            text-decoration: none;
            color: white;
            font-size: 1.5rem;
            margin: 10px;
        }
        .members, .fund{
            width: 400px;
            height: 200px;
            background-color: lightgray;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="side-container">
        <a href="./index.php">Home</a>
        <a href="./register.html" class="nav-link">register</a>
        <a href="./make_deposit.html" class="nav-link">make deposit</a>
    </div>
    <div class="main-container">
        <?php
        if (mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $total_funds = $row['total_funds'];
            echo "<div class='fund'>Total Funds: ".$total_funds."</div>";
        } else {
            echo "No funds found.";
        }

        $members_sql = "SELECT COUNT(*) AS total_members FROM `members`";
        $members_result = mysqli_query($conn, $members_sql);
        if (mysqli_num_rows($members_result) > 0){
            $row = mysqli_fetch_assoc($members_result);
            $total_members = $row['total_members'];
            echo "<div class='members'>Total Members: ".$total_members."</div>";
        } else {
            echo "<div class='members'>No members found.</div>";
        }
        ?>   
    </div>
<script>
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
        });
</script>
</body>
</html>
