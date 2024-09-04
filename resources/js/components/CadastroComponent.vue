<template>
  <div class="card p-4 bg-light">
    <h4 class="mb-3">Informe seus dados pessoais</h4>
    <form @submit.prevent="handleSubmit" class="needs-validation" novalidate>
      <div class="row g-3">
        <div class="col-12">
          <label for="nome" class="form-label">Nome <Tooltip title="Por favor, informe seu nome para indentificação"/></label>
          <input
            type="text"
            class="form-control form-control-sm"
            id="nome"
            v-model="cadastroStore.formData.nome"
            placeholder="seu nome"
            aria-label="Campo nome"
            :class="{'is-invalid': formErrors.nome}"
          />
          <div class="invalid-feedback" v-if="formErrors.nome">{{ formErrors.nome[0] }}</div>
        </div>

        <div class="col-12">
          <label for="email" class="form-label">Email <Tooltip title="Por favor, informe seu e-mail para contato"/></label>
          <input
            type="email"
            class="form-control form-control-sm"
            id="email"
            v-model="cadastroStore.formData.email"
            placeholder="seu-email@example.com"
            aria-label="Campo email"
            :class="{'is-invalid': formErrors.email}"
          />
          <div class="invalid-feedback" v-if="formErrors.email">{{ formErrors.email[0] }}</div>
        </div>

        <div class="col-sm-6 col-md-6" :key="inputKey">
          <label for="cpf" class="form-label">CPF <Tooltip title="Por favor, informe seu CPF para indentificação"/></label>
          <MaskInput
            ref="cpf"
            type="text"
            class="form-control form-control-sm"
            id="cpf"
            v-model="cadastroStore.formData.cpf"
            placeholder="999.999.999-99"
            aria-label="Campo CPF"
            mask="###.###.###-##"
            :class="{'is-invalid': formErrors.cpf}"
          />
          <div class="invalid-feedback" v-if="formErrors.cpf">{{ formErrors.cpf[0] }}</div>
        </div>

        <div class="col-sm-6 col-md-6" :key="inputKey">
          <label for="celular" class="form-label">Celular <Tooltip title="Por favor, informe seu celular para contato"/></label>
          <MaskInput
            ref="celular"
            type="text"
            class="form-control form-control-sm"
            id="celular"
            v-model="cadastroStore.formData.celular"
            placeholder="(99) 99999-9999"
            aria-label="Campo Celular"
            mask="(##) #####-####"
            :class="{'is-invalid': formErrors.celular}"
          />
          <div class="invalid-feedback" v-if="formErrors.celular">{{ formErrors.celular[0] }}</div>
        </div>

        <div class="col-sm-6 col-md-6">
          <label for="uf" class="form-label">Estado <Tooltip title="Por favor, informe o estado para identificação"/></label>
          <select
            class="form-select form-select-sm"
            id="uf"
            v-model="cadastroStore.formData.uf"
            aria-label="Campo Estado"
            @change="onStateChange"
            :class="{'is-invalid': formErrors.uf}"
            
          >
            <option value="">selecione...</option>
            <option v-for="uf in cityStore.ufs" :key="uf" :value="uf">{{ uf }}</option>
          </select>
          <div class="invalid-feedback" v-if="formErrors.uf">{{ formErrors.uf[0] }}</div>
        </div>

        <div class="col-sm-6 col-md-6">
          <label for="cidade" class="form-label">Cidade <Tooltip title="Por favor, informe a cidade para identificação"/></label>
          <select
            class="form-select form-select-sm"
            id="cidade"
            aria-label="Campo Cidade"
            v-model="cadastroStore.formData.cidade"
            :disabled="!cadastroStore.formData.uf"
            :class="{'is-invalid': formErrors.cidade}"
            required
          >
            <option value="">
              {{ isLoading ? 'Carregando...' : 'selecione...' }}
            </option>
            <option v-for="cidade in cityStore.cidades" :key="cidade" :value="cidade">{{ cidade }}</option>
          </select>
          <div class="invalid-feedback" v-if="formErrors.cidade">{{ formErrors.cidade[0] }}</div>
        </div>
      </div>

      <hr class="my-4">

      <button class="w-100 btn custom-btn btn-md" type="submit" :disabled="cadastroStore.isSubmitting">Cadastrar</button>
    </form>

  </div>
</template>

<script>
import { useCadastroStore } from '../stores/cadastroStore';
import { useCityStore } from '../stores/cityStore';
import { validateCPF } from '../utils/cpfValidator';
import { ref, nextTick, computed, watch } from 'vue';
import Tooltip from './Tooltip.vue';

export default {
  components: {
    Tooltip
  },
  setup() {
    const cadastroStore = useCadastroStore(); 
    const cityStore = useCityStore();
    const isLoading = ref(false);
    const inputKey = ref(Date.now());

    watch(() => cadastroStore.isSubmittedSuccessfully, (newVal) => {
      if (newVal) {
        inputKey.value = Date.now(); // Atualiza o key apenas se o cadastro for bem-sucedido
      }
    });

    const onStateChange = async () => {
      const { uf } = cadastroStore.formData;
      cadastroStore.formData.cidade = '';
      cityStore.cidades = [];
      
      if (uf) {
        isLoading.value = true;
        await nextTick();
        await cityStore.fetchCities(uf);
        isLoading.value = false;
      }
    };

    const validateField = (fieldName, errorMessage, condition = true) => {
      cadastroStore.formErrors[fieldName] = condition ? null : [errorMessage];
    };

    const validateNome = () => validateField('nome', 'O campo nome é obrigatório', !!cadastroStore.formData.nome);
    
    const validateEmail = () => {
      const email = cadastroStore.formData.email;
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const isEmailValid = emailPattern.test(email);

      validateField('email', 'O campo e-mail é obrigatório', !!email);
      if (email && !isEmailValid) {
        validateField('email', 'Por favor, informe um e-mail válido', false);
      }
    };

    const validateCelular = () => validateField('celular', 'O campo celular é obrigatório', !!cadastroStore.formData.celular);

    const validateEstado = () => validateField('uf', 'O campo estado é obrigatório', !!cadastroStore.formData.uf);

    const validateCidade = () => validateField('cidade', 'O campo cidade é obrigatório', !!cadastroStore.formData.cidade);

    const handleSubmit = () => {
      const cpfError = validateCPF(cadastroStore.formData.cpf);

      validateNome();
      validateEmail();
      validateCelular();
      validateEstado();
      validateCidade();

      cadastroStore.formErrors.cpf = cpfError ? [cpfError] : null;

      if (!cpfError && Object.values(cadastroStore.formErrors).every(error => error === null)) {
        cadastroStore.submitForm();
      }
    };

    return {
      cadastroStore,
      cityStore,
      isLoading,
      onStateChange,
      handleSubmit,
      inputKey,
      formErrors: computed(() => cadastroStore.formErrors)
    };
  },
};
</script>

<style scoped>
  ::-webkit-input-placeholder {
    color: #d1d0d0;
  }

  :-moz-placeholder {
    /* Firefox 18- */
    color: #d1d0d0;
  }

  ::-moz-placeholder {
    /* Firefox 19+ */
    color: #d1d0d0;
  }

  :-ms-input-placeholder {
    color: #d1d0d0;
  }
  .form-select:disabled {
    background-color: var(--bs-body-bg);
  }

  
</style>
