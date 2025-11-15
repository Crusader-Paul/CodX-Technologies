// main.js - basic frontend interactions for CodX Technologies
document.addEventListener('DOMContentLoaded', function(){
  // Example: add to cart via fetch to PHP
  document.querySelectorAll('.add-to-cart').forEach(btn=>{
    btn.addEventListener('click', async (e)=>{
      const id = btn.dataset.id;
      const qty = btn.dataset.qty || 1;
      try{
        const res = await fetch('/php/cart.php', {
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body: JSON.stringify({action:'add', product_id:id, quantity: qty})
        });
        const data = await res.json();
        alert(data.message || 'Added to cart');
      }catch(err){ console.error(err); alert('Network error') }
    });
  });
});
