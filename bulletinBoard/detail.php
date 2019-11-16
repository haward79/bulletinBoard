<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php require_once('php/string.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>公佈欄詳情</title>
        <link rel="stylesheet" href="css/common.css" />
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/bulletin.css" />
    </head>
    <body>

        <header>
            <h1>公佈欄詳情</h1>
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
                         *  Retrieve bulletin from database and show on page.
                         */
                    
                        // Retrieve bulletin.
                        $dbRetrieve = mysqlQuery('SELECT * FROM `bulletin` WHERE `id`='.$_GET['id'].' ORDER BY `datetime` DESC LIMIT 1;');

                        if(mysqli_num_rows($dbRetrieve) == 0)  // Bulletin not exists.
                        {
                            echo
                            '
                            <tr>
                                <td colspan="3">該公告不存在喔！</td>
                            </tr>
                            '."\n";
                        }
                        else  // Bulletin exists.
                        {
                            // Print basic info: title, type, datetime.
                            $dbExtract = mysqli_fetch_assoc($dbRetrieve);

                            echo
                            '
                            <tr></tr>
                            <tr>
                                <td>'.toHtml($dbExtract['title']).'</td>
                                <td class="bulletin_column_type">'.BulletinType::numberToName($dbExtract['type']).'</td>
                                <td class="bulletin_column_datetime">'.explode(' ', $dbExtract['datetime'])[0].'</td>
                            </tr>
                            '."\n";

                            // Print content.
                            echo
                            '
                            <tr>
                                <td colspan="3">'.toHtml($dbExtract['content']).'</td>
                            </tr>
                            '."\n";

                            // Print link and file block.
                            echo '
                            <tr>
                                <td class="bulletin_column_linkFile" colspan="3">
                            '."\n";

                                    // Print links.
                                    $linksObj = new BulletinLink($dbExtract['link']);

                                    echo
                                    '
                                    <div class="bulletin_column_linkDetail">
                                        <h1>連結</h1>
                                        <ul>
                                    '."\n";
                                    
                                            if($linksObj->getSize() == 0)
                                                echo '<li><i>沒有連結</i></li>'."\n";
                                            else
                                            {
                                                for($i=0; $i<$linksObj->getSize(); ++$i)
                                                    echo '<li><a href="'.toHtml($linksObj->getLinkUrl($i)).'" target="_blank">'.toHtml($linksObj->getLinkName($i)).'</a></li>'."\n";
                                            }

                                    echo
                                    '
                                        </ul>
                                    </div>
                                    '."\n";

                                    // Print files.
                                    $filesObj = new BulletinFile($dbExtract['file']);

                                    echo
                                    '
                                    <div class="bulletin_column_fileDetail">
                                        <h1>檔案</h1>
                                        <ul>
                                    '."\n";
                                    
                                            if($filesObj->getSize() == 0)
                                                echo '<li><i>沒有檔案</i></li>'."\n";
                                            else
                                            {
                                                for($i=0; $i<$filesObj->getSize(); ++$i)
                                                    echo '<li><a href="download.php?filename='.toHtml($filesObj->getFileUrl($i)).'&name='.toHtml($filesObj->getFileName($i)).'" target="_blank">'.toHtml($filesObj->getFileName($i)).'</a></li>'."\n";
                                            }

                                    echo
                                    '
                                        </ul>
                                    </div>
                                    '."\n";

                            echo '
                                </td>
                            </tr>
                            '."\n";

                            // Print action buttons.
                            echo '
                            <tr>
                                <td class="" colspan="3">
                            '."\n";

                                    echo '
                                        <input class="input_button input_button_withIcon" type="button" value="回上頁" onClick="window.location=\'index.php\';" style="background-image:url(\'image/icon_goBack.png\');" />
                                    '."\n";

                                    // Check if logged in.
                                    if(isset($_SESSION['username']) && isNotEmpty($_SESSION['username']))  // Logged in.
                                    {
                                        echo
                                        '
                                            <input class="input_button input_button_withIcon" type="button" value="編輯" onClick="window.location=\'bulletin_edit.php?id='.$_GET['id'].'\';" style="background-image:url(\'image/icon_bulletin_edit.png\');" />
                                            <input class="input_button input_button_withIcon" type="button" value="刪除" onClick="window.location=\'bulletin_delete.php?id='.$_GET['id'].'\';" style="background-image:url(\'image/icon_bulletin_delete.png\');" />
                                        '."\n";
                                    }

                            echo '
                                </td>
                            </tr>
                            '."\n";
                        }

                    ?>
                </tbody>
            </table>
        </section>

        <footer></footer>

    </body>
</html>

