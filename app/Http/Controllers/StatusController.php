<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function store(Request $request)
    {
        return $request->user()
            ->statuses()
            ->create($request->only('title', 'slug', 'order'));
    }

    public function destroy(int $statusId)
    {
        $del_status = Status::find($statusId);
        $del_status->delete();

        return (200);
    }
}
