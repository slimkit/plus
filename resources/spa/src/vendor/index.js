import CONST from '@/constants/app'
const { ENABLE_MOBLINK, ENABLE_GAODE } = CONST

// 加载 moblink 用于引导打开 APP
if (ENABLE_MOBLINK) {
  window.addEventListener('load', () => {
    const key = process.env.VUE_APP_MOBLINK_KEY || ''
    const script = document.createElement('script')
    script.type = 'text/javascript'
    script.src = `//f.moblink.mob.com/3.0.1/moblink.js?appkey=${key}`
    script.onload = () => {
      // eslint-disable-next-line
      MobLink({ path: location.origin + location.pathname })
    }
    document.querySelector('body').appendChild(script)
  })
}

// 加载高德地图脚本 用于定位
if (ENABLE_GAODE) {
  const key = process.env.VUE_APP_LBS_GAODE_KEY || ''
  const script = document.createElement('script')
  script.type = 'text/javascript'
  script.src = `//webapi.amap.com/maps?v=1.4.5&key=${key}`
  document.querySelector('body').appendChild(script)
}
