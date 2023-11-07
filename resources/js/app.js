import './bootstrap';
import { createApp } from 'vue';
import App from './components/App.vue';
import Post from './components/Post.vue';

const app = createApp(App);
const post = createApp(Post);

app.mount('#app');
post.mount('#post')
