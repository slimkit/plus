<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Observers\Fetch;

use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment;
use Zhiyi\Plus\Contracts\Model\FetchComment as CommentFetchConyract;

class CommentFetch implements CommentFetchConyract
{
    /**
     * Feed comment model.
     *
     * @var \Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment
     */
    protected $comment;

    /**
     * Create the comment fetch instance.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicComment $comment
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(MusicComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment centent.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getCommentContentAttribute(): string
    {
        return $this->comment->comment_content;
    }

    /**
     * Get target source display title.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetTitleAttribute(): string
    {
        return $this->musicInfo()->title ?? '';
    }

    /**
     * Get target source image file with ID.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetImageAttribute(): int
    {
        $info = $this->musicInfo();
        if ($info instanceof Music) {
            return $info->singer()->first()->cover;
        } 

        return $info->storage;
    }

    /**
     * Get target source id.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetIdAttribute(): int
    {
        return $this->musicInfo()->id ?? 0;
    }

    /**
     * Get the comment to feed.
     *
     * @return [type]
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function musicInfo()
    {
        if ($this->comment->music_id > 0) {
            $data = Music::find($this->comment->music_id);
        } else {
            $data = MusicSpecial::find($this->comment->special_id);
        }

        return $data;
    }
}
