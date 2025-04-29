export async function POST(request) {
    const { server, discordNick, gameTitle } = await request.json();
    
    if (!server || !discordNick || !gameTitle) {
      return new Response(JSON.stringify({ error: "Dados inválidos" }), {
        status: 400,
      });
    }
  
    try {
      const response = await fetch(process.env.DISCORD_WEBHOOK_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          content: `Novo pedido:\nServidor: ${server}\nNick: ${discordNick}\nJogo: ${gameTitle}`,
        }),
      });
  
      if (!response.ok) throw new Error("Erro ao enviar para Discord");
  
      return new Response(JSON.stringify({ success: true }), {
        status: 200,
      });
    } catch (error) {
      return new Response(JSON.stringify({ error: "Erro no servidor" }), {
        status: 500,
      });
    }
  }