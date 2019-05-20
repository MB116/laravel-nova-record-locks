<?php

namespace Douma\RecordLocks\Repositories;

use App\RecordLock;

class RecordLockRepository
{
    public function deleteLocks(string $model, int $id)
    {
        \DB::statement("DELETE FROM record_locks WHERE model = ? AND model_id = ?", [$model, $id]);
    }

    public function getLocksNotFromUser(string $model, int $id, int $userId)
    {
        return \DB::select("SELECT * FROM record_locks WHERE model = ? AND model_id = ? AND user_id <> ?", [
            $model, $id, $userId
        ]);
    }

    public function deleteForUser(string $model, int $id, int $userId)
    {
        \DB::statement("DELETE FROM record_locks WHERE model = ? AND model_id = ? AND user_id = ?", [
            $model, $id, $userId
        ]);
    }

    public function create(string $model, int $id, int $userId)
    {
        \DB::statement('REPLACE INTO record_locks (model, model_id, user_id) VALUES(?,?,?)', [
            $model, $id, $userId
        ]);
    }
}
