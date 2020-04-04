<?php

namespace Smarthouse\Models\Admin;

use Exception;
use Smarthouse\Models\SingleGoodModel;
use Smarthouse\Services\DBConnService;
use Smarthouse\Services\TwigService;

class AdminGoodModel extends SingleGoodModel
{
    private $priceList;
    private $categoriesList;
    // private $additionalGoods;

    public function init(int $id): void
    {
        parent::init($id);

        $sql = "SELECT * from good_categories ORDER BY name";
        $this->categoriesList = DBConnService::selectRowsSet($sql);

        $sql = "SELECT id,date_open,date_close,price,currency FROM prices WHERE good_id=?";
        $this->priceList = DBConnService::selectRowsSet($sql, [$this->id]);
    }

    public function getPriceList(): array
    {
        $sql = "SELECT id,date_open,date_close,price,currency FROM prices WHERE good_id=? ORDER BY date_open DESC";
        return DBConnService::selectRowsSet($sql, [$this->id]);
    }

    public function getCategoriesList(): array
    {
        return $this->categoriesList;
    }

    public function getGoodCategory(): string
    {
        return $this->categoriesList["{$this->getCategory()}"];
    }

    public function getCurrencyList(): array
    {
        $sql = "SELECT * FROM currencies ORDER BY currency";
        return DBConnService::selectRowsSet($sql);
    }

    public function getPricesContent(): string
    {
        return TwigService::getTwig()->render('components/good_price_list.twig', ['good' => $this]);
    }

    // public function getAddPhotos(int $id = null): array
    // {
    //     $sql = 'SELECT id, img FROM v_additional_goods_photos WHERE good_id=?';
    //     $res = DBConnService::selectRowsSet($sql, [$this->id]);
    //     return $res;
    // }

    public function getAddsImgsContents(): string
    {
        return TwigService::getTwig()->render(
            'components/good_additional_imgs.twig',
            ['addsImages' => $this->getAddPhotos($this->id)]
        );
    }

    public function addPrice(array $params): array
    {
        $sql = "INSERT INTO prices(good_id, price, currency, date_open) VALUES (?,?,?,?) ";
        $result = DBConnService::execQuery($sql, [
            $params['id'],
            floatval($params['price']),
            $params['currency'],
            $params['dateOpen']
        ]);
        if ($result['status'] != 'Ok') {
            return $result;
        }
        $result['status'] = 'success';
        $result['content'] = $this->getPricesContent();
        return $result;
    }

    public function editPrice(array $params): array
    {
        $sql = "UPDATE prices SET price=?, currency=?, date_open=? WHERE id=?";
        $result = DBConnService::execQuery($sql, [

            floatval($params['price']),
            $params['currency'],
            $params['dateOpen'],
            $params['id']
        ]);
        if ($result['status'] != 'Ok') {
            return $result;
        }
        $result['status'] = 'success';
        $result['content'] = $this->getPricesContent();
        return $result;
    }

    public function deletePrice(array $params): array
    {
        $sql = "DELETE FROM prices WHERE id=?";
        $result = DBConnService::execQuery($sql, [$params['priceId']]);
        if ($result['status'] != 'Ok') {
            return $result;
        }
        $result['status'] = 'success';
        $result['content'] = $this->getPricesContent();
        return $result;
    }

    private function updateGeneralInfo(array $params): array
    {
        try {
            $sql = 'update goods set name=?, category_id=?, description=?, img=? WHERE id=?';
            $res = DBConnService::execQuery(
                $sql,
                [
                    $params['name'], $params['category'], $params['description'], $params['mainImg'], $params['goodId']
                ]
            );
        } catch (Exception $e) {
            $res = ['error' => $e->getMessage()];
        }
        return $res;
    }

    private function addAdditionalImgs(array $params): array
    {
        $files = $params['fileNames'];
        $dBase = DBConnService::getConnection();
        try {
            $dBase->beginTransaction();
            foreach ($files as $file) {
                $sql = "INSERT INTO goods_photos (good_id, photo_id) 
                        SELECT ?,id FROM photos WHERE img=?";
                $res = DBConnService::execQuery($sql, [
                    $params['goodId'],
                    $file
                ]);
            }
            $dBase->commit();
            $res = ['status' => 'success', 'content' => $this->getAddsImgsContents()];
        } catch (Exception $e) {
            $dBase->rollBack();
            $res = ['status' => "Error: {$e->getMessage()}"];
        }
        return $res;
    }

    private function delAdditionalPhoto(array $params): array
    {

        try {
            $sql = 'DELETE FROM goods_photos WHERE good_id=? AND photo_id=?';
            $res = DBConnService::execQuery($sql, [$params['goodId'], $params['photoId']]);
            if ($res['status'] == "Ok") {
                $res = ['status' => 'success', 'content' => $this->getAddsImgsContents()];
            }
        } catch (Exception $e) {
            $res = ['status' => "Error: {$e->getMessage()}"];
        }
        return $res;
    }

    public function handleGood(array $params): array
    {
        switch ($params['action']) {
            case 'addPrice':
                $res = $this->addPrice($params);
                break;
            case 'editPrice':
                $res = $this->editPrice($params);
                break;
            case 'deletePrice':
                $res = $this->deletePrice($params);
                break;
            case 'updateGeneralInfo':
                $res = $this->updateGeneralInfo($params);
                break;
            case 'addFiles':
                $res = $this->addAdditionalImgs($params);
                break;
            case 'delAdditionalPhoto':
                $res = $this->delAdditionalPhoto($params);
                break;
            default:
                $res = ['status' => "Error: the action {$params['action']} doesn't exist..."];
        }
        return $res;
    }
}
