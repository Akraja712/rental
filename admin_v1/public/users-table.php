
<section class="content-header">
    <h1>Users /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
            <ol class="breadcrumb">
                <a class="btn btn-block btn-default" href="add-users.php"><i class="fa fa-plus-square"></i> Add New User</a>
</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                 <div class="col-md-12">
                <form action="export-users.php">
                            <button type='submit'  class="btn btn-primary"><i class="fa fa-download"></i> Export Users</button>
                        </form>
                        </div>
                        <div class="col-md-3"><br>
                            <h4 class="box-title">Filter by Day</h4>
                            <select id="day_filter" name="day_filter" class="form-control">
                                <option value="">All</option>
                                <option value="today">Today</option>
                            </select>
                        </div>

                <div class="col-md-3"><br>
                        <h4 class="box-title">Referred By</h4>
                            <input type="text" class="form-control" name="referred_by" id="referred_by" >
                        </div>
                        <div class="col-md-3"><br>
                        <h4 class="box-title">Filter by Profile</h4>
                        <select id="profile" name="profile" class="form-control">
                            <option value="">All</option>
                            <option value="text">Yes</option>
                            <option value="NULL">No</option>
                        </select>
                    </div>
                        </div>
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=users" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="name" data-sortable="true">Name</th>
                                    <th  data-field="mobile" data-sortable="true">Mobile</th>
                                    <th  data-field="password" data-sortable="true">Password</th>
                                    <th data-field="recharge" data-sortable="true">Recharge </th>
                                    <th  data-field="total_recharge" data-sortable="true">Total Recharge</th>
                                    <th  data-field="email" data-sortable="true">Email</th>
                                    <th data-field="age" data-sortable="true">Age</th>
                                    <th  data-field="city" data-sortable="true">City</th>
                                    <th  data-field="state" data-sortable="true">State</th>
                                    <th data-field="refer_code" data-sortable="true">Refer Code</th>
                                    <th data-field="referred_by" data-sortable="true">Referred By</th>
                                    <th  data-field="balance" data-sortable="true">Balance</th>
                                    <th  data-field="total_income" data-sortable="true">Total Income</th>
                                    <th data-field="today_income" data-sortable="true">Today Income</th>
                                    <th  data-field="earning_wallet" data-sortable="true">Earning Wallet</th>
                                    <th  data-field="bonus_wallet" data-sortable="true">Bonus Wallet</th>
                                    <th data-field="device_id" data-sortable="true">Device ID</th>
                                    <th  data-field="account_num" data-sortable="true">Account Number</th>
                                    <th  data-field="holder_name" data-sortable="true">Holder Name</th>
                                    <th  data-field="bank" data-sortable="true">Bank</th>
                                    <th data-field="branch" data-sortable="true">Branch</th>
                                    <th  data-field="ifsc" data-sortable="true">IFSC</th>
                                    <th data-field="total_assets" data-sortable="true">Total Assets</th>
                                    <th  data-field="total_withdrawal" data-sortable="true">Total Withdrawals</th>
                                    <th  data-field="team_income" data-sortable="true">Team Income</th>
                                    <th data-field="registered_datetime" data-sortable="true">Registered Datetime</th>
                                    <th data-field="profile">Profile</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
    </section>

<script>

$(document).ready(function() {
    // Check if the URL has the 'filter' parameter
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter');
    
    if (filter === 'today') {
        // Set 'today' as the selected option in the dropdown
        $('#day_filter').val('today');

        // Trigger table refresh to apply the filter
        $('#users_table').bootstrapTable('refresh');
    }
});


    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#trail_completed').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#day_filter').on('change', function() {
    $('#users_table').bootstrapTable('refresh');
});
    $('#referred_by').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#plan').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#profile').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    function queryParams(p) {
        return {
            "day_filter": $('#day_filter').val(),  // Pass day_filter parameter
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "status": $('#status').val(),
            "trail_completed": $('#trail_completed').val(),
            "referred_by": $('#referred_by').val(),
            "plan": $('#plan').val(),
            "profile": $('#profile').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
    
</script>
<?php
// Retrieve day filter parameter
$dayFilter = isset($_GET['day_filter']) ? $_GET['day_filter'] : '';

// Apply condition based on the selected filter
if ($dayFilter === 'today') {
    $currentDate = date('Y-m-d');
    $sql .= " WHERE DATE(registered_datetime) = '$currentDate'";
}
?>
