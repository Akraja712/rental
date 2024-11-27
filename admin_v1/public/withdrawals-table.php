
<?php

$datetime = date('Y-m-d H:i:s');
if (isset($_POST['btnCancel'])  && isset($_POST['enable'])) {
    $sql="SELECT * FROM withdrawals WHERE id IN (".implode(',',$_POST['enable']).")";
    $db->sql($sql);
    $result = $db->getResult();
    foreach ($result as $row) {
        $amount=$row['amount'];
        $user_id=$row['user_id'];
        $sql = "UPDATE users SET balance= balance + $amount,total_withdrawal = total_withdrawal - $amount WHERE id = $row[user_id]";
        $db->sql($sql);
        $result = $db->getResult();

        $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$amount', '$datetime', 'cancelled')";
        $db->sql($sql);
    }
 
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE withdrawals SET status=2 WHERE id = $enable";
        $db->sql($sql);

    }
}

if (isset($_POST['btnPaid'])  && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
    
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE withdrawals SET status=1 WHERE id = $enable";
        $db->sql($sql);
        $result = $db->getResult();

    }
}

?>
<section class="content-header">
    <h1>Withdrawals /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
  
</section>
    <section class="content">
        <!-- Main row -->
        <form name="withdrawal_form" method="post" enctype="multipart/form-data">
            <div class="row">
                <!-- Left col -->
                <div class="col-12">
                    <div class="box">
                        <div class="box-header">
                                <div class="row">
                                        <div class="form-group col-md-3">
                                            <a href="export-unpaid-withdrawal.php" class="btn btn-primary"><i class="fa fa-download"></i> Export unpaid Withdrawal</a>
                                        </div>                  
                                     
                                </div>
                        </div>
                            <div  class="box-body table-responsive">
                                    <div class="row">
                                        <div class="form-group">
                                           <div class="text-left col-md-2">
                                                <input type="checkbox" onchange="checkAll(this)" name="chk[]" > Select All</input>
                                            </div> 
                                            <div class="col-md-3">
                                            <button type="button" class="btn btn-success" name="btnPaidAll" onclick="redirectToPaidPage()">Paid All</button>
                                             <button type="submit" class="btn btn-success" name="btnPaid">Paid</button>
                                             <button type="submit" class="btn btn-danger" name="btnCancel">Cancel</button>
                                          </div>

                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                                <h4 class="box-title">Filter by Status </h4>
                                                <select id='status' name="status" class='form-control'>
                                                <option value="">All</option>     
                                                <option value="0">Unpaid</option>
                                                        <option value="1">Paid</option>
                                                        <option value="2">Cancelled</option>
                                                </select>
                                        </div>
                                        
                                <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=withdrawals" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                                "fileName": "app-withdrawals-list-<?= date('d-m-Y') ?>",
                                "ignoreColumn": ["operate"] 
                            }'>
                                    <thead>
                                            <tr>
                                                <th data-field="column"> All</th>
                                                <th  data-field="id" data-sortable="true">ID</th>
                                                <th data-field="status" data-sortable="true">Status</th>
                                                <th data-field="name" data-sortable="true">Name</th>
                                                <th data-field="mobile" data-sortable="true">Mobile</th>
                                                <th  data-field="amount" data-sortable="true">Amount</th>
                                                <th  data-field="datetime" data-sortable="true">Date</th>
                                                <th data-field="account_num" data-sortable="true">Account Number</th>
                                        <th data-field="holder_name" data-sortable="true">Holder Name</th>
                                        <th data-field="bank" data-sortable="true">Bank</th>
                                        <th data-field="branch" data-sortable="true">Branch</th>
                                        <th data-field="ifsc" data-sortable="true">IFSC</th>
                                       
                                            </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="separator"> </div>
                </div>
                <!-- /.row (main row) -->
        </form>

    </section>

<script>

    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    function queryParams(p) {
        return {
            "status": $('#status').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
    
</script>
<script>
function redirectToPaidPage() {
    window.location.href = "paid.php";
}
</script>

<script>
 function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
    
</script>

