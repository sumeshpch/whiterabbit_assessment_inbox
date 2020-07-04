<?php
include(APP_PATH . '/views/includes/header.tpl.php');
?>
<div>
    <h2>Inbox</h2>
    <ul class="list-group">
        <?php
        if (count($data) > 0) {
            foreach ($data as $email) {
                ?>
                <li class="list-group-item">
                    <a href="<?php echo APP_URL ?>/email/<?php echo $email['mailId'] ?>">
                        <span class="badge badge-primary badge-pill"><?php echo $email['from'] ?></span>
                        <?php echo $email['subject'] ?>
                    </a>
                </li>
                <?php
            }
        }
        ?>
    </ul>
</div>
<?php
include(APP_PATH . '/views/includes/footer.tpl.php');
?>