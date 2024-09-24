
<section class="content-header">
    <h1>User Coupons /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
  
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
                    <div class="col-md-2">
                         <h4 class="box-title">Filter by Date </h4>
                                  <input type="date" class="form-control" id="date" name="date" value="<?php echo (isset($_GET['date'])) ? $_GET['date'] : "" ?>"></input>
                         </div>
                     </div>
                            
                      
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=user_coupons" data-page-list="[5, 10, 20, 50, 100, 200,500]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true"> ID</th>
                                    <th data-field="name" data-sortable="true">User Name</th>
                                    <th data-field="mobile" data-sortable="true">User Mobile</th>
                                    <th data-field="coupon_num" data-sortable="true">Coupon Number</th>
                                    <th data-field="datetime" data-sortable="true">Datetime</th>
                                    <th data-field="operate" data-events="actionEvents">Action</th>
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