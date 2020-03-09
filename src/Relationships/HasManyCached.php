<?php

namespace Awssat\Kabsa\Relationships;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class HasManyCached extends HasOneOrMany
{
    protected $foreignKey;
    protected $ownerKey;
    protected $related;

    public function __construct($related, Model $parent, $foreignKey = null, $ownerKey = null)
    {
        $this->foreignKey = $foreignKey;
        $this->ownerKey = $ownerKey;
        $this->related = $related;

        parent::__construct((new $related)->newQuery(), $parent, $foreignKey, $ownerKey);
    }

    public function addConstraints()
    {
        //call_user_func($this->baseConstraints, $this);
    }

    public function getResults()
    {
        return $this->related::where($this->foreignKey,
            $this->parent->{$this->ownerKey});
    }

    public function addEagerConstraints(array $models)
    {
// not implemented yet
    }

    public function initRelation(array $models, $relation)
    {
// not implemented yet
    }

    public function match(array $models, Collection $results, $relation)
    {
// not implemented yet
    }


}
