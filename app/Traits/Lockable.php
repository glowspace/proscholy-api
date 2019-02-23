<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Log;

trait Lockable
{
    protected $expireTimeSeconds = 30;

    protected function isSameUser()
    {
        return Auth::user()->id === $this->updating_user_id;
    }

    public function lock($refresh = false)
    {
        if ($this->isLocked() && !$refresh) {
            // todo throw exception?
            Log::error('Disasllowed locking of a locked model.');
            return false;
        }

        $this->updating_at = Carbon::now();
        $this->updating_user_id = Auth::user()->id;
        $this->save();
        return true;
    }

    public function unlock()
    {
        if (!$this->isSameUser()) {
            Log::error('Not recognized unlocking of a locked model.');
            return false;
        }

        $this->updating_at = null;
        $this->updating_user_id = null;
        $this->save();
        return true;
    }

    public function isLocked($allowForSameUser = true)
    {
        if ($this->updating_at == null || 
            ($allowForSameUser && $this->isSameUser())) {
            return false;
        }

        if (Carbon::now()->diffInSeconds($this->updating_at) > $this->expireTimeSeconds) {
            // todo change to null
            return false;
        }
        return true;
    }
}

