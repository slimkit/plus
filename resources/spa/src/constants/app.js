import * as envConstantParseUtil from '../utils/env-constant-type-parse'

export const ENABLE_SERVICE_WORKER = envConstantParseUtil.boolean(
  process.env.VUE_APP_ENABLE_SERVICE_WORKER || false
)

export const ENABLE_MOBLINK = envConstantParseUtil.boolean(
  process.env.VUE_APP_MOBLINK_ENABLE || false
)
