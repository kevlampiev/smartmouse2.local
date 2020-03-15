<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;

class SingleGoodModel
{
    private $id;
    private $category_id;
    private $name;
    private $img;
    private $description;
    private $price;
    private $currency;
    private $additionalImgs;

    public function __construct(int $id)
    {
        $this->init($id);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?int
    {
        return $this->category_id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getAdditionalImgs(): ?array
    {
        return $this->additionalImgs;
    }

    public function init(int $id): void
    {
        $sql = "SELECT * FROM v_available_goods WHERE id=?";
        $res = DBConnService::selectSingleRow($sql, [$id]);
        if ($res == []) {
            $this->id = null;
            $this->name = null;
        } else {
            foreach ($res as $key => $item) {
                $this->$key = $item;
            }
            // $this->id = $res['id'];
            // $this->name = $res['name'];
            $this->additionalImgs = $this->getAddPhotos($id);
        }
    }

    protected function getAddPhotos(int $id): array
    {
        $sql = "SELECT img FROM v_additional_goods_photos WHERE good_id=?";
        return DBConnService::selectRowsSet($sql, [$id]);
    }
}
