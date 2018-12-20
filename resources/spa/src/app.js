// vuex utils
import { mapActions, mapState } from 'vuex'
import CONST from './constants/app'

// components
import PhotoSwipe from './components/PhotoSwipe'
import PostMenu from './page/PostMenu.vue'
import PayFor from './components/PayFor.vue'
import ActionSheet from './components/ActionSheet.vue'
import CommentInput from './components/CommentInput.vue'
import CheckIn from './page/checkin/CheckIn.vue'
import Reward from './components/Reward.vue'
import Report from './components/Report.vue'
import ChooseTags from './page/ChooseTags.vue'
import ApplyTop from './components/ApplyForTop.vue'
import PopupDialog from './components/PopupDialog.vue'
import ChatWithQQ from './components/vendor/ChatWithQQ.vue'

export default {
  render () {
    return (
      <div id="app" class="wap-wrap">
        <keep-alive>{this.keepAlive && <router-view />}</keep-alive>
        {!this.keepAlive && <router-view />}
        <div>
          <PhotoSwipe />
          <PostMenu />
          <PayFor />
          <ActionSheet />
          <CommentInput />
          <CheckIn />
          <Reward />
          <Report />
          <ChooseTags />
          <ApplyTop />
          <PopupDialog />
          {CONST.ENABLE_QQ_CONSULT && <ChatWithQQ />}
        </div>
      </div>
    )
  },
  name: 'App',
  /**
   * The App data.
   *
   * @return {Object}
   */
  data: () => ({
    title: 'Plus (ThinkSNS+)',
  }),
  computed: {
    keepAlive () {
      return this.$route.meta.keepAlive || false
    },
    ...mapState({
      /**
       * Global user id.
       *
       * @param  {Object} state
       *
       * @return {number}
       */
      UID: state => state.CURRENTUSER.id,
      /**
       * Easemob status.
       *
       * @param  {Object} state
       *
       */
      status: state => state.EASEMOB.status,
    }),
  },
  watch: {
    /**
     * `$route` watcher.
     *
     * @param  {Object} newRoute
     *
     * @return {void}
     */
    $route (newRoute) {
      let { title } = newRoute.meta || {}

      if (title) {
        this.title = title
      }
    },
    /**
     * Set document title.
     *
     * @param  {string} newTitle
     *
     * @return {void}
     */
    title (newTitle) {
      if (newTitle) {
        document.title = newTitle
      }
    },
  },
  methods: mapActions({
    bootstrapHandle: 'BOOTSTRAPPERS',
  }),
  /**
   * The created hook.
   * @return {void}
   */
  created () {
    this.bootstrapHandle()
  },
}
