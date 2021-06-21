/*Funcion para que los carruseles avancen y retrocedan*/
var slnoticia = 1;
var sltend = 1;
var slpeq = 1;

showDivs(slnoticia);
showDiv(sltend);
showdv(slpeq);

function plusSlides(n) {
    showDivs(slnoticia += n);
}

function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("sliderNoticias");
    if (n > x.length) { slnoticia = 1; }
    if (n < 1) {
        slnoticia = x.length;
    }
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    x[slnoticia - 1].style.display = "block";
}

function plusSlide(n) {
    showDiv(sltend += n);
}

function showDiv(n) {
    var i;
    var x = document.getElementsByClassName("sliderTendencias");
    if (n > x.length) { sltend = 1; }
    if (n < 1) {
        sltend = x.length;
    }
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    x[sltend - 1].style.display = "block";
}

function Slides(n) {
    showdv(slpeq += n);
}

function showdv(n) {
    var i;
    var x = document.getElementsByClassName("sliderPeq");
    if (n > x.length) { slpeq = 1; }
    if (n < 1) {
        slpeq = x.length;
    }
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    x[slpeq - 1].style.display = "block";
}



/*Funcion para el dropdown debajo del boton de perfil*/
function drop() {
    document.getElementById("midrop").classList.toggle("show");
}


window.onclick = function (event) {
    if (!event.target.matches('.btndrop')) {
        var dropdowns = document.getElementsByClassName("imgdropdown");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

/*Funcion para el dropdown debajo de las notificaciones*/
function dropa() {
    document.getElementById("midrop1").classList.toggle("show");
}


window.onclick = function (event) {
    if (!event.target.matches('.btndrop1')) {
        var dropdowns = document.getElementsByClassName("imgdropdown1");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

/*Funcion para ver la lista en la tienda*/
function verlista() {
    document.getElementById("interlista").classList.toggle("show");
}

/*Funcion para ver los filtros en la página del foro*/
function verfiltros() {
    document.getElementById("filterdrop").classList.toggle("show");
}

window.onclick = function (event) {
    if (!event.target.matches('.btnfiltro')) {
        var dropdowns = document.getElementsByClassName("contenido");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}



/*Funcion para copiar URL tema y compartirlo*/
function compartir(url) {

    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val("http://localhost/Pagina/proyecto/pages/foro/topic.php?id=" + url).select();
    document.execCommand("copy");
    $temp.remove();

    Swal.fire({
        icon: 'success',
        title: 'Copiado enlace',
        showConfirmButton: false,
        timer: 1500
    })
}


