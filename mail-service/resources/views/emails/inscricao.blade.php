<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #333;">Confirmação de Inscrição</h2>
    
    <p>Olá {{ $user['name'] }},</p>
    
    <p>Sua inscrição foi realizada com sucesso! Agradecemos por se cadastrar em nossa plataforma.</p>
    
    <div style="background-color: #f7f7f7; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <p><strong>Detalhes da sua inscrição:</strong></p>
        <ul>
            <li>Data: {{ $inscricao['dt_inscricao'] }}</li>
            <li>E-mail cadastrado: {{ $user['email'] }}</li>
        </ul>
    </div>
    
    <p>Você já pode acessar sua conta usando seu e-mail e senha cadastrados.</p>
    
    <p>Se tiver alguma dúvida, não hesite em nos contatar através do nosso suporte.</p>
    
    <p style="margin-top: 30px;">
        Atenciosamente,<br>
        Sistema de Eventos
    </p>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;">
        <p>Este é um e-mail automático, por favor não responda.</p>
    </div>
</div>