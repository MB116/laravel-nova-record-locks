<?php

namespace Douma\RecordLocks\Fields;

use Laravel\Nova\Fields\Image;

class LockedBy extends Image
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

        if(isset($select[0])) {
            $callback = function () use ($resource, $attribute, $select) {
                $user = auth()->user()->id;
                return ($select[0]->user_id == $user) ? 'https://i.imgur.com/CXdZka4.png' : 'https://i.imgur.com/h6KHI23.png';
            };
            $this->preview($callback)->thumbnail($callback);
        } else
        {
            $callback = function () use ($resource, $attribute, $select) {
                $user = auth()->user()->id;
                return 'https://i.imgur.com/MfFWDSm.png';
            };
            $this->preview($callback)->thumbnail($callback);
        }
    }
}
