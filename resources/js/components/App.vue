<script>
import Dropzone from 'dropzone';
import { VueEditor, f } from "vue3-editor";

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
            isEdit: false,
            imageIdsForDelete: [],
            imageUrlsForDelete: [],
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
                    const url = res.data.url;
                    Editor.insertEmbed(cursorLocation, "image", url);
                    resetUploader();
                })
                .catch(err => {
                    console.log(err);
                });
        },
        edit() {
            this.title = this.post.title
            this.content = this.post.content
            this.post.images.forEach(image => {
                const file = { id: image.id, name: image.name, size: image.size };
                this.dropzone.displayExistingFile(file, image.preview_url);
            })

            this.isEdit = true
        },
        update() {
            const data = new FormData();
            const files = this.dropzone.getAcceptedFiles();
            files.forEach(file => {
                data.append('images[]', file);
                this.dropzone.removeFile(file);
            });
            this.imageIdsForDelete.forEach(id => {
                data.append('image_ids_for_delete[]', id)
            })
            this.imageUrlsForDelete.forEach(url => {
                data.append('image_urls_for_delete', url)
            })
            data.append('title', this.title);
            data.append('content', this.content);
            data.append('_method', 'PATCH')
            this.title = null;
            this.content = null;
            axios.post(`/api/posts/${this.post.id}`, data)
                .then(res => {
                    const previews = this.dropzone.previewsContainer.querySelectorAll('.dz-preview')
                    previews.forEach(preview => {
                        preview.remove()
                    })
                    this.getPost();
                });
            this.isEdit = false
        },
        handleImageRemoved(url) {
            console.log(url)
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: '/api/posts',
            autoProcessQueue: false,
            addRemoveLinks: true
        });
        this.dropzone.on('removedfile', file => {
            this.imageIdsForDelete.push(file.id)
        })
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
            <VueEditor id="content" v-model="content" @imageAdded="handleImageAdded" @imageRemoved="handleImageRemoved"
                useCustomImageHandler />
        </div>
        <div class="mb3">
            <button v-if="!isEdit" @click.prevent="store" type="button" class="btn btn-primary">Add</button>
            <button v-if="isEdit" @click.prevent="update" type="button" class="btn btn-primary">Update</button>
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
                    <p class="card-text"><small class="text-body-secondary">{{ post.created_at }}</small></p>
                    <a href="#" @click="edit(post)" type="button" class="btn btn-primary">Edit</a>
                </div>
            </div>
        </div>
    </div>
</template>
