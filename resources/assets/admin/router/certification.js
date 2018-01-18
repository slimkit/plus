import Main from '../component/certification/Main';
import Category from '../component/certification/Category';
import CategoryEdit from '../component/certification/CategoryEdit';
import Certification from  '../component/certification/Certification';
import CertificationEdit from '../component/certification/CertificationEdit';
import CertificationAdd from '../component/certification/CertificationAdd';
const routers = {
    path: 'certifications',
    component: Main,
    children: [
        {
            path: '',
            name:'certification:users',
            component: Certification,
        },
        {
            path: 'categories',
            name: 'certification:categories',
            component: Category
        },
        {
            path: 'category/:name/edit',
            name: 'certification:category:edit',
            component: CategoryEdit
        },
        {
            path: 'add',
            name: 'certification:add',
            component: CertificationAdd,
        },
        {
            path: ':certification',
            name:'certification:edit',
            component: CertificationEdit,
        },
    ]
};

export default routers;