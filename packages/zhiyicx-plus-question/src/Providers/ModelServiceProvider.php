<?php

namespace SlimKit\PlusQuestion\Providers;

use Zhiyi\Plus\Models\User;
use SlimKit\PlusQuestion\Models\Topic;
use Illuminate\Support\ServiceProvider;
use SlimKit\PlusQuestion\Models\Answer;
use SlimKit\PlusQuestion\Models\Question;
use SlimKit\PlusQuestion\Models\TopicUser;
use SlimKit\PlusQuestion\Models\TopicExpert;
use SlimKit\PlusQuestion\Models\QuestionWatcher;
use Illuminate\Database\Eloquent\Relations\Relation;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Register the model service.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        // Register morph map for polymorphic relations.
        $this->registerMorphMap();

        // Register user model macros tu the application.
        $this->registerUserMacros();
    }

    /**
     * Register user model macros tu the application.
     *
     * @return [type] [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerUserMacros()
    {
        // The user follow topics.
        User::macro('questionTopics', function () {
            return $this->belongsToMany(Topic::class, 'topic_user')
                ->using(TopicUser::class)
                ->withTimestamps();
        });

        // The user blong to topics.
        User::macro('belongTopics', function () {
            return $this->belongsToMany(Topic::class, 'topic_expert')
                ->using(TopicExpert::class)
                ->withTimestamps();
        });

        // Has questions for the user.
        User::macro('questions', function () {
            return $this->hasMany(Question::class, 'user_id', 'id');
        });

        // Has watching questions for the user.
        User::macro('watchingQuestions', function () {
            return $this->belongsToMany(Question::class, 'question_watcher')
                ->using(QuestionWatcher::class)
                ->withTimestamps();
        });
    }

    /**
     * Register morph map for polymorphic relations.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerMorphMap()
    {
        $this->morphMap([
            'questions' => Question::class,
            'question-answers' => Answer::class,
        ]);
    }

    /**
     * Set or get the morph map for polymorphic relations.
     *
     * @param array|null $map
     * @param bool $merge
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function morphMap(array $map = null, bool $merge = true): array
    {
        return Relation::morphMap($map, $merge);
    }
}
