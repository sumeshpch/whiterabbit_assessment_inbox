# whiterabbit_assessment_inbox
Whiterabbit coding test for reading inbox

whiterabbit.sql added to repo.

Create virtual host and provide the project url in "conf/settings.php -> APP_URL"

Add the database credentials and email credentials in "conf/settings.php" file

execute "php fetchAll.php" to load the emails to the database initially.

To read emails periodically, set cron job to execute the file "fetchLatest.php"

"0 */3 * * * /usr/bin/php /fetchLatest.php" - This will run the job to fetch new emails every 3 hours

User & System activity are recorder in "user_activity" and "system_activity" tables respectively