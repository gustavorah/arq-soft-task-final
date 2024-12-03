<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #333;">Confirmação de Cancelamento</h2>
    
    <p>Olá {{ $user['name'] }},</p>
    
    <p>Recebemos sua solicitação de cancelamento e queremos confirmar que seu cadastro foi cancelado com sucesso.</p>
    
    <div style="background-color: #f7f7f7; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <p><strong>Informações do cancelamento:</strong></p>
        <ul>
            <li>Data do cancelamento: {{ $inscricao['dt_cancelamento'] }}</li>
            <li>E-mail: {{ $user['email'] }}</li>
        </ul>
    </div>
    
    <p>Lamentamos sua saída e esperamos que tenha tido uma boa experiência conosco.</p>
    
    <p>Caso queira retornar no futuro, será sempre bem-vindo(a)!</p>
    
    <p>Se o cancelamento foi um engano ou se desejar reativar sua conta, entre em contato com nosso suporte.</p>
    
    <p style="margin-top: 30px;">
        Atenciosamente,<br>
        Sistema de Eventos
    </p>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;">
        <p>Este é um e-mail automático, por favor não responda.</p>
        <p>Se você não solicitou este cancelamento, entre em contato imediatamente com nosso suporte.</p>
    </div>
</div>