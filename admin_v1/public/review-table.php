<?php

if (isset($_POST['btnPaid']) && isset($_POST['enable'])) {
    foreach ($_POST['enable'] as $enable) { 
        $enable = $db->escapeString($fn->xss_clean($enable));

        $sql = "SELECT user_id FROM review WHERE id = $enable";
        $db->sql($sql);
        $res = $db->getResult();

        if (!empty($res)) { 
            $user_id = $res[0]['user_id'];

            $datetime = date('Y-m-d H:i:s');
            $type = 'recharge';
            $price = 100;

            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$price', '$datetime', '$type')";
            $db->sql($sql);

            $sql_query = "UPDATE users SET recharge = recharge + $price WHERE id = $user_id";
            $db->sql($sql_query);
        }
    }
}
?>

<section class="content-header">
    <h1>Review /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <!-- <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-transaction.php"><i class="fa fa-plus-square"></i> Add New Transaction</a>
    </ol> -->
</section>
    <!-- Main content -->
    <section class="content">
    <form name="recharge_form" method="post" enctype="multipart/form-data">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    <div class="row">
                    <div class="col-md-2">
                        <input type="checkbox" onchange="checkAll(this)" name="chk[]" > Select All</input>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success" name="btnPaid">Paid</button>
                   </div>
                </div>
             
                                </div>
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=review" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                <th data-field="column"> All</th>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="name" data-sortable="true"> Name</th>
                                    <th  data-field="mobile" data-sortable="true"> Mobile</th>
                                    <th  data-field="image" data-sortable="true">Image</th>
                                    <th  data-field="status" data-sortable="true">Status</th>
                                    <th  data-field="datetime" data-sortable="true">DateTime</th>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
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
    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#type').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "type": $('#type').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "date": $('#date').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
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