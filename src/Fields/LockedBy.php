<?php

namespace Douma\RecordLocks\Fields;

use Laravel\Nova\Fields\Text;

class LockedBy extends Text
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
            $callback = function () use ($resource, $attribute, $select) {
                $user = auth()->user();
                return ($select[0]->user_id == $user->id) ? 'Заблокированно вами' : 'Заблокированно ' . $user->id;
            };
            $this->withMeta('value', $callback);
        } else {
            $callback = function () use ($resource, $attribute, $select) {
                $user = auth()->user()->name;
                return $user;
            };
            $this->withMeta('value', $callback);
        }
    }
}
