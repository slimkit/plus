import _ from 'lodash'

export default users => {
  let placeholder = ''
  let time = ''
  users = _.take(users, 3)
  users.map(user => {
    let name = !user.user ? '未知用户' : user.user.name
    placeholder += `${name}、`
  })
  placeholder = _.trimEnd(placeholder, '、') + ' '
  time = users[0].time
  return {
    placeholder,
    time,
  }
}
