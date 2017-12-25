<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Installer;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\Ability;
use Illuminate\Support\Facades\Schema;
use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Database\Schema\Blueprint;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\base_path as component_base_path;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSinger;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;

class MusicPackageHandler extends PackageHandler
{
    public function defaultHandle($command)
    {
        $handle = $command->choice('Select handle', ['list','install', 'remove', 'checkstorage', 'quit'], 0);

        if ($handle !== 'quit') {
            return $command->call(
                $command->getName(),
                array_merge($command->argument(), ['handle' => $handle])
            );
        }
    }

    public function removeHandle($command)
    {
        if ($command->confirm('This will delete your datas for music')) {
            Comment::whereIn('commentable_type', ['musics', 'music_specials'])->delete();
            Ability::whereIn('name', ['music-comment', 'music-digg', 'music-collection'])->delete();
            Schema::dropIfExists('musics');
            Schema::dropIfExists('music_comments');
            Schema::dropIfExists('music_specials');
            Schema::dropIfExists('music_special_links');
            Schema::dropIfExists('music_diggs');
            Schema::dropIfExists('music_collections');
            Schema::dropIfExists('music_singers');

            return $command->info('The Music has been removed');
        }
    }

    public function installHandle($command)
    {
        if (!Schema::hasTable('musics')) {
            Schema::create('musics', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
                $table->softDeletes();
            });
            include component_base_path('/databases/table_musics_column.php');
        }

        if (!Schema::hasTable('music_specials')) {
            Schema::create('music_specials', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
                $table->softDeletes();
            });
            include component_base_path('/databases/table_music_specials_column.php');
        }

        if (!Schema::hasTable('music_special_links')) {
            Schema::create('music_special_links', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
            });
            include component_base_path('/databases/table_music_special_links_column.php');
        }

        if (!Schema::hasTable('music_comments')) {
            Schema::create('music_comments', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_music_comments_column.php');
        }

        if (!Schema::hasTable('music_diggs')) {
            Schema::create('music_diggs', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_music_diggs_column.php');
        }

        if (!Schema::hasTable('music_collections')) {
            Schema::create('music_collections', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_music_collections_column.php');
        }

        if (!Schema::hasTable('music_singers')) {
            Schema::create('music_singers', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id')->comment('主键');
                $table->timestamps();
                $table->softDeletes();
            });
            include component_base_path('/databases/table_music_singers_column.php');
        }

        $time = Carbon::now();

        Ability::insert([
            [
                'name' => 'music-comment',
                'display_name' => '评论歌曲',
                'description' => '用户评论歌曲权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'music-digg',
                'display_name' => '点赞歌曲',
                'description' => '用户点赞歌曲权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'music-collection',
                'display_name' => '收藏歌曲',
                'description' => '用户收藏歌曲权限',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);

        return $command->info('Music component install successfully');
    }
}
