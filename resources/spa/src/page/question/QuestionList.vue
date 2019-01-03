<template>
  <div class="p-question-list" @click.capture.stop.prevent="popupBuyTS">
    <!-- Question navs. -->
    <nav class="nav">
      <RouterLink
        to="/question"
        replace
        exact
        exact-active-class="active"
      >
        {{ $t('hot') }}
      </RouterLink>
      <RouterLink
        :to="navRouterLinkBuilder('excellent')"
        replace
        exact
        exact-active-class="active"
      >
        {{ $t('excellent') }}
      </RouterLink>
      <RouterLink
        :to="navRouterLinkBuilder('reward')"
        replace
        exact
        exact-active-class="active"
      >
        {{ $t('group.reward') }}
      </RouterLink>
      <RouterLink
        :to="navRouterLinkBuilder('new')"
        replace
        exact
        exact-active-class="active"
      >
        {{ $t('newest') }}
      </RouterLink>
      <RouterLink
        :to="navRouterLinkBuilder('all')"
        replace
        exact
        exact-active-class="active"
      >
        {{ $t('all') }}
      </RouterLink>
    </nav>

    <!-- Question main. -->
    <main class="main">
      <div v-if="loading" class="main-loading">
        <IconLoading class="main-loading_icon" />
      </div>

      <QuestionCard
        v-for="question in questions"
        :key="question.id"
        :question="question"
        :no-excellent="type === 'excellent'"
      />

      <div v-if="questions.length && !loadmore" class="main-loadmore">
        <button class="main-loadmore_button" @click="fetchQuestionsMore">
          {{ $t('loadmore.bottom[2]') }}
        </button>
      </div>

      <div v-else-if="loadmore" class="main-loadmore">
        <button class="main-loadmore_button active">
          <IconLoading class="main-loading_icon" />
        </button>
      </div>
    </main>

    <button class="create-question">
      <svg class="m-style-svg m-svg-small">
        <use xlink:href="#icon-plus" />
      </svg>
    </button>
  </div>
</template>

<script>
import LinearLoading from '@/icons/LinearLoading.vue'
import QuestionCard from './components/QuestionCard.vue'
import { list } from '@/api/questions'

export default {
  name: 'QuestionList',
  components: {
    QuestionCard,
    IconLoading: LinearLoading,
  },
  data () {
    return {
      questions: [],
      loading: false,
      loadmore: false,
    }
  },
  computed: {
    type () {
      const { type = 'hot' } = this.$route.query
      return type
    },
  },
  watch: {
    $route (to, from) {
      if (
        (to.path === from.path && to.query.type !== from.query.type) ||
        !from.query.type // 后退再进入时重新拉取数据
      ) { this.fetchQuestions() }
    },
  },
  mounted () {
    this.fetchQuestions()
  },
  methods: {
    /**
     * Nav router link builder.
     *
     * @param {string} type
     * @return {Object}
     * @author Seven Du <shiweidu@outlook.com>
     */
    navRouterLinkBuilder (type) {
      return {
        path: '/question',
        query: { type },
      }
    },

    /**
     * Fetch question method.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    fetchQuestions () {
      this.loading = true
      this.questions = []
      list(this.type)
        .then(({ data }) => {
          this.questions = data
          this.loading = false
        })
        .catch(({ response: { data } = {} }) => {
          this.loading = false
        })
    },

    fetchQuestionsMore () {
      this.loadmore = true
      list(this.type, this.questions.length + 1)
        .then(({ data }) => {
          this.loadmore = false
          if (!data.length) return

          this.questions = [...this.questions, ...data]
        })
        .catch(({ response: { data } = {} }) => {
          this.loadmore = false
        })
    },
  },
}
</script>

<style lang="less" scoped>
.p-question-list {
  padding-top: 180px;
  padding-bottom: 100px;
  min-height: 100vh;

  .nav {
    position: fixed;
    top: 90px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 100%;
    max-width: 768px;
    height: 90px;
    background: #fff;
    color: #999;
    font-size: 30px;
    border-bottom: solid 1px #dedede; /* no */
    z-index: 10;

    > a {
      color: #999;
    }

    .active {
      color: #333;
    }
  }

  .main {
    .main-loading {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 24px;

      &_icon {
        width: 120px;
        height: 30px;
        fill: #58b6d7;
      }
    }
    .main-loadmore {
      margin: 24px auto;
      text-align: center;

      &_button {
        padding: 12px 24px;
        color: #58b6d7;
        background-color: transparent;
        border: solid 1px #58b6d7; /* no */
        border-radius: 6px;
        outline: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;

        &.active {
          color: #aaa;
          border: none;
        }
      }
    }
  }

  .create-question {
    position: fixed;
    bottom: 40px;
    right: 40px;
    height: 60px;
    width: 60px;
    padding: 0;
    border-radius: 100%;
    background-color: @primary;
    border: 2px solid #fff; /* no */
    box-shadow: 0px 0px 12px 0px rgba(89, 182, 215, 0.43);
    z-index: 1;

    > svg {
      display: block;
      margin: auto;
      color: #fff;
    }
  }
}
</style>
