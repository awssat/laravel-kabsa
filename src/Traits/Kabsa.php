<?php

namespace Awssat\Kabsa\Traits;

use Illuminate\Database\Eloquent\Collection;


trait Kabsa
{
    static $kabsaCollection;
    
    public function getRows()
    {
        return $this->rows;
    }

    public static function all($columns = [])
    {
        if(!empty(static::$kabsaCollection)) {
            return static::$kabsaCollection;
        }
        
        self::unguard();
        $self = new self();

        return static::$kabsaCollection = Collection::make($self->getRows() ?? [])->map(function ($row) { return new self($row); });
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo(self::all(), $method, $parameters);
    }
}
