<?php

use Zhiyi\Plus\Http\Middleware;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Middleware as FeedMiddleware;

//最新分享列表
Route::get('/feeds', 'FeedController@getNewFeeds');
//热门分享列表
Route::get('/feeds/hots', 'FeedController@getHotFeeds');
// 某个用户的分享列表
Route::get('/feeds/users/{user_id}', 'FeedController@getUserFeeds');
//获取一条动态的赞的用户列表
Route::get('/feeds/{feed_id}/diggusers', 'FeedDiggController@getDiggList');
//获取一条动态的评论列表
Route::get('/feeds/{feed_id}/comments', 'FeedCommentController@getFeedCommentList');
//分享详情
Route::get('/feeds/{feed_id}', 'FeedController@read')->where(['feed_id' => '[0-9]+']);

Route::group([
    'middleware' => [
        'auth:api',
    ],
], function () {
    // 发送分享
    Route::post('/feeds', 'FeedController@store')
        ->middleware('role-permissions:feed-post,你没有发布分享的权限');
    // 删除分享
    Route::delete('/feeds/{feed_id}', 'FeedController@delFeed')->where(['feed_id' => '[0-9]+']);
    // 增加分享浏览量
    Route::post('/feeds/{feed_id}/viewcount', 'FeedController@addFeedViewCount');
    //我关注的分享列表
    Route::get('/feeds/follows', 'FeedController@getFollowFeeds');
    // 我的收藏列表
    Route::get('/feeds/collections', 'FeedController@getUserCollection');
    // 添加评论
    Route::post('/feeds/{feed_id}/comment', 'FeedCommentController@addComment')
        ->middleware('role-permissions:feed-comment,你没有评论分享的权限')
        ->middleware(FeedMiddleware\VerifyCommentContent::class); // 验证评论内容
    // 获取评论
    Route::get('/feeds/comments', 'FeedCommentController@getComment');
    // 删除分享
    // Route::delete('/feeds/{feed_id}', 'xxx@xxx');
    //删除评论 TODO 根据权限及实际需求增加中间件
    Route::delete('/feeds/{feed_id}/comment/{comment_id}', 'FeedCommentController@delComment');
    // 我收到的评论
    Route::get('/feeds/commentmes', 'FeedCommentController@myComment');
    // 我收到的点赞
    Route::get('/feeds/diggmes', 'FeedDiggController@mydiggs');
    // 点赞
    Route::post('/feeds/{feed_id}/digg', 'FeedDiggController@diggFeed')
        ->middleware('role-permissions:feed-digg,你没有点赞分享的权限');
    // 取消点赞
    Route::delete('/feeds/{feed_id}/digg', 'FeedDiggController@cancelDiggFeed');
    // 收藏
    Route::post('/feeds/{feed_id}/collection', 'FeedCollectionController@addFeedCollection')
        ->middleware('role-permissions:feed-collection,你没有收藏分享的权限');
    // 取消收藏
    Route::delete('/feeds/{feed_id}/collection', 'FeedCollectionController@delFeedCollection');
    //获取@我的分享列表
    Route::get('/feeds/atmes', 'FeedAtmeController@getAtmeList');
});
