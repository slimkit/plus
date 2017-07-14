<?php

namespace Zhiyi\Plus\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\FilesystemManager;
use Zhiyi\Plus\Contracts\Model\ShouldAvatar as ShouldAvatarContract;

trait HasAvatar
{
    protected $avatar_extensions = ['svg', 'png', 'jpeg', 'gif', 'bmp'];
    protected $avatar_prefix = 'avatars';

    public static function bootHasAvatar()
    {
        if (! (new static) instanceof ShouldAvatarContract) {
            throw new \Exception(sprintf('使用"HasAvatar"性状必须实现"%s"契约', ShouldAvatarContract::class));
        }
    }

    public function avatar(int $size = 0, string $prefix = 'avatars')
    {
        $path = $this->avatarFilename($prefix);
    }

    public function avatarFilename(string $prefix = 'avatars')
    {
        if ($prefix && $this->avatar_prefix !== $prefix) {
            $this->avatar_prefix = $prefix;
        }

        $path = $this->makeAvatarPath();

        $files = $this->filesystem()->files($path);

        dd($files);

        
        return false;
    }

    /**
     * Store avatar.
     *
     * @param UploadedFile $file
     * @return string|false
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function storeAvatar(UploadedFile $file)
    {
        $extension = strtolower($file->extension());
        if (! in_array($extension, $this->avatar_extensions)) {
            throw new \Exception('保存的头像格式不符合要求');
        }

        $filename = $this->makeAvatarPath();
        $path = pathinfo($filename, PATHINFO_DIRNAME);
        $name = pathinfo($filename, PATHINFO_BASENAME).'.'.$extension;

        return $file->storeAs($path, $name, 'public');
    }

    /**
     * make avatar file path.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeAvatarPath(): string
    {
        $filename = strval($this->getAvatarKey());
        if (strlen($filename) < 11) {
            $filename = str_pad($filename, 11, '0', STR_PAD_LEFT);
        }

        return sprintf(
            '%s/%s/%s/%s/%s',
            $this->avatar_prefix,
            substr($filename, 0, 3),
            substr($filename, 3, 3),
            substr($filename, 6, 3),
            substr($filename, 9)
        );
    }

    /**
     *  Get filesystem.
     *
     * @return \Illuminate\Filesystem\FilesystemManager
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function filesystem(): FilesystemManager
    {
        return app(FilesystemManager::class);
    }
}
