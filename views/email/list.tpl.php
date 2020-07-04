<?php
include(APP_PATH . '/views/includes/header.tpl.php');
?>
<div>
    <h1>Inbox</h1>   
    <!-- Search form -->
    <div class="row">
        <div class="col-sm-12">
            <form method="GET" action="<?php echo APP_URL ?>/inbox" class="col-sm-9">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="search">Keyword</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Enter search keyword" value="<?php echo $search ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="<?php echo $email ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="<?php echo APP_URL ?>/inbox"><button type="button" class="btn btn-primary">Reset</button></a>
            </form>
        </div>
    </div>
    <br/><br/><br/>
    <h5><?php echo $totalEmails ?> Emails</h5>
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