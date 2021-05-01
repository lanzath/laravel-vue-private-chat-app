<template>
    <div class="card card-default chat-box">
        <div class="card-header">
            <b :class="{'text-danger':session.block}">
                {{ friend.name }}
                <span v-if="session.block">(blocked)</span>
            </b>
            <i class="fas fa-times float-right" @click.prevent="close"></i>

            <div class="dropdown float-right">
                <i class="fas fa-ellipsis-v mr-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" v-if="session.block && canUnblock" @click.prevent="unblock">
                        Unblock
                    </a>
                    <a class="dropdown-item" href="#" v-if="!session.block" @click.prevent="block">
                        Block
                    </a>
                    <a class="dropdown-item" href="#" @click.prevent="clear">Clear chat</a>
                </div>
            </div>
        </div>
        <div class="card-body" v-chat-scroll>
            <p class="card-text" :class="{'text-right':chat.type == 'sender', 'text-success':chat.read_at != null}" v-for="chat in chats" :key="chat.id">
                {{ chat.message }}
                <br>
                <span style="font-size: 8px">{{ chat.read_at }}</span>
            </p>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="form-group">
                <input
                    v-model="message"
                    type="text"
                    class="form-control"
                    :placeholder="session.block ? `Blocked session :(` : 'Write your message'"
                    :disabled="session.block"
                />
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: ['friend'],
    data() {
        return {
            chats: [],
            message: null,
        }
    },
    computed: {
        session() {
            return this.friend.session;
        },

        canUnblock() {
            return this.session.blocked_by == authId;
        }
    },
    methods: {
        send() {
            if (this.message) {
                this.pushToChats(this.message);
                axios.post(`/session/${this.friend.session.id}/send`, {
                    content: this.message,
                    to_user: this.friend.id,
                }).then(response => (this.chats[this.chats.length - 1].id = response.data));
                this.message = null;
            }
        },

        pushToChats(message) {
            this.chats.push({
                message: message,
                type: 'sender',
                read_at: null,
                sent_at: 'Just Now'
            });
        },

        close() {
            this.$emit('close');
        },

        clear() {
            axios
              .post(`/session/${this.friend.session.id}/clear`)
              .then(response => (this.chats = []));
        },

        block() {
            // Getting session from computed
            this.session.block = true;
            axios
              .post(`/session/${this.friend.session.id}/block`)
              .then(response => this.session.blocked_by = authId);
        },

        unblock() {
            this.session.block = false;
            axios
              .post(`/session/${this.friend.session.id}/unblock`)
              .then(response => this.session.blocked_by = null);
        },

        getAllMessages() {
            axios
              .post(`/session/${this.friend.session.id}/chats`)
              .then(response => this.chats = response.data);
        },

        // post request to back-end to fill read_at field.
        read() {
            axios.post(`/session/${this.friend.session.id}/read`);
        }
    },
    created() {
        this.read();

        this.getAllMessages();

        // Listens to every private chat event.
        Echo
          .private(`Chat.${this.friend.session.id}`)
          .listen('PrivateChatEvent', event => {
              this.friend.session.open ? this.read() : '';
              this.chats.push({ message: event.content, type: 'received', sent_at: 'Just now' });
          });

        // Listens to every Message Read Event.
        Echo
          .private(`Chat.${this.friend.session.id}`)
          .listen('MsgReadEvent', event => {
              this.chats.forEach(
                (chat, index) => {
                  this.chats[index].read_at = chat.id === event.chat.id ? chat.read_at = event.chat.read_at : ''
                }
              )
          });

        // Listens to every block/unblock event.
        Echo
          .private(`Chat.${this.friend.session.id}`)
          .listen('BlockEvent', event => this.session.block = event.blocked);
    }
};
</script>

<style>
.chat-box {
    height: 400px;
}
.card-body {
    overflow-y: scroll;
}
.card-body::-webkit-scrollbar {
    width: 10px;
    background-color: #33333333;
}
.card-body::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: #7a7a7a;
}
.fas {
    color: rgba(0, 0, 0, 0.6);
    cursor: pointer;
}
.fas:hover {
    transform: scale(1.5);
    transition: ease-in-out 0.3s;
    color: rgba(0, 0, 0, 1);
}
</style>
