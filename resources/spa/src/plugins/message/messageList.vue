<template>
  <div class="v-msg-list">
    <message
      v-for="msg in msgs"
      :key="msg.name"
      :type="msg.type"
      :icon="msg.icon"
      :content="msg.content"
      :duration="msg.duration"
      :closable="msg.closable"
      :name="msg.name"
      :transition-name="msg.transitionName"
      :on-close="msg.onClose"/>
  </div>
</template>
<script>
import message from "./message";

let seed = 0;
const getUuid = () => {
  return "v_msg_" + Date.now() + "_" + seed++;
};
export default {
  name: "MessageList",
  components: {
    message
  },
  data() {
    return {
      msgs: []
    };
  },
  methods: {
    add(msg) {
      const name = msg.name || getUuid();
      let newMsg = Object.assign(
        {
          content: {},
          duration: 3,
          closable: false,
          name: name
        },
        msg
      );
      this.msgs.push(newMsg);
    },
    close(name) {
      const oldMsgs = this.msgs;
      for (let i = 0; i < oldMsgs.length; i++) {
        if (oldMsgs[i].name === name) {
          this.msgs.splice(i, 1);
          break;
        }
      }
    },
    closeAll() {
      this.msgs = [];
    }
  }
};
</script>
