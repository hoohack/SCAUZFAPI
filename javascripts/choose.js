function displaySubMenu(option) {
	var choosedVal = option.options[option.selectedIndex].value;
	if(choosedVal == 'checkScore') {
		var subMenu = document.getElementById('score').style.display = 'block';
	}else {
		var subMenu = document.getElementById('score').style.display = 'none';
	}
}