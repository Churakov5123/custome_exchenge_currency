<?php
declare(strict_types=1);

namespace App\src\Ship\Base\Core\Dto;

use Illuminate\Http\Request;

abstract class BaseDto
{

    abstract protected function className(): string;


    public function fillFromRequest(Request $request)
    {
        return $this->fillFromArray($request->toArray());
    }


    public function instanceTransform(string $target)
    {
        $dto = new $target;

        foreach (get_class_vars($target) as $k => $v) {

            $dto->$k = $this->$k ?? ($v ?? null);
        }

        return $dto;
    }


    public function fillFromArray(array $array)
    {
        $class = $this->className();
        $dto = new $class;

        foreach (get_class_vars($class) as $k => $v) {

            $dto->$k = $array[$k] ?? ($v ?? null);
        }

        return $dto;
    }


    public function fillFromObject(object $object)
    {
        $class = $this->className();
        $dto = new $class;

        foreach (get_class_vars($class) as $k => $v) {

            $dto->$k = $object[$k] ?? ($v ?? null);
        }

        return $dto;
    }


    public function toArray(): array
    {
        $attributes = [];

        foreach ($this as $k => $v) {

            $attributes[$k] = $v;
        }

        return $attributes;
    }
}
