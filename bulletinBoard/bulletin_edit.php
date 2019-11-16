<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('isLogin.php'); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php require_once('php/string.php'); ?>

<?php

    /*
     *  Send bulletin edited data.
     *  Confirmation flag is set to true : send updated bulletin data.
     *  Confirmation flag is NOT set to true : show edition page.
     */

    if(isset($_GET['confirm']) && $_GET['confirm']=='true')  // Send updated bulletin data.
    {
        // Make link name-url pairs string.
        $linkStr = '';
        if(isset($_POST['bulletin_field_linkName']) && sizeof($_POST['bulletin_field_linkName'])>0)
        {
            for($i=0; $i<sizeof($_POST['bulletin_field_linkName']); ++$i)
            {
                if(isNotEmpty(addslashes($_POST['bulletin_field_linkName'][$i])) && isNotEmpty(addslashes($_POST['bulletin_field_linkUrl'][$i])))
                    $linkStr = $linkStr.addslashes($_POST['bulletin_field_linkName'][$i])."\n".addslashes($_POST['bulletin_field_linkUrl'][$i])."\n";
            }
        }

        // Check old files.
        $fileStr = '';
        if(isset($_POST['bulletin_field_fileNameOld']) && sizeof($_POST['bulletin_field_fileNameOld'])>0)
        {
            for($i=0; $i<sizeof($_POST['bulletin_field_fileNameOld']); ++$i)
            {
                if($_POST['bulletin_field_fileNameOld'][$i] != '')
                    $fileStr = $fileStr.addslashes($_POST['bulletin_field_fileNameOld'][$i])."\n".$_POST['bulletin_field_fileUrlOldIdent'][$i]."\n";
                else if(file_exists('upload/'.$_POST['bulletin_field_fileUrlOldIdent'][$i]))
                    unlink('upload/'.$_POST['bulletin_field_fileUrlOldIdent'][$i]);
            }
        }

        // Check new upload file : make file name-url pairs string.
        if(isset($_POST['bulletin_field_fileName']) && sizeof($_POST['bulletin_field_fileName'])>0)
        {
            $timeStamp = date('YmdHis');

            for($i=0; $i<sizeof($_POST['bulletin_field_fileName']); ++$i)
            {
                if(isNotEmpty(addslashes($_POST['bulletin_field_fileName'][$i])) && isNotEmpty(addslashes($_FILES['bulletin_field_fileUrl']['name'][$i])))
                    $fileStr = $fileStr.addslashes($_POST['bulletin_field_fileName'][$i])."\n".$timeStamp.$i."\n";
            }
        }

        // Update database.
        mysqlQuery('UPDATE `bulletin` SET `title`=\''.addslashes($_POST['bulletin_field_title']).'\', `content`=\''.addslashes($_POST['bulletin_field_content']).'\', `type`='.(is_numeric(addslashes($_POST['bulletin_field_type']))?$_POST['bulletin_field_type']:1).', `datetime`=NOW(), link=\''.$linkStr.'\', file=\''.$fileStr.'\' WHERE `id`='.$_GET['id'].';');

        // Upload file.
        if(isset($_POST['bulletin_field_fileName']) && sizeof($_POST['bulletin_field_fileName'])>0)
        {
            for($i=0; $i<sizeof($_POST['bulletin_field_fileName']); ++$i)
            {
                if(isNotEmpty(addslashes($_POST['bulletin_field_fileName'][$i])) && isNotEmpty(addslashes($_FILES['bulletin_field_fileUrl']['name'][$i])))
                {
                    if(is_uploaded_file($_FILES['bulletin_field_fileUrl']['tmp_name'][$i]))
                        move_uploaded_file($_FILES['bulletin_field_fileUrl']['tmp_name'][$i], 'upload/'.$timeStamp.$i);
                }
            }
        }

        header('Location: detail.php?id='.$_GET['id']);
        exit(0);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>編輯公告</title>
        <link rel="stylesheet" href="css/common.css" />
        <link rel="stylesheet" href="css/form.css" />
        <link rel="stylesheet" href="css/bulletin.css" />
        <link rel="stylesheet" href="css/bulletin_edit.css" />
        <script src="js/jquery.js"></script>
        <script src="js/jquery-form.js"></script>
        <script src="js/bulletin_edit.js"></script>
    </head>
    <body>

        <header>
            <h1>編輯公告</h1>
        </header>

        <section>
            <form id="bulletinEdit_form" action="bulletin_edit.php?id=<?php echo $_GET['id']; ?>&confirm=true" method="post" enctype="multipart/form-data">
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
                                // Fetch and print basic info: title, type, datetime.
                                $dbExtract = mysqli_fetch_assoc($dbRetrieve);

                                echo
                                '
                                <tr></tr>
                                <tr>
                                    <td><input class="bulletin_input_textbox" name="bulletin_field_title" value="'.toHtml($dbExtract['title']).'" placeholder="公告標題" required /></td>
                                    <td class="bulletin_column_type">
                                        <select class="bulletin_input_selection" name="bulletin_field_type">
                                ';

                                // Print type selection box.
                                for($i=1; $i<=BulletinType::kMaxType; ++$i)
                                {
                                    if((int)$dbExtract['type'] == $i)
                                        echo '<option value="'.$i.'" selected>'.BulletinType::numberToName($i).'</option>';
                                    else
                                        echo '<option value="'.$i.'">'.BulletinType::numberToName($i).'</option>';
                                }

                                echo '
                                        </select>
                                    </td>
                                    <td class="bulletin_column_datetime">自動更新</td>
                                </tr>
                                '."\n";

                                // Print content.
                                echo
                                '
                                <tr>
                                    <td colspan="3" style="min-width:750px; max-width:750px;"><textarea class="bulletin_input_textarea" name="bulletin_field_content" placeholder="公告內容">'.$dbExtract['content'].'</textarea></td>
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
                                            <ol id="bulletin_link_container">
                                        '."\n";
                                        
                                                if($linksObj->getSize() != 0)
                                                {
                                                    for($i=0; $i<$linksObj->getSize(); ++$i)
                                                    {
                                                        echo '
                                                        <li>
                                                            <div style="margin-bottom:10px; border:1px solid silver;">
                                                                <input class="bulletin_input_textbox" name="bulletin_field_linkName[]" type="text" value="'.$linksObj->getLinkName($i).'" placeholder="連結名稱" />
                                                                <input class="bulletin_input_textbox" name="bulletin_field_linkUrl[]" type="text" value="'.$linksObj->getLinkUrl($i).'" placeholder="連結網址" />
                                                            </div>
                                                        </li>
                                                        '."\n";
                                                    }
                                                }

                                        echo
                                        '
                                            </ol>

                                            <input id="bulletin_input_newLink" class="input_button" type="button" value="新增連結" onClick="" />
                                        </div>
                                        '."\n";

                                        // Print files.
                                        $filesObj = new BulletinFile($dbExtract['file']);

                                        echo
                                        '
                                        <div class="bulletin_column_fileDetail">
                                            <h1>檔案</h1>
                                            <ol id="bulletin_file_container">
                                        '."\n";
                                        
                                                if($filesObj->getSize() != 0)
                                                {
                                                    for($i=0; $i<$filesObj->getSize(); ++$i)
                                                    {
                                                        echo '
                                                        <li>
                                                            <div style="margin-bottom:10px; border:1px solid silver;">
                                                                <input class="bulletin_input_textbox" name="bulletin_field_fileNameOld[]" type="text" value="'.$filesObj->getFileName($i).'" placeholder="檔案名稱" />
                                                                <input name="bulletin_field_fileUrlOldIdent[]" type="text" value="'.$filesObj->getFileUrl($i).'" style="visibility:hidden; position:absolute;" />
                                                            </div>
                                                        </li>
                                                        '."\n";
                                                    }
                                                }

                                        echo
                                        '
                                            </ol>

                                            <input id="bulletin_input_newFile" class="input_button" type="button" value="新增檔案" onClick="" />
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
                                            <input class="input_button input_button_withIcon" type="submit" value="儲存修改" style="background-image:url(\'image/icon_confirm_yes.png\');" />
                                            <input class="input_button input_button_withIcon" type="button" value="取消修改" onClick="window.location=\'detail.php?id='.$_GET['id'].'\';" style="background-image:url(\'image/icon_confirm_no.png\');" />
                                        '."\n";

                                echo '
                                    </td>
                                </tr>
                                '."\n";
                            }

                        ?>
                    </tbody>
                </table>
            </form>
        </section>

        <footer></footer>

    </body>
</html>

    