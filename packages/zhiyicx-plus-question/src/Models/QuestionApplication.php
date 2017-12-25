<?php

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class QuestionApplication extends Model
{
    protected $table = 'question_application';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 审核用户.
     *
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function examiner()
    {
        return $this->hasOne(User::class, 'id', 'examiner');
    }
}
