<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

/**
 *
 */
trait TableColumn
{
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
