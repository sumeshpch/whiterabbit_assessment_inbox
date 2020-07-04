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
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            if ($pageNo > 1) {
                ?>
                <li class="page-item"><a class="page-link" href="<?php echo APP_URL ?>/inbox?page=<?php echo ($pageNo - 1); ?>">Previous</a></li>
                <?php
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                ?>
                <li class="page-item"><a class="page-link" href="<?php echo ($pageNo != $i) ? (APP_URL . "/inbox?page=" . $i) : "javascript:void(0)"; ?>"><?php echo $i; ?></a></li>
                <?php
            }
            if (($totalPages > 1) && ($pageNo != $totalPages)) {
                ?>
                <li class="page-item"><a class="page-link" href="<?php echo APP_URL ?>/inbox?page=<?php echo ($pageNo + 1); ?>">Next</a></li>
                    <?php
                }
                ?>
        </ul>
    </nav>
</div>
<?php
include(APP_PATH . '/views/includes/footer.tpl.php');
?>