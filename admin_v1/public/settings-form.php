<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;


if (isset($_POST['btnUpdate'])) {
    
    $whatsapp_group = $db->escapeString(($_POST['whatsapp_group']));
    $telegram_channel = $db->escapeString(($_POST['telegram_channel']));
    $min_withdrawal = $db->escapeString(($_POST['min_withdrawal']));
    $max_withdrawal = $db->escapeString(($_POST['max_withdrawal']));
    $pay_video = $db->escapeString(($_POST['pay_video']));
    $pay_gateway = $db->escapeString(($_POST['pay_gateway']));
    $scratch_card = $db->escapeString(($_POST['scratch_card']));
    $income_status = $db->escapeString(($_POST['income_status']));
    $withdrawal_status = $db->escapeString(($_POST['withdrawal_status']));
    $secrete_code = $db->escapeString(($_POST['secrete_code']));
    

            $error = array();
            $sql_query = "UPDATE settings SET whatsapp_group='$whatsapp_group',telegram_channel='$telegram_channel',min_withdrawal='$min_withdrawal',max_withdrawal='$max_withdrawal',pay_video='$pay_video',pay_gateway='$pay_gateway',scratch_card = '$scratch_card',income_status = '$income_status',withdrawal_status= '$withdrawal_status',secrete_code = '$secrete_code' WHERE id=1";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['update'] = "<section class='content-header'>
                                                <span class='label label-success'>Settings Updated Successfully</span> </section>";
            } else {
                $error['update'] = " <span class='label label-danger'>Failed</span>";
            }
        }
  

// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM settings WHERE id = 1";
$db->sql($sql_query);
$res = $db->getResult();
?>
<section class="content-header">
    <h1>Settings</h1>
    <?php echo isset($error['update']) ? $error['update'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                  
                </div>
                <!-- /.box-header -->

                <!-- form start -->
                <form name="delivery_charge" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="whatsapp_group">Whatsapp Group</label>
                                    <input type="text" class="form-control" name="whatsapp_group" value="<?= $res[0]['whatsapp_group'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="telegram_channel">Telegram Channel</label>
                                    <input type="text" class="form-control" name="telegram_channel" value="<?= $res[0]['telegram_channel'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="min_withdrawal">Min Withdrawal</label>
                                    <input type="number" class="form-control" name="min_withdrawal" value="<?= $res[0]['min_withdrawal'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="max_withdrawal">Max Withdrawal</label>
                                    <input type="number" class="form-control" name="max_withdrawal" value="<?= $res[0]['max_withdrawal'] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pay_video">Pay Video</label>
                                    <input type="text" class="form-control" name="pay_video" value="<?= $res[0]['pay_video'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_button">Payment Gateway</label><br>
                                    <input type="checkbox" id="payment_button" class="js-switch" <?= isset($res[0]['pay_gateway']) && $res[0]['pay_gateway'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="pay_gateway" name="pay_gateway" value="<?= isset($res[0]['pay_gateway']) && $res[0]['pay_gateway'] == 1 ? 1 : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="scratch_card_button">Scratch Card</label><br>
                                    <input type="checkbox" id="scratch_card_button" class="js-switch" <?= isset($res[0]['scratch_card']) && $res[0]['scratch_card'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="scratch_card" name="scratch_card" value="<?= isset($res[0]['scratch_card']) && $res[0]['scratch_card'] == 1 ? 1 : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="income_status_button">Income Status</label><br>
                                    <input type="checkbox" id="income_status_button" class="js-switch" <?= isset($res[0]['income_status']) && $res[0]['income_status'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="income_status" name="income_status" value="<?= isset($res[0]['income_status']) && $res[0]['income_status'] == 1 ? 1 : 0 ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="withdrawal_status_button">Withdrawal Status</label><br>
                                    <input type="checkbox" id="withdrawal_status_button" class="js-switch" <?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="withdrawal_status" name="withdrawal_status" value="<?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 1 : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="secrete_code">Secrete Code</label>
                                    <input type="text" class="form-control" name="secrete_code" value="<?= $res[0]['secrete_code'] ?>">
                                </div>
                           </div> 
                        </div>
                        <br>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>


<div class="separator"> </div>

<?php $db->disconnect(); ?>

<script>
    var changeCheckbox = document.querySelector('#challenge_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#challenge_status').val(1);

        } else {
            $('#challenge_status').val(0);
        }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#payment_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#pay_gateway').val(1);

        } else {
            $('#pay_gateway').val(0);
        }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#scratch_card_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#scratch_card').val(1);

        } else {
            $('#scratch_card').val(0);
        }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#income_status_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#income_status').val(1);

        } else {
            $('#income_status').val(0);
        }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#withdrawal_status_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#withdrawal_status').val(1);

        } else {
            $('#withdrawal_status').val(0);
        }
    };
</script>
