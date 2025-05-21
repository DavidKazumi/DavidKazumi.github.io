// Este arquivo contém o código JavaScript para implementar o envio do formulário via AJAX
// e o recurso de autocompletar

document.addEventListener("DOMContentLoaded", function () {
    const gameRequestForm = document.getElementById("gameRequestForm");
    const gameTitleInput = document.getElementById("gameTitle");
    const autocompleteList = document.getElementById("autocomplete-list");
    
    // Implementação do autocompletar
    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }
    
    const performSearch = debounce(function () {
        const query = this.value.toLowerCase();
        autocompleteList.innerHTML = "";
        
        if (!query || query.length < 1) return;
        
        // Fazer a requisição AJAX para o servidor
        // Usando a mesma lógica do search-worker.js original
        fetch('search_games.php?q=' + encodeURIComponent(query))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na busca');
                }
                return response.json();
            })
            .then(games => {
                if (games.length === 0) return;
                
                games.forEach(game => {
                    const item = document.createElement("div");
                    item.classList.add("autocomplete-item");
                    item.textContent = game.name;
                    item.addEventListener("click", () => {
                        gameTitleInput.value = game.name;
                        autocompleteList.innerHTML = "";
                    });
                    autocompleteList.appendChild(item);
                });
            })
            .catch(error => console.error('Erro:', error));
    }, 300);
    
    gameTitleInput.addEventListener("input", performSearch);
    
    // Fechar o autocomplete quando clicar fora dele
    document.addEventListener('click', function(event) {
        if (event.target !== gameTitleInput && event.target !== autocompleteList) {
            autocompleteList.innerHTML = '';
        }
    });
    
    // Implementação do envio do formulário via AJAX
    if (gameRequestForm) {
        gameRequestForm.addEventListener("submit", function (e) {
            e.preventDefault();
            
            // Criar um objeto FormData
            const formData = new FormData(this);
            
            // Enviar a requisição AJAX
            fetch('process_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => Promise.reject(data));
                }
                return response.json();
            })
            .then(data => {
                // Exibir mensagem de sucesso
                showMessage(data.message, 'success');
                
                // Limpar o formulário
                gameRequestForm.reset();
            })
            .catch(error => {
                // Exibir mensagem de erro
                let errorMessage = 'Falha ao enviar o pedido. Por favor, tente novamente.';
                if (error && error.message) {
                    errorMessage = error.message;
                }
                showMessage(errorMessage, 'error');
            });
        });
    }
    
    // Função para mostrar mensagens
    function showMessage(message, type) {
        // Verificar se já existe uma mensagem
        let messageDiv = document.querySelector('.message');
        
        if (!messageDiv) {
            // Criar o elemento de mensagem
            messageDiv = document.createElement('div');
            messageDiv.className = 'message';
            
            // Inserir antes do formulário
            const formContainer = document.querySelector('.form-container');
            formContainer.insertBefore(messageDiv, gameRequestForm);
        }
        
        // Definir o conteúdo e classe
        messageDiv.textContent = message;
        messageDiv.className = 'message ' + type;
        
        // Remover a mensagem após alguns segundos
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
    
    // Verificar se há parâmetros de status na URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const message = urlParams.get('message');
    
    if (status && message) {
        showMessage(message, status);
        
        // Remover os parâmetros da URL
        const url = new URL(window.location);
        url.searchParams.delete('status');
        url.searchParams.delete('message');
        window.history.replaceState({}, document.title, url);
    }
});