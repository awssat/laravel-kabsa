<?php

namespace Awssat\Kabsa\Relationships;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BelongsToCached extends BelongsTo
{
    protected $foreignKey;
    protected $ownerKey;
    protected $related;

    public function __construct($related, Model $parent, $foreignKey = null, $ownerKey = null, $relationName = null)
    {
        $this->foreignKey = $foreignKey;
        $this->ownerKey = $ownerKey;
        $this->related = $related;

        parent::__construct((new $related)->newQuery(), $parent, $foreignKey, $ownerKey, $relationName);
    }

    public function getResults()
    {
        return $this->related::where($this->ownerKey,
            $this->parent->{$this->foreignKey})->first();
    }
}
