import Dexie from 'dexie'

let db = new Dexie('ThinkSNS')

db.version(1).stores({
  /* message */
  message:
    'id, time, cid, type, mid, uid, touid, txt, read, easemob_mid, status, [cid+read]',
  /* room */
  room:
    '++id, group, title, type, mid, uid, last_message_time, last_message_txt, del, name, [mid+del], [mid+uid], [mid+group]',
})

export default db
