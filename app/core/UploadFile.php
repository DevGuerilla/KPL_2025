<?php 

class UploadFile
{
    private $file;
    private $fileName;
    private $fileSize;
    private $fileError;
    private $fileTmpName;
    private $allowedExtensions = ['jpg', 'jpeg', 'png'];
    private $maxSize = 5000000;

    public static function upload($file, $name, $folder)
    {
        $uploadFile = new self;
        $uploadFile->file = $file;
        $uploadFile->fileName = $uploadFile->file[$name]['name'];
        $uploadFile->fileSize = $uploadFile->file[$name]['size'];
        $uploadFile->fileError = $uploadFile->file[$name]['error'];
        $uploadFile->fileTmpName = $uploadFile->file[$name]['tmp_name'];

        if ($uploadFile->fileError === 4) {
            return 'default.jpg';
        }

        $fileExtension = explode('.', $uploadFile->fileName);
        $fileExtension = strtolower(end($fileExtension));

        if (!in_array($fileExtension, $uploadFile->allowedExtensions)) {
            Flasher::setFlash('File extension is not allowed', 'danger');
            header('Location: ' . BASEURL . '/dashboard/createpost');
            exit;
        }

        if ($uploadFile->fileSize > $uploadFile->maxSize) {
            Flasher::setFlash('File size is too large', 'danger');
            header('Location: ' . BASEURL . '/dashboard/createpost');
            exit;
        }

        $newFileName = uniqid();
        $newFileName .= '.';
        $newFileName .= $fileExtension;

        move_uploaded_file($uploadFile->fileTmpName, 'img/' . $folder . '/' . $newFileName);

        return $newFileName;
    }
}
?>