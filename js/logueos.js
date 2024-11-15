function agregarsession(){

        usuario=$('#usuario').val();
      
      var pasa = true;
   
       if (usuario == '') {
           pasa = usuario ;  
            alert('mensaje');  
      }
     
   
   if(pasa){                         
   
        sessiones(usuario);
       }};




function sessiones(usuario){ 
document.getElementById("usuario").focus();


$.ajax({
type:"POST",
url:"php/agregarsessiones.php", 
data: {            
usuario : usuario,

}, 
   

});

}