function displaySubMenu(option) {
	var choosedVal = option.options[option.selectedIndex].value;
	if(choosedVal == 'checkScore') {
		var subMenu = document.getElementById('score').style.display = 'block';
	}else {
		var subMenu = document.getElementById('score').style.display = 'none';
	}
}

function displayThirdMenu(option) {
	var choosedVal = option.options[option.selectedIndex].value;
	if(choosedVal == 'yearScore') {
		var subMenu = document.getElementById('yearChoice').style.display = 'block';
	}else {
		var subMenu = document.getElementById('yearChoice').style.display = 'none';
	}
}