import {
    WrapComponent,
    HomeComponent,
    FilesystemsComponent,
    ChannelsComponent,
} from '../pages/file-storage';

export default {
    path: 'file-storage',
    component: WrapComponent,
    children: [
        {
            name: "file-storage:home",
            path: "",
            component: HomeComponent
        },
        {
            name: "file-storage:filesystems",
            path: "filesystems",
            component: FilesystemsComponent
        },
        {
            name: "file-storage:channels",
            path: 'channels',
            component: ChannelsComponent
        }
    ]
};
