import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import TasksContainer from './components/TasksContainer.vue';

const app = createApp(TasksContainer);
app.use(createPinia());
app.mount('#app');
