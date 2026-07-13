<?php

namespace App\Core\Traits;

trait HasFlashMessages
{
    protected function success($message)
    {
        return redirect()->back()->with('success', $message);
    }

    protected function error($message)
    {
        return redirect()->back()->with('error', $message);
    }
}
