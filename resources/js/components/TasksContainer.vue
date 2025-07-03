<template>
  <div class="content-tasks">
    <Appbar @new-task="openModal" />
    <div class="tasks">
      <div class="form-fields no-space-top w-form">
        <TaskList :tasks="tasks" @edit="editTask" @toggle="toggleTask" @delete="deleteTask" />
      </div>
    </div>
    <TaskModal :visible="modalVisible" :task="selectedTask" @close="closeModal" @save="saveTask" />
    <Footer />
    <div v-if="loading" class="loading-state">
      <span class="spinner"></span>
      <span>Carregando...</span>
    </div>
    <div v-if="error" class="text-center text-red-500 mt-4">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useTaskStore } from '../stores/taskStore';
import TaskList from './TaskList.vue';
import TaskModal from './TaskModal.vue';
import Appbar from './Appbar.vue';
import Footer from './Footer.vue';
import { storeToRefs } from "pinia";

const store = useTaskStore();
const modalVisible = ref(false);
const selectedTask = ref(null);

const { tasks, loading, error } = storeToRefs(store);

function openModal(task = null) {
  selectedTask.value = task;
  modalVisible.value = true;
}
function closeModal() {
  selectedTask.value = null;
  modalVisible.value = false;
}
async function saveTask(data) {
  if (selectedTask.value) {
    await store.updateTask(selectedTask.value.id, data);
  } else {
    await store.createTask(data);
  }
  closeModal();
}
function editTask(task) {
  openModal(task);
}
async function deleteTask(task) {
  if (confirm('Excluir tarefa?')) {
    await store.deleteTask(task.id);
  }
}
async function toggleTask(task) {
  await store.toggleTask(task.id);
}
onMounted(() => {
  store.fetchTasks();
});
</script>

<style>
.spinner {
  display: inline-block;
  width: 24px;
  height: 24px;
  border: 3px solid #ccc;
  border-top: 3px solid #2563eb;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-right: 8px;
  vertical-align: middle;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.loading-state {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
  margin-top: 1rem;
  font-size: 1.1rem;
}
</style>
