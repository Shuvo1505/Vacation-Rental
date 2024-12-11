<?php
    require('inc/essentials.php');
    adminLogin();
    
    $hname='localhost';
    $uname='root';
    $pass='';
    $db='hms';
    $conn = mysqli_connect($hname, $uname, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set default search term
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // Query to get users with search filter
    $sql = "SELECT ticketid, name, email, phone FROM calls WHERE 
            name LIKE '%$searchTerm%' OR
            email LIKE '%$searchTerm%' OR
            phone LIKE '%$searchTerm%' OR
            ticketid LIKE '%$searchTerm%'";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Calls</title>

    <?php require('inc/links.php'); ?>
    <style>
                /* Styling for search box and table */
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 0 2px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .add-btn {
            background-color: green;
        }
        .add-btn:hover {
            color: white;
        }
        .delete-btn:hover {
            color: white;
        }
    </style>

</head>
<body class="bg-light">

    <?php require('inc/header.php');?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
               <h3 class="mb-4">Call Management</h3>
               <br>
               <div class="search-box">
    <form action="calls.php" method="get" style="display: inline;">
        <input type="text" name="search" placeholder="Search by any field" value="<?php echo htmlspecialchars($searchTerm); ?>" class="search-input">
        <button type="submit" class="search-button">Search</button>
        <a href="calls.php" class="search-button reset-button" style="margin-left: 5px;">Reset</a>
    </form>
</div>
                <div class="card">
                    <div class="card-body">
                        <table>
                            <tr>
                                <th>Ticket Number</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Actions</th>
                            </tr>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['ticketid']); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td>
    <a href="delete-calls.php?id=<?php echo urlencode($row['ticketid']); ?>" class="action-btn add-btn" 
       onclick="return confirm('Are you sure you want to mark this incident as solved?');">Solve</a>
</td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                <td colspan="10" style="text-align: center; vertical-align: middle; color: gray"><strong>No call requests found.</strong></td>
                                </tr>
                            <?php endif; ?>
                        </table>

                        <?php
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require('inc/script.php'); ?>

</body>
</html>
