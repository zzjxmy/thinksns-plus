<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Services\Storage;
use Zhiyi\Plus\Http\Controllers\Controller;

class StorageController extends Controller
{
    /**
     * 获取所有的储存引擎.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showEngines()
    {
        return response()->json(app(Storage::class)->getEngines())->setStatusCode(200);
    }
}