<?php
    class Filehandler
    {
        protected $fileFolder;

        function __construct()
        {
            $this->fileFolder = 'uploads/';
        }

        protected function determineFileType($mimeType)
        {
            $fileType = null;
            switch($mimeType)
            {
                case 'image/png':
                    $fileType = 'png';
                    break;
                case 'image/jpeg':
                    $fileType = 'jpeg';
                    break;
                case 'application/pdf':
                    $fileType = 'pdf';
                    break;
            }

            return $fileType;
        }

        public function uploadfile($file, $fileName)
        {
            $fileMimeType = mime_content_type($file);

            $fileType = $this->determineFileType($fileMimeType);

            $randomFileName = $this->fileFolder . md5($fileName . microtime()) . '.' . $fileType;

            if($fileType !== null)
            {
                if(strlen($fileName) <= 50)
                {
                    if($_FILES['file']['size'] < 3000000)
                    {
                        $uploadStatus = move_uploaded_file($file, $randomFileName);

                        if($uploadStatus)
                        {
                            return [$randomFileName, $fileType];
                        }
                    }
                }
            }
            return false;
        }
    }
?>
