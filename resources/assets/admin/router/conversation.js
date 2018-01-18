import Main from '../component/conversation/Main.vue';
import Conversation from '../component/conversation/Conversation';

const routers = {
    path: 'conversations',
    component: Main,
    children: [
        {
            path: '',
            name: 'conversation:list',
            component: Conversation
        }
    ]
};
export default routers;