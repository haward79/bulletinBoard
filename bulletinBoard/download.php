<?php

    if(isset($_GET['filename']) && isset($_GET['name']) && is_numeric($_GET['filename']))
    {
        if(file_exists('upload/'.$_GET['filename']))
        {
            $filepath = 'upload/'.$_GET['filename'];
            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename="'.$_GET['name']);
            header('Content-Length:'.filesize($filepath));

            $fileOpener = fopen($filepath, "rb");
            fpassthru($fileOpener);
            fclose($fileOpener);
        }
        else
            header('Location: error');
    }
    else
        header('Location: error');

