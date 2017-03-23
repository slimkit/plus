<?php

namespace Zhiyi\Plus\Traits;

use Zhiyi\Plus\Models\Comment;
use Illuminate\Support\Facades\DB;

trait CommentStatistics
{
    public static function boot()
    {
        parent::boot();
    }

    /**
     * 同步创建.
     * @author bs<414606094@qq.com>
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    public function save(array $options = [])
    {
        $component = $this->component;
        unset($this->component);

        $datas = new Comment();
        $datas->component = $component;

        $datas->user_id = $this->user_id ?? 0;
        $datas->to_user_id = $this->to_user_id ?? 0;
        $datas->reply_to_user_id = $this->reply_to_user_id ?? 0;

        DB::transaction(function () use ($options, $datas) {
            parent::save($options);

            $datas->comment_id = $this->id;
            $datas->save();
        });

        return $this;
    }

    // 同步删除
    public function delete()
    {
        $component = $this->component;
        unset($this->component);

        DB::transaction(function () use ($component) {
            parent::delete();
            Comment::where(['component' => $component, 'comment_id' => $this->id])->delete();
        });

        return $this;
    }
}
