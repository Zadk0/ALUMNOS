function cargarGrupos(id){
fetch("ajax/obtener_grupos.php?id="+id)
.then(res=>res.text())
.then(data=>{
document.getElementById("grupo_select").innerHTML=data;
});
}