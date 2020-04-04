<?php

namespace Smarthouse\Models\Admin;

use Smarthouse\Services\DBConnService;

class ImgRegisterModel
{
    private $files;
    private $imgDir; //Конечная папка с картинками
    private $errorCode;
    private $errorMessages;

    const ERROR_MESSAGES = [
        UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
        UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
        UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
        UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
        UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
        UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
        UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
    ];


    public function __construct(string $imgDir)
    {
        $this->imgDir = $imgDir;
        $this->errorCode = '';
        $this->files = $_FILES;
    }


    public function handleFile(string $fName): string
    {
        $file = $this->files[$fName];

        $ext = pathinfo($file['name'])['extension'];
        $fName = $this->registerNewFile($ext, $fName, $file['size']);
        $fullPath = $_SERVER['DOCUMENT_ROOT']  . $this->imgDir . '/' . $fName;
        move_uploaded_file($file['tmp_name'], $fullPath);

        return $fName;
    }


    public function handleAllFiles(): array
    {
        $res = [];
        foreach ($this->files as $file) {
            $res[] = $this->handleFile($file);
        }
        return $res;
    }

    public function registerNewFile(string $ext, string $title, int $size): ?string
    {
        $sql = 'CALL reg_new_img(?,?,?,?)';
        $row = DBConnService::selectSingleRow(
            $sql,
            [
                $ext, $title, $title, $size
            ]
        );
        return $row['filename'];
    }
}
