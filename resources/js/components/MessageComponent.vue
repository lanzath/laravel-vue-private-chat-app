<template>
    <div class="card card-default chat-box">
        <div class="card-header">
            <b :class="{'text-danger':blocked}">
                {{ friend.name }}
                <span v-if="blocked">(blocked)</span>
            </b>
            <i class="fas fa-times float-right" @click.prevent="close"></i>

            <div class="dropdown float-right">
                <i class="fas fa-ellipsis-v mr-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" @click.prevent="toggleBlock">
                        {{ blocked ? 'Unblock' : 'Block' }}
                    </a>
                    <a class="dropdown-item" href="#" @click.prevent="clear">Clear chat</a>
                </div>
            </div>
        </div>
        <div class="card-body" v-chat-scroll>
            <p class="card-text" :class="{'text-right':chat.type == 'sender'}" v-for="chat in chats" :key="chat.id">
                {{ chat.message }}
            </p>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="form-group">
                <input
                    v-model="message"
                    type="text"
                    class="form-control"
                    :placeholder="blocked ? 'You\'ve bocked User Name :(' : 'Write your message'"
                    :disabled="blocked"
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
            blocked: false,
            message: null,
        }
    },

    methods: {
        send() {
            if(this.message) {
                this.pushToChats(this.message);
                axios.post(`/session/${this.friend.session.id}/send`, {
                    content: this.message,
                    to_user: this.friend.id,
                });
                this.message = null;
            }
        },

        pushToChats(message) {
            this.chats.push({
                message: message,
                type: 'sender',
                sent_at: 'Just Now'
            });
        },

        close() {
            this.$emit('close');
        },

        clear() {
            this.chats = [];
        },

        toggleBlock() {
            this.blocked = !this.blocked;
        },

        getAllMessages() {
            axios
            .post(`/session/${this.friend.session.id}/chats`)
            .then(response => this.chats = response.data);
        }
    },

    created() {
        this.getAllMessages();
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
