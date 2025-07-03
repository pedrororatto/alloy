<template>
  <div class="block-tasks">
    <div v-if="!tasks.length" class="text-center text-gray-400 py-8">Nenhuma tarefa encontrada.</div>
    <TaskItem v-for="task in tasks" :key="task.id" :task="task" @edit="$emit('edit', $event)" @toggle="$emit('toggle', $event)" @delete="$emit('delete', $event)" />
  </div>
</template>

<script setup>
import TaskItem from './TaskItem.vue';

defineProps({
  tasks: Array
});
</script>

<style scoped>
/* Removido: estilização local, pois o visual será herdado dos arquivos CSS do Webflow */
</style> 