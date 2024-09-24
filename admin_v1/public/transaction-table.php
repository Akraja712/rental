
<section class="content-header">
    <h1>Transactions /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <!-- <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-transaction.php"><i class="fa fa-plus-square"></i> Add New Transaction</a>
    </ol> -->
</section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    <div class="row">
                                <div class="form-group col-md-3">
                                     <a href="export-transactions.php" class="btn btn-primary"><i class="fa fa-download"></i> Export Transactions</a>
                                   </div>                 
                                </div>
                    <div class="row">
                                    <div class="form-group col-md-3">
                                            <h4 class="box-title">Filter by Type </h4>
                                            <select id='type' name="type" class='form-control'>
                                                    <?php
                                                    $sql = "SELECT * FROM `transactions` GROUP BY type ORDER BY id";
                                                    $db->sql($sql);
                                                    $result = $db->getResult();
                                                    foreach ($result as $value) {
                                                    ?>
                                                        <option value='<?= $value['type'] ?>'><?= $value['type'] ?></option>
                                                <?php } ?>
                                            </select> 
                                    </div>
                                    <div class="col-md-2">
                                      <h4 class="box-title">Filter by Date </h4>
                                        <input type="date" class="form-control" id="date" name="date" value="<?php echo (isset($_GET['date'])) ? $_GET['date'] : "" ?>"></input>
                                      </div>
                                </div>
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=transactions" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="name" data-sortable="true"> Name</th>
                                    <th  data-field="mobile" data-sortable="true"> Mobile</th>
                                    <th  data-field="ads" data-sortable="true">Ads</th>
                                    <th  data-field="amount" data-sortable="true">Amount</th>
                                    <th  data-field="type" data-sortable="true">Type</th>
                                    <th  data-field="datetime" data-sortable="true">DateTime</th>
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