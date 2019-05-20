<?php

namespace Douma\RecordLocks\Decorators;
use App\User;
use Douma\RecordLocks\Repositories\RecordLockRepository;

class RecordLockPolicyDecorator
{
    private $policy;
    private $lockRepository;

    public function __construct($policy, RecordLockRepository $lockRepository)
    {
        $this->policy = $policy;
        $this->lockRepository = $lockRepository;
    }

    public function view(User $user, $resource)
    {
        if($this->_hasLocksNotFromUser($resource, $user)) {
            return false;
        }
        return $this->policy->view(...func_get_args());
    }

    public function create(User $user)
    {
        return $this->policy->create(...func_get_args());
    }

    public function update(User $user, $resource)
    {
        if($this->_hasLocksNotFromUser($resource, $user)) {
            return false;
        }
        return $this->policy->update(...func_get_args());
    }

    public function delete(User $user, $resource)
    {
        if($this->_hasLocksNotFromUser($resource, $user)) {
            return false;
        }
        return $this->policy->delete(...func_get_args());
    }

    public function restore(User $user, $post)
    {
        return $this->policy->restore(...func_get_args());
    }

    public function forceDelete(User $user, $post)
    {
        return $this->policy->forceDelete(...func_get_args());
    }

    private function _hasLocksNotFromUser($resource, $user)
    {
        $select = $this->lockRepository->getLocksNotFromUser(
            get_class($resource), $resource->id, $user->id
        );

        if(isset($select[0])) {
            return true;
        }
        return false;
    }
}