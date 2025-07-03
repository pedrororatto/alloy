<template>
  <div class="task" @click="emitEdit">
    <label class="w-checkbox checkbox-field">
      <div class="w-checkbox-input w-checkbox-input--inputType-custom checkbox margin-right-10" :class="{'w--redirected-checked': task.finalizado}"></div>
      <input type="checkbox" :checked="task.finalizado" @change="$emit('toggle', task)" style="opacity:0;position:absolute;z-index:-1">
      <span class="checkbox-label" :class="{'checked': task.finalizado}" >{{ task.nome }}</span>
    </label>
    <div class="date-button margin-left-40">
      <div>{{ task.data_limite ? new Date(task.data_limite).toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' }) : 'Sem data' }}</div>
    </div>
    <div v-if="task.descricao" class="task-details">
      <div>{{ task.descricao }}</div>
    </div>
    <div class="remove-task">
      <button class="button outlined rounded small" @click.stop="$emit('delete', task)">Excluir</button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  task: Object
});
const emit = defineEmits(['edit', 'toggle', 'delete']);

function emitEdit(e) {
  // Evita conflito com o clique do botão de deletar ou checkbox
  if (
    e.target.closest('.remove-task') ||
    e.target.type === 'checkbox'
  ) return;
  emit('edit', props.task);
}
</script>

<style scoped>
/* Removido: estilização local, pois o visual será herdado dos arquivos CSS do Webflow */
</style> 