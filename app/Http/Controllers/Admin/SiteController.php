<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CommonConfig;

class SiteController extends Controller
{
    /**
     * The store CommonConfig instance.
     *
     * @var Zhiyi\Plus\Models\CommonConfig
     */
    protected $commonCinfigModel;

    /**
     * Get the website info.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function get()
    {
        $sites = $this->newCommonConfigModel()
            ->byNamespace('site')
            ->whereIn('name', ['title', 'keywords', 'description', 'icp'])
            ->get();

        return response()->json($sites)->setStatusCode(201);
    }

    /**
     * instance a new model.
     *
     * @return Zhiyi\Plus\Models\CommonConfig::newQuery
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function newCommonConfigModel()
    {
        if (!$this->commonCinfigModel instanceof CommonConfig) {
            $this->commonCinfigModel = new CommonConfig();
        }

        return $this->commonCinfigModel->newQuery();
    }
}
