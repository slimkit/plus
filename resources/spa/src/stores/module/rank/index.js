import getters from './getters'
import actions from './actions'
import mutations from './mutations'
const state = {
  rankQuestionExperts: [],
  rankIncome: [],
  rankQuestionLikes: [],
  rankFollowers: [],
  rankQuestionsMonth: [],
  rankQuestionsWeek: [],
  rankQuestionsToday: [],
  rankFeedsMonth: [],
  rankFeedsWeek: [],
  rankFeedsToday: [],
  rankNewsMonth: [],
  rankNewsWeek: [],
  rankNewsToday: [],
  rankBalance: [],
  rankCheckin: [],
}

export default {
  state,
  getters,
  actions,
  mutations,
}
