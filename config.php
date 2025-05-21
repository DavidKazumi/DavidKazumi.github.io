<?php
// Arquivo de configuração para armazenar informações sensíveis

// URL da webhook do Discord
// Em um ambiente de produção, considere usar variáveis de ambiente
// para armazenar informações sensíveis como esta
define('DISCORD_WEBHOOK_URL', 'https://discord.com/api/webhooks/1374795495720882227/xeYRVjLka6GJ6anhD1AJTy8CWWuXLepqKXi9TbWDRaZkImeUgb-yooP7zSsslYWrwQL');

// Configurações de segurança
define('ENABLE_RATE_LIMITING', true);      // Limitar número de requisições
define('MAX_REQUESTS_PER_HOUR', 10);       // Máximo de pedidos por hora por IP
define('ENABLE_REQUEST_VALIDATION', true); // Validar requisições com token CSRF

// Outras configurações
define('DEBUG_MODE', false);               // Modo de depuração (não usar em produção)
?>