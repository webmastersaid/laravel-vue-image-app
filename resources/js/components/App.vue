<script>
import Dropzone from 'dropzone';
import { VueEditor } from "vue3-editor";

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
        };
    },
    methods: {
        store() {
            const data = new FormData();
            const files = this.dropzone.getAcceptedFiles();
            files.forEach(file => {
                data.append('images[]', file);
                this.dropzone.removeFile(file);
            });
            data.append('title', this.title);
            data.append('content', this.content);
            this.title = null;
            this.content = null;
            axios.post('/api/posts', data)
                .then(res => {
                    this.getPost();
                });
        },
        getPost() {
            axios.get('/api/posts/latest')
                .then(res => {
                    this.post = res.data.data;
                });
        },
        handleImageAdded(file, Editor, cursorLocation, resetUploader) {
            var data = new FormData();
            data.append("image", file);

            axios.post('/api/posts/images', data)
                .then(res => {
                    const url = res.data.url; // Get url from response
                    Editor.insertEmbed(cursorLocation, "image", url);
                    resetUploader();
                })
                .catch(err => {
                    console.log(err);
                });
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/posts',
            autoProcessQueue: false,
            addRemoveLinks: true
        });
        this.getPost();
    },
    components: { VueEditor }
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
            <div ref="dropzone" id="image" class="btn btn-outline-dark d-block p-5 w-25">Drag and drop image here</div>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <VueEditor id="content" v-model="content" @imageAdded="handleImageAdded" useCustomImageHandler />
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
                            <img :src="image.preview_url" class="img-fluid m-1" :alt="post.title">
                            <img :src="image.url" class="img-fluid m-1" :alt="post.title">
                        </div>
                    </div>
                    <h5 class="card-title">{{ post.title }}</h5>
                    <div class="card-title ql-editor" v-html="post.content"></div>
                    <p class="card-text"><small class="text-body-secondary">{{post.created_at}}</small></p>
                </div>
            </div>
        </div>
    </div>
</template>
