<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {
    $user_id = $db->escapeString(($_POST['user_id']));
    $plan_id = $db->escapeString(($_POST['plan_id']));
    $days = $db->escapeString(($_POST['days']));
   
    //$refer_bonus_sent = $db->escapeString(($_POST['refer_bonus_sent']));
    //$refer_user_id = $db->escapeString(($_POST['refer_user_id']));
    $error = array();

    // Check if user status is 1
   
    if ( !empty($user_id) && !empty($plan_id) && !empty($days)) {
        $sql_query = "INSERT INTO user_plan (user_id,plan_id,days)VALUES('$user_id','$plan_id','$days')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }

        if ($result == 1) {
            
            $error['add_languages'] = "<section class='content-header'>
                                            <span class='label label-success'>User Plan Added Successfully</span> </section>";
        } else {
            $error['add_languages'] = " <span class='label label-danger'>Failed</span>";
        }
        }
    }
?>
<section class="content-header">
    <h1>Add New User Plan <small><a href='user_plan.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to User Plan</a></small></h1>

    <?php echo isset($error['add_languages']) ? $error['add_languages'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
	<!-- Main row -->

	<div class="row">
		<div class="col-md-6">

			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
				</div><!-- /.box-header -->
				<!-- form start -->
				<form name="add_slide_form" method="post" enctype="multipart/form-data">
				<div class="box-body">
				<div class="form-group">
                            <label for="">Users</label>
                            <?php if (!empty($result) && isset($result[0]['id'], $result[0]['name'], $result[0]['email'])) : ?>
                                <?php $userDetails = $result[0]; ?>
                                <input type="text" id="details" name="user_id" class="form-control" value="<?php echo $userDetails['id'] . ' | ' . $userDetails['name'] . ' | ' . $userDetails['email']; ?>" disabled>
                            <?php else : ?>
                                <input type="text" id="details" name="user_id" class="form-control" value="User details not available" disabled>
                            <?php endif; ?>
                            <input type="hidden" id="user_id" name="user_id">
                        </div>
                        <div class="form-group">
                                    <label for="exampleInputEmail1">Select Plan</label> <i class="text-danger asterik">*</i>
                                    <select id='plan_id' name="plan_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                $sql = "SELECT id, products FROM `plan`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                    ?>
                                                    <option value='<?= $value['id'] ?>'><?= $value['products'] ?></option>
                                                <?php } ?>
                                            </select>
                            </div>
			  <div class="form-group">
                <label for="">Days</label>
                <input type="number" class="form-control" name="days">
              </div>
             
            </div><!-- /.box-body -->

            <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>
            <div class="form-group">

              <div id="result" style="display: none;"></div>
            </div>
          </form>
        </div><!-- /.box -->
      </div>
      <!-- Left col -->
      <div class="col-xs-6">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">users</h3>
          </div>
          <div class="box-body table-responsive">
            <table class="table table-hover" data-toggle="table" id="users" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=users" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-trim-on-search="false" data-show-refresh="true" data-show-columns="true" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="#toolbar" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-y') ?>",
                            "ignoreColumn": ["state"]   
                        }'>
              <thead>
                <tr>
                  <th data-field="state" data-radio="true"></th>
                  <th data-field="id" data-sortable="true">ID</th>
                  <th data-field="name" data-sortable="true">Name</th>
                  <th data-field="mobile" data-sortable="true">Mobile</th>
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
  $('#users').on('check.bs.table', function(e, row) {
    $('#details').val(row.id + " | " + row.name + " | " + row.email);
    $('#user_id').val(row.id); // Update 'user_id' with the selected user's id
  });
</script>
