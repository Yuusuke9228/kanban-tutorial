<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * ステータスを追加
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $request->user()
            ->statuses()
            ->create($request->only('title', 'slug', 'order'));
    }

    /**
     * @TODO ステータスを更新
     *
     * @param Request $request
     * @param Status $status
     */
    public function update(Request $request, Status $status)
    {
        //should be implemented.
    }

    /**
     * ステータスの並び順を更新
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sync(Request $request)
    {
        $this->validate(request(), [
            'columns' => ['required', 'array']
        ]);

        foreach ($request->columns as $i => $status) {
            $order = $i + 1;
            request()->user()->statuses()
                ->find($status['id'])
                ->update(['order' => $order]);
        }

        return $request->user()->statuses()->with('tasks')->get();
    }

    /**
     * ステータスを削除
     *
     * @param int $statusId
     * @return int
     */
    public function destroy(int $statusId)
    {
        $del_status = Status::find($statusId);
        $del_status->delete();

        return (200);
    }
}
