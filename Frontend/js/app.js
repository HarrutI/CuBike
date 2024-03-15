document.addEventListener('DOMContentLoaded', function() {
    var boton = document.querySelector('#Menu .boton_desplegable');
    var lista = document.querySelector('#Menu .menu-lista');
  
    boton.addEventListener('click', function() {
      if (lista.style.display === 'none') {
        lista.style.display = 'block';
      } else {
        lista.style.display = 'none';
      }
    });
});