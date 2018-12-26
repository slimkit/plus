/** common components */
import btnSwitch from './components/common/btnSwitch' /* btnSwitch 按钮 */
import BadgeIcon from './components/common/BadgeIcon' /* Badge 徽标 */
import CommonHeader from './components/common/CommonHeader.vue' /* 通用头部 */
import fullSpin from './components/FullSpin' /* 全屏加载动画 */
import HeadTop from './components/HeadTop'
import FootGuide from './components/FootGuide'
import DiySelect from './components/DiySelect'
import AsyncFile from './components/common/AsyncFile'
import JoLoadMore from '@/components/JoLoadMore.vue'
import Avatar from '@/components/Avatar.vue'
import NavTabs from '@/components/tabs/NavTabs.vue'
import CircleLoading from '@/icons/CircleLoading.vue'

import FormItems from '@/components/form/formItem.js'

export default [
  Avatar,
  btnSwitch,
  BadgeIcon,
  fullSpin,
  HeadTop,
  FootGuide,
  DiySelect,
  NavTabs,
  AsyncFile,
  JoLoadMore,
  CommonHeader,
  CircleLoading,

  ...FormItems,
]
