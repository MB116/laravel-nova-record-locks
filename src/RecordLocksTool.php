<?php

namespace Douma\RecordLocks;

use Douma\RecordLocks\Resources\RecordLocks;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class RecordLocksTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     */
    public function boot()
    {
        Nova::resources([
            RecordLocks::class
        ]);
    }
}
