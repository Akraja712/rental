<?php

if (isset($_POST['btnPaid']) && isset($_POST['enable']) && isset($_POST['price'])) {
    $price = $db->escapeString($fn->xss_clean($_POST['price']));

    foreach ($_POST['enable'] as $enable) {
        $enable = $db->escapeString($fn->xss_clean($enable));

        $sql = "SELECT user_id FROM recharge_trans WHERE id = $enable";
        $db->sql($sql);
        $res = $db->getResult();
        $user_id = $res[0]['user_id'];

        $sql = "SELECT id FROM users WHERE id = $user_id";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);

        if ($num == 1) {
            $sql = "UPDATE recharge_trans SET amount = $price, status = 1 WHERE id = $enable";
            $db->sql($sql);

            $datetime = date('Y-m-d H:i:s');
            $type = 'recharge';
            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$price', '$datetime', '$type')";
            $db->sql($sql);

            $sql_query = "UPDATE users SET recharge = recharge + $price, total_recharge = total_recharge + $price WHERE id = $user_id";
            $db->sql($sql_query);
        }
    }
}
?>

<section class="content-header">
    <h1>Recharge Trans /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>
<!-- Main content -->
<section class="content">
    <form name="recharge_form" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="col-md-3">
                            <h4 class="box-title">Filter by Status</h4>
                            <select id="status" name="status" class="form-control">
                                <option value="0">Not-Paid</option>
                                <option value="1">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                        <h4 class="box-title">Select or Enter Price</h4>
                        <select id="price_select" class="form-control">
                            <option value="">Select</option>
                            <?php
                            $sql = "SELECT price FROM `plan` WHERE price > 0 GROUP BY price ORDER BY id";
                            $db->sql($sql);
                            $result = $db->getResult();
                            foreach ($result as $value) {
                                ?>
                                <option value="<?= $value['price'] ?>"><?= $value['price'] ?></option>
                                <?php
                            }
                            ?>
                            <option value="custom">Enter Price</option>
                        </select>
                        <input type="number" id="custom_price_input" class="form-control" placeholder="Enter price" style="display: none;">
                        <input type="hidden" id="price" name="price">
                    </div>
                        <div class="col-md-3">
                            <h4 class="box-title">Filter by Date </h4>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo (isset($_GET['date'])) ? $_GET['date'] : "" ?>"></input>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="row">
                            <div class="form-group">
                                <div class="text-left col-md-2">
                                    <input type="checkbox" onchange="checkAll(this)" name="chk[]"> Select All</input>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success" name="btnPaid">Paid</button>
                                </div>
                            </div>
                        </div>
                        <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=recharge_trans" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    <th data-field="column"> All</th>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="true"> Name</th>
                                    <th data-field="mobile" data-sortable="true"> Mobile</th>
                                    <th data-field="txn_id" data-sortable="true">Txn ID</th>
                                    <th data-field="order_id" data-sortable="true">Order ID</th>
                                    <th data-field="amount" data-sortable="true">Amount</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="datetime" data-sortable="true">DateTime</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
    </form>
</section>


<script>
    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "status": $('#status').val(),
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
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("price_select").addEventListener("change", function() {
        if (this.value === "custom") {
            document.getElementById("custom_price_input").style.display = "block";
            document.getElementById("custom_price_input").setAttribute("name", "custom_price"); // change the name attribute for custom price
            document.getElementById("price").value = ""; // clear the hidden input value
        } else {
            document.getElementById("custom_price_input").style.display = "none";
            document.getElementById("custom_price_input").removeAttribute("name"); // remove the name attribute for custom price
            document.getElementById("price").value = this.value; // set the hidden input value to selected price
        }
    });
    
    // Listen for changes in the custom price input
    document.getElementById("custom_price_input").addEventListener("input", function() {
        document.getElementById("price").value = this.value; // update the hidden input value
    });
});
</script>
