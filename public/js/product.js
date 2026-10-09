/**
 * Product page interactivity.
 * Expects a global `window.productConfig` object (set inline in the Blade view) with:
 *   { price, currency, productUrl, productName, whatsappNumber, smsNumber }
 */
document.addEventListener('DOMContentLoaded', function () {
  const cfg = window.productConfig;
  if (!cfg) return;

  const qtyDisplay = document.getElementById('qty-display');
  const qtyMinus = document.getElementById('qty-minus');
  const qtyPlus = document.getElementById('qty-plus');
  const totalDisplay = document.getElementById('live-price');
  const whatsappBtn = document.getElementById('btn-whatsapp');
  const smsBtn = document.getElementById('btn-sms');
  const siteBtn = document.getElementById('btn-site');
  const shareBtn = document.getElementById('btn-share');
  const shareFeedback = document.getElementById('share-feedback');

  let quantity = 1;

  function formatMoney(amount) {
    return Math.round(amount).toLocaleString() + ' ' + cfg.currency;
  }

  function buildWhatsAppLink() {
    const message =
      "Hi, I'd like to order:\n" +
      `*${cfg.productName}*\n` +
      `Quantity: ${quantity}\n` +
      `Price: ${formatMoney(cfg.price * quantity)}\n` +
      `Link: ${cfg.productUrl}`;
    return `https://wa.me/${cfg.whatsappNumber}?text=${encodeURIComponent(message)}`;
  }

  function buildSmsLink() {
    const message = `I'd like to order x${quantity}: ${cfg.productName} - ${formatMoney(cfg.price * quantity)} - ${cfg.productUrl}`;
    const isIOS = /iphone|ipad/i.test(navigator.userAgent);
    const separator = isIOS ? '&' : '?';
    return `sms:${cfg.smsNumber}${separator}body=${encodeURIComponent(message)}`;
  }

  function render() {
    if (qtyDisplay) qtyDisplay.textContent = quantity;
    if (totalDisplay) totalDisplay.textContent = formatMoney(cfg.price * quantity);
    if (whatsappBtn) whatsappBtn.href = buildWhatsAppLink();
    if (smsBtn) smsBtn.href = buildSmsLink();
    if (siteBtn) {
      // Pass the chosen quantity through to the checkout form via the URL
      const url = new URL(siteBtn.href, window.location.origin);
      url.searchParams.set('quantity', quantity);
      siteBtn.href = url.toString();
    }
  }

  if (qtyMinus) {
    qtyMinus.addEventListener('click', function () {
      if (quantity > 1) {
        quantity--;
        render();
      }
    });
  }

  if (qtyPlus) {
    qtyPlus.addEventListener('click', function () {
      quantity++;
      render();
    });
  }

  if (shareBtn) {
    shareBtn.addEventListener('click', async function () {
      if (navigator.share) {
        try {
          await navigator.share({
            title: cfg.productName,
            text: `Check out ${cfg.productName}`,
            url: cfg.productUrl,
          });
        } catch (err) {
          // user cancelled the share sheet — do nothing
        }
      } else {
        try {
          await navigator.clipboard.writeText(cfg.productUrl);
          if (shareFeedback) {
            shareFeedback.textContent = 'Link copied!';
            setTimeout(() => (shareFeedback.textContent = ''), 2000);
          }
        } catch (err) {
          if (shareFeedback) shareFeedback.textContent = cfg.productUrl;
        }
      }
    });
  }

  render();
});
