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
            axios.post('/getFriends').then(response => this.friends = response.data);
        },

        openChat(friend) {
            if(friend.session) {
                // Close last chat for each new chat opened.
                this.friends.forEach(friend => {
                    if(friend.session) {
                        friend.session.open = false
                    }
                });

                friend.session.open = true;
            } else {
                this.createSession(friend);
            }
        },

        createSession(friend) {
            axios.post('/session/create', {friend_id: friend.id}).then(response => {
                (friend.session = response.data), (friend.session.open = true);
            });
        }
    },

    created() {
        this.getFriends();
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
</style>
