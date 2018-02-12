<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Illuminate\Support\Facades\Route;
use SlimKit\PlusQuestion\Admin\Controllers as Admin;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'question-admin'], function (RouteRegisterContract $route) {

    // Home router.
    $route->get('/', Admin\HomeController::class.'@index')->name('plus-question::admin');

    // Get Question & Answer switch
    // @get /question-admin/switch
    $route->get('/switch', Admin\HomeController::class.'@switch');

    // Store Question & Answer switch.
    // @put /question-admin/switch
    $route->put('/switch', Admin\HomeController::class.'@store');

    // Statistics.
    // @get /question-admin/statistics
    $route->get('/statistics', Admin\StatisticsController::class.'@show');

    // Questions
    // @Route /question-admin/questions
    $route->group(['prefix' => 'questions'], function (RouteRegisterContract $route) {

        // Get questions list.
        // @get /question-admin/questions
        $route->get('/', Admin\QuestionsController::class.'@index');

        // Get a question.
        // @get /question-admin/questions/:question
        $route->get('/{question}', Admin\QuestionsController::class.'@show');

        // Update question.
        // @patch /question-admin/questions/:question
        $route->patch('/{question}', Admin\QuestionsController::class.'@update');

        // Destroy a question.
        // @delete /question-admin/questions/:question
        $route->delete('/{question}', Admin\QuestionsController::class.'@destroy');

        // Restore a question.
        // @put /question-admin/questions/:question
        $route->put('/{question}', Admin\QuestionsController::class.'@restore');
    });

    // Answer.
    // @Route /question-admin/answers
    $route->group(['prefix' => 'answers'], function (RouteRegisterContract $route) {

        // Get answers list.
        // @get /question-admin/answers
        $route->get('/', Admin\AnswerController::class.'@index');

        // Destroy a answer.
        // @delete /question-admin/answers/:answer
        $route->delete('/{answer}', Admin\AnswerController::class.'@destroy');

        // Restore a answer.
        // @put /question-admin/answers/:answer
        $route->put('/{answer}', Admin\AnswerController::class.'@restore');
    });

    // Comments.
    // @Route /question-admin/comments
    $route->group(['prefix' => 'comments'], function (RouteRegisterContract $route) {

        // Get comments list.
        // @get /question-admin/comments
        $route->get('/', Admin\CommentController::class.'@index');

        // Delete an comment.
        // @delete /question-admin/comments/:comment
        $route->delete('/{comment}', Admin\CommentController::class.'@delete');
    });

    // application records.
    // @Route /question-admin/application-records
    $route->group(['prefix' => 'application-records'], function (RouteRegisterContract $route) {

        // Get application records.
        // @get /question-admin/application-records
        $route->get('/', Admin\QuestionApplicationRecordController::class.'@index');

        // Accept an application.
        // @patch /question-admin/application-records/:application
        $route->patch('/{application}', Admin\QuestionApplicationRecordController::class.'@accept');

        // Reject an application.
        // @get /question-admin/application-records/:application
        $route->delete('/{application}', Admin\QuestionApplicationRecordController::class.'@reject');
    });

    // Topics
    // @Route /question-admin/topics
    $route->group(['prefix' => 'topics'], function (RouteRegisterContract $route) {

        // Get list of topics
        // @get /question-admin/topics
        $route->get('/', Admin\TopicController::class.'@index');

        // Add a topic.
        // @post /question-admin/topics
        $route->post('/', Admin\TopicController::class.'@store');

        // Upload avatar for a topic.
        // @post /question-admin/topics/:topic/avatar
        $route->post('/{topic}/avatar', Admin\TopicController::class.'@storeAvatar');

        // Add experts for a topic
        // @post /question-admin/topics/:topic/experts
        $route->post('/{topic}/experts/{expert}', Admin\TopicController::class.'@storeExperts');

        // Get single topic
        // @get /question-admin/topics/:topic
        $route->get('/{topic}', Admin\TopicController::class.'@show');

        // Update topic info.
        // @patch /question-admin/topics/:topic
        $route->patch('/{topic}', Admin\TopicController::class.'@update');

        // delete a topic
        // @delete /question-admin/topics/:topic
        $route->delete('/{topic}', Admin\TopicController::class.'@delete');

        // Get list of topics`s followers
        // @get /question-admin/topics/:topic/followers
        $route->get('/{topic}/followers', Admin\TopicController::class.'@followers');

        // Remove a follower of topic.
        // @delete /question-admin/topics/:topic/followers/:user
        $route->delete('/{topic}/followers/{user}', Admin\TopicController::class.'@removeFollowers');

        // Get list of topics`s experts
        // @get /question-admin/topics/:topic/experts
        $route->get('/{topic}/experts', Admin\TopicController::class.'@experts');

        // Remove a expert of topic.
        // @delete /question-admin/topics/:topic/experts/:user
        $route->delete('/{topic}/experts/{user}', Admin\TopicController::class.'@removeExperts');

        $route->patch('/{topic}/experts/{user}/sort', Admin\TopicController::class.'@sortExperts');

        $route->patch('/{topic}/sort', Admin\TopicController::class.'@sort');

        $route->patch('/{topic}/status', Admin\TopicController::class.'@status');
    });

    // topic-application-records
    // @Route /question-admin/topic-application-records
    $route->group(['prefix' => 'topic-application-records'], function (RouteRegisterContract $route) {

        // list of topic applications.
        // get /question-admin/topic-application-records
        $route->get('/', Admin\TopicApplicationRecordController::class.'@index');

        // delete a topic applications.
        // delete /question-admin/topic-application-records/:topicApplication
        $route->delete('/{topicApplication}', Admin\TopicApplicationRecordController::class.'@delete');

        // Accept a topic applications.
        // get /question-admin/topic-application-records/:topicApplication/accept
        $route->put('/{topicApplication}/accept', Admin\TopicApplicationRecordController::class.'@accept');

        // Reject a topic applications.
        // get /question-admin/topic-application-records/:topicApplication/reject
        $route->put('/{topicApplication}/reject', Admin\TopicApplicationRecordController::class.'@reject');
    });
});
