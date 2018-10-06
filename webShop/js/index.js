$(document).ready(function() {

  // alert nestane posle 5sec
  setTimeout(function() {
    $('.alert').slideUp();
  }, 5000);

  $('.remove-from-cart').click(function(e) {
    // spreci ono sto bi se inace desilo klikom na dugme
  // ceka se klik na klasu .remove-from-cart
    e.preventDefault();

    // uzima cart_id iz dugmeta
    var cartItemId = $(this).data('id');
    var button = this;
    
    $.ajax({
      url: './api.php',
      type: 'post',
      data: {
        action: 'delete_from_cart',
        id: cartItemId
      },
      success: function(res) {
        console.log(res);
        if ( res.success ) {
          var row = $(button).parent().parent();
          $(row).addClass('alert-danger').fadeOut(1000);
        }
      },
      error: function(err) {
        console.log(err);
        alert('An error has occured. Check console for more info.');
      }
    });
  });

});