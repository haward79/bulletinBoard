<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php require_once('php/string.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>公佈欄</title>
        <link rel="stylesheet" href="css/common.css" />
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/bulletin.css" />
    </head>
    <body>

        <header>
            <h1>公佈欄</h1>
        </header>

        <section>
            <table class="bulletin_table bulletin_table_zebraTexture">
                <thead>
                    <tr>
                        <th>標題</th>
                        <th class="bulletin_column_type">類型</th>
                        <th class="bulletin_column_datetime">發布日期</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                        /*
                         *  Retrieve bulletins from database and show on page.
                         */

                        // Retrieve bulletins.
                        $dbRetrieve = mysqlQuery('SELECT `id`, `title`, `type`, `datetime` FROM `bulletin` ORDER BY `datetime` DESC;');

                        // Show bulletins.
                        if(mysqli_num_rows($dbRetrieve) == 0)  // No bulletin.
                        {
                            echo
                            '
                            <tr>
                                <td colspan="3">目前沒有公告喔，過幾天再來看看吧！</td>
                            </tr>
                            '."\n";
                        }
                        else
                        {
                            for($i=0; $i<mysqli_num_rows($dbRetrieve); ++$i)
                            {
                                $dbExtract = mysqli_fetch_assoc($dbRetrieve);

                                echo
                                '
                                <tr>
                                    <td><a href="detail.php?id='.$dbExtract['id'].'" target="_self">'.toHtml($dbExtract['title']).'</a></td>
                                    <td class="bulletin_column_type">'.BulletinType::numberToName($dbExtract['type']).'</td>
                                    <td class="bulletin_column_datetime">'.explode(' ', $dbExtract['datetime'])[0].'</td>
                                </tr>
                                '."\n";
                            }
                        }

                    ?>
                </tbody>
            </table>
        </section>
        
        <section>
            <?php

                // Print login or logout button.
                if(isset($_SESSION['username']) && isNotEmpty($_SESSION['username']))  // Logged in.
                {
                    echo '
                    <input class="input_button input_button_withIcon" type="button" value="新增公告" onClick="window.location=\'bulletin_add.php\';" style="background-image:url(\'image/icon_bulletin_add.png\');" />
                    <input class="input_button input_button_withIcon" type="button" value="登出管理後台" onClick="window.location=\'logout.php\';" style="background-image:url(\'image/icon_manage.png\');" />
                    '."\n";
                }
                else  // Not logged in.
                    echo '<input class="input_button input_button_withIcon" type="button" value="管理後台" onClick="window.location=\'login.php\';" style="background-image:url(\'image/icon_manage.png\');" />'."\n";

            ?>
        </section>

        <footer></footer>

    </body>
</html>

