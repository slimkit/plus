<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;


class Comment extends CommentModel
{

	public function special()
	{
		return $this->belongsTo(MusicSpecial::class, 'commentable_id', 'id');
	}

	public function music()
	{
		return $this->belongsTo(Music::class, 'commentable_id', 'id');
	}
}