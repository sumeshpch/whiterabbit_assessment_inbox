<?php
include(APP_PATH . '/views/includes/header.tpl.php');
?>
<script type="text/javascript">
    function deleteEmail() {
        return confirm('Do you really want to delete this email?');
    }
</script>
<div>
    <h3><small><?php echo $from ?></small></h3>
    <h6>
        <a class="right" href="<?php echo APP_URL ?>/inbox">Back</a><br/>
        <form method="POST" action="<?php echo APP_URL ?>/email/delete" onsubmit="return deleteEmail();">
            <input type="hidden" name="mailId" value="<?php echo $mailId ?>" />
            <input type="submit" value="Delete" />
        </form>
    </h6>
    <h2>
        <?php echo $subject ?>
        <small class="text-muted"><?php echo $date ?></small>
    </h2>
    <div class="row">
        <div class="col-sm-9">
            <p class="lead">
                <?php
                echo $body;
                ?>
            </p>
        </div>
    </div>
</div>
<?php
include(APP_PATH . '/views/includes/footer.tpl.php');
?>