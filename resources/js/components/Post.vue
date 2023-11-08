<script>
export default {
    data() {
        return {
            posts: [],
        }
    },
    methods: {
        getPosts() {
            axios.get('/api/posts/all')
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
        <div v-if="posts" v-for="post in posts" class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap mb-3">
                    <div v-for="image in post.images" class="m-1">
                        <img :src="image.preview_url" class="img-fluid m-1" :alt="post.title">
                        <img :src="image.url" class="img-fluid m-1" :alt="post.title">
                    </div>
                </div>
                <h5 class="card-title">{{ post.title }}</h5>
                <div class="card-text ql-editor" v-html="post.content"></div>
                <p class="card-text"><small class="text-body-secondary">{{ post.created_at }}</small></p>
            </div>
        </div>
    </div>
</template>
<style>
.dz-success-mark,
.dz-error-mark {
    display: none;
}
</style>