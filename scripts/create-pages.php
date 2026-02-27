<?php
/**
 * Script para criar páginas institucionais no WordPress
 * 
 * Como usar:
 * 1. Acesse: http://localhost:8089/wp-content/plugins/hello.php?page=create-pages
 * 2. Ou execute via WP-CLI: wp eval-file scripts/create-pages.php
 * 3. Ou adicione temporariamente ao functions.php e acesse qualquer página do site
 */

// Carregar WordPress
require_once(__DIR__ . '/../wordpress/wp-load.php');

if (!current_user_can('manage_options')) {
    die('Você precisa ser administrador para executar este script.');
}

// Array com todas as páginas a serem criadas
$pages = [
    [
        'title' => 'Política de Privacidade',
        'slug' => 'politica-privacidade',
        'content' => '<h2>Política de Privacidade - Toy Sport</h2>
<p><strong>Última atualização:</strong> Janeiro de 2026</p>
<p>A Toy Sport respeita sua privacidade e está comprometida em proteger seus dados pessoais. Esta Política de Privacidade explica como coletamos, usamos, armazenamos e protegemos suas informações pessoais em conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018).</p>

<h3>1. Informações que Coletamos</h3>
<p>Coletamos as seguintes informações quando você utiliza nosso site:</p>
<ul>
<li><strong>Dados de Identificação:</strong> Nome completo, CPF, RG, data de nascimento</li>
<li><strong>Dados de Contato:</strong> E-mail, telefone, endereço completo</li>
<li><strong>Dados de Pagamento:</strong> Informações de cartão de crédito (processadas por gateways seguros)</li>
<li><strong>Dados de Navegação:</strong> Endereço IP, cookies, histórico de navegação</li>
<li><strong>Dados de Pedidos:</strong> Histórico de compras, produtos adquiridos</li>
</ul>

<h3>2. Como Utilizamos suas Informações</h3>
<p>Utilizamos suas informações pessoais para:</p>
<ul>
<li>Processar e entregar seus pedidos</li>
<li>Comunicar-nos com você sobre pedidos, produtos e serviços</li>
<li>Melhorar nossos produtos e serviços</li>
<li>Enviar ofertas e promoções (com seu consentimento)</li>
<li>Cumprir obrigações legais e regulatórias</li>
<li>Prevenir fraudes e garantir a segurança</li>
</ul>

<h3>3. Compartilhamento de Dados</h3>
<p>Não vendemos seus dados pessoais. Podemos compartilhar informações apenas com:</p>
<ul>
<li><strong>Fornecedores de Serviços:</strong> Empresas que nos ajudam a operar (processamento de pagamento, entrega, etc.)</li>
<li><strong>Autoridades Legais:</strong> Quando exigido por lei ou para proteger nossos direitos</li>
<li><strong>Parceiros de Negócios:</strong> Apenas com seu consentimento explícito</li>
</ul>

<h3>4. Seus Direitos (LGPD)</h3>
<p>Você tem os seguintes direitos sobre seus dados pessoais:</p>
<ul>
<li><strong>Acesso:</strong> Solicitar informações sobre quais dados temos sobre você</li>
<li><strong>Correção:</strong> Solicitar correção de dados incompletos ou desatualizados</li>
<li><strong>Exclusão:</strong> Solicitar a exclusão de dados desnecessários ou excessivos</li>
<li><strong>Portabilidade:</strong> Solicitar a transferência de seus dados para outro fornecedor</li>
<li><strong>Revogação de Consentimento:</strong> Retirar seu consentimento a qualquer momento</li>
<li><strong>Oposição:</strong> Opor-se ao tratamento de dados em certas situações</li>
</ul>

<h3>5. Segurança dos Dados</h3>
<p>Implementamos medidas técnicas e organizacionais adequadas para proteger seus dados pessoais contra acesso não autorizado, alteração, divulgação ou destruição.</p>

<h3>6. Cookies</h3>
<p>Utilizamos cookies para melhorar sua experiência de navegação. Você pode gerenciar suas preferências de cookies nas configurações do seu navegador.</p>

<h3>7. Retenção de Dados</h3>
<p>Mantemos seus dados pessoais apenas pelo tempo necessário para cumprir as finalidades descritas nesta política ou conforme exigido por lei.</p>

<h3>8. Alterações nesta Política</h3>
<p>Podemos atualizar esta Política de Privacidade periodicamente. Notificaremos sobre mudanças significativas através do nosso site ou por e-mail.</p>

<h3>9. Contato</h3>
<p>Para exercer seus direitos ou esclarecer dúvidas sobre esta política, entre em contato:</p>
<ul>
<li><strong>E-mail:</strong> privacidade@toysport.com.br</li>
<li><strong>Telefone:</strong> (49) 99999-9999</li>
<li><strong>Endereço:</strong> Xanxerê-SC, Brasil</li>
</ul>
<p><strong>Encarregado de Proteção de Dados (DPO):</strong> disponível através dos canais acima.</p>'
    ],
    [
        'title' => 'Termos de Uso e Condições',
        'slug' => 'termos-condicoes',
        'content' => '<h2>Termos de Uso e Condições - Toy Sport</h2>
<p><strong>Última atualização:</strong> Janeiro de 2026</p>
<p>Ao acessar e utilizar o site da Toy Sport, você concorda em cumprir e estar vinculado aos seguintes Termos de Uso. Se você não concorda com qualquer parte destes termos, não deve utilizar nosso site.</p>

<h3>1. Aceitação dos Termos</h3>
<p>Ao acessar este site, você confirma que leu, compreendeu e concorda em ficar vinculado a estes Termos de Uso e a todas as leis e regulamentos aplicáveis.</p>

<h3>2. Uso do Site</h3>
<p>Você concorda em usar o site apenas para fins legais e de maneira que não infrinja os direitos de terceiros ou restrinja ou iniba o uso e aproveitamento do site por terceiros.</p>

<h3>3. Conta de Usuário</h3>
<p>Ao criar uma conta em nosso site, você é responsável por:</p>
<ul>
<li>Manter a confidencialidade de sua senha</li>
<li>Notificar-nos imediatamente sobre qualquer uso não autorizado de sua conta</li>
<li>Fornecer informações precisas e atualizadas</li>
<li>Ser responsável por todas as atividades que ocorram sob sua conta</li>
</ul>

<h3>4. Produtos e Preços</h3>
<p>Reservamo-nos o direito de:</p>
<ul>
<li>Modificar preços a qualquer momento sem aviso prévio</li>
<li>Limitar quantidades de produtos por pedido</li>
<li>Recusar ou cancelar pedidos a nosso critério</li>
<li>Corrigir erros de preços, mesmo após o envio do pedido</li>
</ul>

<h3>5. Pagamento</h3>
<p>Aceitamos os seguintes métodos de pagamento:</p>
<ul>
<li>Cartão de crédito (Visa, Mastercard, Elo, American Express)</li>
<li>PIX</li>
<li>Boleto bancário</li>
</ul>
<p>O pagamento será processado de forma segura através de gateways de pagamento certificados.</p>

<h3>6. Entrega</h3>
<p>Fazemos entregas em todo o território nacional. Os prazos de entrega são estimativas e podem variar. Não nos responsabilizamos por atrasos causados por transportadoras ou eventos fora de nosso controle.</p>

<h3>7. Devoluções e Trocas</h3>
<p>Você tem direito à devolução ou troca de produtos conforme nossa Política de Devolução e Trocas, disponível em página específica.</p>

<h3>8. Propriedade Intelectual</h3>
<p>Todo o conteúdo do site, incluindo textos, gráficos, logos, ícones, imagens e software, é propriedade da Toy Sport ou de seus fornecedores de conteúdo e está protegido por leis de direitos autorais.</p>

<h3>9. Limitação de Responsabilidade</h3>
<p>A Toy Sport não será responsável por quaisquer danos diretos, indiretos, incidentais ou consequenciais resultantes do uso ou incapacidade de usar nosso site ou produtos.</p>

<h3>10. Indenização</h3>
<p>Você concorda em indenizar e isentar a Toy Sport de qualquer reclamação, dano, obrigação, perda, responsabilidade, custo ou dívida decorrente do seu uso do site ou violação destes Termos.</p>

<h3>11. Modificações dos Termos</h3>
<p>Reservamo-nos o direito de modificar estes Termos a qualquer momento. As alterações entrarão em vigor imediatamente após a publicação no site.</p>

<h3>12. Lei Aplicável</h3>
<p>Estes Termos são regidos pelas leis da República Federativa do Brasil. Qualquer disputa será resolvida nos tribunais competentes de Xanxerê-SC.</p>

<h3>13. Contato</h3>
<p>Para questões sobre estes Termos, entre em contato:</p>
<ul>
<li><strong>E-mail:</strong> contato@toysport.com.br</li>
<li><strong>Telefone:</strong> (49) 99999-9999</li>
<li><strong>Endereço:</strong> Xanxerê-SC, Brasil</li>
</ul>'
    ],
    [
        'title' => 'Sobre Nós',
        'slug' => 'sobre-nos',
        'content' => '<h2>Sobre a Toy Sport</h2>
<p>A Toy Sport é uma loja especializada em brinquedos e produtos de entretenimento, localizada em Xanxerê-SC, com mais de [X] anos de experiência no mercado.</p>

<h3>Nossa História</h3>
<p>Fundada com a missão de proporcionar alegria e diversão para crianças e adultos, a Toy Sport nasceu do sonho de oferecer os melhores produtos com qualidade, segurança e os melhores preços do mercado.</p>
<p>Ao longo dos anos, expandimos nosso catálogo para incluir desde brinquedos tradicionais até os mais modernos produtos tecnológicos, sempre priorizando a satisfação de nossos clientes.</p>

<h3>Nossa Missão</h3>
<p>Proporcionar momentos inesquecíveis através de produtos de qualidade, com excelência no atendimento e compromisso com a satisfação total de nossos clientes.</p>

<h3>Nossos Valores</h3>
<ul>
<li><strong>Qualidade:</strong> Selecionamos apenas os melhores produtos do mercado</li>
<li><strong>Confiança:</strong> Transparência e honestidade em todas as relações</li>
<li><strong>Inovação:</strong> Sempre em busca das últimas tendências e novidades</li>
<li><strong>Compromisso:</strong> Com a satisfação e felicidade de nossos clientes</li>
<li><strong>Segurança:</strong> Produtos testados e aprovados pelos órgãos competentes</li>
</ul>

<h3>Por que escolher a Toy Sport?</h3>
<ul>
<li>✅ <strong>Ampla variedade:</strong> Milhares de produtos em diversas categorias</li>
<li>✅ <strong>Preços competitivos:</strong> Os melhores preços do mercado</li>
<li>✅ <strong>Frete grátis:</strong> Para todo o Brasil em compras acima de determinado valor</li>
<li>✅ <strong>Parcelamento:</strong> Em até 10x sem juros</li>
<li>✅ <strong>Atendimento especializado:</strong> Equipe pronta para ajudar você</li>
<li>✅ <strong>Loja física:</strong> Com espaço físico em Xanxerê-SC para você conhecer</li>
<li>✅ <strong>Garantia:</strong> Todos os produtos com garantia de fábrica</li>
<li>✅ <strong>Política de devolução:</strong> 7 dias para troca ou devolução</li>
</ul>

<h3>Nossa Localização</h3>
<p><strong>Endereço:</strong> [Endereço completo]<br>
<strong>Cidade:</strong> Xanxerê-SC<br>
<strong>CEP:</strong> [CEP]<br>
<strong>Telefone:</strong> (49) [Telefone]<br>
<strong>E-mail:</strong> contato@toysport.com.br</p>

<h3>Horário de Funcionamento</h3>
<p><strong>Loja Física:</strong><br>
Segunda a Sexta: [Horário]<br>
Sábado: [Horário]<br>
Domingo: [Horário]</p>

<p><strong>E-commerce:</strong><br>
Funcionamento 24 horas por dia, 7 dias por semana!</p>

<h3>Nossa Equipe</h3>
<p>Contamos com uma equipe apaixonada e dedicada, pronta para oferecer o melhor atendimento e ajudar você a encontrar o produto perfeito para cada ocasião.</p>

<h3>Compromisso com a Comunidade</h3>
<p>A Toy Sport acredita em retribuir à comunidade. Participamos de eventos locais, apoiamos causas sociais e estamos sempre presentes nas principais celebrações da nossa cidade.</p>

<h3>Entre em Contato</h3>
<p>Estamos sempre à disposição para ouvir você! Entre em contato através dos nossos canais:</p>
<ul>
<li><strong>WhatsApp:</strong> [Número]</li>
<li><strong>E-mail:</strong> contato@toysport.com.br</li>
<li><strong>Telefone:</strong> (49) [Telefone]</li>
<li><strong>Redes Sociais:</strong> Siga-nos no Facebook e Instagram</li>
</ul>

<p>Obrigado por escolher a Toy Sport! 🎉</p>'
    ],
    [
        'title' => 'Perguntas Frequentes (FAQ)',
        'slug' => 'perguntas-frequentes',
        'content' => '<h2>Perguntas Frequentes - Toy Sport</h2>

<h3>1. Como faço um pedido?</h3>
<p>Fazer um pedido é muito simples! Basta navegar pelo site, escolher os produtos desejados, adicionar ao carrinho e finalizar a compra. Você precisará criar uma conta ou fazer login para concluir o pedido.</p>

<h3>2. Quais são as formas de pagamento aceitas?</h3>
<p>Aceitamos as seguintes formas de pagamento:</p>
<ul>
<li>Cartão de crédito (Visa, Mastercard, Elo, American Express) - Parcelamento em até 10x sem juros</li>
<li>PIX - Desconto especial para pagamento à vista</li>
<li>Boleto bancário - Vencimento em até 3 dias úteis</li>
</ul>

<h3>3. Como funciona o frete?</h3>
<p>Oferecemos frete grátis para todo o Brasil em compras acima de determinado valor. Para valores menores, o frete é calculado automaticamente no carrinho de compras com base no CEP de entrega. Trabalhamos com as principais transportadoras do país.</p>

<h3>4. Qual o prazo de entrega?</h3>
<p>O prazo de entrega varia conforme a região e o tipo de produto. Geralmente, os pedidos são enviados em até 2 dias úteis após a confirmação do pagamento. O prazo de entrega pela transportadora varia de 5 a 15 dias úteis, dependendo da localidade.</p>

<h3>5. Como rastrear meu pedido?</h3>
<p>Após o envio, você receberá um e-mail com o código de rastreamento. Você também pode rastrear seu pedido na área "Minha Conta" > "Pedidos" ou através da página de rastreamento em nosso site.</p>

<h3>6. Posso cancelar meu pedido?</h3>
<p>Sim! Você pode cancelar seu pedido antes do envio. Após o envio, será necessário seguir o processo de devolução. Entre em contato conosco o quanto antes para processarmos o cancelamento.</p>

<h3>7. Como funciona a política de devolução e troca?</h3>
<p>Você tem até 7 dias após o recebimento do produto para solicitar devolução ou troca, desde que o produto esteja em perfeito estado, com todas as embalagens e acessórios originais. Consulte nossa página completa de Política de Devolução e Trocas para mais detalhes.</p>

<h3>8. Os produtos têm garantia?</h3>
<p>Sim! Todos os produtos possuem garantia de fábrica. O prazo e condições variam conforme o fabricante. As informações de garantia estão disponíveis na página de cada produto.</p>

<h3>9. Como atualizo meus dados cadastrais?</h3>
<p>Você pode atualizar seus dados a qualquer momento acessando "Minha Conta" > "Detalhes da Conta". Mantenha seus dados sempre atualizados para facilitar o processo de entrega.</p>

<h3>10. Esqueci minha senha. O que fazer?</h3>
<p>Na página de login, clique em "Esqueci minha senha" e informe seu e-mail cadastrado. Você receberá um link para redefinir sua senha.</p>

<h3>11. Vocês têm loja física?</h3>
<p>Sim! Nossa loja física está localizada em Xanxerê-SC. Você pode retirar pedidos na loja ou apenas conhecer nossos produtos pessoalmente. Consulte nossa página "Sobre Nós" para o endereço completo e horário de funcionamento.</p>

<h3>12. Como entro em contato com o atendimento?</h3>
<p>Você pode entrar em contato conosco através de:</p>
<ul>
<li><strong>WhatsApp:</strong> [Número] - Atendimento rápido e direto</li>
<li><strong>E-mail:</strong> contato@toysport.com.br</li>
<li><strong>Telefone:</strong> (49) [Telefone]</li>
<li><strong>Formulário de contato:</strong> Disponível em nossa página de contato</li>
</ul>

<h3>13. Vocês fazem entregas internacionais?</h3>
<p>No momento, fazemos entregas apenas no território nacional brasileiro.</p>

<h3>14. Como funciona o programa de pontos/fidelidade?</h3>
<p>[Se aplicável] Nossos clientes acumulam pontos a cada compra, que podem ser trocados por descontos em compras futuras. Consulte os detalhes na área "Minha Conta".</p>

<h3>15. Os preços podem mudar?</h3>
<p>Sim, os preços podem ser alterados a qualquer momento sem aviso prévio. O preço válido é sempre o exibido no momento da finalização da compra.</p>

<h3>16. Como saber se um produto está em estoque?</h3>
<p>Produtos em estoque são exibidos normalmente no site. Produtos esgotados aparecem como "Indisponível" ou "Fora de Estoque". Você pode se cadastrar para receber notificação quando o produto voltar ao estoque.</p>

<h3>17. Vocês oferecem desconto para compras em grande quantidade?</h3>
<p>Sim! Para pedidos em grande quantidade, entre em contato conosco através do WhatsApp ou e-mail para negociarmos condições especiais.</p>

<h3>18. Como faço para receber ofertas e promoções?</h3>
<p>Cadastre-se em nossa newsletter na página inicial ou durante o processo de cadastro. Você também pode seguir nossas redes sociais para ficar por dentro de todas as promoções.</p>

<h3>19. Meu produto veio com defeito. O que fazer?</h3>
<p>Entre em contato conosco imediatamente através do WhatsApp ou e-mail com fotos do produto e descrição do problema. Vamos resolver da forma mais rápida possível, seja através de troca, reparo ou reembolso.</p>

<h3>20. Vocês têm aplicativo mobile?</h3>
<p>Nosso site é totalmente responsivo e otimizado para dispositivos móveis. Você pode acessar de qualquer smartphone ou tablet com a mesma experiência de um aplicativo.</p>

<p><strong>Não encontrou a resposta que procurava?</strong> Entre em contato conosco! Estamos sempre prontos para ajudar.</p>'
    ],
    [
        'title' => 'Política de Devolução e Trocas',
        'slug' => 'politica-devolucao-trocas',
        'content' => '<h2>Política de Devolução e Trocas - Toy Sport</h2>
<p><strong>Última atualização:</strong> Janeiro de 2026</p>
<p>A Toy Sport está comprometida com sua total satisfação. Esta política estabelece as condições para devolução, troca e reembolso de produtos adquiridos em nosso site.</p>

<h3>1. Prazo para Devolução</h3>
<p>Você tem <strong>7 (sete) dias corridos</strong>, a contar da data de recebimento do produto, para solicitar a devolução ou troca, conforme estabelecido pelo Código de Defesa do Consumidor (CDC).</p>

<h3>2. Condições para Devolução</h3>
<p>Para que a devolução seja aceita, o produto deve estar:</p>
<ul>
<li>✅ Em perfeito estado de conservação</li>
<li>✅ Com todas as embalagens originais</li>
<li>✅ Com todos os acessórios e manuais</li>
<li>✅ Com a nota fiscal ou cupom fiscal</li>
<li>✅ Sem sinais de uso ou danos</li>
<li>✅ Com todas as etiquetas e selos originais</li>
</ul>

<h3>3. Produtos que NÃO Podem Ser Devolvidos</h3>
<p>Não aceitamos devolução dos seguintes produtos:</p>
<ul>
<li>Produtos personalizados ou customizados</li>
<li>Produtos íntimos ou de higiene pessoal (quando violada a embalagem)</li>
<li>Produtos danificados por mau uso</li>
<li>Produtos sem embalagem original ou com embalagem violada</li>
<li>Produtos adquiridos em promoções especiais (quando especificado)</li>
</ul>

<h3>4. Como Solicitar Devolução ou Troca</h3>
<p>Para solicitar devolução ou troca, siga estes passos:</p>
<ol>
<li><strong>Entre em contato:</strong> WhatsApp, e-mail ou telefone informando o número do pedido</li>
<li><strong>Aguarde aprovação:</strong> Nossa equipe analisará sua solicitação em até 2 dias úteis</li>
<li><strong>Receba instruções:</strong> Enviaremos as instruções de envio do produto</li>
<li><strong>Envie o produto:</strong> Embalado adequadamente conforme instruções</li>
<li><strong>Aguarde processamento:</strong> Após recebermos e analisarmos o produto</li>
</ol>

<h3>5. Custos de Devolução</h3>
<p><strong>Devolução por arrependimento:</strong> O frete de devolução é por conta do cliente, exceto quando o produto apresentar defeito ou divergência.</p>
<p><strong>Devolução por defeito ou divergência:</strong> Todos os custos de frete são por nossa conta.</p>

<h3>6. Processamento de Reembolso</h3>
<p>Após recebermos o produto e confirmarmos que atende às condições, processaremos o reembolso:</p>
<ul>
<li><strong>Cartão de crédito:</strong> O estorno aparecerá na fatura em até 2 faturas</li>
<li><strong>PIX:</strong> Reembolso em até 5 dias úteis</li>
<li><strong>Boleto:</strong> Reembolso via PIX ou transferência bancária em até 10 dias úteis</li>
</ul>

<h3>7. Processo de Troca</h3>
<p>Para trocar um produto:</p>
<ol>
<li>Siga o mesmo processo de devolução</li>
<li>Informe o produto desejado para troca</li>
<li>Se houver diferença de valor, você pagará ou receberá o valor correspondente</li>
<li>O novo produto será enviado após processarmos a devolução</li>
</ol>

<h3>8. Produtos com Defeito</h3>
<p>Se o produto apresentar defeito de fabricação:</p>
<ul>
<li>Entre em contato imediatamente (até 90 dias após a compra)</li>
<li>Envie fotos ou vídeos do defeito</li>
<li>Processaremos a troca ou reparo conforme a garantia</li>
<li>Todos os custos são por nossa conta</li>
</ul>

<h3>9. Produtos Diferentes do Pedido</h3>
<p>Se você recebeu um produto diferente do que foi pedido:</p>
<ul>
<li>Entre em contato imediatamente</li>
<li>Não é necessário aguardar os 7 dias</li>
<li>Enviaremos o produto correto e recolheremos o incorreto</li>
<li>Todos os custos são por nossa conta</li>
</ul>

<h3>10. Como Enviar o Produto</h3>
<p>Ao receber a aprovação da devolução:</p>
<ol>
<li>Embalar o produto na embalagem original (se possível)</li>
<li>Incluir nota fiscal ou cupom fiscal</li>
<li>Incluir todos os acessórios e manuais</li>
<li>Enviar para o endereço fornecido por nossa equipe</li>
<li>Utilizar serviço de envio com rastreamento</li>
</ol>

<h3>11. Prazo de Análise</h3>
<p>Após recebermos o produto, temos até <strong>7 dias úteis</strong> para analisar e processar o reembolso ou troca.</p>

<h3>12. Garantia Legal</h3>
<p>Além desta política, você tem direito à garantia legal conforme o Código de Defesa do Consumidor:</p>
<ul>
<li><strong>Garantia contra vícios aparentes:</strong> 30 dias para produtos não duráveis</li>
<li><strong>Garantia contra vícios ocultos:</strong> 90 dias para produtos duráveis</li>
<li><strong>Garantia contratual:</strong> Conforme especificado pelo fabricante</li>
</ul>

<h3>13. Contato para Devoluções</h3>
<p>Para solicitar devolução ou esclarecer dúvidas:</p>
<ul>
<li><strong>WhatsApp:</strong> [Número] - Atendimento prioritário</li>
<li><strong>E-mail:</strong> devolucoes@toysport.com.br</li>
<li><strong>Telefone:</strong> (49) [Telefone]</li>
<li><strong>Horário:</strong> Segunda a Sexta, das 8h às 18h</li>
</ul>

<h3>14. Observações Importantes</h3>
<ul>
<li>Mantenha sempre a nota fiscal ou comprovante de compra</li>
<li>Fotografe o produto antes de enviar (para sua proteção)</li>
<li>Use embalagem resistente para evitar danos no transporte</li>
<li>Não envie produtos sem nossa prévia autorização</li>
<li>O reembolso será feito na mesma forma de pagamento utilizada</li>
</ul>

<p><strong>Estamos comprometidos em resolver qualquer situação da forma mais rápida e satisfatória possível!</strong></p>'
    ]
];

$created = 0;
$updated = 0;
$errors = [];

foreach ($pages as $page_data) {
    // Verificar se a página já existe
    $existing_page = get_page_by_path($page_data['slug']);
    
    $page_args = [
        'post_title'    => $page_data['title'],
        'post_name'     => $page_data['slug'],
        'post_content'  => $page_data['content'],
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
    ];
    
    if ($existing_page) {
        // Atualizar página existente
        $page_args['ID'] = $existing_page->ID;
        $result = wp_update_post($page_args);
        if ($result && !is_wp_error($result)) {
            $updated++;
            echo "✓ Página atualizada: {$page_data['title']}\n";
        } else {
            $errors[] = "Erro ao atualizar: {$page_data['title']}";
        }
    } else {
        // Criar nova página
        $result = wp_insert_post($page_args);
        if ($result && !is_wp_error($result)) {
            $created++;
            echo "✓ Página criada: {$page_data['title']}\n";
        } else {
            $errors[] = "Erro ao criar: {$page_data['title']}";
        }
    }
}

echo "\n";
echo "========================================\n";
echo "RESUMO:\n";
echo "========================================\n";
echo "Páginas criadas: $created\n";
echo "Páginas atualizadas: $updated\n";
if (!empty($errors)) {
    echo "Erros:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
}
echo "========================================\n";
echo "\nTodas as páginas foram processadas!\n";
echo "Acesse o WordPress para visualizar as páginas criadas.\n";
