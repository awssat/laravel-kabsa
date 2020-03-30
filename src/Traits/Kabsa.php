<?php

namespace Awssat\Kabsa\Traits;

use Illuminate\Database\Eloquent\Collection;


trait Kabsa
{
    /**
     * @var Collection
     */
    protected static $kabsaCollection;

    public static function bootKabsa()
    {
        self::unguard();
    }

    public function getRows()
    {
        return $this->rows;
    }

    public static function all($columns = [])
    {
        if(!empty(static::$kabsaCollection)) {
            return static::$kabsaCollection;
        }

        return static::$kabsaCollection = Collection::make(
            (new static)->getRows() ?? [])
            ->map(function ($row) {
                return new static($row);
            });
    }

    public static function addRow($row)
    {
        if(static::$kabsaCollection instanceof Collection) {
            static::$kabsaCollection->push(new static($row));
        } else {
            static::$kabsaCollection = Collection::make([new static($row)]);
        }

        return true;
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo(self::all(), $method, $parameters);
    }
}
