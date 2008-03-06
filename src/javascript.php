<script language="JavaScript">
function switch_submit(imagen) {
	document.contact.contact_img.src = imagen;
}

function subir() {
	document.getElementById('div_externo').scrollTop = document.getElementById('div_externo').scrollTop - 30;
}

function bajar() {
	document.getElementById('div_externo').scrollTop = document.getElementById('div_externo').scrollTop + 30;
}

function setear_mapas(mapavisible,mapanovisible1,mapanovisible2) {
	if (mapavisible=='default') {
		document.getElementById(mapavisible).style.display = 'block';
		document.getElementById('ampliar').style.display = 'none';
	} else {
		document.getElementById(mapavisible).style.display = 'block';
		document.getElementById(mapanovisible1).style.display = 'none';
		document.getElementById(mapanovisible2).style.display = 'none';
		document.getElementById('spantexto').style.display = 'none';
		document.getElementById('default').style.display = 'none';
		document.getElementById('ampliar').style.display = 'block';
		document.formu.zoom.value = mapavisible;
	}
}

function popup_map(url) {
	window.open(url + document.formu.zoom.value + '_big.jpg','Zoom','width=820,height=640,menubar=no,scrollbars=no,toolbar=no,location=no,directories=no,resizable=no,top=0,left=0');
}

function popup_recreo(url) {
	window.open(url,'Zoom','width=370,height=280,menubar=no,scrollbars=no,toolbar=no,location=no,directories=no,resizable=no,top=0,left=0');
}

</script>