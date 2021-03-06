<?php

namespace Douma\RecordLocks\Fields;

use Laravel\Nova\Fields\Line;

class LockedBy extends Line
{
    public function __construct($name = 'Is locked', $attribute = '', $resolveCallback = null)
    {
        parent::__construct($name, '', $resolveCallback);
        $this->exceptOnForms();
        $this->withMeta(['indexName' => '']);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $modelClass = get_class($resource);
        $modelId = $resource->id;

        $select = \DB::select("SELECT * FROM record_locks WHERE model = ? AND model_id = ?", [
            $modelClass,
            $modelId
        ]);

        if (isset($select[0])) {
            $lockedBy = \DB::table('users')->select('name')->where('id', $select[0]->user_id)->first();

            $currentUser = auth()->user()->id;
            $stauts = ($select[0]->user_id == $currentUser) ? 'Заблокированно вами' : 'Редактирует ' . $lockedBy->name;

            $this->withMeta(['value' => $stauts]);
        } else {
            $this->withMeta(['value' => 'Свободен']);
        }
    }
}
