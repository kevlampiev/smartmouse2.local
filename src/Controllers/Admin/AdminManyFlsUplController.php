<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\Admin\AdminPanelModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\Admin\ImgRegisterModel;


class AdminManyFlsUplController
{
    /**
     * @Route("/admin/manyFilesUpload", name="manyFilesUpload")
     */
    public function __invoke(?array $parameters): string
    {
        if (($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_FILES))) {
            $fileLoader = new ImgRegisterModel('/img/goods');
            $res = $fileLoader->handleAllFiles();
            return json_encode(['status' => 'success', 'filename' => $res]);
        } else {
            return json_encode(['status' => 'failure']);
        }
    }
}
