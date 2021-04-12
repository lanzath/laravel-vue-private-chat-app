<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default">
                    <div class="card-header">
                        Friends
                    </div>

                    <ul class="list-group">
                        <li class="list-group-item">User 1</li>
                        <li class="list-group-item">User 2</li>
                        <li class="list-group-item">User 3</li>
                        <li class="list-group-item">User 4</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Whenever close event from child component is emmited, call close method. -->
                <message-component v-if="open" @close="close"></message-component>
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
            open: true,
        }
    },

    methods: {
        close() {
            this.open = false;
        }
    },

    mounted() {
        console.log('Component mounted.')
    }
}
</script>
