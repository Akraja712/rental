
<section class="content-header">
    <h1>User Plan /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
  
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
                         <h4 class="box-title">Filter by Plan</h4>
                          <select id='products' name="products" class='form-control'>
                          <option value=''>Select All</option>
                            <?php
                            $sql = "SELECT products FROM `plan` GROUP BY products ORDER BY id"; // Modified to group by 'products' column
                             $db->sql($sql);
                            $result = $db->getResult();
                              foreach ($result as $value) {
                                  ?>
                                 <option value='<?= $value['products'] ?>'><?= $value['products'] ?></option>
                               <?php } ?>
                             </select>
                          </div>
                          <div class="col-md-3">
                                <h4 class="box-title">Joined Date </h4>
                                <input type="date" class="form-control" id="joined_date" name="joined_date" value="<?php echo (isset($_GET['basic_joined_date'])) ? $_GET['basic_joined_date'] : "" ?>"></input>
                        </div>
                     </div>
                            
                      
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=user_plan" data-page-list="[5, 10, 20, 50, 100, 200,500]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true"> ID</th>
                                    <th data-field="user_name" data-sortable="true">User Name</th>
                                    <th data-field="user_mobile" data-sortable="true">User Mobile</th>
                                    <th data-field="plan_products" data-sortable="true">Products</th>
                                    <th data-field="plan_price" data-sortable="true">Price</th>
                                    <th data-field="plan_daily_income" data-sortable="true">Daily Income</th>
                                    <th data-field="plan_monthly_income" data-sortable="true">Monthly Income</th>
                                    <th data-field="plan_daily_quantity" data-sortable="true">Daily Quantity</th>
                                    <th data-field="plan_unit" data-sortable="true">Unit</th>
                                    <th data-field="plan_invite_bonus" data-sortable="true">Invite Bonus</th>
                                    <th data-field="income" data-sortable="true">Income</th>
                                    <th data-field="joined_date" data-sortable="true">Joined Date</th>
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

    $('#date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#products').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#joined_date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
   
   

    function queryParams(p) {
        return {
            "date": $('#date').val(),
            "products": $('#products').val(),
            "joined_date": $('#joined_date').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
    
</script>