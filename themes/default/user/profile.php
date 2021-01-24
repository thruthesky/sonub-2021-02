<h1>Profile</h1>
<button type="button" @click="showProfile">Show Profile</button>
<hr>
<div v-if="show">
    {{ user }}
</div>
<script>
    const mixin = {
        created() {
            console.log('profile.created!');
        },
        data() {
            return {
                show: false,
            }
        },
        methods: {
            showProfile() {
                this.$data.show = !this.$data.show;
                console.log('user', this.$data.user);
            }
        }
    }
</script>
<style>
    body {
        background-color: #333B38;
        color: white;
    }
</style>
TODO: style 태그를 여기서 뺀 다음, template 다음으로 밀어 넣을 것.
<style>
    button {
        background-color: #4CAF50; /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }
</style>