import { boolean } from '../utils/env-constant-type-parse'

export default {
  ENABLE_SERVICE_WORKER: boolean(process.env.VUE_APP_ENABLE_SERVICE_WORKER || false),
  ENABLE_MOBLINK: boolean(process.env.VUE_APP_MOBLINK_ENABLE || false),
  ENABLE_GAODE: process.env.VUE_APP_LBS_GAODE_KEY || false,
}
