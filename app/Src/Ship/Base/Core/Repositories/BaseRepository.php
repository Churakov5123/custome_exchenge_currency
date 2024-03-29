<?php

declare(strict_types=1);

namespace App\Src\Ship\Base\Core\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Абстрактный интерфейс для репозиториев приложения.
 *
 * Class CoreRepository
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;


    /**
     * CoreRepository constructor
     */
    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }


    /**
     * @return mixed
     */
    abstract protected function getModelClass(): string;


    /**
     * @return Model
     */
    protected function startConditions(): Model
    {
        return clone $this->model;
    }
}

