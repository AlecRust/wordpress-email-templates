<?php
/**
 * email footer
 *
 * @version	1.0
 * @since 1.4
 * @package	Wordpress Social Invitations
 * @author Timersys
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

$template_footer = "
	border-top:1px solid #E2E2E2;
	background: ".$settings['footer_bg'].";
	-webkit-border-radius:0px 0px 6px 6px;
	-o-border-radius:0px 0px 6px 6px;
	-moz-border-radius:0px 0px 6px 6px;
	border-radius:0px 0px 6px 6px;
";

$credit = "
	border:0;
	color: ".$settings['footer_text_color'].";
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:".$settings['footer_aligment'].";
";
?>


				</div>
			</td>
        </tr>
    </table>
    <!-- End Content -->
            </td>
        </tr>
    </table>
    <!-- End Body -->
        </td>
    </tr>
	<tr>
    	<td align="center" valign="top">
            <!-- Footer -->
        	<table border="0" cellpadding="10" cellspacing="0" width="100%" id="template_footer" style="<?php echo $template_footer; ?>">
            	<tr>
                	<td valign="top">
                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                            <tr>
                                <td colspan="2" valign="middle" id="credit" style="<?php echo $credit; ?>">
                                
                                	<?php echo apply_filters( 'mailtpl/templates/footer_text', $settings['footer_text'] ); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!-- End Footer -->
        </td>
    </tr>
            </table>
        </td>
    </tr>
</table>
        </div> <?php if( is_customize_preview() ) wp_footer();?>
    </body>
</html>