<?php
/**
 * @category  Joomla Component
 * @package   mod_osdonate
 * @author    VeroPlus.com
 * @copyright 2010 VeroPlus.com
 * @copyright 2011, 2013 Open Source Training, LLC. All rights reserved
 * @contact   www.ostraining.com, support@ostraining.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version   1.9.1
 */

// no direct access
defined('_JEXEC') or die();

//load css
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_osdonate/css/style.css');
//Return the selected paypal language from the module parameters
//substr returns part of the string.
//In this case substr starts at the first character and returns 1 more (2 total)
//e.g. substr(en_US, 3, 2); //will return "US"
//instead of using substr, we could have set the local values to just the lower case code.
//e.g. "en_US" could be "US"
$langSite = substr($params->get('locale'), 3, 2);
//$langSite will never be null so if statement will always execute
if (!$langSite) {
    $langSite = 'US';
}

//get intro text if there is any
//need more comments when I have some time
$introtext = '';
if ($params->get('show_text', 1)) {
    $introtext = '<p align="left">' . $params->get('intro_text', '') . '</p>' . "\n";
}


//need more comments when I have some time
$amountLine = '';
if (!$params->get('show_amount')) {
    $amountLine .= '<input type="hidden" name="amount" value="' . $params->get('amount') . '" />' . "\n";
} else {
    $amountLine .= JText::_(
            'MOD_OSDONATE_AMOUNT_LABEL'
        ) . ':<br/><input type="text" name="amount" size="4" maxlength="10" value="' . $params->get(
            'amount'
        ) . '" style="text-align:right;" />' . "\n";
}

//need more comments when I have some time
$currencies = explode(',', $params->get('currencies'));

//need more comments when I have some time
$availableCurrencies = Array(
    'EUR',
    'USD',
    'GBP',
    'CHF',
    'AUD',
    'HKD',
    'CAD',
    'JPY',
    'NZD',
    'SGD',
    'SEK',
    'DKK',
    'PLN',
    'NOK',
    'HUF',
    'CZK',
    'ILS',
    'MXN'
);

//need more comments when I have some time
$sizeOfCurr = sizeof($currencies);
for ($i = 0; $i < $sizeOfCurr; $i++) {
    for ($j = 0; $j < sizeof($availableCurrencies); $j++) {
        if ($currencies[$i] === $availableCurrencies[$j]) {
            $isOk = 1;
            break;
        }
    }
    if (!$isOk) {
        unset($currencies[$i]);
    }
    $isOk = 0;
}

//need more comments when I have some time
if (sizeof($currencies) == 0) {
    $amountLine = '<p class="error">' . JText::_('Error - no currencies selected!') . '<br/>' . JText::_(
            'Please check the backend parameters!'
        ) . '</p>';
    $fe_c       = '';
} else {
    if (sizeof($currencies) == 1) {
        $fe_c = '<input type="hidden" name="currency_code" value="' . $currencies[0] . '" />' . "\n";
        if ($params->get('show_amount', 1)) {
            $fe_c .= '&nbsp;' . $currencies[0] . "\n";
        }
    } else {
        if (sizeof($currencies) > 1) {
            if ($params->get('show_amount', 1)) {
                $fe_c = '<select name="currency_code">' . "\n";
                foreach ($currencies as $row) {
                    $fe_c .= '<option value="' . $row . '">' . $row . '</option>' . "\n";
                }
                $fe_c .= '</select>' . "\n";
            } else {
                $fe_c = '<input type="hidden" name="currency_code" value="' . $currencies[0] . '" />' . "\n";
            }
        }
    }
}

//need more comments when I have some time
$target = '';
if ($params->get('open_new_window', 1)) {
    $target = 'target="paypal"';
}

$target = '';
if ($params->get('open_new_window', 1)) {
    $target = 'target="paypal"';
}

$fontColor = $params->get('font_color');

$widthOfModule = $params->get('width_of_sticky_hover');

$use_sticky_hover = $params->get('use_sticky_hover');
$horizontal_reference_side = $params->get('horizontal_reference_side');
$horizontal_distance = $params->get('horizontal_distance');
$vertical_reference_side = $params->get('vertical_reference_side');
$vertical_distance = $params->get('vertical_distance');
$sticky = '';
if ($use_sticky_hover == 1) {
    $sticky .= "<div align=\"center\" style=\"position:fixed;color:";
    $sticky .= $fontColor . ";";
    $sticky .= $horizontal_reference_side . ":";
    $sticky .= $horizontal_distance . "px" . ";";
    $sticky .= $vertical_reference_side . ":";
    $sticky .= $vertical_distance . "px;width:" . $widthOfModule . "px;z-index:1000;\" id=\"osdonatesticky\">";
} else {
    $sticky .= "<div align=\"center\" id=\"osdonatestatic\">";
}

echo $sticky;
echo $introtext;

?>



<form class="osdonate-form" action="https://www.paypal.com/cgi-bin/webscr"
      method="post" <?php echo $target; ?>>
    <input type="hidden" name="cmd" value="_donations"/>
    <input type="hidden" name="business" value="<?php echo $params->get('business', ''); ?>"/>
    <input type="hidden" name="return" value="<?php echo $params->get('return', ''); ?>"/>
    <input type="hidden" name="undefined_quantity" value="0"/>
    <input type="hidden" name="item_name" value="<?php echo $params->get('item_name', ''); ?>"/>
    <?php echo $amountLine . $fe_c; ?>
    <input type="hidden" name="rm" value="2"/>
    <input type="hidden" name="charset" value="utf-8"/>
    <input type="hidden" name="no_shipping" value="1"/>
    <input type="hidden" name="image_url" value="<?php echo $params->get('image_url', ''); ?>"/>
    <input type="hidden" name="cancel_return" value="<?php echo $params->get('cancel_return', ''); ?>"/>
    <input type="hidden" name="no_note" value="0"/><br/><br/>
    <input type="image" src="<?php echo $params->get('pp_image', ''); ?>" name="submit" alt="PayPal secure payments."/>
    <input type="hidden" name="lc" value="<?php echo $langSite; ?>">
</form>
</div>
