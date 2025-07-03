import { defineStore } from 'pinia';
import { getTasks, getTask, createTask, updateTask, deleteTask, toggleTask } from '../services/taskService';

export const useTaskStore = defineStore('tasks', {
  state: () => ({
    tasks: [],
    loading: false,
    error: null,
    selectedTask: null,
  }),
  actions: {
    async fetchTasks() {
      this.loading = true;
      try {
        this.tasks = await getTasks();
        this.error = null;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },
    async fetchTask(id) {
      this.loading = true;
      try {
        this.selectedTask = await getTask(id);
        this.error = null;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },
    async createTask(data) {
      this.loading = true;
      try {
        const task = await createTask(data);
        this.tasks.unshift(task);
        this.error = null;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },
    async updateTask(id, data) {
      this.loading = true;
      try {
        const updated = await updateTask(id, data);
        this.tasks = this.tasks.map(t => t.id === id ? updated : t);
        this.error = null;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },
    async deleteTask(id) {
      this.loading = true;
      try {
        await deleteTask(id);
        this.tasks = this.tasks.filter(t => t.id !== id);
        this.error = null;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },
    async toggleTask(id) {
      this.loading = true;
      try {
        const toggled = await toggleTask(id);
        this.tasks = this.tasks.map(t => t.id === id ? toggled : t);
        this.error = null;
      } catch (e) {
        this.error = e.message;
      } finally {
        this.loading = false;
      }
    },
    selectTask(task) {
      this.selectedTask = task;
    },
    clearSelectedTask() {
      this.selectedTask = null;
    }
  }
}); 