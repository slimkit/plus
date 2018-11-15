import _ from "lodash";
// import localEvent from 'store'
export default {
  SAVE_MESSAGE_UNREAD_COUNT(state, options) {
    state.UNREAD_COUNT = {
      ...state.UNREAD_COUNT,
      ...options
    };
  },
  SAVE_NEW_UNREAD_COUNT(state, counts) {
    state.NEW_UNREAD_COUNT = {
      ...state.NEW_UNREAD_COUNT,
      ...counts
    };
  },
  /**
   * 保存动态评论审核
   * @Author   Wayne
   * @DateTime 2018-02-06
   * @Email    qiaobin@zhiyicx.com
   * @param    {[type]}            state   [description]
   * @param    {[type]}            options [description]
   */
  SAVE_FEED_COMMENT_AUDITS(state, options) {
    if (options.type === "all") {
      state.MY_COMMENT_AUDIT = options.data;
    } else if (options.type === "new") {
      state.MY_COMMENT_AUDIT = _.unionBy(
        [...options.data, ...state.MY_COMMENT_AUDIT],
        "id"
      );
    } else {
      state.MY_COMMENT_AUDIT = [...state.MY_COMMENT_AUDIT, ...options.data];
    }
  },
  /**
   * 保存我收到的评论
   * @Author   Wayne
   * @DateTime 2018-01-29
   * @Email    qiaobin@zhiyicx.com
   * @param    {[type]}            state   [description]
   * @param    {[type]}            options [description]
   */
  SAVE_MY_COMMENTED(state, options) {
    if (options.type === "all") {
      state.MY_COMMENTED = options.data;
    } else if (options.type === "new") {
      state.MY_COMMENTED = _.unionBy(
        [...options.data, ...state.MY_COMMENTED],
        "id"
      );
    } else {
      state.MY_COMMENTED = [...state.MY_COMMENTED, ...options.data];
    }
  },

  /**
   * 保存我收到的赞
   * @Author   Wayne
   * @DateTime 2018-02-02
   * @Email    qiaobin@zhiyicx.com
   * @param    {[type]}            state   [description]
   * @param    {[type]}            options [description]
   */
  SAVE_MY_LIKED(state, options) {
    if (options.type === "all") {
      state.MY_LIKED = options.data;
    } else if (options.type === "new") {
      state.MY_LIKED = _.unionBy([...options.data, ...state.MY_LIKED], "id");
    } else {
      state.MY_LIKED = [...state.MY_LIKED, ...options.data];
    }
  }
};
