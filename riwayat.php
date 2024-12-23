<?php 
    session_start();
    include("koneksi.php");
    if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];
        $query = mysqli_query($konek,"select * from users where role='user' and user_id='$id'")or die (mysqli_error($konek));
        while($data=mysqli_fetch_array($query)){
            $_SESSION['email'] = $data['email'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['fullname'] = $data['fullname'];
            $_SESSION['major'] = $data['major'];
            $_SESSION['university'] = $data['university'];
            $_SESSION['profile_picture'] = $data['profile_picture'];
        }
    }else{
        header("Location: user/login.php");
    }

    // Data dummy untuk monetisasi
    $dataMonetisasi = [
        "January" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "February" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "March" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "April" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "May" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "June" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "July" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "August" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "September" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "October" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "November" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"],
        "December" => ["aksesPremium" => "0K", "pembeliKonten" => "0K", "review" => "0K"]
    ];

    // Query database untuk mendapatkan data bulan Desember
    $queryPayments = mysqli_query($konek, "SELECT SUM(amount) AS totalAmount, COUNT(*) AS totalTransactions 
    FROM payments 
    WHERE user_id='$id'") or die(mysqli_error($konek));

    $queryRating = mysqli_query($konek, "SELECT COUNT(*) AS totalReviews 
    FROM rating 
    WHERE user_id='$id'") or die(mysqli_error($konek));

    $paymentsData = mysqli_fetch_assoc($queryPayments);
    $ratingData = mysqli_fetch_assoc($queryRating);

    // Hitung aksesPremium, pembeliKonten, dan review
    $totalAmount = $paymentsData['totalAmount'] ?? 0;
    $totalTransactions = $paymentsData['totalTransactions'] ?? 0;
    $totalReviews = $ratingData['totalReviews'] ?? 0;

    $aksesPremium = round($totalAmount * 0.9); // 90% dari total amount
    $pembeliKonten = $totalTransactions;
    $review = $totalReviews;

    // Update data bulan Desember
    $dataMonetisasi['December'] = [
        "aksesPremium" => $aksesPremium,
        "pembeliKonten" => $pembeliKonten,
        "review" => $review
    ];

    // Ambil parameter bulan dari query string
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : null;

    if ($bulan) {
        if (array_key_exists($bulan, $dataMonetisasi)) {
            $monetisasiBulan = $dataMonetisasi[$bulan];
        } else {
            $monetisasiBulan = null; // Jika data tidak ditemukan
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <title>Profile Pengguna</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background-color: #F9F9F9;
        }
        .navbar{
            background-color: #1E5B86;
        }
        .nav-link{
            color:white;
        }
        .profile-nav{
            border-radius: 20%;
            width: 50px;
            height: 38px;
        }
        p{
            color: #ADA7A7;
        }
        .img-top img{
            width: 100%;
            margin-bottom: 10px;
        }
        main{
            width: 90%;
        }
        .profile-img{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .name-email{
            margin-left: 20px;
        }
        .d-flex .profile-pict{
            width: 80px;
            height: 80px;
            border-radius: 100%;
        }
        .name-email{
            margin-top: 14px;
        }
        .form-section {
            display: flex;
            gap: 20px;
        }
        .form-section .left, .form-section .right {
            flex: 1;
        }
        .form-label {
            color: #555;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-primary px-3">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" aria-current="page" href="search.php">Buy</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" aria-current="page" href="uploads/uploaded.php">Sell</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" aria-current="page" href="#">History</a>
            </li>
        </ul>
        <form class="d-flex" role="search" action="search.php" method="get">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
            <?php if($_SESSION['profile_picture'] != "") { ?>
                <div class="dropdown" style="width : 38px;">
                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0;">
                        <img class="profile-nav" src="uploads/<?=$_SESSION['profile_picture']?>" alt="Profile Picture" style="width: 38px;">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="user/logout.php">Logout</a></li>
                    </ul>
                </div>
                <?php } else { ?>
                <div class="dropdown" style="width : 38px;">
                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0;">
                        <img class="profile-nav" src="default.png" alt="Profile Picture" style="width: 38px;">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="user/logout.php">Logout</a></li>
                    </ul>
                </div>
                <?php } ?>
            <a href="notifikasi.php" style="margin-left: 8px;"><img src="notif.png" alt="Notifikasi"></a>
            <a href="cart.php" style="margin-left: 8px; padding:1px; background-color:white; border-radius:8px;"><img src="cart (2).png" alt="Cart" style="height:36px"></a>
        </form>
        </div>
    </div>
    </nav>

    <main class="m-auto">
        <h2 class="mt-4 mb-3">Monetisasi</h2>
        <div style="background-color: black; width:100%; height:5px;"></div>
        <div class="mt-4">
        <label for="bulan" class="form-label">Pilih Bulan</label>
        <form method="get" action="">
            <select id="bulan" name="bulan" class="form-select" onchange="this.form.submit()">
                <option value="" disabled selected>Pilih Bulan</option>
                <?php foreach ($dataMonetisasi as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= isset($bulan) && $bulan === $key ? "selected" : "" ?>><?= $key ?></option>
                <?php } ?>
            </select>
        </form>
    </div>

    <?php if (isset($monetisasiBulan)) { ?>
        <div class="mt-4">
            <h4>Data Monetisasi untuk Bulan <?= $bulan ?></h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Akses Premium</th>
                        <th>Pembeli Konten</th>
                        <th>Review</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> <?='Rp. '. $monetisasiBulan['aksesPremium'] ?>,00</td>
                        <td><?= $monetisasiBulan['pembeliKonten'] ?></td>
                        <td><?= $monetisasiBulan['review'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php } elseif ($bulan) { ?>
        <p class="mt-4 text-danger">Data untuk bulan <?= htmlspecialchars($bulan) ?> tidak ditemukan.</p>
    <?php } ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-Xmzf3PZZTXUmHBaIKzKVRMFFxub0ocFVo9z7g9VVy5P5bgyX+vR21Bl5vCAVHcc3" crossorigin="anonymous"></script>
</body>
</html>
