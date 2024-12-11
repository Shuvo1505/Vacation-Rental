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

    // Query to get rooms with search filter
    $sql = "SELECT serials, roomname, price, available, location, maxperson, roomsize, roomview, bed FROM rooms WHERE 
    serials LIKE '%$searchTerm%' OR
    roomname LIKE '%$searchTerm%' OR
    price LIKE '%$searchTerm%' OR
    available LIKE '%$searchTerm%' OR
    location LIKE '%$searchTerm%' OR
    maxperson LIKE '%$searchTerm%' OR
    roomsize LIKE '%$searchTerm%' OR
    roomview LIKE '%$searchTerm%' OR
    bed LIKE '%$searchTerm%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Rooms</title>

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
        }
        .delete-btn {
            background-color: #f44336;
        }
        .add-btn {
            background-color: #007bff; /* Blue color */
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
               <h3 class="mb-4">Room Management</h3>
               <br>
                <!--general setting section-->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0 fw-bold">Room Parameters (Need to Enter)</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm border" data-bs-toggle="modal" data-bs-target="#general-s">
    Save <i class="bi bi-save-fill ms-2" style="font-size: 1.2em;"></i>
</button>
<button type="button" class="btn btn-dark shadow-none btn-sm border" data-bs-toggle="modal" data-bs-target="#general-sp">
    Edit <i class="bi bi-pencil-fill ms-2" style="font-size: 1.2em;"></i>
</button>
<button type="button" class="btn btn-dark shadow-none btn-sm border" data-bs-toggle="modal" data-bs-target="#remove-s">
    Remove <i class="bi bi-trash3-fill ms-2" style="font-size: 1.2em;"></i>
</button>

                        </div>                        
                        <h6 class="card-subtitle mb-1">1. Name</h6>
                        <h6 class="card-subtitle mb-1">2. Price</h6>
                        <h6 class="card-subtitle mb-1">3. Capacity (Max Person)</h6>
                        <h6 class="card-subtitle mb-1">4. Size (Square meter/s)</h6>
                        <h6 class="card-subtitle mb-1">5. View Site</h6>
                        <h6 class="card-subtitle mb-1">6. Bed Capacity</h6>
                        <h6 class="card-subtitle mb-1">7. Image (Only in .png format and less than 16MB)</h6>
                        <h6 class="card-subtitle mb-1">8. Location</h6>
                        <h6 class="card-subtitle mb-1">9. Availability</h6>
                    </div>
                </div>
                <br>
                <div class="search-box">
    <form action="rooms.php" method="get" style="display: inline;">
        <input type="text" name="search" placeholder="Search by any field" value="<?php echo htmlspecialchars($searchTerm); ?>" class="search-input">
        <button type="submit" class="search-button">Search</button>
        <a href="rooms.php" class="search-button reset-button" style="margin-left: 5px;">Reset</a>
    </form>
</div>
                <div class="card">
                    <div class="card-body">
                    <table>
                            <tr>
                                <th>Serial Number</th>
                                <th>Hotel Name</th>
                                <th>Price (Per night)</th>
                                <th>Availability</th>
                                <th>Max Persion</th>
                                <th>Room Size</th>
                                <th>Room View</th>
                                <th>Bed Capacity</th>
                                <th>Location</th>
                            </tr>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['serials']); ?></td>
                                        <td><?php echo htmlspecialchars($row['roomname'], ENT_QUOTES); ?></td>
                                        <td><?php echo htmlspecialchars($row['price']); echo ' ₹';?></td>
                                        <td><?php echo htmlspecialchars($row['available']); ?></td>
                                        <td><?php echo htmlspecialchars($row['maxperson']); ?></td>
                                        <td><?php echo htmlspecialchars($row['roomsize']); echo " m<sup>2</sup>";?></td>
                                        <td><?php echo htmlspecialchars($row['roomview']); ?></td>
                                        <td><?php echo htmlspecialchars($row['bed']); ?></td>
                                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                <td colspan="10" style="text-align: center; vertical-align: middle; color: gray"><strong>No rooms found.</strong></td>
                                </tr>
                            <?php endif; ?>
                        </table>

                        <?php
                        $conn->close();
                        ?>
                    </div>
                </div>

                
<!-- Add Room Modal -->
<div class="modal fade" id="general-s" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalLabel">Enter room details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for adding room parameters in a two-column layout -->
                <form action="save-room.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="roomName" class="form-label">Room Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="roomName" name="room_name" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="roomPrice" class="form-label">Room Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="roomPrice" name="room_price" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <select class="form-select" id="location" name="location" required>
                                <option value="Mumbai">Mumbai</option>
                                <option value="Kolkata">Kolkata</option>
                                <option value="Bangalore">Bangalore</option>
                                <option value="Hyderabad">Hyderabad</option>
                                <option value="Chennai">Chennai</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
      <label class="form-label d-block">Availability <span class="text-danger">*</span></label>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="availability" id="available" value="YES" required>
        <label class="form-check-label" for="available">YES</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="availability" id="unavailable" value="NO" required>
        <label class="form-check-label" for="unavailable">NO</label>
    </div>
</div>
                        <div class="col-md-6 mb-3">
                            <label for="roomCapacity" class="form-label">Room Capacity (Max Person) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="roomCapacity" name="room_capacity" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomSize" class="form-label">Room Size <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="roomSize" name="room_size" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomView" class="form-label">Room View Site <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="roomView" name="room_view" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bedCapacity" class="form-label">Bed Capacity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="bedCapacity" name="bed_capacity" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomImage" class="form-label">Room Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="roomImage" name="room_image" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Room Modal -->
<div class="modal fade" id="general-sp" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalLabel">Enter room details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for adding room parameters in a two-column layout -->
                <form action="edit-room.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                    <div class="col-md-6 mb-3">
                            <label for="serialNumber" class="form-label">Serial Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="serialNumber" name="room_serial" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomName" class="form-label">Room Name</label>
                            <input type="text" class="form-control" id="roomName" name="room_name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="roomPrice" class="form-label">Room Price</label>
                            <input type="number" class="form-control" id="roomPrice" name="room_price">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="location" class="form-label">Location</label>
                            <select class="form-select" id="location" name="location">
                                <option value="Mumbai">Mumbai</option>
                                <option value="Kolkata">Kolkata</option>
                                <option value="Bangalore">Bangalore</option>
                                <option value="Hyderabad">Hyderabad</option>
                                <option value="Chennai">Chennai</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
      <label class="form-label d-block">Availability</label>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="availability" id="available" value="YES">
        <label class="form-check-label" for="available">YES</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="availability" id="unavailable" value="NO">
        <label class="form-check-label" for="unavailable">NO</label>
    </div>
</div>
                        <div class="col-md-6 mb-3">
                            <label for="roomCapacity" class="form-label">Room Capacity (Max Person)</label>
                            <input type="number" class="form-control" id="roomCapacity" name="room_capacity">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomSize" class="form-label">Room Size</label>
                            <input type="number" class="form-control" id="roomSize" name="room_size">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomView" class="form-label">Room View Site</label>
                            <input type="text" class="form-control" id="roomView" name="room_view">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bedCapacity" class="form-label">Bed Capacity</label>
                            <input type="number" class="form-control" id="bedCapacity" name="bed_capacity">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomImage" class="form-label">Room Image</label>
                            <input type="file" class="form-control" id="roomImage" name="room_image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Modify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Remove Room Modal -->
<div class="modal fade" id="remove-s" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="removeModalLabel">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                &nbsp;This action can't be undone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for removing room by serial number -->
                <form action="delete-room.php" method="POST">
                    <div class="mb-3">
                        <label for="serialNumberRemove" class="form-label">Enter Serial Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="serialNumberRemove" name="serial_number_remove" required>
                    </div>
                    <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Remove</button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>

<?php require('inc/script.php'); ?>

</body>
</html>