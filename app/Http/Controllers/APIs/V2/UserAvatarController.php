<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class UserAvatarController extends Controller
{
    /**
     * Show user avatar.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, ResponseContract $response, UserModel $user)
    {
        $size = intval($request->query('s', 0));
        $size = max($size, 0);
        $size = min($size, 500) === 500 ? 0 : $size;

        return $response->redirectTo($user->avatar($size));
    }

    /**
     * Upload user avatar.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(Request $request, ResponseContract $response)
    {
        $this->validate($request, $this->uploadAvatarRules(), $this->uploadAvatarMessages());

        $avatar = $request->file('avatar');
        if (! $avatar->isValid()) {
            return $response->json(['messages' => [$avatar->getErrorMessage()]], 400);
        }

        return $request->user()->storeAvatar($avatar)
            ? $response->make('', 204)
            : $response->json(['message' => ['上传失败']], 500);
    }

    /**
     * Get upload valodate rules.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function uploadAvatarRules(): array
    {
        return [
            'avatar' => [
                'required',
                'image',
                'max:'.$this->getMaxFilesize() / 1024,
                'dimensions:min_width=100,min_height=100,max_width=500,max_height=500,ratio=1/1',
            ],
        ];
    }

    /**
     * Get upload validate messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function uploadAvatarMessages(): array
    {
        return [
            'avatar.required' => '请上传头像.',
            'avatar.image' => '头像必须是 png/jpeg/bmp/gif/svg 图片',
            'avatar.max' => sprintf('头像尺寸必须小于%sMB', $this->getMaxFilesize() / 1024 / 1024),
            'avatar.dimensions' => '头像必须是正方形，宽高必须在 100px - 500px 之间',
        ];
    }

    /**
     * Get upload max file size.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getMaxFilesize()
    {
        return UploadedFile::getMaxFilesize();
    }
}
