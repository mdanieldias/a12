describe('Testando o formulário Vue.js no Laravel', () => {
    beforeEach(() => {
      // Navega para a página onde o formulário está sendo renderizado
      cy.visit('http://localhost:8000/');
    });
  
    it('exibe mensagem de erro para campos obrigatórios', () => {
      // Clica no botão de envio sem preencher os campos
      cy.get('button[type="submit"]').click();
  
      // Verifica se a mensagem de erro está presente
      cy.contains('O campo nome é obrigatório').should('be.visible');
      cy.contains('O campo e-mail é obrigatório').should('be.visible');
      cy.contains('O campo CPF é obrigatório').should('be.visible');
      cy.contains('O campo celular é obrigatório').should('be.visible');
      cy.contains('O campo estado é obrigatório').should('be.visible');
      cy.contains('O campo cidade é obrigatório').should('be.visible');
    });

    it('valida Email inválido', () => {
      // Preenche o campo de email com um valor inválido
      cy.get('#email').type('marcosdaniel.com.br');
  
      // Clica no botão de envio
      cy.get('button[type="submit"]').click();
  
      // Verifica se a mensagem de erro de Email inválido é exibida
      cy.contains('Por favor, informe um e-mail válido').should('be.visible');
    });
  
    it('valida CPF inválido', () => {
      // Preenche o campo de CPF com um valor inválido
      cy.get('#cpf').type('12345678900');
  
      // Clica no botão de envio
      cy.get('button[type="submit"]').click();
  
      // Verifica se a mensagem de erro de CPF inválido é exibida
      cy.contains('CPF inválido').should('be.visible');
    });

    describe('Testando seleção dinâmica de estado e cidade', () => {
      it('Deve carregar as cidades após selecionar um estado', () => {
        // Visita a página do formulário
        cy.visit('http://localhost:8000/');
    
        // Verifica que o campo cidade começa desabilitado
        cy.get('select#cidade').should('be.disabled');
    
        // Seleciona um estado no campo "Estado"
        cy.get('select#uf').select('SP'); // Altere para o valor do estado desejado
    
        // Aguarda até que o campo cidade seja habilitado após o carregamento
        cy.get('select#cidade', { timeout: 10000 }).should('not.be.disabled'); // Espera até que o campo seja habilitado
    
        // Verifica se as cidades foram carregadas corretamente
        cy.get('select#cidade').find('option').should('have.length.greaterThan', 1);
    
        // Seleciona uma cidade no campo de cidade
        cy.get('select#cidade').select('CRUZEIRO'); // Altere para uma cidade válida
      });
    });

    describe('submete o formulário corretamente e exibe erro de validação', () => {

      it('Deve preencher os campos e clicar no botão ok', () => {

        // Visita a página do formulário
        cy.visit('http://localhost:8000/');

        cy.get('#nome').type('Marcos Daniel');
        cy.get('#email').type('marcosdaniel.dias@gmail.com');
        cy.get('#cpf').type('413.551.858-31');
        cy.get('#celular').type('(12) 12345-6789');
    
        // Verifica que o campo cidade começa desabilitado
        cy.get('select#cidade').should('be.disabled');
    
        // Seleciona um estado no campo "Estado"
        cy.get('select#uf').select('SP'); // Altere para o valor do estado desejado
    
        // Aguarda até que o campo cidade seja habilitado após o carregamento
        cy.get('select#cidade', { timeout: 10000 }).should('not.be.disabled'); // Espera até que o campo seja habilitado
    
        // Verifica se as cidades foram carregadas corretamente
        cy.get('select#cidade').find('option').should('have.length.greaterThan', 1); // Deve haver mais de uma opção
    
        // Seleciona uma cidade no campo de cidade
        cy.get('select#cidade').select('CRUZEIRO'); // Altere para uma cidade válida

        // Submete o formulário
        cy.get('button[type="submit"]').click();

        cy.intercept('POST', '/api/v1/customers', (req) => {
          req.reply((res) => {
            // Verifica se o status 422 foi retornado
            if (res.statusCode === 422) {
              // Trate o erro aqui ou verifique a mensagem
              cy.contains('O campo email já está sendo utilizado.').should('be.visible');
            }
          });
        });
    
      });
      
    });
    

    describe('submete o formulário corretamente e exibe mensagem de sucesso', () => {

      beforeEach(() => {
        // Faz uma requisição para apagar todos os registros de teste
        cy.request('DELETE', '/api/v1/customers/truncate').then((response) => {
          // Verifica se a limpeza foi bem-sucedida
          expect(response.status).to.be.oneOf([200, 204]);
        });
      });

      it('Deve preencher os campos e clicar no botão ok', () => {

        // Visita a página do formulário
        cy.visit('http://localhost:8000/');

        cy.get('#nome').type('Marcos Daniel');
        cy.get('#email').type('marcosdaniel.dias@gmail.com');
        cy.get('#cpf').type('413.551.858-31');
        cy.get('#celular').type('(12) 12345-6789');
    
        // Verifica que o campo cidade começa desabilitado
        cy.get('select#cidade').should('be.disabled');
    
        // Seleciona um estado no campo "Estado"
        cy.get('select#uf').select('SP'); // Altere para o valor do estado desejado
    
        // Aguarda até que o campo cidade seja habilitado após o carregamento
        cy.get('select#cidade', { timeout: 10000 }).should('not.be.disabled'); // Espera até que o campo seja habilitado
    
        // Verifica se as cidades foram carregadas corretamente
        cy.get('select#cidade').find('option').should('have.length.greaterThan', 1); // Deve haver mais de uma opção
    
        // Seleciona uma cidade no campo de cidade
        cy.get('select#cidade').select('CRUZEIRO'); // Altere para uma cidade válida

        // Submete o formulário
        cy.get('button[type="submit"]').click();
    
        // Verifica se a mensagem de sucesso aparece
        cy.get('.swal2-popup', { timeout: 10000 }).should('be.visible'); 
        cy.contains('Cadastrado com sucesso!').should('be.visible'); // Verifica o texto da mensagem no modal
        cy.get('.swal2-confirm').click(); // Clica no botão "OK" ou de confirmação do SweetAlert2
        cy.get('.swal2-popup').should('not.exist');
    
      });
      
    });
    
  });
  