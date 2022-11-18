function changeText(x){
	var value = x.value;
	var id = x.options[x.selectedIndex].id;
	window.location="place-order.php?action=editQty&id=" + id + "&value=" +value;
}