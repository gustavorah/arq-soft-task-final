<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #333;">Confirmação de Presença</h2>
    
    <p>Olá {{ $user['name'] }},</p>
    
    <p>Sua presença foi confirmada com sucesso no evento <strong>{{ $evento['descricao'] }}</strong>!</p>
    
    <div style="background-color: #f7f7f7; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <p><strong>Detalhes do evento:</strong></p>
        <ul>
            <li>Data Inicial: {{ $evento['dt_inicio'] }}</li>
            <li>Data Final: {{ $evento['dt_fim'] }}</li>
        </ul>
    </div>
    
    <div style="background-color: #fff3cd; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <p><strong>Lembretes importantes:</strong></p>
        <ul>
            <li>Chegue com 30 minutos de antecedência</li>
            <li>Não esqueça de trazer um documento com foto</li>
        </ul>
    </div>
    
    <p>Caso não possa comparecer, por favor, cancele sua presença através da plataforma ou entre em contato conosco.</p>
    
    <p style="margin-top: 30px;">
        Atenciosamente,<br>
        Sistema de Eventos
    </p>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;">
        <p>Este é um e-mail automático, por favor não responda.</p>
        <p>Em caso de dúvidas, entre em contato com nosso suporte.</p>
    </div>
</div>