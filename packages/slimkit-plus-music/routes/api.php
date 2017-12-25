<?php
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Middleware as MusicMiddleware;

//最新分享列表
// 获取专辑
Route::get('/music/specials', 'MusicSpecialController@getSpecialList');
// 专辑详情
Route::get('/music/specials/{special_id}', 'MusicSpecialController@getSpecialInfo')->where(['special_id' => '[0-9]+']);
// 歌曲详情
Route::get('/music/{music_id}', 'MusicController@getMusicInfo')->where(['music_id' => '[0-9]+']);

Route::group([
	'middleware' => [
		'auth:api',
	]
], function() {
	// 添加歌曲评论
	Route::post('/music/{music_id}/comment', 'MusicCommentController@addComment')
		->middleware('role-permissions:music-comment,你没有评论歌曲的权限')
		->middleware(MusicMiddleware\VerifyCommentContent::class); // 验证评论内容
	// 查看歌曲评论列表
	Route::get('/music/{music_id}/comment', 'MusicCommentController@getMusicCommentList');
	// 添加专辑评论
	Route::post('/music/special/{special_id}/comment', 'MusicCommentController@addSpecialComment')
		->middleware('role-permissions:music-comment,你没有评论歌曲的权限')
		->middleware(MusicMiddleware\VerifyCommentContent::class); // 验证评论内容
	// 分享歌曲统计
	Route::patch('/music/{music_id}/share', 'MusicController@share');
	// 分享专辑统计
	Route::patch('/music/special/{special_id}/share', 'MusicSpecialController@share');
	// 查看专辑评论列表
	Route::get('/music/special/{special_id}/comment', 'MusicCommentController@getSpecialCommentList');
	//删除评论 TODO 根据权限及实际需求增加中间件
	Route::delete('/music/comment/{comment_id}', 'MusicCommentController@delComment');
	// 点赞
	Route::post('/music/{music}/digg', 'MusicDiggController@diggMusic')
		->middleware('role-permissions:music-digg,你没有点赞歌曲的权限');
	// 取消点赞
	Route::delete('/music/{music}/digg', 'MusicDiggController@cancelDiggMusic');
	// 我的收藏列表
	Route::get('/music/special/collections', 'MusicSpecialController@getCollectionSpecialList');
	// 收藏
	Route::post('/music/special/{special_id}/collection', 'MusicCollectionController@addMusicCollection')
		->middleware('role-permissions:music-collection,你没有收藏歌曲的权限');
	// 取消收藏
	Route::delete('/music/special/{special_id}/collection', 'MusicCollectionController@delMusicCollection');
});