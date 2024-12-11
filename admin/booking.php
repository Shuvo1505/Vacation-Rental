<?php
    require('inc/essentials.php');
    adminLogin();
    
    $hname = 'localhost';
    $uname = 'root';
    $pass = '';
    $db = 'hms';
    $conn = mysqli_connect($hname, $uname, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    $searchTermEscaped = mysqli_real_escape_string($conn, $searchTerm);


    // Query to get users with search filter
    $sql = "SELECT bookingid, roomname, name, email, checkin, checkout FROM bookedrooms 
    WHERE bookingid LIKE '%$searchTermEscaped%' 
    OR roomname LIKE '%$searchTermEscaped%' 
    OR name LIKE '%$searchTermEscaped%'
    OR checkin LIKE '%$searchTermEscaped%'
    OR checkout LIKE '%$searchTermEscaped%' 
    OR email LIKE '%$searchTermEscaped%'";
    $result1 = $conn->query($sql);

    // Query to get booking account data
    $sql = "SELECT bookingid, email, phone, status FROM bookedrooms 
    WHERE bookingid LIKE '%$searchTermEscaped%' 
    OR email LIKE '%$searchTermEscaped%' 
    OR phone LIKE '%$searchTermEscaped%'";
    $result2 = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Booking</title>

    <?php require('inc/links.php'); ?>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .action-btn { padding: 5px 10px; margin: 0 2px; text-decoration: none; color: white; border-radius: 3px; }
        .delete-btn { background-color: #f44336; }
        .add-btn { background-color: #007bff; }
        .add-btn:hover, .delete-btn:hover { color: white; }
        .search-box {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 20px;
    }

    .search-input {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 5px 0 0 5px;
        outline: none;
        width: 300px;
        transition: border 0.3s;
    }

    .search-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .search-button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .search-button:hover {
        background-color: #0056b3;
    }
    .reset-button {
    background-color: #6c757d; /* Gray color */
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s;
}

.reset-button:hover {
    background-color: #5a6268;
    color: white;
}
    </style>

</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">Booking Management</h3>
            <br>
            <div class="search-box">
    <form action="booking.php" method="get" style="display: inline;">
        <input type="text" name="search" placeholder="Search by any field" value="<?php echo htmlspecialchars($searchTerm); ?>" class="search-input">
        <button type="submit" class="search-button">Search</button>
        <a href="booking.php" class="search-button reset-button" style="margin-left: 5px;">Reset</a>
    </form>
</div>
            <!-- Booking Details Table -->
            <div class="card">
                <div class="card-body">
                    <table>
                        <tr>
                            <th>Booking ID</th>
                            <th>Hotel Name</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Actions</th>
                        </tr>
                        <?php if ($result1->num_rows > 0): ?>
                            <?php while ($row = $result1->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['bookingid']); ?></td>
                                    <td><?php echo htmlspecialchars($row['roomname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['checkin']); ?></td>
                                    <td><?php echo htmlspecialchars($row['checkout']); ?></td>
                                    <td>
                                        <a href="cancel-booking.php?bookingid=<?php echo urlencode($row['bookingid']); ?>" 
                                           class="action-btn delete-btn" 
                                           onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                            <td colspan="10" style="text-align: center; vertical-align: middle; color: gray"><strong>No bookings found.</strong></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <br>
            <!-- Booking Account Table -->
            <div class="card">
                <div class="card-body">
                    <table>
                        <tr>
                            <th>Booking ID</th>
                            <th>Booking Account</th>
                            <th>Phone Number</th>
                            <th>Actions</th>
                        </tr>
                        <?php if ($result2->num_rows > 0): ?>
    <?php while ($row = $result2->fetch_assoc()): ?>
        <?php
            $status = $row['status'];
            $svalue = ($status == 'YES') ? 'Paid & Booked' : 'Confirm';
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['bookingid']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td>
                <?php if ($svalue === 'Paid & Booked'): ?>
                    <span style="color: green;"><?php echo $svalue; ?></span>
                <?php else: ?>
                    <a href="confirm-booking.php?id=<?php echo urlencode($row['bookingid']); ?>"
                       class="action-btn add-btn"><?php echo $svalue; ?></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
    <td colspan="10" style="text-align: center; vertical-align: middle; color: gray"><strong>No booking accounts found.</strong></td>
    </tr>
<?php endif; ?>
                    </table>
                </div>
            </div>

            <?php $conn->close(); ?>
        </div>
    </div>
</div>

<?php require('inc/script.php'); ?>

</body>
</html>
