<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;


if (isset($_POST['btnUpdate'])) {

        if ($_FILES['offer_image']['size'] != 0 && $_FILES['offer_image']['error'] == 0 && !empty($_FILES['offer_image'])) {
            //image isn't empty and update the image
           
            $extension = pathinfo($_FILES["offer_image"]["name"])['extension'];
    
            $result = $fn->validate_image($_FILES["offer_image"]);
            $target_path = 'upload/images/';
            
            $filename = microtime(true) . '.' . strtolower($extension);
            $full_path = $target_path . "" . $filename;
            if (!move_uploaded_file($_FILES["offer_image"]["tmp_name"], $full_path)) {
                echo '<p class="alert alert-danger">Can not upload image.</p>';
                return false;
                exit();
            }
            if (!empty($old_image)) {
                unlink($old_image);
            }
            $upload_image = 'upload/images/' . $filename;
            $sql = "UPDATE settings SET `offer_image`='" . $upload_image . "' WHERE id = 1";
            $db->sql($sql);
        }
    
    $whatsapp_group = $db->escapeString(($_POST['whatsapp_group']));
    $telegram_channel = $db->escapeString(($_POST['telegram_channel']));
    $min_withdrawal = $db->escapeString(($_POST['min_withdrawal']));
    $max_withdrawal = $db->escapeString(($_POST['max_withdrawal']));
    $pay_video = $db->escapeString(($_POST['pay_video']));
    $withdrawal_status = $db->escapeString(($_POST['withdrawal_status']));
    $income_status = $db->escapeString(($_POST['income_status']));
    $withdrawal_ins = $db->escapeString(($_POST['withdrawal_ins']));
    

            $error = array();
            $sql_query = "UPDATE settings SET whatsapp_group='$whatsapp_group',telegram_channel='$telegram_channel',min_withdrawal='$min_withdrawal',max_withdrawal='$max_withdrawal',pay_video='$pay_video',withdrawal_status = '$withdrawal_status',income_status = '$income_status', withdrawal_ins = '$withdrawal_ins'  WHERE id=1";
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
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <form name="delivery_charge" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" id="old_offer_image" name="old_offer_image" value="<?= $res[0]['offer_image']; ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Whatsapp Group</label><br>
                                    <input type="text" class="form-control" name="whatsapp_group" value="<?= $res[0]['whatsapp_group'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Telegram Channel</label><br>
                                    <input type="text" class="form-control" name="telegram_channel" value="<?= $res[0]['telegram_channel'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Min Withdrawal</label><br>
                                    <input type="number" class="form-control" name="min_withdrawal" value="<?= $res[0]['min_withdrawal'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Max Withdrawal</label><br>
                                    <input type="number" class="form-control" name="max_withdrawal" value="<?= $res[0]['max_withdrawal'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputFile">Offer Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['offer_image']) ? $error['offer_image'] : ''; ?>
                                <input type="file" name="offer_image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" /><br>
                                <img id="blah" src="<?php echo $res[0]['offer_image']; ?>" alt="" width="150" height="150" <?php echo empty($res[0]['offer_image']) ? 'style="display: none;"' : ''; ?> />
                            </div>
                            <div class="col-md-3">
                                <label for="">Pay Video</label><br>
                                <input type="text" class="form-control" name="pay_video" value="<?= $res[0]['pay_video'] ?>">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Withdrawal Status</label><br>
                                <input type="checkbox" id="withdrawal_status_button" class="js-switch" <?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 'checked' : '' ?>>
                                <input type="hidden" id="withdrawal_status" name="withdrawal_status" value="<?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 1 : 0 ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="">Income Status</label><br>
                                <input type="checkbox" id="income_status_button" class="js-switch" <?= isset($res[0]['income_status']) && $res[0]['income_status'] == 1 ? 'checked' : '' ?>>
                                <input type="hidden" id="income_status" name="income_status" value="<?= isset($res[0]['income_status']) && $res[0]['income_status'] == 1 ? 1 : 0 ?>">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="withdrawal_ins">Withdrawal Ins :</label> <i class="text-danger asterik">*</i><?php echo isset($error['withdrawal_ins']) ? $error['withdrawal_ins'] : ''; ?>
                                <textarea name="withdrawal_ins" id="withdrawal_ins" class="form-control" rows="8"><?php echo $res[0]['withdrawal_ins']; ?></textarea>
                                <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
                                <script type="text/javascript">
                                   CKEDITOR.replace('withdrawal_ins');
                                </script>
                            </div>
                        </div>

                    </div>
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(150)
                    .css('display', 'block');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>