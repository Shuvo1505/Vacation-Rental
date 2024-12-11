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
    $sql = "SELECT serials, email FROM subscribers WHERE 
    serials LIKE '%$searchTerm%' OR
    email LIKE '%$searchTerm%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Subscribers</title>

    <?php require('inc/links.php'); ?>
    <style>
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
            cursor: pointer;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .promote-btn {
            background-color: #007bff; /* Blue color */
        }
        .promote-btn:hover {
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
               <h3 class="mb-4">Subscriber Management</h3>
               <br>
               <div class="search-box">
    <form action="subscribers.php" method="get" style="display: inline;">
        <input type="text" name="search" placeholder="Search by any field" value="<?php echo htmlspecialchars($searchTerm); ?>" class="search-input">
        <button type="submit" class="search-button">Search</button>
        <a href="subscribers.php" class="search-button reset-button" style="margin-left: 5px;">Reset</a>
    </form>
</div>
                <div class="card">
                    <div class="card-body">
                        <table>
                            <tr>
                                <th>Serial Number</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['serials']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td>
                                        <a class="action-btn promote-btn" data-toggle="modal" data-target="#sendEmailModal"
                                                    data-email="<?php echo htmlspecialchars($row['email']); ?>">
                                                Notify
                                            </a>
                                        <a href="delete-subscriber.php?id=<?php echo urlencode($row['serials']); ?>" class="action-btn delete-btn"
                                               onclick="return confirm('Are you sure you want to revoke this subscription?');">Revoke</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                <td colspan="10" style="text-align: center; vertical-align: middle; color: gray"><strong>No subscribers found.</strong></td>
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

        <!-- Modal for sending promotion email -->
        <div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendEmailModalLabel">Promotional Email</h5>
                </div>
                <form action="send-promotion.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="email" id="modalEmail">
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="body">Mail Body</label>
                            <textarea class="form-control" id="body" name="body" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap and jQuery scripts for modal -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $('#sendEmailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var email = button.data('email');
            var modal = $(this);
            modal.find('#modalEmail').val(email);
        });
    </script>
<?php require('inc/script.php'); ?>
</body>
</html>
