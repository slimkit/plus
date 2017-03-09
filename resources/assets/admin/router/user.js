/**
 * The file is defined "/setting" route.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import User from '../component/User';
import Manage from '../component/user/Manage';
import Role from '../component/user/Role';
import Permission from '../component/user/Permission';

const routers = {
  path: 'users',
  component: User,
  children: [
    { path: '', redirect: '/users/manage' },
    { path: 'manage', component: Manage },
    { path: 'role', component: Role },
    { path: 'permission', component: Permission }
  ]
};

export default routers;
