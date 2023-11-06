<script>
import Dropzone from 'dropzone';

export default {
    data() {
        return {
            dropzone: null,
        }
    },
    methods: {
        store() {
            const images = new FormData()
            const files = this.dropzone.getAcceptedFiles()
            files.forEach(file => {
                images.append('images[]', file)
            })
            axios.post('/api/posts', images)
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, { url: '/api/posts', autoProcessQueue: false })
    }
}
</script>
<template>
    <div class="container pt-5">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" class="form-control" placeholder="Some title">
        </div>
        <div class="mb-3">
            <label for="image">Image</label>
            <div ref="dropzone" id="image" class="btn btn-outline-dark d-block p-5 w-25">Upload image</div>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" placeholder="Some content"></textarea>
        </div>
        <div class="mb3">
            <button @click.prevent="store" type="button" class="btn btn-primary">Add</button>
        </div>
    </div>
</template>
