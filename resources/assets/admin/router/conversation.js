import ConversationBase from '../component/Conversation.vue';
import Conversation from '../component/conversation/Conversation';

const routers = {
    path: 'conversations',
    component: ConversationBase,
    children: [
        {
            path: '',
            name: 'conversation:list',
            component: Conversation
        }
    ]
};
export default routers;