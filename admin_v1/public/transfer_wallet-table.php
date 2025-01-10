
<style>
.btn-success {
    color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;
    margin-top: 25px;
    margin-left: 10px;
}
.btn-primary {
    color: #fff;
    background-color: #337ab7;
    border-color: #2e6da4;
    margin-top: 13px;
}   
.box-footer{
    margin-top: -14px;
}

    </style>

<section class="content-header">
    <h1>Transfer Wallet /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb"></ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="col-md-12">
                        <form id="mobileSearchForm" method="POST">
                            <div class="form-group">
                                <div class='col-md-3'>
                                <label for="mobile">From Mobile Number:</label>
                                <input type="text" id="mobile" name="mobile" class="form-control" maxlength="10" placeholder="Enter 10-digit mobile number" required>
                                
                                </div>
                                
                            </div>
                            <div class="form-group">
                                 <div class="box-footer col-mb-5">
                          <button type="button" id="checkMobile" class="btn btn-primary">Submit</button>
                    </div>
                            </div>
                            <div class="form-group">
                                <div class='col-md-3'>
                                <label for="mobile"> To Mobile Number:</label>
                                <input type="text" id="to_mobile" name="to_mobile" class="form-control" maxlength="10" placeholder="Enter 10-digit mobile number" required>
                                </div>
                            </div>
                             <div>
                                 <div class="form-group">
                                    <div class='col-md-3'>
                                <label for="transfer_amount">Transfer Amount:</label>
                                <input type="number" id="transfer_amount" name="transfer_amount" class="form-control" min="1" placeholder="Enter transfer amount" required>
                                    </div>
                            </div>
                                
                               <button type="button" id="transferBtn" class="btn btn-success">Wallet Transfer</button>
                                
                            </div>
                            <br>

                           
                            
                         
                        </form>
                        <div id="mobileResult" style="margin-top: 20px;"></div>
                    </div>
                </div>
                <!-- <div class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=transfer_wallet" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                        "fileName": "users-list-<?= date('d-m-Y') ?>",
                        "ignoreColumn": ["operate"] 
                    }'>
                        <thead>
                            <tr>
                                <th data-field="name" data-sortable="true">Name</th>
                                <th data-field="mobile" data-sortable="true">Mobile</th>
                                <th data-field="recharge" data-sortable="true">Recharge</th>
                            </tr>
                        </thead>
                    </table>
                </div> -->
            </div>
        </div>
    </div>
</section>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Handle mobile number submission
    $('#checkMobile').click(function() {
        const mobile = $('#mobile').val();

        // Validate the mobile number format
        if (!/^\d{10}$/.test(mobile)) {
            alert('Please enter a valid 10-digit mobile number.');
            return;
        }

        // Send an AJAX request to check the mobile number
        $.ajax({
            url: 'check-mobile.php', // Backend endpoint to handle the mobile check
            type: 'POST',
            data: { mobile: mobile },
            success: function(response) {
                console.log(response); // Debug: Log response
               if (response.includes('Mobile number found')) {
                    $('#mobile').prop('disabled', false); // Enable the transfer button
                } else {
                    $('#mobile').prop('disabled', true); // Disable the transfer button

                }


                $('#mobileResult').html(response); // Display the result in the designated div
            },
            error: function() {
                alert('An error occurred while checking the mobile number.');
            }
        });
    });


    // Handle Wallet Transfer
    $('#transferBtn').click(function() {
        const mobile = $('#mobile').val();
        const toMobile = $('#to_mobile').val();
        const transferAmount = parseFloat($('#transfer_amount').val());

        // Validate the input fields
        if (!/^\d{10}$/.test(mobile) || !/^\d{10}$/.test(toMobile)) {
            alert('Please enter valid 10-digit mobile numbers.');
            return;
        }

        if (transferAmount <= 0 || isNaN(transferAmount)) {
            alert('Please enter a valid transfer amount.');
            return;
        }

        // Send AJAX request to process the wallet transfer
        $.ajax({
            url: 'process-transfer.php', // Backend endpoint to handle the transfer
            type: 'POST',
            data: { mobile: mobile, to_mobile: toMobile, transfer_amount: transferAmount },
            success: function(response) {
                console.log(response); // Debug: Log response
                try {
                    let result = JSON.parse(response); // Ensure it's a valid JSON response
                    alert(result.message); // Show the success or error message
                    $('#mobileResult').html(''); // Clear previous results
                } catch (error) {
                    alert('Error: Invalid response format.');
                }
            },
            error: function() {
                alert('An error occurred while processing the transfer.');
            }
        });
    });
});
</script>