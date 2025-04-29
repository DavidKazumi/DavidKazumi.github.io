const { exec } = require("child_process");

// Função para executar comandos no terminal
const runCommand = (command) => {
  return new Promise((resolve, reject) => {
    exec(command, (error, stdout, stderr) => {
      if (error) {
        console.error(`Erro ao executar o comando: ${command}`);
        console.error(stderr);
        reject(error);
      } else {
        console.log(stdout);
        resolve(stdout);
      }
    });
  });
};

// Função principal para executar os comandos Git
const deploy = async () => {
  try {
    await runCommand("git add .");
    await runCommand('git commit -m "autocomplete"');
    await runCommand("git push origin main");
    console.log("Deploy concluído com sucesso!");
  } catch (error) {
    console.error("Erro durante o deploy:", error);
  }
};

// Executar a função de deploy
deploy();
