<script>
import Dropzone from 'dropzone';

export default {
    data() {
        return {
            dropzone: null,
            title: '',
            content: '',
            post: {
                title: '',
                content: '',
            },
        }
    },
    methods: {
        store() {
            const data = new FormData()
            const files = this.dropzone.getAcceptedFiles()
            files.forEach(file => {
                data.append('images[]', file)
                this.dropzone.removeFile(file)
            })
            data.append('title', this.title)
            data.append('content', this.content)
            this.title = null
            this.content = null
            axios.post('/api/posts', data)
                .then(res => {
                    this.getPost()
                })
        },
        getPost() {
            axios.get('/api/posts/latest')
                .then(res => {
                    this.post = res.data.data
                })
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/posts',
            autoProcessQueue: false,
            addRemoveLinks: true
        })
        this.getPost()
    }
}
</script>
<template>
    <div class="container pt-5">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input v-model="title" type="text" id="title" class="form-control" placeholder="Some title">
        </div>
        <div class="mb-3">
            <label for="image">Image</label>
            <div ref="dropzone" id="image" class="btn btn-outline-dark d-block p-5 w-25">Upload image</div>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea v-model="content" class="form-control" id="content" placeholder="Some content"></textarea>
        </div>
        <div class="mb3">
            <button @click.prevent="store" type="button" class="btn btn-primary">Add</button>
        </div>
        <div class="pt-5">
            <h1>Latest post</h1>
            <div v-if="post" class="card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-wrap mb-3">
                        <div v-for="image in post.images" class="m-1">
                            <img :src="image.url" class="card-img-top" :alt="post.title" height="100">
                        </div>
                    </div>
                    <h5 class="card-title">{{ post.title }}</h5>
                    <p class="card-text">{{ post.content }}</p>
                    <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                </div>
            </div>
        </div>
    </div>
</template>
