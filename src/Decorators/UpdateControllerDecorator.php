<?php

namespace Douma\RecordLocks\Decorators;

use App\RecordLock;
use Douma\RecordLocks\Repositories\RecordLockRepository;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\DeleteResourceRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UpdateControllerDecorator extends Controller
{
    private $controller;
    private $lockRepository;

    public function __construct(Controller $controller, RecordLockRepository $lockRepository)
    {
        $this->controller = $controller;
        $this->lockRepository = $lockRepository;
    }

    public function index(NovaRequest $request)
    {
        $resource = $request->newResourceWith($request->findModelOrFail());
        $id = $resource->model()->id;
        $select = $this->lockRepository->getLocksNotFromUser(
            get_class($resource->model()), $id, auth()->user()->id
        );
        if(isset($select[0])) {
            throw new HttpException(403);
        }

        if(in_array(get_class($resource->model()), config('record_locks.enabled'))) {
            $this->lockRepository->create(get_class($resource->model()), $id, auth()->user()->id);
        }

        return $this->controller->index($request);
    }

    public function handle(DeleteResourceRequest $request)
    {
        $request->chunks(150, function ($models) use ($request) {
            $models->each(function ($model) use ($request) {
                $userId = auth()->user()->id;
                $select = $this->lockRepository->getLocksNotFromUser(get_class($model), $model->id, $userId);
                if(isset($select[0])) {
                    throw new HttpException(403);
                }
            });
        });

        return $this->controller->handle($request);
    }
}
