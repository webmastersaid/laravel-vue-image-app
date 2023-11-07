<script>
export default {
    data() {
        return {
            posts: [],
        }
    },
    methods: {
        getPosts() {
            axios.get('/api/posts')
                .then(res => {
                    this.posts = res.data.data
                })
        },
    },
    mounted() {
        this.getPosts()
    }
}
</script>
<template>
    <div class="container pt-5">
            <h1>Posts</h1>
            <div v-for="post in posts" class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ post.title }}</h5>
                    <p class="card-text">{{ post.content }}</p>
                </div>
            </div>
        </div>
</template>