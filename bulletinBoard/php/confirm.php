<?php

    abstract class ConfirmType
    {
        public const ok = 0;
        public const yesNo = 1;
    }

    $msg_pageTitle = '';
    $msg_title = '';
    $msg_content = '';
    $msg_type = ConfirmType::ok;