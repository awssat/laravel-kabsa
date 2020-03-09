<?php

namespace Awssat\Kabsa\Traits;

use Illuminate\Database\Eloquent\Collection;


trait Kabsa
{
    public static function all($columns = [])
    {
        self::unguard();
        $self = new self();
        return Collection::make($self->rows ?? [])->map(fn ($row) => new self($row));
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo(self::all(), $method, $parameters);
    }
}
