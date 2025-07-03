<template>
  <Teleport to="body">
    <div v-if="visible" class="modal-task">
      <div class="container-modal regular">
        <div class="top-modal">
          <h3>Nova tarefa / Editar tarefa</h3>
          <div class="close-modal" @click="close">
            <div class="icon w-embed">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 7L7 17M7 7L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </div>
          </div>
        </div>
        <div class="content-modal">
          <div class="form-fields w-form">
            <form @submit.prevent="onSave" class="form" id="modal-form">
              <div class="block-fields-form">
                <div class="input-wrap no-margin-bottom">
                  <input class="input w-input" maxlength="256" v-model="form.nome" required placeholder="" type="text" id="name-3" :disabled="saving">
                  <label for="name-3" class="field-label">Título</label>
                  <div v-if="errors.nome" class="error-message">{{ errors.nome }}</div>
                </div>
                <div class="input-wrap no-margin-bottom">
                  <input class="input w-input" maxlength="256" v-model="form.descricao" placeholder="" type="text" id="name-4" :disabled="saving">
                  <label for="name-4" class="field-label">Detalhes</label>
                </div>
                <div class="input-wrap no-margin-bottom">
                  <input class="input w-input" maxlength="256" v-model="form.data_limite" type="datetime-local" id="name-5" :disabled="saving">
                  <label for="name-5" class="field-label">Data</label>
                </div>
              </div>
              <div v-if="apiError" class="error-message">{{ apiError }}</div>
            </form>
          </div>
        </div>
        <div class="bottom-modal">
          <div class="flex-block-horizontal-right-align" style="justify-content: center;">
            <button type="submit" class="button rounded" form="modal-form" :disabled="saving">
              <span v-if="saving" class="spinner spinner-btn"></span>
              <div v-if="!saving">Salvar / Editar</div>
              <div v-else>Salvando...</div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue';

const props = defineProps({
  visible: Boolean,
  task: Object
});
const emit = defineEmits(['close', 'save']);

const isEdit = computed(() => !!props.task && typeof props.task === 'object');

const form = ref({ nome: '', descricao: '', data_limite: '' });
const errors = ref({});
const apiError = ref('');
const saving = ref(false);

function resetForm() {
  if (isEdit.value) {
    form.value = {
      nome: props.task?.nome || '',
      descricao: props.task?.descricao || '',
      data_limite: props.task?.data_limite ? props.task.data_limite.slice(0, 16) : ''
    };
  } else {
    form.value = { nome: '', descricao: '', data_limite: '' };
  }
  errors.value = {};
  apiError.value = '';
  saving.value = false;
}

watch(() => props.visible, (v) => {
  if (v) {
    nextTick(resetForm);
  }
});

function close() {
  if (saving.value) return;
  emit('close');
}

function validate() {
  errors.value = {};
  if (!form.value.nome || form.value.nome.trim().length === 0) {
    errors.value.nome = 'O título é obrigatório.';
  } else if (form.value.nome.length > 255) {
    errors.value.nome = 'O título deve ter no máximo 255 caracteres.';
  }
  return Object.keys(errors.value).length === 0;
}

async function onSave() {
  apiError.value = '';
  if (!validate()) return;
  saving.value = true;
  try {
    await emit('save', { ...form.value });
    resetForm();
  } catch (e) {
    apiError.value = e?.message || 'Erro ao salvar tarefa.';
  } finally {
    saving.value = false;
  }
}
</script>

<style>
.modal-task {
  display: flex !important;
  position: fixed !important;
  left: 0 !important;
  top: 0 !important;
  width: 100vw !important;
  height: 100vh !important;
  z-index: 99999 !important;
  background: rgba(0,0,0,0.25) !important;
  align-items: center !important;
  justify-content: center !important;
}
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
.spinner-btn {
  width: 18px;
  height: 18px;
  border-width: 2px;
  margin-right: 6px;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.disabled,
.button[disabled],
button[disabled] {
  opacity: 0.6;
  pointer-events: none;
  cursor: not-allowed;
}
</style>
