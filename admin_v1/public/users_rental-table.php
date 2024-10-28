
<section class="content-header">
    <h1>User Rental /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
  
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                 <div class="col-md-12">
                <form action="export-user_rental.php">
                            <button type='submit'  class="btn btn-primary"><i class="fa fa-download"></i> Export User Rental</button>
                        </form>
                        </div>
                    <div class="form-group col-md-3"><br>
                         <h4 class="box-title">Filter by Rental</h4>
                          <select id='name' name="name" class='form-control'>
                          <option value=''>Select All</option>
                            <?php
                            $sql = "SELECT name FROM `rental` GROUP BY name ORDER BY id"; // Modified to group by 'products' column
                             $db->sql($sql);
                            $result = $db->getResult();
                              foreach ($result as $value) {
                                  ?>
                                 <option value='<?= $value['name'] ?>'><?= $value['name'] ?></option>
                               <?php } ?>
                             </select>
                          </div>
                          <div class="col-md-3"><br>
                                <h4 class="box-title">Joined Date </h4>
                                <input type="date" class="form-control" id="joined_date" name="joined_date" value="<?php echo (isset($_GET['basic_joined_date'])) ? $_GET['basic_joined_date'] : "" ?>"></input>
                        </div>
                     </div>
                            
                      
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=user_rental" data-page-list="[5, 10, 20, 50, 100, 200, 500]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true"> ID</th>
                                    <th data-field="user_name" data-sortable="true">User Name</th>
                                    <th data-field="user_mobile" data-sortable="true">User Mobile</th>
                                    <th data-field="rental_name" data-sortable="true">Rental Name</th>
                                    <th data-field="rental_price" data-sortable="true">Price</th>
                                    <th data-field="user_referred_by" data-sortable="true">Referred By</th>
                                    <th data-field="rental_course_charges" data-sortable="true">Course Charges</th>
                                    <th data-field="rental_per_month" data-sortable="true">Per Month</th>
                                    <th data-field="rental_monthly_rental_earnings" data-sortable="true">Monthly Rental Earnings</th>
                                    <th data-field="rental_min_refers" data-sortable="true">Min Refers</th>
                                    <th data-field="rental_daily_earnings" data-sortable="true">Daily Earnings</th>
                                    <th data-field="rental_invite_bonus" data-sortable="true">Invite Bonus</th>
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
    $('#name').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#joined_date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
   
   

    function queryParams(p) {
        return {
            "date": $('#date').val(),
            "name": $('#name').val(),
            "joined_date": $('#joined_date').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
    
</script>