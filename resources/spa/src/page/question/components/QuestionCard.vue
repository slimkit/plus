<template>
  <div class="c-question-card">
    <!-- The question title. -->
    <RouterLink
      :to="`/questions/${question.id}`"
      tag="h3"
      class="title"
    >
      {{ question.subject }}
      <span
        v-show="question.excellent && !noExcellent"
        class="shang-i"
      >
        精
      </span>
    </RouterLink>

    <!-- The question first image. -->
    <RouterLink
      v-if="firstImageStyle"
      :style="firstImageStyle"
      :to="`/questions/${question.id}`"
      tag="div"
      class="image"
    />

    <!-- Answer. -->
    <QuestionListAnswerCard
      v-if="Object.keys(answer).length"
      :answer="answer"
    />

    <!-- Bottom -->
    <RouterLink
      :to="`/questions/${question.id}`"
      class="button"
      tag="div"
    >
      <span class="button-style1">{{ question.watchers_count }}</span>
      关注
      <span class="dot">·</span>
      <span class="button-style1">{{ question.answers_count }}</span>
      回答
      <span class="dot">·</span>
      <span
        v-show="question.amount"
        class="shang"
      >
        <span>赏</span> {{ question.amount }}
      </span>
      <span class="button-time">{{ question.updated_at | time2tips }}</span>
    </RouterLink>
  </div>
</template>

<script>
import QuestionListAnswerCard from './QuestionListAnswerCard.vue'
import { baseURL } from '@/api'
import { syntaxTextAndImage } from '@/util/markdown'

export default {
  name: 'QuestionCard',
  components: {
    QuestionListAnswerCard,
  },
  props: {
    question: { type: Object, required: true },
    noExcellent: { type: Boolean, default: false },
  },
  computed: {
    /**
     * Answer data.
     *
     * @return {Object|null}
     * @author Seven Du <shiweidu@outlook.com>
     */
    answer () {
      const { answer } = this.question
      return answer || {}
    },

    /**
     * Question body, Images and text contents.
     *
     * @return {Object: { images: Array, text: string }}
     * @author Seven Du <shiweidu@outlook.com>
     */
    body () {
      return syntaxTextAndImage(this.question.body)
    },

    /**
     * Question body first image style.
     *
     * @return {string|false}
     * @author Seven Du <shiweidu@outlook.com>
     */
    firstImageStyle () {
      const body = this.answer.body || ''
      const image = body.match(/@!\[image]\((\d+)\)/)

      if (!image) return false
      return `background-image: url(${baseURL}/files/${image[1]})`
    },
  },
}
</script>

<style lang="less" scoped>
.c-question-card {
  background: #fff;
  padding: 30px;
  margin-bottom: 10px;

  .title {
    margin: 0;
    margin-bottom: 30px;
    font-size: 32px;
    font-weight: normal;
    font-stretch: normal;
    color: #333;
    line-height: 1.4;
  }

  .image {
    width: calc(~"100% + 60px");
    height: 300px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    margin-bottom: 30px;
    margin-left: -30px;
  }

  .button {
    width: 100%;
    font-size: 28px;
    font-weight: normal;
    font-stretch: normal;
    letter-spacing: 0px;
    color: #999999;
    &-time {
      float: right;
    }
    &-style1 {
      color: #58b6d7;
    }

    .dot {
      margin: 0 10px 0 0;
    }
  }

  .shang {
    // margin-left: 10px;
    color: #fca308;

    &-i,
    span {
      color: #fca308;
      width: 20px;
      height: 21px;
      font-size: 22px;
      font-weight: normal;
      font-stretch: normal;
      line-height: 0px;
      letter-spacing: 0px;
      border: solid 1px #fca308; /* no */
      padding: 0 4px;
      border-radius: 6px;
    }
  }
}
</style>
