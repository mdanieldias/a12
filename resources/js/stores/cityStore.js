import { defineStore } from 'pinia';

export const useCityStore = defineStore('cityStore', {
  state: () => ({
    ufs: ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'],
    cidades: [],
  }),
  actions: {
    async fetchCities(uf) {
      try {
        const response = await fetch(`https://brasilapi.com.br/api/ibge/municipios/v1/${uf}`);
        const data = await response.json();
        this.cidades = data.map(cidade => cidade.nome);
      } catch (error) {
        console.error('Erro ao buscar cidades:', error);
        this.cidades = [];
      }
    },
  },
});
