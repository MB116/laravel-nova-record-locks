<?php
namespace Douma\RecordLocks\Decorators;

use Douma\RecordLocks\Repositories\RecordLockRepository;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\UpdateResourceRequest;

class ResourceUpdateControllerDecorator extends Controller
{
    private $controller;
    private $lockRepository;

    public function __construct(Controller $controller, RecordLockRepository $lockRepository)
    {
        $this->controller = $controller;
        $this->lockRepository = $lockRepository;
    }

    public function handle(UpdateResourceRequest $request)
    {
        if($response = $this->controller->handle($request))
        {
            $resource = $request->newResourceWith($request->findModelOrFail());
            $this->lockRepository->deleteLocks(get_class($resource->model()), $resource->model()->id);
            return $response;
        }
    }
}
