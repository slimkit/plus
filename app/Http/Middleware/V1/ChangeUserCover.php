<?php

namespace Zhiyi\Plus\Http\Middleware\V1;

use Closure;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\FileWith;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Models\UserProfileSetting;
use Zhiyi\Plus\Traits\CreateJsonResponseData;

class ChangeUserCover
{
    use CreateJsonResponseData;

    /**
     * Modify the user background image middleware entry.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $file_with_id = $request->input('cover_storage_task_id');
        if (! $file_with_id) {
            return $next($request);
        }

        $user = $request->user();

        return $this->userProfileExiste($user, $file_with_id, $next, $request);
    }

    /**
     * 检查用户拓展字段是否存在.
     *
     * @param User        $user 用户模型
     * @param StorageTask $task 任务模型
     * @param Closure     $next
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function userProfileExiste(User $user, int $file_with_id, Closure $next, Request $request)
    {
        $profile = UserProfileSetting::where('profile', 'cover')->first();
        if (! $profile) {
            return response()->json(static::createJsonData([
                'code' => 1017,
            ]))->setStatusCode(500);
        }

        return $this->setUserProfile($user, $profile->id, $file_with_id, $next, $request);
    }

    /**
     * 保存用户头像信息.
     *
     * @param User    $user      用户模型
     * @param int     $profileId 字段id
     * @param int     $storageId 储存ID
     * @param Closure $next
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function setUserProfile(User $user, int $profileId, int $file_with_id, Closure $next, Request $request)
    {
        $file_with = FileWith::find($file_with_id);
        if (! $file_with) {
            return response()->json(static::createJsonData([
                'code' => 1017,
            ]))->setStatusCode(500);
        }
        $data = [
            $profileId => $file_with_id,
        ];
        DB::transaction(function () use ($user, $data, $file_with) {
            $user->syncData($data);
            $this->setFileWith($file_with, $user);
        });

        return $next($request);
    }

    protected function setFileWith(FileWith $file_with, User $user)
    {
        $file_with->channel = 'user:cover';
        $file_with->raw = $user->id;
        $file_with->save();
    }
}
