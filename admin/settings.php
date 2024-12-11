<?php
    require('inc/essentials.php');
    adminLogin();
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - settings</title>

    <?php require('inc/links.php'); ?>

</head>
<body class="bg-light">

    <?php require('inc/header.php');?>
    

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
               <h3 class="mb-4">Setting</h3>
                <!--general setting section-->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Genenal Settings</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm border" data-bs-toggle="modal" data-bs-target="#general-s">
                                Edit<i class="bi bi-pencil-square ms-1"></i>
                            </button>
                        </div>
                        
                        <h6 class="card-subtitle mb-1 fw-bold">Hotel Name</h6>
                        <p class="card-text" id="site_title"></p>
                        <h6 class="card-subtitle mb-1 fw-bold">Details</h6>
                        <p class="card-text" id="site_about"></p>
                    </div>
                </div>

                
                <!-- general settings Modal -->
                    <div class="modal fade" id="general-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Genenal Settings</h5>
                                    </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Hotel Name</label>
                                        <input type="text" name="hotel_title" class="form-control shadow-none">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="hotel_address" class="form-control shadow-none" rows="6"></textarea>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn custom-bg text-white shadow-none">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require('inc/script.php'); ?>

<script>
    let general_data;

    function get_general()
    {
        let site_title=document.getElementById('site_title');
        let site_about=document.getElementById('site_about');

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/settings_crud.php",true);
        xhr.setRequestHeader('content-Type','application/x-www-form-urlencoded');

        xhr.onload = function() {
            general_data = JSON.parse(this.responseText);
            site_title.innerText = general_data.site_title;
            site_about.innerText = general_data.site_about; 
        }


        xhr.send('get-general');
    }

    window.onload = function(){
        get_general();
    }
</script>

</body>
</html>