<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default">
                    <div class="card-header">
                        Friends
                    </div>

                    <ul class="list-group">
                        <li
                            class="list-group-item"
                            v-for="friend in friends"
                            :key="friend.id"
                            @click.prevent="openChat(friend)"
                        >
                            {{ friend.name }}
                            <span
                                class="text-danger"
                                v-if="friend.session && (friend.session.unreadCount > 0)"
                            >{{ friend.session.unreadCount }}</span>
                            <i class="fas fa-circle float-right" v-if="friend.online"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <span v-for="friend in friends" :key="friend.id" v-if="friend.session">
                    <!-- Whenever close event from child component is emmited, call close method. -->
                    <message-component
                        v-if="friend.session.open"
                        @close="close(friend)"
                        :friend=friend
                    ></message-component>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import MessageComponent from './MessageComponent';

export default {
    components: { MessageComponent },

    data() {
        return {
            friends: [],
        }
    },

    methods: {
        close(friend) {
            friend.session.open = false;
        },

        getFriends() {
            axios.post('/getFriends').then(response => {
                this.friends = response.data;

                this.friends.forEach(
                    friend => (friend.session ? this.listenForEverySession(friend) : '')
                );
            });
        },

        openChat(friend) {
            if (friend.session) {
                // Close last chat for each new chat opened.
                this.friends.forEach(friend => friend.session ? friend.session.open = false : '');

                friend.session.open = true;
                friend.session.unreadCount = 0;
            } else {
                this.createSession(friend);
            }
        },

        createSession(friend) {
            axios.post('/session/create', {friend_id: friend.id}).then(response => {
                (friend.session = response.data), (friend.session.open = true);
            });
        },

        listenForEverySession(friend) {
            Echo
                .private(`Chat.${friend.session.id}`)
                .listen('PrivateChatEvent',
                    event => (friend.session.open ? '' : friend.session.unreadCount++)
                );
        }
    },

    created() {
        this.getFriends();

        Echo.channel('Chat').listen('SessionEvent', event => {
            let friend =  this.friends.find(friend => friend.id === event.session_by);
            friend.session = event.session;
            this.listenForEverySession(friend);
        });

        // Join user to presence channel and show whether user is online
        Echo.join('Chat')
            .here((users) => {
                this.friends.forEach(friend => {
                    users.forEach(user => {
                        if (user.id == friend.id) {
                            friend.online = true
                        }
                    })
                });
            })
            .joining((user) => {
                this.friends.forEach(friend => user.id == friend.id ? friend.online = true : '')
            })
            .leaving((user) => {
                this.friends.forEach(friend => user.id == friend.id ? friend.online = false : '')
            })
    }
}
</script>
<style scoped>
.list-group-item {
    color: rgba(0, 0, 0, 0.7);
    cursor: pointer;
 }
 .list-group-item:hover {
     transform: scale(1.05);
     color: rgba(0, 0, 0, 1);
     transition: ease-in-out 0.3s;
 }
 .fa-circle {
     color: rgb(132, 252, 132);
 }
</style>
