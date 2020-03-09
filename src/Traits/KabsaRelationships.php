<?php


namespace Awssat\Kabsa\Traits;

use Awssat\Kabsa\Relationships\BelongsToCached;
use Awssat\Kabsa\Relationships\HasManyCached;

trait KabsaRelationships
{
    public function belongsToKabsaRow($related,  $foreignKey = null, $ownerKey = null)
    {
        return new BelongsToCached($related, $this, $foreignKey, $ownerKey);
    }

    public function hasManyKabsaRows($related,  $foreignKey = null, $ownerKey = null)
    {
        return new HasManyCached($related, $this, $foreignKey, $ownerKey);
    }
}