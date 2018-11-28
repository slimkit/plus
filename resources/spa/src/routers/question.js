import QuestionBase from '@/page/question/QuestionBase.vue'
import QuestionList from '@/page/question/QuestionList.vue'

export default [
  {
    path: '/question',
    component: QuestionBase,
    meta: {
      requiresAuth: true,
      keepAlive: true,
    },
    children: [
      {
        name: 'question',
        path: '',
        component: QuestionList,
        meta: { keepAlive: true },
      },
    ],
  },
]
