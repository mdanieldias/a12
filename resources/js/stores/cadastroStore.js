import { defineStore } from 'pinia';
import axios from '../axiosConfig';

import Swal from 'sweetalert2';

export const useCadastroStore = defineStore('formStore', {
    state: () => ({
        formData: {
            nome: '',
            cpf: '',
            celular: '',
            email: '',
            uf: '',
            cidade: '',
        },
        isSubmitting: false,
        formErrors: {},
        isSubmittedSuccessfully: false,
    }),
    actions: {
        async submitForm() {
            this.isSubmitting = true;
            this.formErrors = {};
            this.isSubmittedSuccessfully = false;
            try {
                const response = await axios.post('/customers', this.formData);

                if (response.status === 201) {
                    Swal.fire({
                        title: 'Cadastrado com sucesso!',
                        text: 'Você receberá um e-mail de confirmação do cadastro no email informado.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#41b882',
                    });

                    this.isSubmittedSuccessfully = true;
                    this.clearForm();
                    
                } else {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Erro ao realizar o cadastro. Tente novamente.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff7674',
                      });
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.formErrors = error.response.data.errors;
                } else {
                    console.log('Ocorreu um erro: ' + error.message);
                }
            } finally {
                this.isSubmitting = false;
            }
        },
        clearForm() {
            this.formData = {
                nome: '',
                email: '',
                cpf: '',
                celular: '',
                uf: '',
                cidade: '',
            };
            this.formErrors = {};
        },
    },
});
