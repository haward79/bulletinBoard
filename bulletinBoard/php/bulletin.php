<?php

    /*
        公告類型編號對應：
            +-------+--------+
            | Type  |  Name  |
            +-------+--------|
            |  0    |  全部   |
            |  1    |  活動   |
            |  2    |  宣導   |
            |  3    |  公告   |
            |  4    |  徵人   |
            |  5    |  其他   |
            +-------+--------+
     */

    include_once('debug.php');

    class BulletinType
    {
        // Variable.
        public const kMaxType = 5;

        // Constructor.
        public function __construct()
        {
            // Create object instance is not supported.
            fatalErrorReport('Reate instance of BulletinType is not allowed.');
        }

        // Method.
        static public function numberToName($number)
        {
            switch((int)$number)
            {
                case 0:
                    return '全部';

                case 1:
                    return '活動';

                case 2:
                    return '宣導';

                case 3:
                    return '公告';

                case 4:
                    return '徵人';

                case 5:
                    return '其他';

                default:
                    return '未知';
            }
        }
    }

    class BulletinLink
    {
        // Variable.
        private $size = 0;
        private $linkName = array();
        private $linkUrl = array();

        // Constructor.
        public function __construct($linkString)
        {
            $linkParse = explode("\n", $linkString);

            // Store size.
            $this->size = (int)floor(sizeof($linkParse)/2);

            // Detach link names and urls.
            for($i=0; $i<$this->size; ++$i)
            {
                $this->linkName[$i] = $linkParse[2*$i];
                $this->linkUrl[$i] = $linkParse[2*$i+1];
            }
        }

        // Accessor.
        public function getSize() { return $this->size; }

        public function getLinkName($index)
        {
            if($index>=0 && $index<$this->size)
                return $this->linkName[$index];
            else
                return '';
        }

        public function getLinkUrl($index)
        {
            if($index>=0 && $index<$this->size)
                return $this->linkUrl[$index];
            else
                return '';
        }
    }

    class BulletinFile
    {
        // Variable.
        private $size = 0;
        private $fileName = array();
        private $fileUrl = array();

        // Constructor.
        public function __construct($fileString)
        {
            $fileParse = explode("\n", $fileString);

            // Store size.
            $this->size = (int)floor(sizeof($fileParse)/2);

            // Detach link names and urls.
            for($i=0; $i<$this->size; ++$i)
            {
                $this->fileName[$i] = $fileParse[2*$i];
                $this->fileUrl[$i] = $fileParse[2*$i+1];
            }
        }

        // Accessor.
        public function getSize() { return $this->size; }

        public function getFileName($index)
        {
            if($index>=0 && $index<$this->size)
                return $this->fileName[$index];
            else
                return '';
        }

        public function getFileUrl($index)
        {
            if($index>=0 && $index<$this->size)
                return $this->fileUrl[$index];
            else
                return '';
        }
    }

