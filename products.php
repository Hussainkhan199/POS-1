<?php
// Include the database connection
include './config/database.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include './widgets/sidebar.php'; ?>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="dashboard.php" class="logo">
                            <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                </div>
                <?php include './widgets/navbar.php'; ?>
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">All Data</h3>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Add Row</h4>
                                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                        <i class="fa fa-plus"></i> Add Row
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Modal -->
                                <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">
                                                    <span class="fw-mediumbold">New</span>
                                                    <span class="fw-light">Row</span>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small">
                                                    Create a new row using this form, make sure you fill them all
                                                </p>
                                                <form id="addProductForm">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Product Name</label>
                                                                <input id="addName" name="product_name" type="text" class="form-control" placeholder="fill name" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 pe-0">
                                                            <div class="form-group form-group-default">
                                                                <label>Quantity</label>
                                                                <input id="addQuantity" name="quantity" type="number" class="form-control" placeholder="fill quantity" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group form-group-default">
                                                                <label>Images URL</label>
                                                                <input id="addImages" name="images" type="text" class="form-control" placeholder="fill image URL" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Description</label>
                                                                <textarea id="addDescription" name="descriptions" class="form-control" placeholder="fill description" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" id="addRowButton" class="btn btn-primary" onclick="addRow()">Add</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Images</th>
                                                <th>Description</th>
                                                <th style="width: 10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    // Replace field names with your actual column names
                                                    $productName = htmlspecialchars($row["product_name"]);
                                                    $quantity = htmlspecialchars($row["quantity"]);
                                                    $images = htmlspecialchars($row["images"]);
                                                    $descriptions = htmlspecialchars($row["descriptions"]);

                                                    echo '<tr>
                                                        <td>' . $productName . '</td>
                                                        <td>' . $quantity . '</td>
                                                        <td><img src="' . $images . '" alt="Product Image" style="width: 100px; height: auto;"></td>
                                                        <td>' . $descriptions . '</td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <button type="button" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Task">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-link btn-danger" data-bs-toggle="tooltip" title="Remove">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>';
                                                }
                                                $result->free();
                                            } else {
                                                echo "<tr><td colspan='5'>No records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addRow() {
            // This function will handle the form submission
            var form = document.getElementById('addProductForm');
            var formData = new FormData(form);

            fetch('add_product.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(result => {
                if (result === 'success') {
                    alert('Product added successfully!');
                    location.reload(); // Reload the page to see the new product
                } else {
                    alert('Failed to add product.');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
