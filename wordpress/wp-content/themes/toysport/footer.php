<?php
/**
 * Footer do tema - Toy Sport
 *
 * @package ToySport
 */
?>

    </main><!-- #main -->

    <footer id="colophon" class="site-footer">
        <div class="footer-content">
            <!-- Widget 1: Institucional -->
            <div class="footer-widget">
                <h3><?php _e('Institucional', 'toysport'); ?></h3>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'toysport'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/sobre-nos')); ?>"><?php _e('Sobre Nós', 'toysport'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/contato')); ?>"><?php _e('Fale Conosco', 'toysport'); ?></a></li>
                    <?php if (function_exists('wc_get_page_id')) : ?>
                        <li><a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"><?php _e('Todos os Produtos', 'toysport'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Widget 2: Ajuda -->
            <div class="footer-widget">
                <h3><?php _e('Ajuda', 'toysport'); ?></h3>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/perguntas-frequentes')); ?>"><?php _e('Perguntas Frequentes', 'toysport'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/politica-devolucao-trocas')); ?>"><?php _e('Política de Devolução e Trocas', 'toysport'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/rastreamento-pedidos')); ?>"><?php _e('Rastreamento de Pedidos', 'toysport'); ?></a></li>
                    <?php if (function_exists('wc_get_page_permalink')) : ?>
                        <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php _e('Minha Conta', 'toysport'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Widget 3: Formas de Pagamento -->
            <div class="footer-widget">
                <h3><?php _e('Formas de Pagamento', 'toysport'); ?></h3>
                <div class="payment-methods">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                    <i class="fab fa-cc-pix"></i>
                </div>
                <p><?php _e('Parcelamento em até 10x sem juros', 'toysport'); ?></p>
            </div>

            <!-- Widget 4: Redes Sociais -->
            <div class="footer-widget">
                <h3><?php _e('Redes Sociais', 'toysport'); ?></h3>
                <div class="social-links">
                    <a href="#" target="_blank" rel="noopener" aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" target="_blank" rel="noopener" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" target="_blank" rel="noopener" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
                
                <div class="footer-features">
                    <div class="footer-feature">
                        <i class="fas fa-truck"></i>
                        <div>
                            <strong><?php _e('Receba em casa', 'toysport'); ?></strong>
                            <p><?php _e('Enviamos para todo Brasil', 'toysport'); ?></p>
                        </div>
                    </div>
                    <div class="footer-feature">
                        <i class="fas fa-undo"></i>
                        <div>
                            <strong><?php _e('Devolução', 'toysport'); ?></strong>
                            <p><?php _e('7 Dias após o recebimento', 'toysport'); ?></p>
                        </div>
                    </div>
                    <div class="footer-feature">
                        <i class="fas fa-credit-card"></i>
                        <div>
                            <strong><?php _e('Parcele em até 10x', 'toysport'); ?></strong>
                            <p><?php _e('Toda loja em até 10x sem juros', 'toysport'); ?></p>
                        </div>
                    </div>
                    <div class="footer-feature">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong><?php _e('Site 100% seguro', 'toysport'); ?></strong>
                            <p><?php _e('Loja com espaço físico localizada em Xanxerê-SC', 'toysport'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p>
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                    <?php _e('Todos os direitos reservados.', 'toysport'); ?>
                    <?php if (get_theme_mod('toysport_dev_credits', true)) : ?>
                        <br>
                        <?php _e('Site desenvolvido por', 'toysport'); ?> 
                        <a href="https://conext.click" target="_blank" rel="noopener">CONEXT</a>
                    <?php endif; ?>
                </p>
                <p class="footer-links">
                    <a href="<?php echo esc_url(home_url('/politica-privacidade')); ?>"><?php _e('Política de Privacidade', 'toysport'); ?></a> |
                    <a href="<?php echo esc_url(home_url('/termos-condicoes')); ?>"><?php _e('Termos e Condições', 'toysport'); ?></a>
                </p>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
