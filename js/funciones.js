function borrar(id){
        
    Swal.fire({
        title: '¿Desea borrar el registro?',
        showCancelButton: true,
        confirmButtonText: 'Si, Borrar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location="index.php?txtID="+id;
        }
    })
}

function borrarRegistro(id_pv,id_registro){
        
    Swal.fire({
        title: '¿Desea borrar el registro?',
        showCancelButton: true,
        confirmButtonText: 'Si, Borrar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location="gestion.php?pv="+id_pv+"&txtID="+id_registro;
        }
    })
}

function llenamunicipio (){
    var deptoSelect = document.getElementById("idDepartamento").value;
    $.ajax({
        url: '../../ajax/muniselect.php',
        type: 'post',
        data: {deptoselection:deptoSelect},
        dataType: 'json',
        success:function(response){
        var len = response.length;
        $("#idmunicipio").empty();
        $("#idmunicipio").append("<option value='' selected disabled>Seleccione un municipio</option>");
        for( var i = 0; i<len; i++){
        var id = response[i]['id'];
        var muni = response[i]['muni'];
        
        $("#idmunicipio").append("<option value='"+id+"'>"+muni+"</option>");
        
        }
        }
        });
}