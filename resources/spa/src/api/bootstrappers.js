import api from "./api";
import lstore from "@/plugins/lstore";
import location from "@/util/location.js";

/**
 * 获取热门城市列表
 * @return {Promise}
 */
export async function getHotCities() {
  if (lstore.hasData("H5_HOT_CITIES")) {
    return lstore.getData("H5_HOT_CITIES");
  }

  let response = await api.get("/locations/hots", {
    validateStatus: status => status === 200
  });
  lstore.setData("H5_HOT_CITIES", response.data);

  return response.data;
}

/**
 * 获取当前定位信息
 * @return {Promise}
 */
export async function getCurrentPosition() {
  let data = await location.getCurrentPosition();
  let { city, province, formatted_address } = data.addressComponent || {};

  return {
    lng: data.position.getLng(),
    lat: data.position.getLat(),
    label: city || province || formatted_address || "定位失败"
  };
}

export function getGeo(address) {
  const res = {};
  return api.get(`around-amap/geo?address=${address}`).then(
    ({
      data: {
        geocodes: [
          { /*city, district, province, */ location, formatted_address }
        ]
      } = {}
    }) => {
      // city, district, province, location, formatted_address;
      const label = formatted_address;
      const [lng, lat] = location.split(",");
      return Object.assign(res, { lng, lat, label });
    },
    () => {
      return res;
    }
  );
}

/**
 * 搜索城市
 * @param  {String} name
 * @return {Promise -> Array}
 */
export async function searchCityByName(
  name,
  defaultResponseValue = { data: [] }
) {
  if (!name) {
    return defaultResponseValue;
  }

  return await api.get("/locations/search", {
    params: { name },
    validateStatus: status => status === 200
  });
}

/**
 * 获取启动信息
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @returns
 */
export function getBootstrappers() {
  return api.get("/bootstrappers", { validateStatus: s => s === 200 });
}
