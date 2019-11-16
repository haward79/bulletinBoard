<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('isLogin.php'); ?>
<?php require_once('php/database.php'); ?>
<?php require_once('php/bulletin.php'); ?>
<?php require_once('php/string.php'); ?>

<?php

    /*
     *  Send new bulletin data.
     *  Confirmation flag is set to true : send new bulletin data.
     *  Confirmation flag is NOT set to true : show add bulletin fileds page.
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

        // Check upload file : make file name-url pairs string.
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
        echo '<script>alert("Sent.");</script>';
        mysqlQuery('INSERT INTO `bulletin` (`title`, `content`, `type`, `datetime`, `link`, `file`) VALUES (\''.addslashes($_POST['bulletin_field_title']).'\', \''.addslashes($_POST['bulletin_field_content']).'\', '.(is_numeric(addslashes($_POST['bulletin_field_type']))?$_POST['bulletin_field_type']:1).', NOW(), \''.$linkStr.'\', \''.$fileStr.'\');');

        // Upload file.
        if(isset($_POST['bulletin_field_fileName']) && sizeof($_POST['bulletin_field_fileName'])>0)
        {
            for($i=0; $i<sizeof($_POST['bulletin_field_fileName']); ++$i)
            {
                if(isNotEmpty(addslashes($_POST['bulletin_field_fileName'][$i])) && isNotEmpty(addslashes($_FILES['bulletin_field_fileUrl']['name'][$i])))
                {
                    if(is_uploaded_file($_FILES['bulletin_field_fileUrl']['tmp_name'][$i]))
                        move_uploaded_file($_FILES['bulletin_field_fileUrl']['tmp_name'][$i], 'upload/'.$timeStamp.$i); echo '<script>alert("Part Done.");</script>';
                }
            }
        }

        header('Location: index.php');
        exit(0);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>新增公告</title>
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
            <h1>新增公告</h1>
        </header>

        <section>
            <form id="bulletinEdit_form" action="bulletin_add.php?confirm=true" method="post" enctype="multipart/form-data">
                <table class="bulletin_table bulletin_table_zebraTexture">
                    <thead>
                        <tr>
                            <th>標題</th>
                            <th class="bulletin_column_type">類型</th>
                            <th class="bulletin_column_datetime">發布日期</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr></tr>
                        <tr>
                            <td><input class="bulletin_input_textbox" name="bulletin_field_title" value="" placeholder="公告標題" required /></td>
                            <td class="bulletin_column_type">
                                <select class="bulletin_input_selection" name="bulletin_field_type">

                                <?php

                                    // Print type selection box option.
                                    for($i=1; $i<=BulletinType::kMaxType; ++$i)
                                        echo '<option value="'.$i.'">'.BulletinType::numberToName($i).'</option>';

                                ?>

                                </select>
                            </td>
                            <td class="bulletin_column_datetime">自動產生</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="min-width:750px; max-width:750px;"><textarea class="bulletin_input_textarea" name="bulletin_field_content" placeholder="公告內容"></textarea></td>
                        </tr>
                        <tr>
                            <td class="bulletin_column_linkFile" colspan="3">
                                <div class="bulletin_column_linkDetail">
                                    <h1>連結</h1>
                                    <ol id="bulletin_link_container"></ol>
                                    <input id="bulletin_input_newLink" class="input_button" type="button" value="新增連結" onClick="" />
                                </div>
                                <div class="bulletin_column_fileDetail">
                                    <h1>檔案</h1>
                                    <ol id="bulletin_file_container"></ol>
                                    <input id="bulletin_input_newFile" class="input_button" type="button" value="新增檔案" onClick="" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="" colspan="3">
                                <input class="input_button input_button_withIcon" type="submit" value="確定新增" style="background-image:url('image/icon_confirm_yes.png');" />
                                <input class="input_button input_button_withIcon" type="button" value="放棄新增" onClick="window.location='index.php';" style="background-image:url('image/icon_confirm_no.png');" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </section>

        <footer></footer>

    </body>
</html>

    