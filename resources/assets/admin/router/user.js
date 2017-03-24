/**
 * The file is defined "/setting" route.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import User from '../component/User';
import UserAdd from '../component/user/UserAdd';
import Manage from '../component/user/Manage';
import Roles from '../component/user/Roles';
import RoleManage from '../component/user/RoleManage';
import Permissions from '../component/user/Permissions';

const routers = {
  path: 'users',
  component: User,
  children: [
    { path: '', component: Manage },
    { path: 'add', component: UserAdd },
    { path: 'roles', component: Roles },
    { path: 'roles/:role', component: RoleManage },
    { path: 'permissions', component: Permissions }
  ]
};

export default routers;
