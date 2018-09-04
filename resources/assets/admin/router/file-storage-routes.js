import {
    WrapComponent,
    HomeComponent,
} from '../pages/file-storage';

export default {
    path: 'file-storage',
    component: WrapComponent,
    children: [
        {
            name: "file-storage:home",
            path: "",
            component: HomeComponent
        }
    ]
};
