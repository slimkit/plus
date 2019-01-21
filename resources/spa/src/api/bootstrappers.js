import api from './api'
import lstore from '@/plugins/lstore'
import location from '@/util/location.js'

/**
 * 获取热门城市列表
 * @return {Promise}
 */
export async function getHotCities () {
  if (lstore.hasData('H5_HOT_CITIES')) {
    return lstore.getData('H5_HOT_CITIES')
  }

  let response = await api.get('/locations/hots', {
    validateStatus: status => status === 200,
  })
  lstore.setData('H5_HOT_CITIES', response.data)

  return response.data
}

/**
 * 获取当前定位信息
 * @return {Promise}
 */
export async function getCurrentPosition () {
  let data = await location.getCurrentPosition()
  let { country, city, province } = data.addressComponent || {}
  const [lng, lat] = [data.position.getLng(), data.position.getLat()]

  // 保存位置信息 (异步，无需 await)
  saveCurrentPosition(lng, lat)

  let label = ''
  if (city) label = `${province} ${city}`
  else if (province) label = `${country} ${city}`
  else label = '定位失败'

  return {
    lng,
    lat,
    label,
  }
}

/**
 * 保存当前位置信息
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} longitude 经度
 * @param {number} latitude 纬度
 * @returns
 */
export async function saveCurrentPosition (longitude, latitude) {
  try {
    await api.post(`/around-amap`, { longitude, latitude })
  } catch (error) {
    // eslint-disable-next-line no-console
    console.warn('保存位置信息失败: ', error)
  }
}

export function getGeo (address) {
  const res = {}
  return api.get(`around-amap/geo?address=${address}`).then(
    ({
      data: {
        geocodes: [ { location, formatted_address: label } ],
      } = {},
    }) => {
      // city, district, province, location, formatted_address;
      const [lng, lat] = location.split(',')
      return Object.assign(res, { lng, lat, label })
    },
    () => {
      return res
    }
  )
}

/**
 * 搜索城市
 * @param  {String} name
 * @return {Promise -> Array}
 */
export async function searchCityByName (
  name,
  defaultResponseValue = { data: [] }
) {
  if (!name) return defaultResponseValue

  return api.get('/locations/search', {
    params: { name },
    validateStatus: status => status === 200,
  })
}

/**
 * 获取启动信息
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @returns
 */
export function getBootstrappers () {
  return api.get('/bootstrappers', { validateStatus: s => s === 200 })
}
