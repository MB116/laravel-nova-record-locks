<?php

namespace Douma\RecordLocks\Actions;

use Douma\RecordLocks\Repositories\RecordLockRepository;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class RemoveAllLocks extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $standalone = true;

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public $name  = 'Разблокировать все материалы';

    private $lockRepository;

    public function __construct()
    {
        $this->lockRepository = app()->build(RecordLockRepository::class);
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach($models as $model) {
            $this->lockRepository->clear();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
