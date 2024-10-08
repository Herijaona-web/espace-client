//Quantity value
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.quantity_price').forEach(function(element) {
      element.addEventListener('change', function() {
          let value = this.value;
          const parent = this.parentNode.parentNode;
          parent.querySelector('.quantity_values').value = value;
      });
      let defaultValue = element.value;
      const parent = element.parentNode.parentNode;
      parent.querySelector('.quantity_values').value = defaultValue;
   });
   
});